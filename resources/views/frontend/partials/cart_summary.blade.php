@php

    $coupons = \App\Models\Coupon::where('end_date', '>', now())->get();
    foreach ($coupons as $key => $coupon) {
        $coupon_code = $coupon->code;
    }
    $discount = Session::get('coupon');

@endphp
<div class="card border-0 shadow-sm rounded" id="cart_summary">
    <div class="card-header">
        <h3 class="fs-16 fw-600 mb-0">{{ translate('Order Summary') }}</h3>
        <div class="text-right">
            <span class="badge badge-inline badge-primary" style="padding: 15px">
                {{ count($carts) }}
                {{ translate('Items') }}
            </span>
        </div>
    </div>

    <div class="card-body" style="border:1px solid #000c50; margin:15px; padding:5px">
        {{-- @if (addon_is_activated('club_point'))
            @php
                $total_point = 0;
            @endphp

            @foreach ($carts as $key => $cartItem)
                @php

                    $product = \App\Models\Product::find($cartItem['product_id']);
                    $total_point += $product->earn_point * $cartItem['quantity'];
                @endphp
            @endforeach

            <div class="rounded px-2 mb-2 bg-soft-primary border-soft-primary border">
                {{ translate('Total Club point') }}:
                <span class="fw-700 float-right">{{ $total_point }}</span>
            </div>
        @endif --}}
        <table class="table order_summery_table">
            {{-- <thead>
                <tr>
                    <th class="product-name">{{translate('Product')}}</th>
                    <th class="product-total text-right">{{translate('Total')}}</th>
                </tr>
            </thead> --}}
            <tbody>
                @php
                    $subtotal = 0;
                    $tax = 0;
                    $shipping = Session::get('delivery_charge');
                @endphp
                @if (Auth::check() && get_setting('coupon_system') == 1)
                    <tr>
                        <div class="mt-3 promo_code ml-2 mb-1 d-flex">
                            <input type="checkbox" id="promo_code" name="" value="{{ $coupon_code }}">
                            <div class="ml-2"><b>Promo Code</b> </div>
                        </div>
                        <div class="promo_code_input">
                            @if (Auth::check() && get_setting('coupon_system') == 1)
                                @if ($carts[0]['discount'] > 0)
                                    <div class="mt-3">
                                        {{-- <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                                            @csrf --}}
                                        <input type="hidden" id="owner_id" name="owner_id"
                                            value="{{ $carts[0]['owner_id'] }}">
                                        <input type="hidden" id="code" name="code"
                                            value="{{ $carts[0]['coupon_code'] }}">
                                        <div class="input-group">
                                            <div class="form-control">{{ $carts[0]['coupon_code'] }}</div>
                                            <div class="input-group-append">
                                                <button type="button" id="coupon-remove"
                                                    class="btn btn-primary">{{ translate('Change Promo') }}</button>
                                            </div>
                                        </div>
                                        {{-- </form> --}}
                                    </div>
                                @else
                                    <div class="mt-3">
                                        {{-- <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                                            @csrf --}}
                                        <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                                        <div class="input-group">
                                            <input type="text" id="couponCode" class="form-control" name="code"
                                                onkeydown="return event.key != 'Enter';"
                                                placeholder="{{ translate('Have Promo code? Enter here') }}">
                                            <div class="input-group-append">
                                                <button type="button" id="coupon-apply"
                                                    class="btn btn-primary">{{ translate('Apply') }}</button>
                                            </div>
                                        </div>
                                        {{-- </form> --}}
                                    </div>
                                @endif
                            @endif


                        </div>
                    </tr>
                @endif
                @foreach ($carts as $key => $cartItem)
                    @php

                        $product = \App\Models\Product::find($cartItem['product_id']);
                        $offer_discount = getOfferDiscount($product->id, $cartItem['quantity']);

                        $subtotal += $cartItem['price'] * $cartItem['quantity'] - $offer_discount;
                        $tax += $cartItem['tax'] * $cartItem['quantity'];

                        $product_name_with_choice = $product->getTranslation('name');
                        if ($cartItem['variant'] != null) {
                            $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
                        }
                        $getColor = explode('-', $cartItem->variation);

                    @endphp

                    <tr class="cart_item">
                        <td class="product-name">
                            <b>Product Name</b>

                        </td>
                        <td class="product-total text-right">
                            {{ $product_name_with_choice }}

                            @if (@$getColor[0] || @$getColor[1] != '')
                                ({{ @$getColor[0] }})
                                ({{ @$getColor[1] }})
                            @endif

                            <strong class="product-quantity">
                                × {{ $cartItem['quantity'] }}
                            </strong>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table order_summery_table">

            <tfoot class="">
                <tr class="cart-subtotal">
                    <th>{{ translate('Subtotal') }}</th>
                    <td class="text-right">
                        {{-- <span
                            class="pl-4 pr-0"><b>{{ single_price($cartItem['price'] * $cartItem['quantity']) }}</b></span> --}}
                        <span class="fw-600">{{ single_price($subtotal) }}</span>
                    </td>
                </tr>

                <tr class="cart-shipping">
                    <th>{{ translate('Delivery Charge') }}</th>
                    <td class="text-right">
                        <strong><span class="font-italic">৳ {{ Session::get('delivery_charge') }}</span></strong>
                    </td>
                </tr>

                @if (Session::has('club_point'))
                    <tr class="cart-shipping">
                        <th>{{ translate('Redeem point') }}</th>
                        <td class="text-right">
                            <span class="font-italic">৳{{ single_price(Session::get('club_point')) }}</span>
                        </td>
                    </tr>
                @endif


                @if ($cartItem['discount'] > 0)
                    <tr class="cart-shipping">
                        <th>{{ translate('Coupon Discount') }}</th>
                        <td class="text-right">
                            <span class="font-italic"><strong>৳ {{ $cartItem['discount'] }}</strong></span>
                        </td>
                    </tr>
                @endif

                @php

                    $total = $subtotal + $tax + $shipping;
                    if (Session::has('club_point')) {
                        $total -= Session::get('club_point');
                    }
                    if ($cartItem['discount'] > 0) {
                        $total -= $cartItem['discount'];
                    }
                @endphp

                <tr class="cart-total">
                    <th><span class="strong-600">{{ translate('Total Amount to Pay') }}</span></th>
                    <td class="text-right">
                        <strong><span>{{ single_price($total) }}</span></strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        @if (addon_is_activated('club_point'))
            @if (Session::has('club_point'))
                <div class="mt-3">
                    <form class="" action="{{ route('checkout.remove_club_point') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <div class="form-control">{{ Session::get('club_point') }}</div>
                            <div class="input-group-append">
                                <button type="submit"
                                    class="btn btn-primary">{{ translate('Remove Redeem Point') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                {{-- @if (Auth::user()->point_balance > 0)
                    <div class="mt-3">
                        <p>
                            {{translate('Your club point is')}}:
                            @if (isset(Auth::user()->point_balance))
                                {{ Auth::user()->point_balance }}
                            @endif
                        </p>
                        <form class="" action="{{ route('checkout.apply_club_point') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="point" placeholder="{{translate('Enter club point here')}}" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{translate('Redeem')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif --}}
            @endif
        @endif



    </div>
</div>
@push('new_script')
    <script>
        $(document).ready(function() {
            $(".promo_code_input").hide();

            if ($("#promo_code").is(":checked") && $("#promo_code").val()) {
                $(".promo_code_input").show();
            }

            $("#promo_code").change(function() {
                if ($(this).is(":checked") && $(this).val()) {
                    $(".promo_code_input").show();
                } else {
                    $(".promo_code_input").hide();
                }
            });
        });
    </script>
    <script>
        $(document).on("click", "#coupon-apply", function(e) {
            e.preventDefault();
            var data = $('#couponCode').val();
            //             $.ajax({
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //                 },
            //                 method: "POST",
            //                 url: "{{ route('checkout.apply_coupon_code') }}",
            //                 data: data,
            //                 cache: false,
            //                 success: function (data, textStatus, jqXHR) {
            //                     AIZ.plugins.notify(data.response_message.response, data.response_message.message);
            // //                    console.log(data.response_message);
            //                     $("#cart_summary").html(data.html);
            //                 }
            //             })

            $.ajax({
                type: 'POST',
                url: '{{ route('checkout.apply_coupon_code') }}',
                data: {
                    data: data,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data, textStatus, jqXHR) {
                    // location.reload();
                    console.log(data);
                    AIZ.plugins.notify(data.response_message.response, data.response_message.message);

                    $("#cart_summary").html(data.html);

                },
                error: function(error) {
                    console.error('Error updating session variable:', error);
                }
            });
        });

        $(document).on("click", "#coupon-remove", function() {
            var data = new FormData($('#remove-coupon-form')[0]);
            var owner_id = $('#owner_id').val();
            var code = $('#code').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.remove_coupon_code') }}",
                data: data,
                code: code,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    // location.reload();
                    AIZ.plugins.notify('success', 'Promo Code Changed');
                    $("#cart_summary").html(data);
                }
            })
        })
    </script>
    <script>
        var new__price = 0;
        var upohar__price = 0;
        // if ($('input[type="radio"]:checked').length > 0) {
        //     $(".class__continue").removeClass("disabled-link");
        //     $(".class__continue").removeAttr("disabled");
        //     var radioValue = $(this).val();
        //     $(".delivery_charge").html(radioValue + 'tk');
        //     updateSessionVariable('delivery_charge', this.value);
        // }

        $('.delivery_charge').click(function() {
            var price = Number($(".get__total__price").val());
            var delivery = price + Number(this.value);
            updateSessionVariable('delivery_charge', this.value);


            new__price = delivery;
            $(".class__continue").removeClass("disabled-link");
            $(".class__continue").removeAttr("disabled");
            if (upohar__price > 0) {
                $(".new__price").html((delivery + upohar__price));
            } else {
                $(".new__price").html(delivery + 'tk');
            }

            var radioValue = $(this).val();
            $(".delivery_charge").html(radioValue + 'tk');


            // updateSession('selected_radio', radioValue);

        });
        // $('input:checkbox').change(
        //     function() {
        //         if ($(this).is(':checked')) {
        //             upohar__price = Number(this.value);
        //             if (new__price > 0) {
        //                 $(".new__price").html("৳" + (new__price + upohar__price));
        //             }
        //             updateSessionVariable('upohar_price', upohar__price);
        //         } else {
        //             if (new__price > 0) {
        //                 $(".new__price").html("৳" + (new__price));
        //             }
        //             updateSessionVariable('upohar_price', 0);
        //         }

        //         var checkboxValue = $(this).is(':checked');


        //         updateSession('checkbox_checked', checkboxValue);
        //     });


        function updateSessionVariable(variableName, value) {
            $.ajax({
                type: 'POST',
                url: '/update-session',
                data: {
                    variableName: variableName,
                    value: value
                },
                success: function(response) {},
                error: function(error) {
                    console.error('Error updating session variable:', error);
                }
            });
        }
    </script>
@endpush
