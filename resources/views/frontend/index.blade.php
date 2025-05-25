@extends('frontend.layouts.app')

@section('content')

    <div class="home-banner-area mb-4">
        <div class="">
            <div class="row gutters-10 position-relative">


                @php
                    $num_todays_deal = count($todays_deal_products);

                @endphp

                <div class="@if ($num_todays_deal > 0) col-lg-12 @else col-lg-12 @endif">
                    @if (get_setting('home_slider_images') != null)
                        <div class="aiz-carousel banner dots-inside-bottom mobile-img-auto-height" data-arrows="true"
                            data-dots="true" data-autoplay="true">
                            @php $slider_images = json_decode(get_setting('home_slider_images'), true);  @endphp
                            @foreach ($slider_images as $key => $value)
                                <div class="carousel-box">
                                    <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                        <img class="d-block mw-100 img-fit rounded shadow-sm overflow-hidden"
                                            src="{{ uploaded_asset($slider_images[$key]) }}"
                                            alt="{{ env('APP_NAME') }} promo"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    {{-- @if (count($featured_categories) > 0)
                        <ul class="list-unstyled mb-0 row gutters-5">
                            @foreach ($featured_categories as $key => $category)
                                <li class="minw-0 col-4 col-md mt-3">
                                    <a href="{{ route('products.category', $category->slug) }}"
                                        class="d-block rounded bg-white p-2 text-reset shadow-sm">
                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($category->banner) }}"
                                            alt="{{ $category->getTranslation('name') }}" class="lazyload img-fit"
                                            height="78"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        <div class="text-truncate fs-12 fw-600 mt-2 opacity-70">
                                            {{ $category->getTranslation('name') }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif --}}
                </div>


            </div>
        </div>
    </div>
    <div id="new_selling">

    </div>
    {{-- Best Selling  --}}
    <div id="section_best_selling">

    </div>
    <div id="recently_viewed">

    </div>

    {{-- Featured Section --}}
    <div id="section_featured">

    </div>
    @if ($num_todays_deal > 0)
        <div id="todays_deal">

        </div>
    @endif

    {{-- Flash Deal --}}
    @php
        $flash_deals = \App\Models\FlashDeal::where('status', 1)->get();
    @endphp
    @if ($flash_deals->isNotEmpty())
        <section class="home_page_sec_pad_25 current_offer_res mb-4">
            <div class="container">
                <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                    <div class="d-flex align-items-baseline">
                        <h3 class="h5 fw-700 mb-0">
                            <span class="border-width-2 pb-3 d-inline-block">{{ translate('Flash Sale') }}</span>
                        </h3>
                    </div>
                    <div class="row gutters-10 mt-2">
                        @foreach ($flash_deals as $key => $flash_deal)
                            @if (
                                $flash_deal != null &&
                                    strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date &&
                                    strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
                                <div class="col-xl-4 col-md-4 col-lg-4 col-sm-6  col-6 mb-3">
                                    <div class="mb-3 mb-lg-0 position-relative">
                                        <div class="banner_bot_text text-uppercase mt-1 flash_sale_count_down">
                                            <div class="aiz-count-down justify-content-end ml-auto ml-lg-3 align-items-center current_offer_count_down"
                                                data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                        </div>

                                        <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                            class="d-block text-reset">
                                            <div class="banner_img_main_div">
                                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                    data-src="{{ uploaded_asset($flash_deal->p_image) }}"
                                                    alt="{{ env('APP_NAME') }} promo"
                                                    class="img-fluid lazyload w-100 banner_img">
                                                <div class="flash-overlay">
                                                    <div class="flash-overlay-text">{{ $flash_deal->title }}</div>
                                                </div>
                                            </div>

                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- gallery --}}
    <div id="section_home_gallery">

    </div>







    {{-- Category wise Products --}}
    <div id="section_home_categories">

    </div>

    {{-- Classified Product --}}
    @if (get_setting('classified_product') == 1)
        @php
            $classified_products = \App\Models\CustomerProduct::where('status', '1')
                ->where('published', '1')
                ->take(10)
                ->get();
        @endphp
        @if (count($classified_products) > 0)
            <section class="mb-4">
                <div class="container">
                    <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                            <h3 class="h5 fw-700 mb-0">
                                <span
                                    class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Classified Ads') }}</span>
                            </h3>
                            <a href="{{ route('customer.products') }}"
                                class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View More') }}</a>
                        </div>
                        <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5"
                            data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                            @foreach ($classified_products as $key => $classified_product)
                                <div class="carousel-box">
                                    <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                        <div class="position-relative">
                                            <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                class="d-block">
                                                <img class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($classified_product->thumbnail_img) }}"
                                                    alt="{{ $classified_product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <div class="absolute-top-left pt-2 pl-2">
                                                @if ($classified_product->conditon == 'new')
                                                    <span
                                                        class="badge badge-inline badge-success">{{ translate('new') }}</span>
                                                @elseif($classified_product->conditon == 'used')
                                                    <span
                                                        class="badge badge-inline badge-danger">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="p-md-3 p-2 text-left">
                                            <div class="fs-15 mb-1">
                                                <span
                                                    class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                            </div>
                                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block text-reset">{{ $classified_product->getTranslation('name') }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    {{-- Banner Section 2 --}}
    @if (get_setting('home_banner3_images') != null)
        <div class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @php $banner_3_imags = json_decode(get_setting('home_banner3_images')); @endphp
                    @foreach ($banner_3_imags as $key => $value)
                        <div class="col-xl col-md-6">
                            <div class="mb-3 mb-lg-0">
                                <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}"
                                    class="d-block text-reset">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($banner_3_imags[$key]) }}"
                                        alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Best Seller --}}
    {{-- <div id="section_best_sellers">

    </div> --}}

    {{-- Banner section 1 --}}
    @if (get_setting('home_banner1_images') != null)
        <div class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @php $banner_1_imags = json_decode(get_setting('home_banner1_images')); @endphp
                    @foreach ($banner_1_imags as $key => $value)
                        <div class="col-xl">
                            <div class="mb-3 mb-lg-0">
                                <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}"
                                    class="d-block text-reset">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($banner_1_imags[$key]) }}"
                                        alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Top 10 categories and Brands --}}


    @if (get_setting('top10_categories') != null)
        <section class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @if (get_setting('top10_categories') != null)
                        <div class="col-lg-12">
                            <div class="d-flex mb-3 align-items-baseline">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="pb-3 d-inline-block">{{ translate('Top Categories') }}</span>
                                </h3>
                                <h6 class="border-bottom border-width-2 ml-auto mr-0">
                                    <a href="{{ route('categories.all') }}"
                                        class="view_all_btn">{{ translate('View All') }} <i
                                            class="fa-solid fa-caret-right"></i></a>
                                </h6>
                            </div>
                            <div class="aiz-carousel gutters-10 half-outside-arrow category_arrow" data-items="4"
                                data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2"
                                data-xs-items="2" data-arrows='true' data-infinite='true'>
                                @php $top10_categories = json_decode(get_setting('top10_categories')); @endphp
                                @foreach ($top10_categories as $key => $value)
                                    @php $category = \App\Models\Category::find($value); @endphp
                                    @if ($category != null)
                                        <div class="carousel-box">
                                            <a href="{{ route('products.category', $category->slug) }}"
                                                class="bg-white cat_border d-block text-reset  p-2 mb-2">
                                                <div class="align-items-center no-gutters p-2">
                                                    <div class="text-center overflow-hidden rounded">
                                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                            data-src="{{ uploaded_asset($category->icon) }}"
                                                            alt="{{ $category->getTranslation('name') }}"
                                                            class="img-fluid img lazyload hov-shadow-md  cat_img"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="text-truncat-2 pl-3 fs-14 fw-600 text-center">
                                                            {{ $category->getTranslation('name') }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
    @if (get_setting('top10_brands') != null)
        <section class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @if (get_setting('top10_brands') != null)
                        <div class="col-lg-12">
                            <div class="d-flex mb-3 align-items-baseline">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="pb-3 d-inline-block">{{ translate('Shop by Brands') }}</span>
                                </h3>
                                <h6 class="border-bottom border-width-2 ml-auto mr-0">
                                    <a href="{{ route('brands.all') }}" class="view_all_btn">{{ translate('View All') }}
                                        <i class="fa-solid fa-caret-right"></i></a>
                                </h6>
                            </div>
                            <div class="brand_box">
                                <div class="aiz-carousel gutters-10 half-outside-arrow brand_arrow" data-items="5"
                                    data-xl-items="5" data-lg-items="5" data-md-items="3" data-sm-items="2"
                                    data-xs-items="2" data-arrows='true' data-infinite='true'>
                                    @php $top10_brands = json_decode(get_setting('top10_brands')); @endphp
                                    @foreach ($top10_brands as $key => $value)
                                        @php $brand = \App\Models\Brand::find($value); @endphp
                                        @if ($brand != null)
                                            <div class="carousel-box">
                                                <a href="{{ route('products.brand', $brand->slug) }}"
                                                    class="bg-white d-block text-reset p-2 hov-shadow-md">
                                                    <div class="align-items-center no-gutters">
                                                        <div class="text-center">
                                                            <img src="{{ static_asset('brand/placeholder.jpg') }}"
                                                                data-src="{{ uploaded_asset($brand->logo) }}"
                                                                alt="{{ $brand->getTranslation('name') }}"
                                                                class="img-fluid img lazyload"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('brand/placeholder.jpg') }}';">
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif


    @if (get_setting('home_customer_review') != null)
        <section class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @if (get_setting('home_customer_review') != null)
                        <div class="col-lg-12">
                            <div class="d-flex mb-3 align-items-baseline">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="pb-3 d-inline-block">{{ translate('Customer Review') }}</span>
                                </h3>
                                <h6 class="border-bottom border-width-2 ml-auto mr-0">
                                    <a href="{{ route('reviews') }}" class="view_all_btn">{{ translate('View All') }} <i
                                            class="fa-solid fa-caret-right"></i></a>
                                </h6>
                            </div>
                            <div class="aiz-carousel gutters-10 half-outside-arrow review_arrow" data-items="4"
                                data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2"
                                data-xs-items="2" data-arrows='true' data-infinite='true'>
                                @php
                                    $home_customer_review = json_decode(get_setting('home_customer_review'));
                                    // dd($home_customer_review);
                                @endphp
                                @foreach ($home_customer_review as $review_photo)
                                    <div class="carousel-box">

                                        <div class="align-items-center no-gutters p-2">
                                            <div class="text-center overflow-hidden rounded">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($review_photo) }}" alt=""
                                                    class="img-fluid img lazyload hov-shadow-md "
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </div>

                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Banner Section 2 --}}
    @if (get_setting('home_banner2_images') != null)
        <div class="mb-4">
            <div class="container">
                <div class="row gutters-10">
                    @php $banner_2_imags = json_decode(get_setting('home_banner2_images')); @endphp
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="col-xl col-md-6">
                            <div class="mb-3 mb-lg-0">
                                <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}"
                                    class="d-block text-reset">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($banner_2_imags[$key]) }}"
                                        alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    {{-- top_client  --}}
    {{-- <div id="section_top_client">

    </div> --}}

    {{-- blogs --}}
    <div id="section_home_blog">

    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $.post('{{ route('home.section.featured') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.recently_viewed') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#recently_viewed').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.new_selling') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#new_selling').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.top_client') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_top_client').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_selling') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.home_categories') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.home_todays_deal') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#todays_deal').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.home_gallery') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_home_gallery').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.home_blog') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_home_blog').html(data);
                AIZ.plugins.slickCarousel();
            });

        });
    </script>






    <!--Csustom Code- Tawk.to chat by Mredul-->
    {{-- 
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/6197db9d6bb0760a49436b34/1fksj3pi3';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script> --}}


    <!--End of Tawk.to Script------------------------------------>
@endsection
