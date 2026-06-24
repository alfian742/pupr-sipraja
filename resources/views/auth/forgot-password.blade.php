<x-auth-layout>
    @php $pageTitle = __('Forgot Password'); @endphp

    <x-slot name="title">{{ $pageTitle }}</x-slot>

    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-md-4 col-lg-3 col-10 box-shadow-2 card-auth p-0">
                <div class="card border-grey border-lighten-3 m-0 px-1 py-1">
                    <div class="card-content">
                        <p class="card-subtitle line-on-side text-uppercase font-weight-bold mx-2 my-3 text-center">
                            <span>{{ $pageTitle }}</span>
                        </p>

                        <p class="card-subtitle m-2">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </p>

                        <div class="card-body">

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-3" :status="session('status')" />

                            <form method="POST" action="{{ route('password.email') }}" class="form-horizontal">
                                @csrf

                                <!-- Email -->
                                <fieldset class="form-group position-relative has-icon-left">
                                    <x-text-input id="email" type="email" name="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('Email') }}" autocomplete="current-email" />
                                    <div class="form-control-position">
                                        <i class="ft-mail"></i>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="invalid-feedback d-block" />
                                </fieldset>

                                <!-- Submit -->
                                <x-primary-button class="text-uppercase btn-block">
                                    {{ __('Email Password Reset Link') }}
                                </x-primary-button>
                            </form>

                            <div class="d-block mt-2 text-center">
                                <a class="btn btn-outline-indigo btn-block" href="{{ route('login') }}">
                                    <i class="fa fa-arrow-left"></i>
                                    {{ __('Back') }}
                                </a>
                            </div>

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
    @endpush
</x-auth-layout>
