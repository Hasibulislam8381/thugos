@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $advance_payment_status = $order->advance_payment;
                    $offer_discount = 0;

                @endphp




                <div class="col-md-3 ml-auto">
                    <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                        id="update_payment_status">
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{ translate('Unpaid') }}
                        </option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>{{ translate('Paid') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                    @if ($delivery_status != 'delivered' && $delivery_status != 'cancelled')
                        <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                            id="update_delivery_status">
                            <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                {{ translate('Pending') }}</option>
                            <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                {{ translate('Confirmed') }}</option>
                            <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                {{ translate('Picked Up') }}</option>
                            <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                {{ translate('On The Way') }}</option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                {{ translate('Delivered') }}</option>
                            <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                {{ translate('Cancel') }}</option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                    @endif
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_tracking_code">{{ translate('Tracking Code (optional)') }}</label>
                    <input type="text" class="form-control" id="update_tracking_code"
                        value="{{ $order->tracking_code }}">
                </div>
            </div>
            <div class="mb-3">
                @php
                    $removedXML = 'xml version="1.0" encoding="UTF-8"';
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate(route('orders.track', $order->code))) !!}
            </div>
            {{-- {{ dd($order_shipping_address) }} --}}
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                    <address>

                        <strong class="text-main">{{ $order_shipping_address->name }}</strong><br>
                        {{ $order_shipping_address->email }}<br>
                        {{ $order_shipping_address->phone }}<br>
                        @if ($order_shipping_address->area_id)
                            {{ getAreaName($order_shipping_address->area_id, $order_shipping_address->zone_id) }},
                        @endif
                        @if ($order_shipping_address->zone_id)
                            {{ getZoneName($order_shipping_address->zone_id, $order_shipping_address->city_id) }},
                        @endif
                        @if ($order_shipping_address->city_id)
                            {{ getCityName($order_shipping_address->city_id) }},
                        @endif
                        {{ @$order_shipping_address->address }}
                    </address>
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}: {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}"
                            target="_blank"><img
                                src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100"></a>
                    @endif
                </div>

                <div class="col-md-4 ml-auto">
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-right text-info text-bold"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Grand Total') }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            @if ($order->advance_payment == 'paid')
                                <tr>
                                    <td class="text-main text-bold">
                                        {{ translate('Advance Payment') }}
                                    </td>
                                    <td class="text-right">
                                        @php
                                            $advance_payment = intval(get_setting('advance_payment'));
                                        @endphp
                                        @if ($order->advance_payment == 'paid')
                                            {{ single_price($advance_payment) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-main text-bold">
                                        {{ translate('Total Due amount') }}
                                    </td>
                                    <td class="text-right">
                                        @php
                                            $advance_payment = intval(get_setting('advance_payment'));
                                        @endphp
                                        @if ($order->advance_payment == 'paid')
                                            {{ single_price($order->grand_total - $advance_payment) }}
                                        @else
                                            {{ single_price($order->grand_total) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            <tr>

                                <td class="text-main text-bold">{{ translate('Shipping Type') }}</td>
                                <td class="text-right"> {{ getShippingType($order->orderDetails[0]->shipping_cost) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                            </tr>
                            @if ($order->payment_type == 'bkash' || $order->payment_type == 'nagad' || $order->payment_type == 'rocket')
                                <tr>
                                    <td class="text-main text-bold">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                                        {{ translate('Account number') }}</td>
                                    <td class="text-right">
                                        {{ $order->bkash_number ?? ($order->nagad_number ?? $order->rocket_number) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-main text-bold">{{ translate('Transaction Id') }}</td>
                                    <td class="text-right">
                                        <span
                                            class="badge badge-inline badge-success trans_btn1">{{ $order->bkash_transaction_id ?? ($order->nagad_transaction_id ?? $order->rocket_transaction_id) }}</span>

                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                {{-- <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th> --}}
                                <th data-breakpoints="lg" class="min-col text-center text-uppercase">{{ translate('Qty') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-center text-uppercase">
                                    {{ translate('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-right text-uppercase">
                                    {{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @php
                                    $offer_discount += getOfferDiscount(
                                        $orderDetail->product_id,
                                        $orderDetail->quantity,
                                    );

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
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                @if ($variant_color != null)
                                                    <img height="50"
                                                        src="{{ uploaded_asset($productStock->image) ?? uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                                @else
                                                    <img height="50"
                                                        src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                                @endif

                                            </a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank"><img height="50"
                                                    src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong><a href="{{ route('product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                            <small>{{ $orderDetail->variation }}</small>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong><a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    {{-- <td>
                                        @if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($orderDetail->shipping_type == 'pickup_point')
                                            @if ($orderDetail->pickup_point != null)
                                                {{ $orderDetail->pickup_point->getTranslation('name') }}
                                                ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @endif
                                    </td> --}}
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        @if ($order->orderDetails->sum('tax'))
                            >0)
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Tax') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($order->orderDetails->sum('tax')) }}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping amount') }} :</strong>
                            </td>
                            <td>

                               {{ single_price($order->shipping_cost) }}
                            </td>
                        </tr>
                        @if (single_price($order->coupon_discount) > 0)
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($order->coupon_discount) }}
                                </td>
                            </tr>
                        @endif
                        @if ($offer_discount > 0)
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Discount') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($offer_discount) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Grand TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">

                                {{ single_price($order->grand_total) }}

                            </td>
                        </tr>


                        @if ($order->advance_payment == 'paid')
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Advance Payment') }} :</strong>
                                </td>
                                <td class="text-success">
                                    {{ single_price($advance_payment) }}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Due Amount') }} :</strong>
                                </td>
                                <td class="text-muted h5">
                                    @if ($order->advance_payment == 'paid')
                                        {{ single_price($order->grand_total - $advance_payment) }}
                                    @else
                                        {{ single_price($order->grand_total) }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>
    <style>
        .trans_btn1 {
            font-size: 15px;
            padding: 16px;
        }
    </style>
@endsection

@section('script')
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = {{ $order->id }};
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('{{ route('orders.delivery-boy-assign') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
            });
        });

        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });


        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
            });
        });
    </script>

    {{-- <script>
        // Redirect to your desired route after QR code generation
        window.location.href = "{{ route('orders.track') }}";
    </script> --}}
@endsection
