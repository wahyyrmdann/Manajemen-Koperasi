<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Kolom username atau email wajib diisi.',
            'password.required' => 'Kolom password wajib diisi.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = trim($this->string('email')->toString());
        $password = $this->string('password')->toString();
        $remember = $this->boolean('remember');

        // Cari user yang cocok berdasarkan email atau username/name (fleksibel: mendukung 'admin', 'Administrator', atau email)
        $user = User::where(function ($query) use ($login) {
            $query->where('email', $login)
                  ->orWhere('name', $login);

            // Dukung alias username admin <-> Administrator
            if (strtolower($login) === 'admin') {
                $query->orWhere('name', 'Administrator')
                      ->orWhere('email', 'admin@koperasi.test');
            } elseif (strtolower($login) === 'administrator') {
                $query->orWhere('name', 'admin');
            }
        })->first();

        // Validasi keberadaan user dan kecocokan password
        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Username atau password salah. Silakan periksa kembali kredensial Anda.',
            ]);
        }

        // Autentikasi user terdaftar yang cocok
        Auth::login($user, $remember);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
