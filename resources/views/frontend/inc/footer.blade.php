<section class="footer_background pt-5 text-dark footer-widget">
    <div class="container footer_top">
        <div class="row">

            <div class="col-md-4">
                <div class="want_the_chance">
                    Want the chance to trial our new products?
                </div>
            </div>
            <div class="col-md-4 sign_up_for">
                Sign up for the latest news, exclusive offers,and the chance to trial new and unreleased products.
            </div>
            <div class="col-md-4">
                <form class="form-inline subscribe_form" method="POST" action="{{ route('subscribers.store') }}">
                    @csrf
                    <div class="form-group mb-0">
                        <input type="email" class="form-control subscribe_input"
                            placeholder="{{ translate('Your Email Address') }}" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary subscribe_btn">{{ translate('Subscribe') }}</button>
                </form>
            </div>

        </div>
    </div>
    <div class="container">

        <div class="row">
            <div class="col-lg-5 col-xl-4 text-center text-md-left">
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="d-block">
                        @if (get_setting('footer_logo') != null)
                            <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}"
                                height="44">
                        @else
                            <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                height="44">
                        @endif
                    </a>
                    <div class="my-3">
                        {!! get_setting('about_us_description', null, App::getLocale()) !!}
                    </div>
                    <div class="d-inline-block d-md-block mb-4 under_subscribe_text">
                        Step into a world of timeless grace and unmatched elegance with Elegant Abaya. Where tradition
                        meets modernity, and every abaya tells a story of beauty and sophistication.

                    </div>

                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <div class="text-center text-md-left mt-4">
                    <h4 class="fs-13 text-uppercase fw-600 border-bottom footer_nav_title">
                        {{ get_setting('widget_one', null, App::getLocale()) }}
                    </h4>
                    <ul class="list-unstyled">
                        @if (get_setting('widget_one_labels', null, App::getLocale()) != null)
                            @foreach (json_decode(get_setting('widget_one_labels', null, App::getLocale()), true) as $key => $value)
                                <li class="mb-2 footer_nav_text">
                                    <a href="{{ json_decode(get_setting('widget_one_links'), true)[$key] }}"
                                        class="opacity-100 hov-opacity-100 text-reset">
                                        {{ $value }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 ml-xl-auto col-md-4 mr-0">
                <div class="text-center text-md-left mt-4">
                    <h4 class="fs-13 text-uppercase fw-600 border-bottom footer_nav_title">
                        {{ translate('Contact Info & Follow') }}
                    </h4>
                    <ul class="list-unstyled">

                        <li class="mb-2 footer_nav_text">
                            <a href="tel:{{ get_setting('contact_phone') }}" class="d-block opacity-100"><i
                                    class="fa-solid fa-phone mr-1"></i>{{ get_setting('contact_phone') }}</a>

                        </li>
                        <li class="mb-2 footer_nav_text">
                            <a href="mailto:{{ get_setting('contact_email') }}" class="text-reset"><i
                                    class="fa-regular fa-envelope pr-1"></i>{{ get_setting('contact_email') }}</a>

                        </li>
                        <li class="mb-2 footer_nav_text">
                            @if (get_setting('show_social_links'))

                                @if (get_setting('instagram_link') != null)
                                    <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram"><i
                                            class="fa-brands fa-instagram pr-1"></i>Instagram</a>
                                @endif

                            @endif

                        </li>
                        <li class="mb-2 footer_nav_text">
                            @if (get_setting('show_social_links'))

                                @if (get_setting('facebook_link') != null)
                                    <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook"><i
                                            class="fa-brands fa-facebook pr-1"></i> Facebook</a>
                                @endif

                            @endif

                        </li>
                        <li class="mb-2 footer_nav_text ">
                            @if (get_setting('show_social_links'))

                                @if (get_setting('youtube_link') != null)
                                    <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i
                                            class="fa-brands fa-youtube pr-1"></i>Youtube</a>
                                @endif

                            @endif

                        </li>
                        <li class="mb-2 footer_nav_text ">
                            @if (get_setting('show_social_links'))

                                @if (get_setting('tiktok_link') != null)
                                    <a href="{{ get_setting('tiktok_link') }}" target="_blank" class="tiktok"><i
                                            class="fa-brands fa-tiktok pr-1"></i>Tiktok</a>
                                @endif

                            @endif

                        </li>
                        <li class="mb-2 footer_nav_text">
                            @if (get_setting('show_social_links'))

                                @if (get_setting('linkedin_link') != null)
                                    <a href="{{ get_setting('linkedin_link') }}" target="_blank" class="linkedin"><i
                                            class="fa-brands fa-linkedin  pr-1"></i>Linkedin</a>
                                @endif

                            @endif

                        </li>
                    </ul>
                </div>
            </div>


            <div class="col-md-4 col-lg-2 footer_nav_text">
                <div class="text-center text-md-left mt-4">
                    <h4 class="fs-13 text-uppercase fw-600 border-bottom footer_nav_title ">
                        {{ translate('My Account') }}
                    </h4>
                    <ul class="list-unstyled">
                        @if (Auth::check())
                            <li class="mb-2">
                                <a class=" hov-opacity-100 text-reset" href="{{ route('logout') }}">
                                    {{ translate('Logout') }}
                                </a>
                            </li>
                        @else
                            <li class="mb-2">
                                <a class=" hov-opacity-100 text-reset" href="{{ route('user_login') }}">
                                    {{ translate('Login') }}
                                </a>
                            </li>
                        @endif
                        <li class="mb-2">
                            <a class="opacity-100 hov-opacity-100 text-reset"
                                href="{{ route('purchase_history.index') }}">
                                {{ translate('Order History') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="opacity-100 hov-opacity-100 text-reset" href="{{ route('wishlists.index') }}">
                                {{ translate('My Wishlist') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="opacity-100 hov-opacity-100 text-reset" href="{{ route('orders.track') }}">
                                {{ translate('Track Order') }}
                            </a>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="pt-3 pb-7 pb-xl-3 footer_background text-dark  text-center">
    <div class="container footer_border_top p-3">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="text-center" current-verison="{{ get_setting('current_version') }}">
                    {!! get_setting('frontend_copyright_text', null, App::getLocale()) !!}
                </div>
            </div>
            {{-- <div class="col-lg-4">
                @if (get_setting('show_social_links'))
                    <ul class="list-inline my-3 my-md-0 social colored text-center">
                        @if (get_setting('facebook_link') != null)
                            <li class="list-inline-item">
                                <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook"><i
                                        class="lab la-facebook-f"></i></a>
                            </li>
                        @endif
                        @if (get_setting('twitter_link') != null)
                            <li class="list-inline-item">
                                <a href="{{ get_setting('twitter_link') }}" target="_blank" class="twitter"><i
                                        class="lab la-twitter"></i></a>
                            </li>
                        @endif
                        @if (get_setting('instagram_link') != null)
                            <li class="list-inline-item">
                                <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram"><i
                                        class="lab la-instagram"></i></a>
                            </li>
                        @endif
                        @if (get_setting('youtube_link') != null)
                            <li class="list-inline-item">
                                <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i
                                        class="lab la-youtube"></i></a>
                            </li>
                        @endif
                        @if (get_setting('linkedin_link') != null)
                            <li class="list-inline-item">
                                <a href="{{ get_setting('linkedin_link') }}" target="_blank" class="linkedin"><i
                                        class="lab la-linkedin-in"></i></a>
                            </li>
                        @endif
                    </ul>
                @endif
            </div> --}}
            <div class="col-lg-6">
                <div class="text-center text-md-right">
                    <ul class="list-inline mb-0">
                        @if (get_setting('payment_method_images') != null)
                            @foreach (explode(',', get_setting('payment_method_images')) as $key => $value)
                                <li class="list-inline-item">
                                    <img src="{{ uploaded_asset($value) }}" height="30" class="mw-100 h-auto"
                                        style="max-height: 30px">
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom bg-white shadow-lg border-top rounded-top"
    style="box-shadow: 0px -1px 10px rgb(0 0 0 / 15%)!important; ">
    <div class="row align-items-center gutters-5">
        <div class="col">
            <a href="{{ route('home') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i
                    class="las la-home fs-20 opacity-60 {{ areActiveRoutes(['home'], 'opacity-100 text-primary') }}"></i>
                <span
                    class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['home'], 'opacity-100 fw-600') }}">{{ translate('Home') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('categories.all') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i
                    class="las la-list-ul fs-20 opacity-60 {{ areActiveRoutes(['categories.all'], 'opacity-100 text-primary') }}"></i>
                <span
                    class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['categories.all'], 'opacity-100 fw-600') }}">{{ translate('Categories') }}</span>
            </a>
        </div>
        @php
            if (auth()->user() != null) {
                $user_id = Auth::user()->id;
                $cart = \App\Models\Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = Session()->get('temp_user_id');
                if ($temp_user_id) {
                    $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
                }
            }
        @endphp
        <div class="col-auto">
            <a href="{{ route('cart') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span
                    class="align-items-center  border border-white border-width-4 d-flex justify-content-center position-relative rounded-circle size-50px"
                    style="background:#000c50;margin-top: -33px;box-shadow: 0px -5px 10px rgb(0 0 0 / 15%);border-color: #fff !important;">
                    <i class="las la-shopping-bag la-2x text-white"></i>
                </span>
                <span
                    class="d-block mt-1 fs-10 fw-600 opacity-60 {{ areActiveRoutes(['cart'], 'opacity-100 fw-600') }}">
                    {{ translate('Cart') }}
                    @php
                        $count = isset($cart) && count($cart) ? count($cart) : 0;
                    @endphp
                    (<span class="cart-count">{{ $count }}</span>)
                </span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('all-notifications') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-inline-block position-relative px-2">
                    <i
                        class="las la-bell fs-20 opacity-60 {{ areActiveRoutes(['all-notifications'], 'opacity-100 text-primary') }}"></i>
                    @if (Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"
                            style="right: 7px;top: -2px;"></span>
                    @endif
                </span>
                <span
                    class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['all-notifications'], 'opacity-100 fw-600') }}">{{ translate('Notifications') }}</span>
            </a>
        </div>
        <div class="col">
            @if (Auth::check())
                @if (isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-reset d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if (Auth::user()->photo != null)
                                <img src="{{ custom_asset(Auth::user()->avatar_original) }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
                    </a>
                @else
                    <a href="javascript:void(0)"
                        class="text-reset d-block text-center pb-2 pt-3 mobile-side-nav-thumb"
                        data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                        <span class="d-block mx-auto">
                            @if (Auth::user()->photo != null)
                                <img src="{{ custom_asset(Auth::user()->avatar_original) }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('user_login') }}" class="text-reset d-block text-center pb-2 pt-3">
                    <span class="d-block mx-auto">
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                            class="rounded-circle size-20px">
                    </span>
                    <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
<div class="whatsapp-icon">
    <a href="https://wa.me/{{ get_setting('contact_phone') }}" target="_blank">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
</div>
@if (Auth::check() && !isAdmin())
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static"
            data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div>
@endif
<style>
    .whatsapp-icon {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        /* Ensure it's above other content */
    }

    .whatsapp-icon a {
        display: block;
        width: 50px;
        height: 50px;
        background: #25d366;
        /* WhatsApp green color */
        color: #fff;
        border-radius: 50%;
        text-align: center;
        line-height: 50px;
        font-size: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
{{-- <script>
    // JavaScript for showing/hiding WhatsApp icon on scroll
    $(document).ready(function() {
        var whatsappIcon = $('.whatsapp-icon');

        // Initially hide the icon
        whatsappIcon.hide();

        // Show/hide the icon based on scroll position
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                whatsappIcon.fadeIn();
            } else {
                whatsappIcon.fadeOut();
            }
        });
    });
</script> --}}
