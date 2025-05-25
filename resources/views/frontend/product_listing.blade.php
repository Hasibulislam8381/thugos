@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = \App\Models\Category::find($category_id)->meta_title;
        $meta_description = \App\Models\Category::find($category_id)->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Models\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Models\Brand::find($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')

    <section class="mb-4 pt-3">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="" method="GET">
                <div class="row mobile_row">

                    <div class="col-xl-12">

                        <ul class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item opacity-50">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                            </li>
                            @if (!isset($category_id))
                                <li class="breadcrumb-item fw-600  text-dark">
                                    <a class="text-reset"
                                        href="{{ route('search') }}">"{{ translate('All Categories') }}"</a>
                                </li>
                            @else
                                <li class="breadcrumb-item opacity-50">
                                    <a class="text-reset"
                                        href="{{ route('search') }}">{{ translate('All Categories') }}</a>
                                </li>
                            @endif
                            @if (isset($category_id))
                                <li class="text-dark fw-600 breadcrumb-item">
                                    <a class="text-reset"
                                        href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}">"{{ \App\Models\Category::find($category_id)->getTranslation('name') }}"</a>
                                </li>
                            @endif
                        </ul>
                        @php
                            $category_banner = \App\Models\Category::find($category_id);

                        @endphp
                        <div class="row">
                            <div class="col-md-12">
                                <img src="{{ uploaded_asset(@$category_banner->banner) }}" alt="" class=""
                                    width="100%">
                            </div>
                        </div>
                        <div class="row category_name">
                            <div class="col-lg col-10">
                                <h1 class="h6 fw-600 text-body">
                                    @if (isset($category_id))
                                        {{ \App\Models\Category::find($category_id)->getTranslation('name') }}
                                    @elseif(isset($query))
                                        {{ translate('Search result for ') }}"{{ $query }}"
                                    @else
                                        {{ translate('All Products') }}
                                    @endif
                                </h1>
                                <input type="hidden" name="keyword" value="{{ $query }}">
                            </div>

                        </div>

                        <div class="text-left filter_border">
                            <div class="row gutters-5 flex-wrap align-items-center">

                                <div class="col-2 col-lg-auto d-xl-none mb-lg-3 text-right">
                                    <button type="button" class="btn btn-icon p-0" data-toggle="class-toggle"
                                        data-target=".aiz-filter-sidebar">
                                        <i class="la la-filter la-2x"></i>
                                    </button>
                                </div>
                                <div class="col-6 col-lg-auto mb-3 filter_title">
                                    Filter By
                                </div>
                                <div class="col-6 col-lg-auto mb-3 w-lg-200px laptop-w-156">


                                    <select class="form-control form-control-sm aiz-selectpicker" data-live-search="true"
                                        name="category" onchange="filter()">
                                        <option value="">{{ translate('Product Type') }}</option>

                                        <option value="all_cat">
                                            {{ translate('All Category') }}</option>
                                        @foreach (\App\Models\Category::get() as $category)
                                            <option value="{{ $category->slug }}"
                                                @isset($category_id_get) @if ($category_id_get == $category->id) selected @endif @endisset>
                                                {{ $category->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-6 col-lg-auto mb-3 w-lg-200px laptop-w-156">
                                    @if (Route::currentRouteName() != 'products.brand')
                                        <select class="form-control form-control-sm aiz-selectpicker set_border"
                                            data-live-search="true" name="stock" onchange="filter()">
                                            <option value="">{{ translate('Availability') }}</option>
                                            <option value="in_stock" @if (request()->input('stock') == 'in_stock') selected @endif>
                                                In Stock
                                            </option>
                                            <option value="out_of_stock" @if (request()->input('stock') == 'out_of_stock') selected @endif>
                                                Out of Stock
                                            </option>
                                        </select>
                                    @endif
                                </div>
                                <div class="col-6 col-lg-auto mb-3 w-lg-200px laptop-w-156">
                                    @if (Route::currentRouteName() != 'products.brand')

                                        <select class="form-control form-control-sm aiz-selectpicker"
                                            data-live-search="true" name="brand" onchange="filter()">
                                            <option value="">{{ translate('All Brands') }}</option>

                                            @foreach (\App\Models\Brand::all() as $brand)
                                                <option value="{{ $brand->slug }}"
                                                    @isset($brand_info) @if ($brand_info == $brand->id) selected @endif @endisset>
                                                    {{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-6 col-lg-auto mb-3 w-lg-200px laptop-w-156 price-box-main">
                                    <div class="price" onclick="togglePriceRange()">
                                        Price
                                    </div>

                                    {{-- Price range --}}
                                    <div id="priceRangeSection" class="bg-white shadow-sm rounded mb-3"
                                        style="display: none">

                                        <div class="p-3">
                                            <div class="aiz-range-slider">
                                                <div id="input-slider-range"
                                                    data-range-value-min="@if (\App\Models\Product::count() < 1) 0 @else {{ \App\Models\Product::min('unit_price') }} @endif"
                                                    data-range-value-max="@if (\App\Models\Product::count() < 1) 0 @else {{ \App\Models\Product::max('unit_price') }} @endif">
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                            @if (isset($min_price)) data-range-value-low="{{ $min_price }}"
                                                            @elseif($products->min('unit_price') > 0)
                                                                data-range-value-low="{{ $products->min('unit_price') }}"
                                                            @else
                                                                data-range-value-low="0" @endif
                                                            id="input-slider-range-value-low"></span>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                            @if (isset($max_price)) data-range-value-high="{{ $max_price }}"
                                                            @elseif($products->max('unit_price') > 0)
                                                                data-range-value-high="{{ $products->max('unit_price') }}"
                                                            @else
                                                                data-range-value-high="0" @endif
                                                            id="input-slider-range-value-high"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Price range --}}
                                </div>



                                <div class="col-6 col-lg-2 mb-3 w-lg-200px laptop-w-156 d-flex sm-none"></div>
                                <div class="col-3 col-lg-auto mb-3 d-flex">
                                    <div class="mb-0 filter_title">{{ translate('Sort by') }}</div>
                                </div>
                                {{-- <div class="col-6 col-lg-auto mb-3 w-lg-200px d-flex"></div> --}}

                                <div class="col-6 col-lg-auto mb-3 w-lg-200px laptop-w-156 d-flex">

                                    <select class="form-control form-control-sm aiz-selectpicker" name="sort_by"
                                        onchange="filter()">
                                        <option value="newest"
                                            @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>
                                            {{ translate('Newest') }}</option>
                                        <option value="oldest"
                                            @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>
                                            {{ translate('Oldest') }}</option>
                                        <option value="price-asc"
                                            @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>
                                            {{ translate('Price low to high') }}</option>
                                        <option value="price-desc"
                                            @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>
                                            {{ translate('Price high to low') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="min_price" value="">
                        <input type="hidden" name="max_price" value="">
                        <div
                            class="row gutters-5 row-cols-xxl-6 row-cols-xl-6 row-cols-lg-6 row-cols-md-3 row-cols-2 pad_category_box">
                            @foreach ($products as $key => $product)
                                <div class="col">
                                    @include('frontend.partials.product_box_1', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                        <div class="aiz-pagination aiz-pagination-center mt-4">
                            {{ $products->appends(request()->input())->links() }}
                        </div>

                        <div class="category_description">
                            @php
                                $category_des = \App\Models\Category::where('id', $category_id)->first();
                            @endphp
                            {!! @$category_des->description ?? '' !!}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }

        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
    <script>
        // Initialize a flag to track the visibility state of the price range section
        var priceRangeVisible = false;

        function togglePriceRange() {
            var selectElement = document.querySelector('select[name="price_range"]');
            var priceRangeSection = document.getElementById('priceRangeSection');

            // Toggle the visibility based on the flag
            // console.log(!priceRangeVisible);
            if (!priceRangeVisible) {
                priceRangeSection.style.display = 'block';
                priceRangeVisible = true;
            } else {
                priceRangeSection.style.display = 'none';
                priceRangeVisible = false;
            }
        }
    </script>





@endsection
