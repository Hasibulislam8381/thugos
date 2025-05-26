@php

    $qty = 0;
    foreach ($product->stocks as $key => $stock) {
        $qty += $stock->qty;
    }
@endphp

<div class="aiz-card-box  border-light rounded mt-1 mb-2 has-transition bg-white">
    @if (discount_in_percentage($product) > 0)
        <span class="badge-custom">{{ discount_in_percentage($product) }}% {{ translate('OFF') }}</span>
    @endif
    <div class="position-relative">
        <a href="{{ route('product', $product->slug) }}" class="d-block">
            <img class="img-fit lazyload mx-auto  border-light rounded has-transition"
                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>
        @if ($qty <= 0)
            <div class="rotate_stock">
                <div class="stock_out">Stock Out</div>
            </div>
        @endif

        @if ($product->wholesale_product)
            <span class="absolute-bottom-left fs-11 text-white fw-600 px-2 lh-1-8" style="background-color: #455a64">
                {{ translate('Wholesale') }}
            </span>
        @endif
        <div class="absolute-top-right aiz-p-hov-icon">
            <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})" data-toggle="tooltip"
                data-title="{{ translate('Add to wishlist') }}" data-placement="left">
                <i class="la la-heart-o"></i>
            </a>
            {{-- <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})" data-toggle="tooltip"
                data-title="{{ translate('Add to compare') }}" data-placement="left">
                <i class="las la-sync"></i>
            </a>
            <a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" data-toggle="tooltip"
                data-title="{{ translate('Add to cart') }}" data-placement="left">
                <i class="las la-shopping-cart"></i>
            </a> --}}
        </div>
    </div>
    <div class="">
        <div class="d-flex justify-content-between">
        <h3 class="fw-600 fs-13 text-truncate-2 mb-0 h-35px product__name mt-2">
            <a href="{{ route('product', $product->slug) }}"
                class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
        </h3>
        {{-- <div class="rating rating-sm mt-1">
            {{ renderStarRating($product->rating) }}
        </div> --}}
        <div class="fs-15 mt-2">
            @if (home_base_price($product) != home_discounted_base_price($product))
                <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
            @endif
            <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
        </div>
        </div> 


        @if (addon_is_activated('club_point'))
            <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                {{ translate('Club Point') }}:
                <span class="fw-700 float-right">{{ $product->earn_point }}</span>
            </div>
        @endif

        @if ($qty >= 1)
            <div class="product_box_buynow mb-2">
                @if ($product->attributes === '[]' && $product->colors === '[]')
                    <div class="btn btn-primary buy-now fw-700 second_button text-uppercase add_to_cart_btn_product w-100"
                        onclick="buyNow_temp({{ $product->id }})">
                        <i class="la la-shopping-cart fw-700 fs-20 custom_icon"></i>
                        <span>{{ translate('Buy Now') }}</span>
                    </div>
                @else
                    <div class="btn btn-primary buy-now fw-700 second_button text-uppercase add_to_cart_btn_product w-100"
                        onclick="showAddToCartModal('{{ $product->id }}')" data-toggle="tooltip"
                        data-placement="left">
                        <i class="las la-shopping-cart fs-20 pad_bot_3 custom_icon"></i>  <span>{{ translate('Buy Now') }}</span>

                    </div>
                @endif
            </div>
        @else
            <div class="text-center notify_pad mb-2">
                <button class="notify_bg btn btn-primary fw-700 text-uppercase add_to_cart_btn" data-toggle="tooltip"
                    disabled>Stock Out</button>
            </div>

        @endif
        @if ($qty >= 1)
            <div class="btn btn-primary fw-700 text-uppercase add_to_cart_btn"
                onclick="showAddToCartModal('{{ $product->id }}')" data-toggle="tooltip" data-placement="left">
                <i class="las la-shopping-cart fs-20 pad_bot_3 custom_icon"></i>Add To Cart

            </div>
        @else
            <div class="text-center notify_pad">
                <button class="notify_bg btn btn-primary fw-700 text-uppercase add_to_cart_btn"
                    onclick="notifyMe({{ $product->id }})" data-toggle="tooltip"
                    data-title="{{ translate('Notify Me') }}">Notify me</button>
            </div>
        @endif
        

    </div>


</div>
