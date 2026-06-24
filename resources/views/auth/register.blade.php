<x-auth-layout>
    @php $pageTitle = __('Register'); @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section class="auth-page">
        <div class="auth-card box-shadow-2">
            <div class="row no-gutters min-vh-card">

                {{-- BRAND AREA --}}
                <div class="col-lg-5 d-none d-lg-flex">
                    <div class="auth-brand-area">
                        <div class="auth-brand-content">
                            <div class="auth-brand-logo">
                                <x-application-logo height="190" />
                            </div>

                            <h2 class="auth-brand-title">
                                SI PRAJA
                            </h2>

                            <p class="auth-brand-subtitle">
                                Sistem Informasi Pengukuran Kinerja <br> DPUPR Kabupaten Lombok Tengah.
                            </p>
                        </div>

                        <div class="auth-brand-footer">
                            &copy; {{ date('Y') }} {{ config('app.subname', 'DPUPR Kabupaten Lombok Tengah') }}.
                        </div>
                    </div>
                </div>

                {{-- FORM AREA --}}
                <div class="col-12 col-lg-7">
                    <div class="auth-form-area">

                        {{-- Mobile Logo --}}
                        <div class="auth-mobile-logo d-lg-none">
                            <x-application-logo height="120" />
                        </div>

                        <div class="auth-heading">
                            <span class="auth-eyebrow">Pendaftaran Akun</span>
                            <h1 class="auth-title">{{ $pageTitle }}</h1>
                            <p class="auth-description">
                                Lengkapi data berikut untuk membuat akun pengguna SI PRAJA.
                            </p>
                        </div>

                        {{-- Session Status --}}
                        <x-auth-session-status class="mb-3" :status="session('status')" />

                        <form method="POST" action="{{ route('register') }}" class="form-horizontal">
                            @csrf

                            {{-- Name --}}
                            <fieldset class="form-group position-relative has-icon-left">
                                <x-text-input id="name" type="text" name="name"
                                    class="form-control auth-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    placeholder="{{ __('Name') }}" :value="old('name')" autofocus autocomplete="name" />
                                <div class="form-control-position">
                                    <i class="ft-user"></i>
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="invalid-feedback d-block" />
                            </fieldset>

                            {{-- Email --}}
                            <fieldset class="form-group position-relative has-icon-left">
                                <x-text-input id="email" type="email" name="email"
                                    class="form-control auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    placeholder="{{ __('Email') }}" :value="old('email')" autocomplete="username" />
                                <div class="form-control-position">
                                    <i class="ft-mail"></i>
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="invalid-feedback d-block" />
                            </fieldset>

                            {{-- Password --}}
                            <fieldset class="form-group position-relative has-icon-left">
                                <x-text-input id="password" type="password" name="password"
                                    class="form-control auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="{{ __('Password') }}" autocomplete="new-password" />
                                <div class="form-control-position">
                                    <i class="fa fa-key"></i>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="invalid-feedback d-block error-list" />
                                <ul id="password-client-errors"
                                    class="client-error-list list-unstyled text-danger small mb-0"></ul>
                            </fieldset>

                            {{-- Password Confirmation --}}
                            <fieldset class="form-group position-relative has-icon-left">
                                <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-control auth-input {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                    placeholder="{{ __('Confirm Password') }}" autocomplete="new-password" />
                                <div class="form-control-position">
                                    <i class="fa fa-key"></i>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="invalid-feedback d-block error-list" />
                                <ul id="password-confirm-client-errors"
                                    class="client-error-list list-unstyled text-danger small mb-0"></ul>
                            </fieldset>

                            <div class="auth-password-hint">
                                <i class="fa fa-info-circle"></i>
                                <span>Kata sandi minimal 8 karakter dan harus sama dengan konfirmasi kata sandi.</span>
                            </div>

                            <div class="auth-action">
                                {{-- Submit --}}
                                <x-primary-button class="text-uppercase btn-block mb-0 auth-submit-button">
                                    {{ __('Register') }}
                                </x-primary-button>

                                <a class="btn btn-outline-indigo auth-home-button" href="{{ route('home') }}">
                                    <i class="fa fa-arrow-left mr-1"></i>
                                    {{ __('Back to Home') }}
                                </a>
                            </div>
                        </form>

                        <div class="auth-register">
                            <span>{{ __('Already registered?') }}</span>
                            <a class="auth-link font-weight-bold" href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
                        </div>

                        <p class="mb-0 text-center auth-footer d-lg-none">
                            &copy; {{ date('Y') }} {{ config('app.subname', 'Laravel') }}.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .auth-page {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px 16px;
                background:
                    radial-gradient(circle at top left, rgba(20, 65, 135, 0.12), transparent 32%),
                    radial-gradient(circle at bottom right, rgba(242, 174, 20, 0.16), transparent 34%),
                    #f4f6fb;
            }

            .auth-card {
                width: 100%;
                max-width: 980px;
                overflow: hidden;
                border-radius: 14px;
                background: #ffffff;
            }

            .min-vh-card {
                min-height: 620px;
            }

            .auth-brand-area {
                position: relative;
                width: 100%;
                min-height: 620px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 42px 38px 30px;
                color: #ffffff;
                background:
                    linear-gradient(135deg, rgba(10, 54, 117, 0.96), rgba(8, 42, 91, 0.98)),
                    #0b3c78;
            }

            .auth-brand-area::before {
                content: "";
                position: absolute;
                width: 260px;
                height: 260px;
                right: -90px;
                top: -70px;
                border-radius: 50%;
                background: rgba(242, 174, 20, 0.18);
            }

            .auth-brand-area::after {
                content: "";
                position: absolute;
                width: 180px;
                height: 180px;
                left: -70px;
                bottom: 50px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.08);
            }

            .auth-brand-content,
            .auth-brand-footer {
                position: relative;
                z-index: 1;
            }

            .auth-brand-logo {
                width: 220px;
                min-height: 220px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 28px;
                padding: 22px;
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
            }

            .auth-brand-logo img,
            .auth-brand-logo svg {
                max-width: 180px;
                width: 100%;
                height: auto;
            }

            .auth-brand-title {
                margin-bottom: 12px;
                font-size: 2rem;
                font-weight: 800;
                letter-spacing: 0.04em;
                text-align: center;
                color: #ffffff;
            }

            .auth-brand-subtitle {
                max-width: 320px;
                margin: 0 auto;
                font-size: 0.95rem;
                line-height: 1.7;
                text-align: center;
                color: rgba(255, 255, 255, 0.82);
            }

            .auth-info-item {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 12px 14px;
                border: 1px solid rgba(255, 255, 255, 0.14);
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.08);
                color: rgba(255, 255, 255, 0.84);
                font-size: 0.86rem;
                line-height: 1.5;
            }

            .auth-info-item i {
                margin-top: 3px;
                color: #f2ae14;
            }

            .auth-brand-footer {
                font-size: 0.82rem;
                line-height: 1.5;
                text-align: center;
                color: rgba(255, 255, 255, 0.72);
            }

            .auth-form-area {
                width: 100%;
                max-width: 500px;
                min-height: 620px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin: 0 auto;
                padding: 46px 38px;
                background: #ffffff;
            }

            .auth-mobile-logo {
                margin-bottom: 24px;
                text-align: center;
            }

            .auth-mobile-logo img,
            .auth-mobile-logo svg {
                max-width: 140px;
                width: 100%;
                height: auto;
            }

            .auth-heading {
                margin-bottom: 24px;
            }

            .auth-eyebrow {
                display: inline-block;
                margin-bottom: 8px;
                color: #f2ae14;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .auth-title {
                margin-bottom: 8px;
                color: #102a43;
                font-size: 1.75rem;
                font-weight: 800;
                line-height: 1.2;
            }

            .auth-description {
                margin-bottom: 0;
                color: #6c757d;
                font-size: 0.95rem;
                line-height: 1.6;
            }

            .auth-input {
                height: 44px;
                border-color: #dfe5ee;
                border-radius: 8px;
            }

            .auth-input:focus {
                border-color: #4054b2;
                box-shadow: 0 0 0 0.12rem rgba(64, 84, 178, 0.12);
            }

            .auth-password-hint {
                display: flex;
                align-items: flex-start;
                gap: 8px;
                margin-bottom: 16px;
                padding: 11px 13px;
                border-radius: 8px;
                background: #f8fafc;
                color: #6c757d;
                font-size: 0.84rem;
                line-height: 1.5;
            }

            .auth-password-hint i {
                margin-top: 3px;
                color: #4054b2;
            }

            .auth-action {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .auth-submit-button {
                height: 44px;
                border-radius: 8px;
                font-weight: 700;
                letter-spacing: 0.03em;
            }

            .auth-home-button {
                height: 42px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                font-weight: 600;
            }

            .auth-register {
                margin-top: 22px;
                padding-top: 18px;
                border-top: 1px solid #edf1f5;
                text-align: center;
                color: #6c757d;
                font-size: 0.92rem;
            }

            .auth-link {
                color: #4054b2;
                font-size: 0.9rem;
            }

            .auth-link:hover {
                color: #0b3c78;
                text-decoration: none;
            }

            .auth-footer {
                margin-top: 20px;
                color: #6c757d;
                font-size: 0.82rem;
            }

            .error-list,
            .client-error-list {
                margin-top: 0.25rem;
            }

            .error-list li,
            .client-error-list li {
                line-height: 1.4;
            }

            @media (max-width: 991.98px) {
                .auth-card {
                    max-width: 560px;
                    border-radius: 12px;
                }

                .min-vh-card {
                    min-height: auto;
                }

                .auth-form-area {
                    max-width: 100%;
                    min-height: auto;
                    padding: 38px 34px 30px;
                }

                .auth-heading {
                    text-align: center;
                }
            }

            @media (max-width: 575.98px) {
                .auth-page {
                    align-items: flex-start;
                    padding: 16px 12px;
                    background: #f4f6fb;
                }

                .auth-card {
                    box-shadow: none !important;
                    border-radius: 10px;
                }

                .auth-form-area {
                    padding: 30px 20px 24px;
                }

                .auth-title {
                    font-size: 1.5rem;
                }

                .auth-description {
                    font-size: 0.9rem;
                }
            }

            @media (max-width: 360px) {
                .auth-form-area {
                    padding-left: 16px;
                    padding-right: 16px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                const passwordErrors = document.getElementById('password-client-errors');
                const confirmErrors = document.getElementById('password-confirm-client-errors');

                if (!password || !confirmPassword || !passwordErrors || !confirmErrors) return;

                const touched = {
                    password: false,
                    confirm: false
                };

                function renderErrors(input, errorContainer, messages, key) {
                    if (!touched[key]) return;

                    errorContainer.innerHTML = '';

                    if (messages.length) {
                        input.classList.add('is-invalid');

                        messages.forEach(function(message) {
                            const li = document.createElement('li');
                            li.innerHTML = '<i class="fa fa-exclamation-circle"></i> ' + message;
                            errorContainer.appendChild(li);
                        });

                        return;
                    }

                    input.classList.remove('is-invalid');
                }

                function validatePassword() {
                    const value = password.value.trim();
                    const errors = [];

                    if (!value) {
                        errors.push('Kata sandi wajib diisi.');
                    } else if (value.length < 8) {
                        errors.push('Kata sandi minimal 8 karakter.');
                    }

                    renderErrors(password, passwordErrors, errors, 'password');
                }

                function validateConfirmation() {
                    const value = confirmPassword.value.trim();
                    const errors = [];

                    if (!value) {
                        errors.push('Konfirmasi kata sandi wajib diisi.');
                    } else if (value !== password.value) {
                        errors.push('Konfirmasi kata sandi tidak sesuai.');
                    }

                    renderErrors(confirmPassword, confirmErrors, errors, 'confirm');
                }

                password.addEventListener('input', function() {
                    touched.password = true;
                    validatePassword();

                    if (confirmPassword.value.length) {
                        touched.confirm = true;
                        validateConfirmation();
                    }
                });

                confirmPassword.addEventListener('input', function() {
                    touched.confirm = true;
                    validateConfirmation();
                });
            });
        </script>
    @endpush
</x-auth-layout>
