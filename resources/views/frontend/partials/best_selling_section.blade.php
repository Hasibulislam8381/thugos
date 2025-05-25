@php
    $best_selling_products = Cache::remember('best_selling_products', 86400, function () {
        return filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(20)->get();
    });   
@endphp

@if (get_setting('best_selling') == 1)
    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="d-flex mb-3 align-items-baseline">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-width-2 pb-3 d-inline-block">{{ translate('Best Selling') }}</span>
                    </h3>
                    <h6 class="border-bottom border-width-2 ml-auto mr-0">
                        <a href="{{ route('best_selling') }}" class="view_all_btn">{{ translate('View All') }} <i class="fa-solid fa-caret-right"></i></a>
                    </h6>
                </div>
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="4" data-xl-items="4" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                    @foreach ($best_selling_products as $key => $product)
                        <div class="carousel-box">
                            @include('frontend.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
