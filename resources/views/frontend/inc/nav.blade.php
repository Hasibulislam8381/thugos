@if (get_setting('topbar_text'))
    <div class="top-navbar bg-white border-bottom  z-1035">
        <div class="topbar-image">
            @if (get_setting('topbar_text'))
                <marquee behavior="scroll" direction="left" class="marquee_data" id="marquee_data">
                    <a href="{{ get_setting('topbar_banner_link') }}" class="text-white">
                        {{ get_setting('topbar_text') }}
                    </a>
                </marquee>
            @endif
        </div>
    </div>

@endif

<header class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020   shadow-sm ">
    <div class="position-relative logo-bar-area z-1 nav_back_color">
        <div class="container">
            <div class="d-flex align-items-center">

                <div class="col-auto col-xl-2 pl-0 pr-3 d-flex align-items-center">
                    <a class="d-block py-10px header_logo_res mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-60px h-md-60px" height="40">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-60px h-md-60px" height="40">
                        @endif
                    </a>

                    {{-- @if (Route::currentRouteName() != 'home')
                        <div class="d-none d-xl-block align-self-stretch category-menu-icon-box ml-auto mr-0">
                            <div class="h-100 d-flex align-items-center" id="category-menu-icon">
                                <div
                                    class="dropdown-toggle navbar-light bg-light h-40px w-50px pl-2 rounded border c-pointer">
                                    <span class="navbar-toggler-icon"></span>
                                </div>
                            </div>
                        </div>
                    @endif --}}
                </div>
                <div class="d-lg-none ml-auto mr-0 header_search_icon_res">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x text-white"></i>
                    </a>
                </div>

                <div class="flex-grow-1 front-header-search d-flex align-items-center ">
                    <div class="position-relative flex-grow-1">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center justify-content-end searchbar_res">
                                <div class="d-lg-none search_arrow" data-toggle="class-toggle"
                                    data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i
                                            class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="input-group  mobile_searchbar">
                                    <input style="color: #333" type="text" class="search_field border-lg form-control bg_nav"
                                        id="search" name="keyword"
                                        @isset($query)
                                        value="{{ $query }}"
                                    @endisset
                                        placeholder="{{ translate('Search here..') }}" autocomplete="off">
                                    <div class="input-group-append d-none d-lg-block">
                                        <button class="btn btn-primary search__btn" type="submit">
                                            <i class="la la-search la-flip-horizontal fs-18"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-none d-lg-none ml-3 mr-0">
                    <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="d-none d-lg-block  align-self-stretch ml-3 mr-0" data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items">
                        @include('frontend.partials.cart')
                    </div>
                </div>
                <div class="d-none d-lg-block ml-3 mr-0">
                    <div class="" id="wishlist">
                        @include('frontend.partials.wishlist')
                    </div>
                </div>
                <div class="d-none d-lg-block ml-3 mr-0">
                    <div class="" id="compare">
                        @include('frontend.partials.compare')
                    </div>
                </div>

            </div>
        </div>
        @if (Route::currentRouteName() != 'home')
            <div class="hover-category-menu position-absolute w-100 top-100 left-0 right-0 d-none z-3"
                id="hover-category-menu">
                <div class="container">
                    <div class="row gutters-10 position-relative">
                        <div class="col-lg-3 position-static">
                            @include('frontend.partials.category_menu')
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @php
        $categories = \App\Models\Category::where('parent_id', 0)
            ->where('featured', 1)
            ->orderBy('order_level', 'asc')
            ->get();

    @endphp
    <div class=" py-2 nav_back_color2">
        <div class="container">
            <ul class="list-inline mb-0 pl-0 mobile-hor-swipe text-center nav_menu_bar">

                @foreach ($categories as $key => $category)
                    @php
                        $isActive = request()->is('category/' . $category->slug);
                    @endphp
                    <li class="list-inline-item mr-0 nav__item nav__item_hov {{ $isActive ? 'active' : '' }}">
                        <a href="{{ route('products.category', $category->slug) }}"
                            class="fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                            {{ $category->name }}
                        </a>
                        {{-- <a href="#"
                                class=" fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset  mobile">
                                {{ $category->name }}
                            </a> --}}
                        @php
                            $subCategory = \App\Models\Category::where('parent_id', $category->id)->get();
                        @endphp

                        @if (count($subCategory) > 0)
                            <div class="subcategories-container">
                                <div class="background_white">
                                    <div class="container">
                                        <div class="row subcategorie_inner">
                                            @foreach ($subCategory as $item)
                                                <div class="col-md-2 ">

                                                    <div class="nav_text"><a
                                                            href="{{ route('products.category', $item->slug) }}">{{ $item->name }}</a>
                                                    </div>

                                                    @php
                                                        $childCat = \App\Models\Category::where(
                                                            'parent_id',
                                                            $item->id,
                                                        )->get();
                                                    @endphp
                                                    @if (count($childCat) > 0)
                                                        @foreach ($childCat as $catItems)
                                                            <div class="nav_items opacity-50">
                                                                <a
                                                                    href="{{ route('products.category', $catItems->slug) }}">
                                                                    {{ $catItems->name }}</a>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</header>
@push('new_script')
    <script>
        const marquee = document.getElementById("marquee_data");

        marquee.addEventListener("mouseover", () => {
            marquee.stop();
        });

        marquee.addEventListener("mouseout", () => {
            marquee.start();
        });
    </script>
@endpush
