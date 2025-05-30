<div class="modal-body p-4 c-scrollbar-light add_to_cart_modal">
    <div class="row">
        <div class="col-lg-6">
            <div class="row gutters-10 flex-row-reverse">
                @php
                    $photos = explode(',', $product->photos);
                @endphp
                <div class="col">
                    <div class="aiz-carousel product-gallery " data-nav-for='.product-gallery-thumb' data-fade='true'
                        data-auto-height='true'>
                        @foreach ($photos as $key => $photo)
                            <div class="carousel-box img-zoom rounded">
                                <img class="img-fluid lazyload" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($photo) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </div>
                        @endforeach
                        @foreach ($product->stocks as $key => $stock)
                            @if ($stock->image != null)
                                <div class="carousel-box img-zoom rounded">
                                    <img class="img-fluid lazyload"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($stock->image) }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-auto w-90px">
                    <div class="aiz-carousel carousel-thumb product-gallery-thumb set_height" data-items='5'
                        data-nav-for='.product-gallery' data-vertical='true' data-focus-select='true'>
                        @foreach ($photos as $key => $photo)
                            <div class="carousel-box c-pointer  m-1 rounded">
                                <img class="lazyload mw-100 size-60px mx-auto border"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($photo) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </div>
                        @endforeach
                        @foreach ($product->stocks as $key => $stock)
                            @if ($stock->image != null)
                                <div class="carousel-box c-pointer  m-1 rounded"
                                    data-variation="{{ $stock->variant }}">
                                    <img class="lazyload mw-100 size-50px mx-auto border"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($stock->image) }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="text-left">
                <h2 class="mb-2 fs-20 fw-600">
                    {{ $product->getTranslation('name') }}
                </h2>

                <div class="">
                    @if (home_price($product) != home_discounted_price($product))
                        <div class="row no-gutters my-2 d-flex">
                            <div class="col-sm-3 col-3">
                                {{ translate('Price') }}:
                            </div>

                            <div class="col-sm-3 col-3">
                                <div class="fs-20 opacity-60">
                                    <del>
                                        {{ home_price($product) }}
                                    </del>
                                </div>
                            </div>

                            <div class="col-sm-3 col-3">
                                <div class="">
                                    <strong class="fs-20 fw-600 text-primary">
                                        {{ home_discounted_price($product) }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                </div>
            @else
                <div class="row no-gutters mt-3">
                    <div class="col-sm-3 col-3">
                        {{ translate('Price') }}:
                    </div>
                    <div class="col-sm-19 col-9">
                        <div class="">
                            <strong class="fs-20 fw-600 text-primary">
                                {{ home_discounted_price($product) }}
                            </strong>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if (addon_is_activated('club_point') && $product->earn_point > 0)
                <div class="row no-gutters mt-4">
                    <div class="col-2">
                        <div class="opacity-50">{{ translate('Club Point') }}:</div>
                    </div>
                    <div class="col-10">
                        <div class="d-inline-block club-point bg-soft-primary px-3 py-1 border">
                            <span class="strong-700">{{ $product->earn_point }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <hr>

            @php
                $qty = 0;
                foreach ($product->stocks as $key => $stock) {
                    $qty += $stock->qty;
                }
            @endphp

            <form id="option-choice-form">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">

                <!-- Quantity + Add to cart -->
                @if ($product->digital != 1)
                    @if ($product->choice_options != null)
                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            <div class="row no-gutters">
                                <div class="col-2">
                                    <div class="opacity-50 mt-2 ">
                                        {{ \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name') }}:
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="aiz-radio-inline">
                                        @foreach ($choice->values as $key => $value)
                                            <label class="aiz-megabox pl-0 mr-2">
                                                <input type="radio" name="attribute_id_{{ $choice->attribute_id }}"
                                                    value="{{ $value }}"
                                                    @if ($key == 0) checked @endif>
                                                <span
                                                    class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-2 px-3 mb-2">
                                                    {{ $value }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (count(json_decode($product->colors)) > 0)
                        <div class="row no-gutters">
                            <div class="col-sm-3 col-3">
                                <div class="opacity-50 mt-2">{{ translate('Color') }}:</div>
                            </div>
                            <div class="col-sm-9 col-9">
                                <div class="aiz-radio-inline">
                                    @foreach (json_decode($product->colors) as $key => $color)
                                        <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip"
                                            data-title="{{ \App\Models\Color::where('code', $color)->first()->name }}">
                                            <input type="radio" name="color"
                                                value="{{ \App\Models\Color::where('code', $color)->first()->name }}"
                                                @if ($key == 0) checked @endif>
                                            <span
                                                class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                <span class="size-30px d-inline-block rounded"
                                                    style="background: {{ $color }};"></span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <hr>
                    @endif

                    <div class="row no-gutters">
                        <div class="col-3">
                            <div class="opacity-50 mt-2">{{ translate('Quantity') }}:</div>
                        </div>
                        <div class="col-9">
                            <div class="product-quantity d-flex align-items-center">
                                <div class="row no-gutters align-items-center aiz-plus-minus mr-3"
                                    style="width: 130px;">
                                    <button class="btn col-auto btn-icon btn-sm minus_btn btn___padding" type="button"
                                        data-type="minus" data-field="quantity" disabled="">
                                        <i class="las la-minus"></i>
                                    </button>

                                    <input type="text" name="quantity"
                                        class="col border-0 text-center flex-grow-1 fs-16 input-number num_input"
                                        placeholder="1" value="{{ $product->min_qty }}"
                                        min="{{ $product->min_qty }}" max="10">
                                    <button class="btn  col-auto btn-icon btn-sm btn___padding plus_btn" type="button"
                                        data-type="plus" data-field="quantity">
                                        <i class="las la-plus"></i>
                                    </button>
                                </div>
                                <div class="avialable-amount opacity-60">
                                    @if ($product->stock_visibility_state == 'quantity')
                                        (<span id="available-quantity">{{ $qty }}</span>
                                        {{ translate('available') }})
                                    @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                                        (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                @endif

                <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                    <div class="col-3">
                        <div class="opacity-50">{{ translate('Total Price') }}:</div>
                    </div>
                    <div class="col-9">
                        <div class="product-price">
                            <strong id="chosen_price" class="h4 fw-600 text-primary">

                            </strong>
                            <span class="discount_text_css">
                                <span class="discount_text"  id="discount_text"></span><span class="offer_discount fw-600" id="offer_discount"> </span>
                            </span>
                        </div>
                    </div>
                </div>

            </form>
            <div class="mt-3">
                @if ($product->digital == 1)
                    <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart" onclick="addToCart()">
                        <i class="la la-shopping-cart"></i>
                        <span class="d-none d-md-inline-block">{{ translate('Add to cart') }}</span>
                    </button>
                @elseif($qty > 0)
                    @if ($product->external_link != null)
                        <a type="button" class="btn btn-soft-primary mr-2 add-to-cart fw-600"
                            href="{{ $product->external_link }}">
                            <i class="las la-share"></i>
                            <span class="d-none d-md-inline-block">{{ translate($product->external_link_btn) }}</span>
                        </a>
                    @else
                        <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart"
                            onclick="addToCart()">
                            <i class="la la-shopping-cart"></i>
                            <span class="d-md-inline-block">{{ translate('Add to cart') }}</span>
                        </button>
                    @endif
                @endif
                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                    <i class="la la-cart-arrow-down"></i>{{ translate('Out of Stock') }}
                </button>
            </div>

        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $('#option-choice-form input').on('change', function() {
        getVariantPrice();
    });
</script>
