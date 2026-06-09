<x-auth-layout>
    @php $pageTitle = __('Reset Password'); @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-md-4 col-lg-3 col-10 box-shadow-2 card-auth p-0">
                <div class="card border-grey border-lighten-3 m-0 px-1 py-1">
                    <div class="card-content">
                        <p class="card-subtitle line-on-side text-uppercase font-weight-bold mx-2 my-3 text-center">
                            <span>{{ $pageTitle }}</span>
                        </p>

                        <div class="card-body">

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-3" :status="session('status')" />

                            <form method="POST" action="{{ route('password.store') }}" class="form-horizontal">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <!-- Email -->
                                <fieldset class="form-group position-relative has-icon-left">
                                    <x-text-input id="email" type="email" name="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('Email') }}" :value="old('email', $request->email)" autofocus
                                        autocomplete="username" />
                                    <div class="form-control-position">
                                        <i class="ft-mail"></i>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="invalid-feedback d-block" />
                                </fieldset>

                                <!-- Password -->
                                <fieldset class="form-group position-relative has-icon-left">
                                    <x-text-input id="password" type="password" name="password"
                                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('Password') }}" autocomplete="new-password" />
                                    <div class="form-control-position">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="invalid-feedback d-block error-list" />
                                </fieldset>

                                <!-- Password Confirmation -->
                                <fieldset class="form-group position-relative has-icon-left">
                                    <x-text-input id="password_confirmation" type="password"
                                        name="password_confirmation"
                                        class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('Confirm Password') }}" autocomplete="new-password" />
                                    <div class="form-control-position">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="invalid-feedback d-block error-list" />
                                </fieldset>

                                <!-- Submit -->
                                <x-primary-button class="text-uppercase btn-block">
                                    {{ __('Reset Password') }}
                                </x-primary-button>
                            </form>

                            <hr class="my-2">

                            <p class="mb-0 text-center">
                                &copy; {{ date('Y') }} {{ config('app.subname', 'Laravel') }}.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                if (!password || !confirmPassword) return;

                const touched = {
                    password: false,
                    confirm: false
                };

                function getFormGroup(input) {
                    return input.closest('.form-group');
                }

                function getOrCreateErrorList(input) {
                    const group = getFormGroup(input);
                    if (!group) return null;

                    let ul = group.querySelector('.error-list');

                    if (!ul) {
                        ul = document.createElement('ul');
                        ul.className = 'error-list list-unstyled text-danger small mb-0';
                        group.appendChild(ul);
                    }

                    return ul;
                }

                function setErrors(input, messages, key) {
                    if (!touched[key]) return;

                    const ul = getOrCreateErrorList(input);
                    if (!ul) return;

                    ul.innerHTML = '';

                    if (messages.length) {
                        input.classList.add('is-invalid');
                        messages.forEach(msg => {
                            const li = document.createElement('li');
                            li.innerHTML = `<i class="fa fa-exclamation-circle"></i> ${msg}`;
                            ul.appendChild(li);
                        });
                    } else {
                        input.classList.remove('is-invalid');
                    }
                }

                function validatePassword() {
                    const val = password.value.trim();
                    const errors = [];

                    if (!val) {
                        errors.push('Kata sandi wajib diisi.');
                    } else if (val.length < 8) {
                        errors.push('Kata sandi minimal 8 karakter.');
                    }

                    setErrors(password, errors, 'password');
                }

                function validateConfirmation() {
                    const val = confirmPassword.value.trim();
                    const errors = [];

                    if (!val) {
                        errors.push('Konfirmasi kata sandi wajib diisi.');
                    } else if (val !== password.value) {
                        errors.push('Konfirmasi kata sandi tidak sesuai.');
                    }

                    setErrors(confirmPassword, errors, 'confirm');
                }

                password.addEventListener('input', () => {
                    touched.password = true;
                    validatePassword();
                    if (confirmPassword.value.length) {
                        touched.confirm = true;
                        validateConfirmation();
                    }
                });

                confirmPassword.addEventListener('input', () => {
                    touched.confirm = true;
                    validateConfirmation();
                });
            });
        </script>
    @endpush
</x-auth-layout>
