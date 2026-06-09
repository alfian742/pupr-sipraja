<x-auth-layout>
    @php $pageTitle = __('Confirm Password'); @endphp

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
                            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                        </p>

                        <div class="card-body">

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-3" :status="session('status')" />

                            <form method="POST" action="{{ route('password.confirm') }}" class="form-horizontal">
                                @csrf

                                <!-- Password -->
                                <fieldset class="form-group position-relative has-icon-left">
                                    <x-text-input id="password" type="password" name="password"
                                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        placeholder="{{ __('Password') }}" autocomplete="current-password" />
                                    <div class="form-control-position">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="invalid-feedback d-block" />
                                </fieldset>

                                <!-- Submit -->
                                <x-primary-button class="text-uppercase btn-block">
                                    {{ __('Confirm') }}
                                </x-primary-button>
                            </form>

                            <div class="d-block mt-2 text-center">
                                <a class="btn btn-outline-indigo btn-block" href="{{ route('home') }}">
                                    <i class="fa fa-arrow-left"></i>
                                    {{ __('Back to Home') }}
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
