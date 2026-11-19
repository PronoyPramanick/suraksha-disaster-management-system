<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
            'login_type' => ['required', 'in:citizen,volunteer,admin'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'volunteer_id' => ['required_if:login_type,volunteer', 'nullable', 'string', 'max:100'],
            'password' => ['required', 'string'],
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

        $loginType = $this->string('login_type')->toString();
        $email = Str::lower(trim((string) $this->input('email')));
        $credentials = [
            'email' => $email,
            'role' => $loginType,
            'password' => $this->input('password'),
        ];

        if ($loginType === 'volunteer') {
            $volunteer = User::query()
                ->where('email', $email)
                ->where('volunteer_id', Str::upper(trim((string) $this->input('volunteer_id'))))
                ->where('role', 'volunteer')
                ->whereHas('volunteerApplication', fn ($query) => $query->where('status', 'Approved'))
                ->first();

            $credentials['email'] = $volunteer?->email ?? '';
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $errorField = $loginType === 'volunteer' ? 'volunteer_id' : 'email';

            throw ValidationException::withMessages([
                $errorField => trans('auth.failed'),
            ]);
        }

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
        return Str::transliterate(implode('|', [
            Str::lower($this->string('login_type')->toString()),
            Str::lower($this->string('email')->toString()),
            Str::upper($this->string('volunteer_id')->toString()),
            $this->ip(),
        ]));
    }
}
