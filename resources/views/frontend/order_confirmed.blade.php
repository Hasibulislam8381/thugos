@extends('frontend.layouts.app')

@section('content')
    <section class="pt-5 py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    @php
                        $offer_discount = 0;
                        $advance_payment = intval(get_setting('advance_payment'));
                        $first_order = $combined_order->orders->first();

                        $shipping_address = json_decode($first_order->shipping_address);

                        $delivery_type = Session::get('delivery_charge');
                        $shipping_name = \App\Models\BusinessSetting::where('value', $delivery_type)->first();

                        foreach ($combined_order->orders as $key => $order) {
                            if ($key == 0) {
                                $orderDetail_fisrt = $order;
                                break;
                            }
                        }

                        Session::forget('delivery_charge');
                        $shipping_type = getShippingType($first_order->shipping_cost);
                    @endphp
                    <div class="text-center mb-4">
                        <i class="la la-check-circle la-3x text-success mb-3"></i>
                        <h1 class="h3 mb-3 fw-600">{{ translate('Thank You for Your Order!') }}</h1>
                        {{-- <p class="opacity-70 font-italic">{{ translate('A copy or your order summary has been sent to') }}
                            {{ $shipping_address->email }}</p> --}}
                        {{-- @if ($shipping_name->type == 'outside_dhaka')
                            <h3>{{ translate('সম্মানিত কাস্টমার মৌ গ্যালারি ওয়েব সাইটে অর্ডার করার জন্য আপনাকে ধন্যবাদ।') }}
                            </h3>
                            <h4 class="fw-500 text-danger"> আপনার প্রোডাক্টের দাম এবং ডেলিভারি চার্জ সহ টোটাল টাকা থেকে
                                {{ get_setting('advance_payment') }} টাকা
                                {{ translate(' বিকাশ না করলে ঢাকার বাহিরে অর্ডার কনফার্ম হবে না , আপনার অর্ডার এবং পেমেন্ট কনফার্ম এর জন্য  আমাদের একজন প্রতিনিধি আগামী ২৪ ঘণ্টার মধ্যে আপনার সাথে যোগাযোগ করবে  ধন্যবাদ।') }}
                            </h4>
                            <h5>এডভান্স টাকা পাঠাতে এই নাম্বারে বিকাশ করুন </h5>
                            <h3>{{ get_setting('bkash') }}</h3>
                        @endif --}}


                        {{-- @if ($orderDetail_fisrt->payment_type == 'cash_on_delivery' && $order->orderDetail_fisrt != 'paid' && $first_order->payment_status != 'paid' && $shipping_name->type == 'outside_dhaka')
                            <h3 class="fw-600 text-danger">{{ translate('Please Pay ') }}
                                {{ get_setting('advance_payment') }} tk
                                {{ translate('first otherwise Your Order Will be Cancelled') }}
                            </h3>
                            <form action="{{ route('advance_payment') }}" method="POST">
                                @csrf
                                @if (get_setting('sslcommerz_payment') == 1)
                                    <div class="col-6 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="sslcommerz" class="online_payment" type="radio"
                                                name="payment_option" checked>
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
                                    <input type="hidden" name="shipping_address"
                                        value="{{ $first_order->shipping_address }}">
                                    <input type="hidden" name="order_details" value="{{ $first_order }}">
                                @endif
                                <button type="submit" class="btn btn-sm btn-primary d-block ml-3">
                                    {{ translate('Pay Now') }}
                                </button>
                            </form>
                        @endif --}}
                    </div>
                    <div class="mb-4 bg-white p-4 rounded shadow-sm">
                        <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Summary') }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Order date') }}:</td>
                                        <td>{{ date('d-m-Y h:i A', $first_order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Name') }}:</td>
                                        <td>{{ $shipping_address->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Comments') }}:</td>
                                        <td>{{ $shipping_address->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping address') }}:</td>
                                        <td>
                                            @if ($shipping_address->area_id)
                                                {{ getAreaName($shipping_address->area_id, $shipping_address->zone_id) }},
                                            @endif
                                            @if ($shipping_address->zone_id)
                                                {{ getZoneName($shipping_address->zone_id, $shipping_address->city_id) }},
                                            @endif
                                            @if ($shipping_address->city_id)
                                                {{ getCityName($shipping_address->city_id) }},
                                            @endif
                                            {{ @$shipping_address->address }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Order status') }}:</td>
                                        <td>{{ translate(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Total Order amount') }}:</td>
                                        <td>{{ single_price($combined_order->grand_total + $order->orderDetails->sum('tax')) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping') }}:</td>
                                        {{-- @if ($shipping_name->type == 'inside_dhaka')
                                            <td>{{ translate('Inside Dhaka') }}</td>
                                        @elseif ($shipping_name->type == 'outside_dhaka')
                                            <td>{{ translate('Outside Dhaka') }}</td>
                                        @endif --}}
                                        <td>{{ $shipping_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Payment method') }}:</td>
                                        <td>{{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    @foreach ($combined_order->orders as $order)
                        <div class="card shadow-sm border-0 rounded">
                            <div class="card-body">
                                <div class="text-center py-4 mb-4">
                                    <h2 class="h5">{{ translate('Order Code:') }} <span
                                            class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                </div>
                                <div>
                                    <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Details') }}</h5>
                                    <div>
                                        <table class="table table-responsive-md">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ translate('Image') }}</th>
                                                    <th width="30%">{{ translate('Product') }}</th>
                                                    <th>{{ translate('Quantity') }}</th>
                                                    <th>{{ translate('Payment Status') }}</th>
                                                    <th class="text-right">{{ translate('Price') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    @php

                                                        $variant_color = $orderDetail['variation'];

                                                        $productStock = \App\Models\ProductStock::where(
                                                            'product_id',
                                                            $orderDetail->product_id,
                                                        )
                                                            ->where('variant', $variant_color)
                                                            ->first();

                                                    @endphp
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            @if ($variant_color != null)
                                                                <img src="{{ uploaded_asset($productStock->image) ?? uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                                                    width="60" alt="">
                                                            @else
                                                                <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                                                    width="60" alt="">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                                    target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                                    @php
                                                                        if ($orderDetail->combo_id != null) {
                                                                            $combo = \App\ComboProduct::findOrFail(
                                                                                $orderDetail->combo_id,
                                                                            );

                                                                            echo '(' . $combo->combo_title . ')';
                                                                        }

                                                                        $offer_discount += getOfferDiscount(
                                                                            $orderDetail->product_id,
                                                                            $orderDetail->quantity,
                                                                        );

                                                                        $subtotal = $order->orderDetails->sum('price');
                                                                        $grand_total = $order->grand_total;

                                                                    @endphp
                                                                </a>
                                                                {{ $orderDetail->variation }}
                                                            @else
                                                                <strong>{{ translate('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            {{ $orderDetail->quantity }}
                                                        </td>
                                                        <td>
                                                            @if ($orderDetail->payment_status == 'paid')
                                                                <span
                                                                    class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                                            @elseif ($orderDetail->payment_status == 'advance')
                                                                <span
                                                                    class="badge badge-inline badge-success">{{ translate('Advance Paid') }}</span>
                                                            @else
                                                                <span
                                                                    class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-right">{{ single_price($orderDetail->price) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                            <table class="table ">
                                                <tbody>
                                                    <tr>
                                                        <th>{{ translate('Subtotal') }}</th>
                                                        <td class="text-right">
                                                            <span class="fw-600">{{ single_price($subtotal) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ translate('Shipping') }}</th>
                                                        <td class="text-right">
                                                            <span
                                                                class="font-italic">{{ single_price($order->shipping_cost) }}</span>
                                                        </td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <th>{{ translate('Advance Payment') }}</th>
                                                        <td class="text-right">
                                                            <span
                                                                class="font-italic">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                        </td>
                                                    </tr> --}}
                                                    @if (intval($order->orderDetails->sum('tax')) > 0)
                                                        <tr>
                                                            <th>{{ translate('Tax') }}</th>
                                                            <td class="text-right">
                                                                <span
                                                                    class="font-italic">{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if ($order->coupon_discount > 0)
                                                        <tr>
                                                            <th>{{ translate('Coupon Discount') }}</th>
                                                            <td class="text-right">
                                                                <span
                                                                    class="font-italic">{{ single_price($order->coupon_discount) }}</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($offer_discount > 0)
                                                        <tr>
                                                            <th>{{ translate('Discount') }}</th>
                                                            <td class="text-right">
                                                                <span
                                                                    class="font-italic">{{ single_price($offer_discount) }}</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <th><span class="fw-600">{{ translate('Total') }}</span></th>
                                                        <td class="text-right">
                                                            <strong><span>

                                                                    {{ single_price($grand_total) }}
                                                                </span></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><span class="fw-600">{{ translate('Due') }}</span></th>
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
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- @if ($orderDetail_fisrt->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
                        <h1 class="fw-600 text-danger">{{ translate('Please Pay ') }}
                            {{ $shipping_name->value }} tk
                            {{ translate('otherwise Your Order Will be Cancelled') }}
                        </h1>
                    @endif --}}
                </div>
            </div>
        </div>
    </section>
@endsection
