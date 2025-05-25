@php
    use Illuminate\Support\Facades\Session;
    $temp_user_id = Session::has('temp_user') ? session('temp_user') : (auth()->check() ? auth()->user()->id : null);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $recently_viewed_products = \App\Models\RecentlyViewed::with('viewedProduct')
        ->whereHas('viewedProduct')
        ->where('ip_address', $ip_address)
        ->orderBy('visitor', 'desc')
        ->limit(20)
        ->get();
    // $recently_viewed_products = RecentlyViewed::where('product_id', $detailedProduct->id)
    //     ->where('ip_address', $ip_address)
    //     ->orderBy('visitor','desc')
    //     ->get();
@endphp





@if (count($recently_viewed_products) > 0)
    <section class="home_page_sec_pad_25 mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="d-flex mb-3 align-items-baseline">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="pb-3 d-inline-block">{{ translate('Recently Viewed Product') }}</span>
                    </h3>
                    <h6 class="border-bottom border-width-2 ml-auto mr-0">
                        <a href="{{ route('recently_viewed') }}" class="view_all_btn">{{ translate('View All') }} <i
                                class="fa-solid fa-caret-right"></i></a>
                    </h6>
                </div>
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"
                    data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                    @foreach ($recently_viewed_products as $key => $recent_product)
                        @php
                            $product = $recent_product->viewedProduct;
                        @endphp
                        <div class="carousel-box">
                            @include('frontend.partials.product_box_1', [
                                'product' => $product,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
            
        </div>

        </div>
    </section>
@endif
