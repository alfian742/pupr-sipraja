 <div class="card border-indigo">
     <div class="card-header bg-indigo">
         <h5 class="font-weight-bold text-uppercase mb-0 text-white">{{ __('Profile Information') }}</h5>
     </div>
     <div class="card-body">
         <p class="text-muted mb-2">
             {{ __("Update your account's profile information and email address.") }}
         </p>

         <form id="send-verification" method="post" action="{{ route('verification.send') }}">
             @csrf
         </form>

         <form method="post" action="{{ route('profile.update') }}">
             @csrf
             @method('patch')

             <div class="mb-2">
                 <x-input-label for="name" :value="__('Name')" />
                 <x-text-input id="name" name="name" type="text"
                     class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" :value="old('name', $user->name)" required
                     autofocus autocomplete="name" />
                 <x-input-error class="invalid-feedback d-block" :messages="$errors->get('name')" />
             </div>

             <div class="mb-2">
                 <x-input-label for="email" :value="__('Email')" />
                 <x-text-input id="email" name="email" type="email"
                     class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" :value="old('email', $user->email)" required
                     autocomplete="username" />
                 <x-input-error class="invalid-feedback d-block" :messages="$errors->get('email')" />

                 @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                     <div>
                         <p class="d-flex align-items-center text-danger mt-2 text-sm">
                             {{ __('Your email address is unverified.') }}

                             <button form="send-verification" class="btn btn-indigo btn-sm ml-2">
                                 {{ __('Click here to re-send the verification email.') }}
                             </button>
                         </p>

                         @if (session('status') === 'verification-link-sent')
                             <p class="text-success mt-2 text-sm">
                                 {{ __('A new verification link has been sent to your email address.') }}
                             </p>
                         @endif
                     </div>
                 @endif
             </div>

             <div class="d-flex align-items-center justify-content-end">
                 @if (session('status') === 'profile-updated')
                     <p class="text-success mb-0 mr-1 text-sm"><i class="fa fa-check-circle"></i> {{ __('Saved.') }}
                     </p>
                 @endif

                 <x-primary-button>{{ __('Save') }}</x-primary-button>
             </div>
         </form>
     </div>
 </div>
