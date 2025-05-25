<div class="modal-body p-4 added-to-cart">
    <div class="text-center text-success mb-4">
        <i class="las la-check-circle la-3x"></i>
        <h3>{{ translate('Item added to your cart!') }}</h3>
    </div>
    @php
        $offer_quantity = json_decode($product->offer_quantity);
        $total_price = ($data['price'] + $data['tax']) * $data['quantity'];
        if ($offer_quantity != null) {
            foreach ($offer_quantity as $o_quantity) {
                if ($o_quantity->quantity == $data['quantity']) {
                    $total_price = $total_price - $o_quantity->discount;
                }
            }
        }
        $variant_color = $data['variation'];
        $product_id = $product->id;
        $productStock = \App\Models\ProductStock::where('product_id', $product_id)
            ->where('variant', $variant_color)
            ->first();

    @endphp
    <div class="media mb-4">
        @if ($variant_color != null)
            <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ uploaded_asset(@$productStock->image) ?? uploaded_asset($product->thumbnail_img) }}"
                class="mr-3 lazyload size-100px img-fit rounded" alt="Product Image">
        @else
            <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ uploaded_asset($product->thumbnail_img) }}" class="mr-3 lazyload size-100px img-fit rounded"
                alt="Product Image">
        @endif
        <div class="media-body pt-3 text-left">
            <h6 class="fw-600">
                {{ $product->getTranslation('name') }}
            </h6>
            <div class="row mt-3">
                <div class="col-sm-2 opacity-60">
                    <div>{{ translate('Price') }}:</div>
                </div>
                <div class="col-sm-10">
                    <div class="h6 text-primary">
                        <strong>
                            {{ single_price($total_price) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-outline-primary mb-3 mb-sm-0"
            data-dismiss="modal">{{ translate('Back to shopping') }}</button>
        <a href="{{ route('cart') }}" class="btn btn-primary mb-3 mb-sm-0">{{ translate('Proceed to Checkout') }}</a>
    </div>
</div>
