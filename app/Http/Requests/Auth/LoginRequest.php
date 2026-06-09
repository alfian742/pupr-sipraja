<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\Validator;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'captcha_answer' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $number1 = $this->session()->get('captcha_number_1');
                    $number2 = $this->session()->get('captcha_number_2');

                    if ($number1 === null || $number2 === null || (int) $value !== ((int) $number1 + (int) $number2)) {
                        $fail(trans('auth.captcha_incorrect'));
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'captcha_answer.required' => trans('auth.captcha_required'),
            'captcha_answer.integer' => trans('auth.captcha_integer'),
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $this->regenerateCaptcha();

        parent::failedValidation($validator);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            $this->regenerateCaptcha();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        Auth::user()->update([
            'last_login_at' => now(),
        ]);

        $this->session()->forget([
            'captcha_number_1',
            'captcha_number_2',
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $this->regenerateCaptcha();

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
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }

    private function regenerateCaptcha(): void
    {
        $this->session()->put([
            'captcha_number_1' => random_int(1, 15),
            'captcha_number_2' => random_int(1, 15),
        ]);
    }
}
