<x-app-layout>
    @php $pageTitle = __('Profile') @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <div class="content-body">
        <section id="dom">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="font-weight-bold text-uppercase mb-0">{{ $pageTitle }}</h3>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content show collapse">
                            <div class="card-body card-dashboard">
                                @include('dashboard.profile.partials.update-profile-information-form')

                                @include('dashboard.profile.partials.update-password-form')

                                @if (Route::has('profile.destroy'))
                                    @include('dashboard.profile.partials.delete-user-form')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const form = document.getElementById('form-update-password');
                if (!form) return;

                const currentPassword = form.querySelector('#update_password_current_password');
                const password = form.querySelector('#update_password_password');
                const confirmPassword = form.querySelector('#update_password_password_confirmation');
                const submitBtn = form.querySelector('button[type="submit"]');

                if (!currentPassword || !password || !confirmPassword || !submitBtn) return;

                const touched = {
                    current: false,
                    password: false,
                    confirm: false
                };

                /* =========================
                    UTIL
                ========================== */

                function createErrorElement(input) {
                    let errorDiv = input.parentElement.querySelector('.frontend-error');

                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback frontend-error d-block';
                        input.parentElement.appendChild(errorDiv);
                    }

                    return errorDiv;
                }

                function setError(input, message, key) {
                    const errorDiv = createErrorElement(input);

                    if (!touched[key]) return;

                    if (message) {
                        input.classList.add('is-invalid');
                        errorDiv.innerHTML = `<i class="fa fa-exclamation-circle"></i> ${message}`;
                    } else {
                        input.classList.remove('is-invalid');
                        errorDiv.innerHTML = '';
                    }
                }

                function disablePasswordFields(state) {
                    password.disabled = state;
                    confirmPassword.disabled = state;

                    if (state) {
                        password.value = '';
                        confirmPassword.value = '';
                        password.classList.remove('is-invalid');
                        confirmPassword.classList.remove('is-invalid');
                        submitBtn.disabled = true;
                    }
                }

                /* =========================
                    VALIDATION
                ========================== */

                function validateCurrentPassword() {
                    const value = currentPassword.value.trim();

                    if (!value) {
                        disablePasswordFields(true);
                        return false;
                    }

                    disablePasswordFields(false);
                    return true;
                }

                function validatePassword() {
                    const value = password.value.trim();

                    if (!value) {
                        setError(password, 'Password wajib diisi.', 'password');
                        return false;
                    }

                    if (value.length < 8) {
                        setError(password, 'Password minimal 8 karakter.', 'password');
                        return false;
                    }

                    setError(password, null, 'password');
                    return true;
                }

                function validateConfirmation() {
                    const value = confirmPassword.value.trim();

                    if (!value) {
                        setError(confirmPassword, 'Konfirmasi password wajib diisi.', 'confirm');
                        return false;
                    }

                    if (value !== password.value) {
                        setError(confirmPassword, 'Konfirmasi password tidak sesuai.', 'confirm');
                        return false;
                    }

                    setError(confirmPassword, null, 'confirm');
                    return true;
                }

                function validateForm() {
                    const validCurrent = validateCurrentPassword();
                    const validPassword = validatePassword();
                    const validConfirm = validateConfirmation();

                    submitBtn.disabled = !(validCurrent && validPassword && validConfirm);
                }

                /* =========================
                    INIT
                ========================== */

                disablePasswordFields(true);

                currentPassword.addEventListener('input', function() {
                    touched.current = true;
                    validateForm();
                });

                password.addEventListener('input', function() {
                    touched.password = true;
                    validateForm();
                });

                confirmPassword.addEventListener('input', function() {
                    touched.confirm = true;
                    validateForm();
                });

            });
        </script>
    @endpush
</x-app-layout>
