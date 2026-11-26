<?php

namespace Tests\Feature;

use App\Enums\PartnerType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\UserRole;
use App\Models\Partner;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\StockTransactionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Partner $supplier;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $this->supplier = Partner::create([
            'name'    => 'PT Sumber Pangan',
            'type'    => PartnerType::Supplier,
            'phone'   => '021-5551234',
            'address' => 'Jakarta',
        ]);

        $this->product = Product::create([
            'code'  => 'BRG-001',
            'name'  => 'Gula Pasir 50kg',
            'unit'  => 'Zak',
            'stock' => 50,
        ]);
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────

    /** Tamu tanpa autentikasi ditolak dan dialihkan ke login saat mengakses dashboard. */
    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** User yang sudah login dapat mengakses dashboard dengan status 200. */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Total Jenis Barang');
        $response->assertSee('Total Stok');
        $response->assertSee('Barang Masuk');
        $response->assertSee('Barang Keluar');
        $response->assertDontSee('+ Barang Masuk');
        $response->assertDontSee('+ Barang Keluar');
        $response->assertDontSee('Ikhtisar');
        $response->assertDontSee('Operasi');
        $response->assertDontSee('Laporan');
        $response->assertDontSee('Konfigurasi');
        $response->assertDontSee('Notifikasi Sistem');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    /** Dashboard menampilkan ringkasan data statistik dan transaksi terbaru. */
    public function test_dashboard_displays_summary_cards_and_recent_transactions(): void
    {
        $this->actingAs($this->user);

        $tx = StockTransaction::create([
            'reference'        => 'WH/IN/00001',
            'type'             => TransactionType::In,
            'partner_id'       => $this->supplier->id,
            'user_id'          => $this->user->id,
            'transaction_date' => now()->toDateString(),
            'status'           => TransactionStatus::Draft,
        ]);

        StockTransactionItem::create([
            'stock_transaction_id' => $tx->id,
            'product_id'           => $this->product->id,
            'quantity'             => 10,
        ]);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('WH/IN/00001');
        $response->assertSee('PT Sumber Pangan');
        $response->assertSee('Masuk');
        $response->assertSee('Draft');
    }

    // ─── Barang Masuk ─────────────────────────────────────────────────────────

    /** Tamu tanpa autentikasi ditolak saat mengakses halaman barang masuk. */
    public function test_unauthenticated_user_cannot_access_incoming_list(): void
    {
        $response = $this->get('/barang-masuk');
        $response->assertRedirect('/login');
    }

    /** User yang sudah login dapat mengakses halaman barang masuk dengan status 200. */
    public function test_authenticated_user_can_access_incoming_list(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/barang-masuk');
        $response->assertStatus(200);
        $response->assertSee('Daftar Barang Masuk');
        $response->assertSee('Transaksi Barang Masuk');
    }

    /** Filter status bekerja pada halaman barang masuk. */
    public function test_incoming_list_filters_by_status(): void
    {
        $this->actingAs($this->user);

        StockTransaction::create([
            'reference'        => 'WH/IN/00099',
            'type'             => TransactionType::In,
            'partner_id'       => $this->supplier->id,
            'user_id'          => $this->user->id,
            'transaction_date' => now()->toDateString(),
            'status'           => TransactionStatus::Draft,
        ]);

        $response = $this->get('/barang-masuk?status=draft');
        $response->assertStatus(200);
        $response->assertSee('WH/IN/00099');
    }

    // ─── Barang Keluar ────────────────────────────────────────────────────────

    /** Tamu tanpa autentikasi ditolak saat mengakses halaman barang keluar. */
    public function test_unauthenticated_user_cannot_access_outgoing_list(): void
    {
        $response = $this->get('/barang-keluar');
        $response->assertRedirect('/login');
    }

    /** User yang sudah login dapat mengakses halaman barang keluar dengan status 200. */
    public function test_authenticated_user_can_access_outgoing_list(): void
    {
        $this->actingAs($this->user);

        $response = $this->get('/barang-keluar');
        $response->assertStatus(200);
        $response->assertSee('Daftar Barang Keluar');
        $response->assertSee('Transaksi Barang Keluar');
    }

    // ─── Form Barang Masuk & Barang Keluar ────────────────────────────────────

    /** Tamu tanpa autentikasi ditolak saat mengakses form buat barang masuk dan keluar. */
    public function test_unauthenticated_user_cannot_access_transaction_forms(): void
    {
        $this->get('/barang-masuk/create')->assertRedirect('/login');
        $this->post('/barang-masuk', [])->assertRedirect('/login');
        $this->get('/barang-keluar/create')->assertRedirect('/login');
        $this->post('/barang-keluar', [])->assertRedirect('/login');
    }

    /** User dapat membuat dan memvalidasi transaksi barang masuk sehingga stok bertambah. */
    public function test_authenticated_user_can_create_and_validate_incoming_transaction(): void
    {
        $this->actingAs($this->user);

        $this->get('/barang-masuk/create')->assertStatus(200);

        $response = $this->post('/barang-masuk', [
            'partner_id'       => $this->supplier->id,
            'transaction_date' => now()->toDateString(),
            'origin'           => 'PO-001',
            'action'           => 'validate',
            'items'            => [
                ['product_id' => $this->product->id, 'quantity' => 15],
            ],
        ]);

        $response->assertRedirect();
        $this->assertEquals(65, $this->product->fresh()->stock);
        $this->assertDatabaseHas('stock_transactions', [
            'type'   => TransactionType::In->value,
            'status' => TransactionStatus::Done->value,
        ]);
    }

    /** Validasi barang keluar ditolak jika stok tidak mencukupi dan diterima jika cukup. */
    public function test_outgoing_validation_checks_stock_sufficiency(): void
    {
        $this->actingAs($this->user);

        $member = Partner::create([
            'name'    => 'Anggota Budi',
            'type'    => PartnerType::Anggota,
            'phone'   => '08123456789',
            'address' => 'Jl. Koperasi No. 1',
        ]);

        $this->get('/barang-keluar/create')->assertStatus(200);

        // Stok 50, minta 80 -> gagal validasi (tetap draft, stok tetap 50)
        $responseFail = $this->post('/barang-keluar', [
            'partner_id'       => $member->id,
            'transaction_date' => now()->toDateString(),
            'action'           => 'validate',
            'items'            => [
                ['product_id' => $this->product->id, 'quantity' => 80],
            ],
        ]);

        $responseFail->assertSessionHas('error');
        $this->assertEquals(50, $this->product->fresh()->stock);
        $this->assertDatabaseHas('stock_transactions', [
            'type'   => TransactionType::Out->value,
            'status' => TransactionStatus::Draft->value,
        ]);

        // Stok 50, minta 20 -> berhasil validasi (stok jadi 30)
        $responseOk = $this->post('/barang-keluar', [
            'partner_id'       => $member->id,
            'transaction_date' => now()->toDateString(),
            'action'           => 'validate',
            'items'            => [
                ['product_id' => $this->product->id, 'quantity' => 20],
            ],
        ]);

        $responseOk->assertSessionHas('success');
        $this->assertEquals(30, $this->product->fresh()->stock);
    }
}
