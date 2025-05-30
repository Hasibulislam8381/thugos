@extends('frontend.layouts.app')

@section('content')
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-600 h4">{{ translate('Track Order') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            <a class="text-reset" href="{{ route('orders.track') }}">"{{ translate('Track Order') }}"</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-5">
        <div class="container text-left">
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-lg-8 mx-auto">
                    <form class="" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                        <div class="bg-white rounded shadow-sm">
                            <div class="fs-15 fw-600 p-3 border-bottom text-center">
                                {{ translate('Check Your Order Status') }}
                            </div>
                            <div class="form-box-content p-3">
                                <div class="form-group">
                                    <input type="text" class="form-control mb-3"
                                        placeholder="{{ translate('Order Code') }}" name="order_code" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">{{ translate('Track Order') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @isset($order)
                @php
                    $shipping_info = json_decode($order->shipping_address);
                    $advance_payment = intval(get_setting('advance_payment'));
                    $shipping_type = getShippingType($order->shipping_cost);
                @endphp
                <div class="bg-white rounded shadow-sm mt-5">
                    <div class="fs-15 fw-600 p-3 border-bottom">
                        {{ translate('Order Summary') }}
                    </div>
                    <div class="p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Order Code') }}:</td>
                                        <td>{{ $order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Customer') }}:</td>
                                        <td>{{ json_decode($order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Email') }}:</td>
                                        @if ($order->user_id != null)
                                            <td>{{ $order->user->email ?? $shipping_info->email }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping address') }}:</td>

                                        <td>
                                            {{ getAreaName($shipping_info->area_id, $shipping_info->zone_id) }},
                                            {{ getZoneName($shipping_info->zone_id, $shipping_info->city_id) }},
                                            {{ getCityName($shipping_info->city_id) }},
                                            {{ $shipping_info->address }}

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
                                    {{-- <tr>
                                        <td class="w-50 fw-600">{{ translate('Total Amount to Pay') }}:</td>
                                        <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}
                                        </td>
                                        <td>
                                            @if ($order->advance_payment == 'paid')
                                                {{ single_price($order->grand_total - $advance_payment) }}
                                            @else
                                                {{ single_price($order->grand_total) }}
                                            @endif
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping') }}:</td>
                                        <td>{{ getShippingType($order->shipping_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Payment method') }}:</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Delivery Status') }}:</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping Cost') }}:</td>
                                        <td>৳ {{ $order->shipping_cost }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Total') }}:</td>
                                        <td>৳ {{ $order->grand_total + $order->orderDetails->sum('tax') }}</td>
                                    </tr>
                                    @if ($order->advance_payment == 'paid')
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('Advance Pament') }}:</td>
                                            <td>৳ {{ $advance_payment }}</td>
                                        </tr>
                                    @endif
                                    @if ($order->advance_payment == 'paid')
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Due Amount') }}:</td>
                                        <td>{{ single_price($order->grand_total + $order->orderDetails->sum('tax') - $advance_payment)  }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Due Amount') }}:</td>
                                        <td>{{ single_price($order->grand_total + $order->orderDetails->sum('tax')) }}</td>
                                    </tr>
                                    @endif
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


                @foreach ($order->orderDetails as $key => $orderDetail)
                    @php
                        $status = $order->delivery_status;
                    @endphp
                    <div class="bg-white rounded shadow-sm mt-4">

                        @if ($orderDetail->product != null)
                            <div class="p-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Product Name') }}</th>
                                            <th>{{ translate('Quantity') }}</th>
                                            <th>{{ translate('Shipped By') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $orderDetail->product->getTranslation('name') }}
                                                @if ($orderDetail->variation)
                                                ({{ $orderDetail->variation }})
                                                @endif
                                            </td>
                                            <td>{{ $orderDetail->quantity }}</td>
                                            <td>{{ $orderDetail->product->user->name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endisset
        </div>
    </section>

@endsection
