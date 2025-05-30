<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style media="all">
        @print {
            body {
                padding-left: 30px;
                padding-right: 30px;
            }
        }

        @page {
            margin: 0;


        }

        body {
            font-size: 0.875rem;
            font-family: '<?php echo $font_family; ?>';
            font-weight: normal;
            direction: <?php echo $direction; ?>;
            text-align: <?php echo $text_align; ?>;
            padding: 0;
            margin: 0;
        }

        .gry-color *,
        .gry-color {
            color: #000;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .25rem .7rem;
        }

        table.padding td {
            padding: .25rem .7rem;
        }

        table.sm-padding td {
            padding: .1rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: <?php echo $text_align; ?>;
        }

        .text-right {
            text-align: <?php echo $not_text_align; ?>;
        }
    </style>
</head>

<body>
    <div>

        @php
            $logo = get_setting('header_logo');
            $offer_discount = 0;
        @endphp

        <div style="background: #eceff4;padding: 1rem;">
            <table>
                <tr>
                    <td>
                        @if ($logo != null)
                            <img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" height="30"
                                style="display:inline-block;">
                        @endif
                    </td>
                    <td style="font-size: 1.5rem;" class="text-right strong">{{ translate('INVOICE') }}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="font-size: 1rem;" class="strong">{{ get_setting('site_name') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ get_setting('contact_address') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}: {{ get_setting('contact_email') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order ID') }}:</span> <span
                            class="strong">{{ $order->code }}</span></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order Date') }}:</span>
                        <span class=" strong">{{ date('d-m-Y', $order->date) }}</span>
                    </td>
                </tr>
            </table>

        </div>

        <div style="padding: 1rem;padding-bottom: 0">
            <table>
                @php
                    $shipping_address = json_decode($order->shipping_address);
                @endphp
                <tr>
                    <td class="strong small gry-color">{{ translate('Bill to') }}:</td>
                </tr>
                <tr>
                    <td class="strong">{{ @$shipping_address->name }}</td>
                </tr>
                <tr>


                    <td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->country }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}: {{ $shipping_address->email }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ $shipping_address->phone }}</td>
                </tr>
            </table>
        </div>

        <div style="padding: 1rem;">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th class="text-left">{{ translate('Image') }}</th>
                        <th width="35%" class="text-left">{{ translate('Product Name') }}</th>
                        <th width="15%" class="text-left">{{ translate('Delivery Type') }}</th>
                        <th width="10%" class="text-left">{{ translate('Qty') }}</th>
                        <th width="15%" class="text-left">{{ translate('Unit Price') }}</th>
                        {{-- <th width="10%" class="text-left">{{ translate('Tax') }}</th> --}}
                        <th width="15%" class="text-right">{{ translate('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @php

                            $offer_discount += getOfferDiscount($orderDetail->product_id, $orderDetail->quantity);

                            $variant_color = $orderDetail['variation'];

                            $productStock = \App\Models\ProductStock::where('product_id', $orderDetail->product_id)
                                ->where('variant', $variant_color)
                                ->first();
                        @endphp
                        @if ($orderDetail->product != null)
                            <tr class="">
                                <td>
                                    @if ($variant_color != null)
                                        <img src="{{ uploaded_asset($productStock->image) ?? uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                            width="60" alt="">
                                    @else
                                        <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                            width="60" alt="">
                                    @endif

                                </td>
                                <td>{{ $orderDetail->product->name }} @if ($orderDetail->variation != null)
                                        ({{ $orderDetail->variation }})
                                    @endif
                                </td>
                                <td>
                                    @if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
                                        {{ translate('Home Delivery') }}
                                    @elseif ($orderDetail->shipping_type == 'pickup_point')
                                        @if ($orderDetail->pickup_point != null)
                                            {{ $orderDetail->pickup_point->getTranslation('name') }}
                                            ({{ translate('Pickip Point') }})
                                        @endif
                                    @endif
                                </td>
                                <td class="">{{ $orderDetail->quantity }}</td>
                                <td class="currency">{{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                </td>
                                {{-- <td class="currency">{{ single_price($orderDetail->tax / $orderDetail->quantity) }}
                                </td> --}}
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price + $orderDetail->tax) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0 1.5rem;">
            <table class="text-right sm-padding small strong">
                <thead>
                    <tr>
                        <th width="60%"></th>
                        <th width="40%"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="text-left">
                            @php
                            $removedXML = '<?xml version="1.0" encoding="UTF-8"@endphp';
                            ?>

                            {!! str_replace($removedXML, '', QrCode::size(100)->generate(route('orders.track', $order->code))) !!}
                        </td>

                        <td>
                            <table class="text-right sm-padding small strong">
                                <tbody>
                                    <tr>
                                        <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
                                        <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
                                        <td class="currency">
                                            {{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
                                    </tr>
                                    @if ($order->orderDetails->sum('tax'))
                                        <tr class="border-bottom">
                                            <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
                                            <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($order->coupon_discount)
                                        <tr class="border-bottom">
                                            <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
                                            <td class="currency">{{ single_price($order->coupon_discount) }}</td>
                                        </tr>
                                    @endif
                                    @if ($offer_discount > 0)
                                        <tr class="border-bottom">
                                            <th class="gry-color text-left">{{ translate('Discount') }}</th>
                                            <td class="currency">{{ single_price($offer_discount) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th class="text-left strong">{{ translate('Grand Total') }}</th>
                                        <td class="currency">{{ single_price($order->grand_total) }}</td>
                                    </tr>
                                    @php
                                        $advance_payment = intval(get_setting('advance_payment'));
                                    @endphp
                                    @if ($order->advance_payment == 'paid')
                                        <tr>
                                            <th class="text-left">
                                                {{ translate('Advance Payment') }}
                                                :
                                            </th>
                                            <td class="text-success">
                                                {{ single_price($advance_payment) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-left">
                                                {{ translate('Due Amount') }} :
                                            </th>
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
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>
