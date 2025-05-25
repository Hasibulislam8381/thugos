<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Order id') }}: {{ $order->code }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>

@php

    $advance_payment = intval(get_setting('advance_payment'));
    $status = $order->orderDetails->first()->delivery_status;
    $shipping_address = json_decode($order->shipping_address);
    $order_details = $order->orderDetails->first();

    if ($order) {
        if (Session::has('pathao_cities')) {
            $pathao_cities = session()->get('pathao_cities');
        } else {
            $pathao_cities = pathao_city();
        }

        $filtered_city = '';
        foreach ($pathao_cities as $city) {
            if ($city->city_id == $shipping_address->city_id) {
                $filtered_city = $city;

                break;
            }
        }
    }
    $pathao_zone = pathao_zone(@$filtered_city->city_id);

    $filtered_zone = null;
    foreach ($pathao_zone as $zone) {
        if ($zone->zone_id == $shipping_address->zone_id) {
            $filtered_zone = $zone;
            break;
        }
    }
    if ($filtered_zone->zone_id) {
        $pathao_area = pathao_area($filtered_zone->zone_id);
        $filtered_area = null;
        foreach ($pathao_area as $area) {
            if ($area->area_id == $shipping_address->area_id) {
                $filtered_area = $area;
                break;
            }
        }
    }
    $shipping_name = \App\Models\BusinessSetting::where('type', 'outside_dhaka')->first();
    $shipping_type = getShippingType($order_details->shipping_cost);
    $offer_discount = 0;

@endphp

<div class="modal-body gry-bg px-3 pt-3">
    {{-- <div class="py-4">
        <div class="row gutters-5 text-center aiz-steps">
            <div class="col @if ($status == 'pending') active @else done @endif">
                <div class="icon">
                    <i class="las la-file-invoice"></i>
                </div>
                <div class="title fs-12">{{ translate('Order placed')}}</div>
            </div>
            <div class="col @if ($status == 'confirmed') active @elseif($status == 'on_delivery' || $status == 'delivered') done @endif">
                <div class="icon">
                    <i class="las la-newspaper"></i>
                </div>
              <div class="title fs-12">{{ translate('Confirmed')}}</div>
            </div>
            <div class="col @if ($status == 'on_delivery') active @elseif($status == 'delivered') done @endif">
                <div class="icon">
                    <i class="las la-truck"></i>
                </div>
                <div class="title fs-12">{{ translate('On delivery')}}</div>
            </div>
            <div class="col @if ($status == 'delivered') done @endif">
                <div class="icon">
                    <i class="las la-clipboard-check"></i>
                </div>
                <div class="title fs-12">{{ translate('Delivered')}}</div>
            </div>
        </div>
    </div> --}}
    <div class="card mt-4">
        <div class="p-2">
            @if ($shipping_name->type == 'outside_dhaka')
            <h3>{{ translate('সম্মানিত কাস্টমার মৌ গ্যালারি ওয়েব সাইটে অর্ডার করার জন্য আপনাকে ধন্যবাদ।') }}</h3>
                <h4 class="fw-500 text-danger"> আপনার প্রোডাক্টের দাম এবং ডেলিভারি চার্জ সহ টোটাল টাকা থেকে
                    {{ get_setting('advance_payment') }} টাকা
                    {{ translate(' বিকাশ না করলে ঢাকার বাহিরে অর্ডার কনফার্ম হবে না , আপনার অর্ডার এবং পেমেন্ট কনফার্ম এর জন্য  আমাদের একজন প্রতিনিধি আগামী ২৪ ঘণ্টার মধ্যে আপনার সাথে যোগাযোগ করবে  ধন্যবাদ।') }}
                </h4>
                <h5>এডভান্স টাকা পাঠাতে এই নাম্বারে বিকাশ করুন </h5>
                <h3>{{ get_setting('bkash') }}</h3>
            @endif
        </div>
        {{-- @if (
            $order->payment_type == 'cash_on_delivery' &&
                $order->advance_payment != 'paid' &&
                $order->payment_status != 'paid' &&
                $shipping_type == 'Outside Dhaka')
            <h3 class="fw-600 text-danger text-center p-2">{{ translate('Please Pay ') }}
                {{ get_setting('advance_payment') }} tk
                {{ translate('first otherwise Your Order Will be Cancelled') }}
            </h3>
            <div class="p-2">
                <form action="{{ route('advance_payment') }}" method="POST">
                    @csrf
                    @if (get_setting('sslcommerz_payment') == 1)
                        <div class="col-6 col-md-3">
                            <label class="aiz-megabox d-block mb-3">
                                <input value="sslcommerz" class="online_payment" type="radio" name="payment_option"
                                    checked>
                                <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                    style="border-radius: 5px !important">
                                    <img src="{{ static_asset('assets/img/cards/sslcommerz.png') }}"
                                        class="img-fluid mb-2">
                                    <span class="d-block text-center">
                                        <span class="d-block fw-600 fs-13">{{ translate('sslcommerz') }}</span>
                                    </span>
                                </span>
                            </label>
                        </div>

                        <input type="hidden" name="grand_total" id=""
                            value="{{ get_setting('advance_payment') }}">
                        <input type="hidden" name="shipping_address" value="{{ $order->shipping_address }}">
                        <input type="hidden" name="order_details" value="{{ $order }}">
                    @endif
                    <div class="ml-3">
                        <button type="submit" class="btn btn-sm btn-primary">
                            {{ translate('Pay Now') }}
                        </button>
                    </div>
                </form>
            </div>
        @endif --}}
        <div class="card-header">
            <b class="fs-15">{{ translate('Order Summary') }}</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order Code') }}:</td>
                            <td>{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Customer') }}:</td>
                            <td>{{ $shipping_address->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Email') }}:</td>
                            @if ($order->user_id != null)
                                <td>{{ $shipping_address->email }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Shipping address') }}:</td>
                            <td> {{ $filtered_area->area_name }},
                                {{ $filtered_zone->zone_name }},
                                {{ $filtered_city->city_name }},
                                {{ $shipping_address->address }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order date') }}:</td>
                            <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order status') }}:</td>
                            <td>{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Total Amount to Pay') }}:</td>
                            {{-- <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}
                            </td> --}}
                            <td>
                                @if ($order->advance_payment == 'paid')
                                    {{ single_price($order->grand_total - $advance_payment) }}
                                @else
                                    {{ single_price($order->grand_total) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Shipping') }}:</td>
                            <td>{{ $shipping_type }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Payment method') }}:</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                        </tr>
                        @if ($order->tracking_code)
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Tracking code') }}:</td>
                                <td>{{ $order->tracking_code }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div class="card mt-4">
                <div class="card-header">
                    <b class="fs-15">{{ translate('Order Details') }}</b>
                </div>
                <div class="card-body pb-0">
                    <table class="table table-borderless table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th >{{ translate('Image') }}</th>
                                <th width="30%">{{ translate('Product') }}</th>
                                <th>{{ translate('Quantity') }}</th>
                                {{-- <th>{{ translate('Delivery Type') }}</th> --}}
                                <th>{{ translate('Price') }}</th>
                                <th>{{ translate('Payment Status') }}</th>
                                @if (addon_is_activated('refund_request'))
                                    <th>{{ translate('Refund') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @php
                                    
                                    $offer_discount += getOfferDiscount(
                                        $orderDetail->product_id,
                                        $orderDetail->quantity,
                                    );
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}" width="60" alt="">
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                            {{ $orderDetail->variation }}
                                        @elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                            {{ $orderDetail->variation }}
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $orderDetail->quantity }}
                                    </td>
                                    {{-- <td>
                                        @if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($orderDetail->shipping_type == 'pickup_point')
                                            @if ($orderDetail->pickup_point != null)
                                                {{ $orderDetail->pickup_point->name }}
                                                ({{ translate('Pickip Point') }})
                                            @endif
                                        @endif
                                    </td> --}}
                                    <td>{{ single_price($orderDetail->price) }}</td>
                                    <td>
                                        @if ($order->payment_status == 'paid')
                                            <span
                                                class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                        @elseif ($order->payment_status == 'advance')
                                            <span
                                                class="badge badge-inline badge-success">{{ translate('Advance Paid') }}</span>
                                        @else
                                            <span
                                                class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                        @endif
                                    </td>
                                    @if (addon_is_activated('refund_request'))
                                        @php
                                            $no_of_max_day = get_setting('refund_request_time');
                                            $last_refund_date = $orderDetail->created_at->addDays($no_of_max_day);
                                            $today_date = Carbon\Carbon::now();
                                        @endphp
                                        <td>
                                            @if (
                                                $orderDetail->product != null &&
                                                    $orderDetail->product->refundable != 0 &&
                                                    $orderDetail->refund_request == null &&
                                                    $today_date <= $last_refund_date &&
                                                    $orderDetail->payment_status == 'paid' &&
                                                    $orderDetail->delivery_status == 'delivered')
                                                <a href="{{ route('refund_request_send_page', $orderDetail->id) }}"
                                                    class="btn btn-primary btn-sm">{{ translate('Send') }}</a>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 0)
                                                <b class="text-info">{{ translate('Pending') }}</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 2)
                                                <b class="text-success">{{ translate('Rejected') }}</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 1)
                                                <b class="text-success">{{ translate('Approved') }}</b>
                                            @elseif ($orderDetail->payment_status == 'advance')
                                                <b class="text-success">{{ translate('Advance Paid') }}</b>
                                            @elseif ($orderDetail->product->refundable != 0)
                                                <b>{{ translate('N/A') }}</b>
                                            @else
                                                <b>{{ translate('Non-refundable') }}</b>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mt-4">
                <div class="card-header">
                    <b class="fs-15">{{ translate('Order Ammount') }}</b>
                </div>
                <div class="card-body pb-0">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Subtotal') }}</td>
                                <td class="text-right">
                                    <span
                                        class="strong-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Shipping') }}</td>
                                <td class="text-right">
                                    <span
                                        class="text-italic">{{ single_price($order->orderDetails[0]->shipping_cost) }}</span>
                                </td>
                            </tr>

                            @if (intval($order->orderDetails->sum('tax')) > 0)
                                <tr>
                                    <td class="w-50 fw-600">{{ translate('Tax') }}</td>
                                    <td class="text-right">
                                        <span
                                            class="text-italic">{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                    </td>
                                </tr>
                            @endif
                            @if ($order->coupon_discount > 0)
                                <tr>
                                    <td class="w-50 fw-600">{{ translate('Coupon') }}</td>
                                    <td class="text-right">
                                        <span class="text-italic">{{ single_price($order->coupon_discount) }}</span>
                                    </td>
                                </tr>
                            @endif
                            @if ($offer_discount > 0)
                                <tr>
                                    <th>{{ translate('Discount') }}</th>
                                    <td class="text-right">
                                        <span class="font-italic">{{ single_price($offer_discount) }}</span>
                                    </td>
                                </tr>
                            @endif



                            <tr>
                                <td class="w-50 fw-600">{{ translate('Total') }}</td>
                                <td class="text-right">
                                    <strong><span>

                                            {{ single_price($order->grand_total) }}
                                        </span></strong>
                                </td>
                            </tr>
                            @if ($order->advance_payment == 'paid')
                                <tr>
                                    <td class="w-50 fw-600">{{ translate('Advance Payment') }}</td>
                                    <td class="text-right">
                                        <span class="text-italic">৳ {{ $advance_payment }}</span>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="w-50 fw-600">{{ translate('Due') }}</td>
                                <td class="text-right">
                                    <strong><span>
                                            @if ($order->advance_payment == 'paid')
                                                {{ single_price($order->grand_total - $advance_payment) }}
                                            @else
                                                {{ single_price($order->grand_total) }}
                                            @endif
                                        </span></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($order->manual_payment && $order->manual_payment_data == null)
                <button onclick="show_make_payment_modal({{ $order->id }})"
                    class="btn btn-block btn-primary">{{ translate('Make Payment') }}</button>
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
    function show_make_payment_modal(order_id) {
        $.post('{{ route('checkout.make_payment') }}', {
            _token: '{{ csrf_token() }}',
            order_id: order_id
        }, function(data) {
            $('#payment_modal_body').html(data);
            $('#payment_modal').modal('show');
            $('input[name=order_id]').val(order_id);
        });
    }
</script>
