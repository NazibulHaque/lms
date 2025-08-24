@php
    $socials = getSocials();
    if (!empty($socials) and count($socials)) {
        $socials = collect($socials)->sortBy('order')->toArray();
    }

    $footerColumns = getFooterColumns();
@endphp

<footer class="footer position-relative user-select-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class=" footer-subscribe d-block d-md-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <strong>{{ trans('footer.join_us_today') }}</strong>
                        <span class="d-block mt-5 text-white">{{ trans('footer.subscribe_content') }}</span>
                    </div>
                    <div class="subscribe-input bg-white p-10 flex-grow-1 mt-30 mt-md-0">
                        <form action="/newsletters" method="post">
                            {{ csrf_field() }}

                            <div class="form-group d-flex align-items-center m-0">
                                <div class="w-100">
                                    <input type="text" name="newsletter_email"
                                        class="form-control border-0 @error('newsletter_email') is-invalid @enderror"
                                        placeholder="{{ trans('footer.enter_email_here') }}" />
                                    @error('newsletter_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit"
                                    class="btn btn-primary rounded-pill">{{ trans('footer.join') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $columns = ['first_column', 'second_column', 'third_column', 'forth_column'];
    @endphp

    <div class="container"
        style="background-image: url('{{ asset('assets/admin/img/BackgroundVectors.png') }}');
            background-color: #03060F;                 /* the solid color to blend */
            background-size: cover;
            background-position: center;">
        <div class="row">

            <div class="col-md-6 academy-card">
                <div class="header-container">
                    <img src="{{ asset('assets/admin/img/logo.png') }}" alt="Beacon Logo" class="logo">
                    <h1>Beacon Academy Bangladesh</h1>
                </div>
                <p>
                    Empowering future chefs through certified culinary training and expert guidance.
                </p>
            </div>

            <div class="col-md-6 beacon-container">
                <div class="page-link">
                    <ul>
                        <li>About</li>
                        <li>Courses</li>
                        <li>Workshop</li>
                        <li>Contact</li>
                        <li>Careers</li>
                        <li>Blog</li>
                    </ul>
                </div>
                <div class="footer-content">
                    <div class="address col-md-6">
                        <ul>
                            <li>Dhaka, Dhaka Division, Bangladesh</li>
                            <li>+8801904-110641</li>
                            <li>beaconacademybangladesh@gmail.com</li>
                        </ul>
                    </div>
                    <div class="footer-social col-md-6">
                        @if (!empty($socials) && count($socials))
                            @foreach ($socials as $social)
                                <a href="{{ $social['link'] }}" target="_blank">
                                    <img src="{{ $social['image'] }}" alt="{{ $social['title'] }}" class="mr-15">
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap py-3">
        <p class="m-0">©{{ now()->year }}. All Rights Reserved.</p>
        <p class="m-0">
            <a href="#" class="text-decoration-none">Terms &amp; Conditions</a>
            <span class="mx-2">|</span>
            <a href="#" class="text-decoration-none">Privacy Policy</a>
        </p>
        </div>

    </div>

    {{-- <div class="mt-40 border-blue py-25 d-flex align-items-center justify-content-between">
            <div class="footer-logo">
                <a href="/">
                    @if (!empty($generalSettings['footer_logo']))
                        <img src="{{ $generalSettings['footer_logo'] }}" class="img-cover" alt="footer logo">
                    @endif
                </a>
            </div>

            <div class="footer-social">
                @if (!empty($socials) and count($socials))
                    @foreach ($socials as $social)
                        <a href="{{ $social['link'] }}" target="_blank">
                            <img src="{{ $social['image'] }}" alt="{{ $social['title'] }}" class="mr-15">
                        </a>
                    @endforeach
                @endif
            </div>
        </div> --}}
    </div>

    {{-- @if (getOthersPersonalizationSettings('platform_phone_and_email_position') == 'footer')
        <div class="footer-copyright-card">
            <div class="container d-flex align-items-center justify-content-between py-15">
                <div class="font-14 text-white">{{ trans('update.platform_copyright_hint') }}</div>

                <div class="d-flex align-items-center justify-content-center">
                    @if (!empty($generalSettings['site_phone']))
                        <div class="d-flex align-items-center text-white font-14">
                            <i data-feather="phone" width="20" height="20" class="mr-10"></i>
                            {{ $generalSettings['site_phone'] }}
                        </div>
                    @endif

                    @if (!empty($generalSettings['site_email']))
                        <div class="border-left mx-5 mx-lg-15 h-100"></div>

                        <div class="d-flex align-items-center text-white font-14">
                            <i data-feather="mail" width="20" height="20" class="mr-10"></i>
                            {{ $generalSettings['site_email'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif --}}

</footer>
