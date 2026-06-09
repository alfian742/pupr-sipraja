<div class="card border-indigo">
    <div class="card-header bg-indigo">
        <h5 class="font-weight-bold text-uppercase mb-0 text-white">{{ __('Update Password') }}</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-2">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>

        <form method="post" action="{{ route('password.update') }}" id="form-update-password">
            @csrf
            @method('put')

            <div class="mb-2">
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password"
                    class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="invalid-feedback d-block" />
            </div>

            <div class="mb-2">
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input id="update_password_password" name="password" type="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="invalid-feedback d-block" />
            </div>

            <div class="mb-2">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="invalid-feedback d-block" />
            </div>

            <div class="d-flex align-items-center justify-content-end">
                @if (session('status') === 'password-updated')
                    <p class="text-success mb-0 mr-1 text-sm"><i class="fa fa-check-circle"></i> {{ __('Saved.') }}</p>
                @endif

                <x-primary-button>{{ __('Save') }}</x-primary-button>
            </div>
        </form>
    </div>
</div>
