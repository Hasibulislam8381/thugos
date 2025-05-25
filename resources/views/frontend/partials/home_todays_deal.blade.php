@php
    // $new_selling_products = Cache::remember('new_selling_products', 86400, function () {
    //     return filter_products(\App\Models\Product::where('published', 1)->orderBy('id', 'desc'))->limit(20)->get();
    // });
    $todays_deal_products = Cache::remember('todays_deal_products', 86400, function () {
        return filter_products(\App\Models\Product::where('published', 1)->where('todays_deal', '1'))->limit(20)->get();
    });
    // $todays_deal_products = filter_products(\App\Models\Product::where('published', 1)->where('todays_deal', '1'))
    //     ->limit(20)
    //     ->get();
@endphp

@if (count($todays_deal_products) > 0)
    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="d-flex mb-3 align-items-baseline">
                    <h3 class="h5 fw-700 mb-0">
                        <span
                            class="pb-3 d-inline-block">{{ translate('Todays Deal') }}</span>
                    </h3>
                    <h6 class="border-bottom border-width-2 ml-auto mr-0">
                        <a href="{{ route('todays_deal') }}" class="view_all_btn">{{ translate('View All') }} <i class="fa-solid fa-caret-right"></i></a>
                    </h6>
                </div>
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"
                    data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                    @foreach ($todays_deal_products as $key => $product)
                        <div class="carousel-box">
                            @include('frontend.partials.product_box_1', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
