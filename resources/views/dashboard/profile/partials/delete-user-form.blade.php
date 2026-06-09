<div class="card border-danger">
    <div class="card-header bg-danger">
        <h5 class="font-weight-bold text-uppercase mb-0 text-white"> {{ __('Delete Account') }}</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-2">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

        <div class="d-flex justify-content-end">
            <x-danger-button data-toggle="modal" data-target="#modal-confirm-user-deletion">
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="delete-user-form">
                @csrf
                @method('delete')

                <h4 class="font-weight-medium text-danger mb-2">
                    {{ __('Are you sure you want to delete your account?') }}
                </h4>

                <p class="text-muted mb-3">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mb-2">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input id="password" name="password" type="password"
                        class="form-control {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}"
                        placeholder="{{ __('Password') }}" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="invalid-feedback d-block" />
                </div>

                <div class="d-flex justify-content-end">
                    <x-secondary-button data-dismiss="modal">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-1">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            var form = $('.delete-user-form');

            if (form.length) {
                // cek apakah ada field yang is-invalid
                if (form.find('.is-invalid').length > 0) {
                    form.closest('.modal').modal('show');
                }
            }

        });
    </script>
@endpush
