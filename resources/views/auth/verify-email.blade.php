<x-auth-layout>
    @php $pageTitle = __('Verify Email Address'); @endphp

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
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                        </p>

                        <div class="card-body">

                            @if (session('status') == 'verification-link-sent')
                                <x-alert type="success" :message="__(
                                    'A new verification link has been sent to the email address you provided during registration.',
                                )" />
                            @endif

                            <form method="POST" action="{{ route('verification.send') }}" class="form-horizontal">
                                @csrf

                                <!-- Submit -->
                                <x-primary-button class="text-uppercase btn-block">
                                    {{ __('Resend Verification Email') }}
                                </x-primary-button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="btn btn-outline-danger btn-block text-uppercase mt-2">
                                    {{ __('Log Out') }}
                                </button>
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
    @endpush
</x-auth-layout>
