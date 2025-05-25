@extends('frontend.layouts.app')
@php
    $addressData = Session::get('addressData');
    $address = App\Models\Address::where('id', $addressData)->first();
    $userAddress = App\Models\Address::where('user_id', auth()->user()->id)->first();
    $userAddress_all = App\Models\Address::where('user_id', auth()->user()->id)->get();

    if ($address) {
        $pathao_zone = pathao_zone($address->city_id);
        $pathao_area = pathao_area($address->zone_id);
    } elseif ($userAddress) {
        $pathao_zone = pathao_zone($userAddress->city_id);
        $pathao_area = pathao_area($userAddress->zone_id);
    }

@endphp

@section('content')

    <section class="pt-5 mb-4 gry-bg">
        <div class="container">
            <form action="{{ route('payment.checkout') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row cols-xs-space cols-sm-space cols-md-space mobile_row">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 mx-auto">

                        <div class="shadow-sm bg-white p-4 rounded mb-4">
                            <div class="row">

                                <div class="col-lg-12">
                                    
                                    {{-- <div style="padding-top: 0" class="sms">
                                        চেকআউট সম্পর্কিত সমস্যা হলে যোগাযোগ
                                        করুন এই নাম্বারে
                                        <a href="tel:{{ get_setting('contact_phone') }}" class="fw-600">{{ get_setting('contact_phone') }}</a></div> --}}
                                    <div class="row">
                                        <div class="col-md-7 font_24_mobile">
                                            <h2 class="fw-700 ">Shipping Address</h2>
                                        </div>

                                        {{-- @if (Auth::check())
                                            <div class="col-md-5 mx-auto mb-3">
                                                <div class="border rounded mb-3 c-pointer text-center bg-white h-100 d-flex flex-column justify-content-center"
                                                    onclick="add_new_address()">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="alpha-7 mr-2">{{ translate('Add New Address') }}
                                                        </div>
                                                        <div>
                                                            <i class="fa-solid fa-plus"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif --}}

                                    </div>
                                    <div class="row justify-content-end">
                                        @if (Auth::check() && isset($userAddress))
                                            @if ($userAddress_all->count() > 0)
                                                <div class="col-md-4 mb-3">
                                                    <div class="border rounded mb-3 c-pointer text-center bg-white h-100 d-flex flex-column justify-content-center"
                                                        onclick="select_address()">
                                                        <div class="d-flex justify-content-center">
                                                            <div class="alpha-7 mr-2">{{ translate('Select Address') }}
                                                            </div>
                                                            <div>
                                                                <i class="fa-solid fa-circle-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- <form class="form-default" data-toggle="validator" id="addressForm" role="form" method="POST">
                            @csrf --}}
                            <div class="row gutters-5">
                                
                                <div class="col-lg-12 shipping_addess">
                                    <div class="form-group">
                                        <label for="">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id=""
                                            placeholder="Enter Name*" required
                                            value="{{ @$address->name ? @$address->name : (@$userAddress ? @$userAddress->name : auth()->user()->name) }}"
                                            required>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" pattern="\d{11}"
                                            id="" placeholder="Ex:012xxxxxxxx" required
                                            value="{{ @$address->phone ? @$address->phone : (@$userAddress ? @$userAddress->phone : auth()->user()->phone) }}">
                                        <small class="text-muted">Please enter an 11-digit phone number.</small>
                                    </div>


                                    {{-- <div class="form-group mt-3">
                                        <label for="">City</label>
                                        <input type="text" name="city" class="form-control" id=""
                                            placeholder="City" value="{{ @$filtered_city->city_name ?? @$userAddress->city }}">
                                    </div> --}}

                                    <div class="form-group mt-3">
                                        <label for="">Address(সম্পূর্ণ ঠিকানা, উপজেলা, জেলা, বিভাগ প্রদান করুন)<span class="text-danger">*</span></label>
                                        <textarea name="address" id="" class="form-control" placeholder="সম্পূর্ণ  ঠিকানা, উপজেলা, জেলা, বিভাগ প্রদান করুন" rows="3" required>{{ @$address->address ?? @$userAddress->address }}</textarea>
                                    </div>
                                        <div class="form-group mt-3">
                                        <label for="">Comments(মন্তব্য)</label>
                                        <input type="text" name="email" class="form-control" id=""
                                            placeholder="Comments"
                                            value="{{ @$address->email ? @$address->email : (@$userAddress ? @$userAddress->email : auth()->user()->email) }}">
                                    </div>
                                </div>
                                <input type="hidden" name="checkout_type" value="logged">
                                {{-- <div class="m-auto">
                                    @if (isset($address))
                                        <div class="mt-2">
                                            <button type="button"
                                                onclick="submitForm('{{ route('addresses.update', ['id' => $address->id]) }}')"
                                                class="m-auto fw-600 fs-16 mr-0 btn btn-secondary btn-sm shadow-md mt-4 view_all_btn"
                                                style="border: none">{{ translate('Save Address') }}</button>
                                        </div>
                                    @elseif ($userAddress_all->count() > 0)
                                        <div class="mt-2">
                                            <button type="button"
                                                onclick="submitForm('{{ route('addresses.update', ['id' => $userAddress->id]) }}')"
                                                class="m-auto fw-600 fs-16 mr-0 btn btn-secondary btn-sm shadow-md mt-4 view_all_btn"
                                                style="border: none">{{ translate('Save Address') }}</button>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <button type="button" onclick="submitForm('{{ route('addresses.store') }}')"
                                                class="m-auto fw-600 fs-16 mr-0 btn btn-secondary btn-sm shadow-md mt-4 view_all_btn"
                                                style="border: none">{{ translate('Add & Save Address') }}</button>
                                        </div>
                                    @endif

                                </div> --}}

                            </div>
                            {{-- </form> --}}


                        </div>



                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 mx-auto">

                        @include('frontend.partials.cart_summary')

                        <div>
                            <div class="card shadow-sm border-0 rounded">
                                <div class="card-header p-3">
                                    <h3 class="fs-16 fw-600 mb-0">
                                        {{ translate('Select a payment option') }}
                                    </h3>
                                </div>

                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-xxl-12 col-xl-12 mx-auto">
                                            <div class="row gutters-10">
                                                @if (get_setting('paypal_payment') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="paypal" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/paypal.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-13">{{ translate('Paypal') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('stripe_payment') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="stripe" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/stripe.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-13">{{ translate('Stripe') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
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
                                                                    <span
                                                                        class="d-block fw-600 fs-13">{{ translate('sslcommerz') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('instamojo_payment') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="instamojo" class="online_payment"
                                                                type="radio" name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/instamojo.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Instamojo') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('razorpay') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="razorpay" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/rozarpay.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Razorpay') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('paystack') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="paystack" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/paystack.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Paystack') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('voguepay') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="voguepay" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/vogue.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('VoguePay') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('payhere') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="payhere" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/payhere.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('payhere') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('ngenius') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="ngenius" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/ngenius.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('ngenius') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('iyzico') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="iyzico" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/iyzico.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Iyzico') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('nagad') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="nagad" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/nagad.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Nagad') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('bkash') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="bkash" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/bkash.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Bkash') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('aamarpay') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="aamarpay" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/aamarpay.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Aamarpay') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('authorizenet') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="authorizenet" class="online_payment"
                                                                type="radio" name="payment_option" checked
                                                                style="border-radius: 5px !important">
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/authorizenet.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Authorize Net') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('payku') == 1)
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="payku" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/payku.png') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Payku') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (addon_is_activated('african_pg'))
                                                    @if (get_setting('mpesa') == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="mpesa" class="online_payment"
                                                                    type="radio" name="payment_option" checked>
                                                                <span
                                                                    class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                    style="border-radius: 5px !important">
                                                                    <img src="{{ static_asset('assets/img/cards/mpesa.png') }}"
                                                                        class="img-fluid mb-2">
                                                                    <span class="d-block text-center">
                                                                        <span
                                                                            class="d-block fw-600 fs-15">{{ translate('mpesa') }}</span>
                                                                    </span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if (get_setting('flutterwave') == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="flutterwave" class="online_payment"
                                                                    type="radio" name="payment_option" checked>
                                                                <span
                                                                    class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                    style="border-radius: 5px !important">
                                                                    <img src="{{ static_asset('assets/img/cards/flutterwave.png') }}"
                                                                        class="img-fluid mb-2">
                                                                    <span class="d-block text-center">
                                                                        <span
                                                                            class="d-block fw-600 fs-15">{{ translate('flutterwave') }}</span>
                                                                    </span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if (get_setting('payfast') == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="payfast" class="online_payment"
                                                                    type="radio" name="payment_option" checked>
                                                                <span
                                                                    class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                    style="border-radius: 5px !important">
                                                                    <img src="{{ static_asset('assets/img/cards/payfast.png') }}"
                                                                        class="img-fluid mb-2">
                                                                    <span class="d-block text-center">
                                                                        <span
                                                                            class="d-block fw-600 fs-15">{{ translate('payfast') }}</span>
                                                                    </span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if (addon_is_activated('paytm'))
                                                    <div class="col-6 col-md-4">
                                                        <label class="aiz-megabox d-block mb-3">
                                                            <input value="paytm" class="online_payment" type="radio"
                                                                name="payment_option" checked>
                                                            <span class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                style="border-radius: 5px !important">
                                                                <img src="{{ static_asset('assets/img/cards/paytm.jpg') }}"
                                                                    class="img-fluid mb-2">
                                                                <span class="d-block text-center">
                                                                    <span
                                                                        class="d-block fw-600 fs-15">{{ translate('Paytm') }}</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (get_setting('cash_payment') == 1)
                                                    @php
                                                        $digital = 0;
                                                        $cod_on = 1;
                                                        foreach ($carts as $cartItem) {
                                                            $product = \App\Models\Product::find(
                                                                $cartItem['product_id'],
                                                            );
                                                            if ($product['digital'] == 1) {
                                                                $digital = 1;
                                                            }
                                                            if ($product['cash_on_delivery'] == 0) {
                                                                $cod_on = 0;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($digital != 1 && $cod_on == 1)
                                                        <div class="col-6 col-md-4">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="cash_on_delivery" class="online_payment"
                                                                    type="radio" name="payment_option" checked>
                                                                <span
                                                                    class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                    style="border-radius: 5px !important">
                                                                    <img src="{{ static_asset('assets/img/cards/cod.png') }}"
                                                                        class="img-fluid mb-2">
                                                                    <span class="d-block text-center">
                                                                        <span
                                                                            class="d-block fw-600 fs-13">{{ translate('Cash on Delivery') }}</span>
                                                                    </span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if (Auth::check())
                                                    @if (addon_is_activated('offline_payment'))
                                                        @foreach (\App\Models\ManualPaymentMethod::all() as $method)
                                                            <div class="col-6 col-md-4">
                                                                <label class="aiz-megabox d-block mb-3">
                                                                    <input value="{{ $method->heading }}" type="radio"
                                                                        name="payment_option"
                                                                        onchange="toggleManualPaymentData({{ $method->id }})"
                                                                        data-id="{{ $method->id }}" checked>
                                                                    <span
                                                                        class="d-block p-3 aiz-megabox-elem aiz_megabox_elem_new"
                                                                        style="border-radius: 5px !important">
                                                                        <img src="{{ uploaded_asset($method->photo) }}"
                                                                            class="img-fluid mb-2">
                                                                        <span class="d-block text-center">
                                                                            <span
                                                                                class="d-block fw-600 fs-15">{{ $method->heading }}</span>
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endforeach

                                                        @foreach (\App\Models\ManualPaymentMethod::all() as $method)
                                                            <div id="manual_payment_info_{{ $method->id }}"
                                                                class="d-none">
                                                                @php echo $method->description @endphp
                                                                @if ($method->bank_info != null)
                                                                    <ul>
                                                                        @foreach (json_decode($method->bank_info) as $key => $info)
                                                                            <li>{{ translate('Bank Name') }} -
                                                                                {{ $info->bank_name }},
                                                                                {{ translate('Account Name') }} -
                                                                                {{ $info->account_name }},
                                                                                {{ translate('Account Number') }} -
                                                                                {{ $info->account_number }},
                                                                                {{ translate('Routing Number') }} -
                                                                                {{ $info->routing_number }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if (addon_is_activated('offline_payment'))
                                        <div class="bg-white border mb-3 p-3 rounded text-left d-none">
                                            <div id="manual_payment_description">

                                            </div>
                                        </div>
                                    @endif
                                    {{-- <div class="bkash_payment text-left">
                                        Payment Method:
                                        <label style="padding-right: 15px;padding-left:10px">
                                            <input type="radio" name="payment_method" value="bkash"
                                                onclick="showFields('bkash');">

                                            <img src="{{ static_asset('assets/img/bi.png') }}" alt="">
                                        </label>
                                        <label style="padding-right: 15px">
                                            <input type="radio" name="payment_method" value="nagad"
                                                onclick="showFields('nagad');">
                                            <img src="{{ static_asset('assets/img/no.png') }}" alt="">
                                        </label>
                                        <label>
                                            <input type="radio" name="payment_method" value="rocket"
                                                onclick="showFields('rocket');">
                                            <img width="35px" src="{{ static_asset('assets/img/rc (1).png') }}"
                                                alt="">
                                        </label>

                                        <!-- Additional Fields (Initially Hidden) -->
                                        <div id="bkash_fields" style="display: none;">
                                            <b>Bkash Personal Number : {{ get_setting('bkash') }}</b>
                                            <div class="send_money_text">
                                                উপরের নাম্বারে টাকা সেন্ড মানি করে নিচের বক্সে আপনার বিকাশ নাম্বার ও
                                                ট্রাঞ্জেকশন নাম্বার টি দিন</div>
                                            <div class="form-group">
                                                <label for="bkash_number">bKash Number:</label>
                                                <input type="text" name="bkash_number" id="bkash_number"
                                                    class="form-control" placeholder="017xxxxxxxx">
                                            </div>
                                            <div class="form-group">
                                                <label for="bkash_transaction_id">Transaction ID:</label>
                                                <input type="text" name="bkash_transaction_id"
                                                    id="bkash_transaction_id" class="form-control" placeholder="8N7A6DEEF1">
                                            </div>
                                        </div>

                                        <div id="nagad_fields" style="display: none;">
                                            <b>Nagad Personal Number :{{ get_setting('nagad') }}</b>
                                            <div class="send_money_text">
                                                উপরের নাম্বারে টাকা সেন্ড মানি করে নিচের বক্সে আপনার নগদ নাম্বার ও
                                                ট্রাঞ্জেকশন নাম্বার টি দিন</div>
                                            <div class="form-group">
                                                <label for="nagad_number">Nagad Number:</label>
                                                <input type="text" name="nagad_number" id="nagad_number"
                                                    class="form-control"  placeholder="017xxxxxxxx">
                                            </div>
                                            <div class="form-group">
                                                <label for="nagad_transaction_id">Transaction ID:</label>
                                                <input type="text" name="nagad_transaction_id"
                                                    id="nagad_transaction_id" class="form-control" placeholder="8N7A6DEEF1">
                                            </div>
                                        </div>

                                        <div id="rocket_fields" style="display: none;">
                                            <div class="form-group">
                                                <b>Rocket Personal Number : {{ get_setting('rocket') }}</b>
                                                <div class="send_money_text">
                                                    উপরের নাম্বারে টাকা সেন্ড মানি করে নিচের বক্সে আপনার রকেট নাম্বার ও
                                                    ট্রাঞ্জেকশন নাম্বার টি দিন</div>
                                                <label for="rocket_number">Rocket Number:</label>
                                                <input type="text" name="rocket_number" id="rocket_number"
                                                    class="form-control"  placeholder="017xxxxxxxx">
                                            </div>
                                            <div class="form-group">
                                                <label for="rocket_transaction_id">Transaction ID:</label>
                                                <input type="text" name="rocket_transaction_id"
                                                    id="rocket_transaction_id" class="form-control" placeholder="8N7A6DEEF1">
                                            </div>
                                        </div>
                                    </div> --}}





                                    <div class="pt-3 text-left">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" required id="agree_checkbox" checked>
                                            <span class="aiz-square-check"></span>
                                            <span>{{ translate('I agree to the') }}</span>
                                        </label>
                                        <a href="{{ route('terms') }}">{{ translate('terms and conditions') }}</a>,
                                        <a href="{{ route('returnpolicy') }}">{{ translate('return policy') }}</a> &
                                        <a href="{{ route('privacypolicy') }}">{{ translate('privacy policy') }}</a>
                                    </div>

<div class="row align-items-center mt-3">
    <!-- Return to shop button (first on desktop, second on mobile) -->
    <div class="col-md-6 col-sm-12 col-12 text-center text-md-left order-md-0 order-1">
        <a href="{{ route('home') }}" class="btn btn-sm btn-secondary fw-600 pd_5_res">
            <i class="las la-arrow-left"></i> {{ translate('Return to shop') }}
        </a>
    </div>

    @if (isset($address->area_id) || isset($userAddress->area_id))
        @if (isset($address))
            <input type="hidden" name="address_id" value="{{ $address->id }}">
            <!-- Complete Order button (first on mobile, second on desktop) -->
            <div class="col-md-6 col-sm-12 col-12 mobile_mb margin_top_14 text-center text-md-right order-md-1 order-0">
                <button style="padding-left:22px !important;padding-right:22px !important" id="complete-order-button" type="submit" class="btn btn-sm btn-primary fw-600 pd_5_res">
                    {{ translate('Complete Order') }}
                </button>
            </div>
        @elseif ($userAddress_all->count() > 0)
            <input type="hidden" name="address_id" value="{{ $userAddress->id }}">
            <!-- Complete Order button (first on mobile, second on desktop) -->
            <div class="col-md-6 col-sm-12 col-12  mobile_mb margin_top_14 text-center text-md-right order-md-1 order-0">
                <button  style="padding-left:22px !important;padding-right:22px !important" id="complete-order-button" type="submit" class="btn btn-sm btn-primary fw-600 pd_5_res">
                    {{ translate('Complete Order') }}
                </button>
            </div>
        @endif
    @else
        <!-- Complete Order button (first on mobile, second on desktop) -->
        <div class="col-md-6 col-sm-12 col-12 mobile_mb  margin_top_14 text-center text-md-right order-md-1 order-0">
            <button  style="padding-left:22px !important;padding-right:22px !important" type="submit" id="complete-order-button" class="btn btn-sm btn-primary fw-600 pd_5_res">
                {{ translate('Complete Order') }}
            </button>
        </div>
    @endif
</div>


                                </div>


                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </section>
@endsection

@section('modal')
    @include('frontend.partials.address_modal')
    @include('frontend.partials.select_address_modal')
@endsection
@push('new_script')
    <script>
        function showFields(paymentMethod) {
            // Hide all fields
            document.getElementById('bkash_fields').style.display = 'none';
            document.getElementById('nagad_fields').style.display = 'none';
            document.getElementById('rocket_fields').style.display = 'none';

            // Show fields based on selected payment method
            if (paymentMethod === 'bkash') {
                document.getElementById('bkash_fields').style.display = 'block';
            } else if (paymentMethod === 'nagad') {
                document.getElementById('nagad_fields').style.display = 'block';
            } else if (paymentMethod === 'rocket') {
                document.getElementById('rocket_fields').style.display = 'block';
            }
        }
    </script>
    <script>
        function select_address() {
            $('#select-address-modal').modal('show');
        }
    </script>
    <script>
        function submitForm(action) {
            var form = document.getElementById('addressForm');
            form.action = action;
            form.submit();

        }
    </script>
    <script>
        function msgBtn() {
            AIZ.plugins.notify('warning', "Please choose all address");

            // var errorMessage = document.querySelector('.text-error');
            // errorMessage.classList.remove('d-none');
            // errorMessage.classList.add('d-block');
        }
    </script>


    <script>
        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=city_id]', function() {
            var city_id = $(this).val();
            get_zones(city_id);
        });

        $(document).on('change', '[name=zone_id]', function() {
            var zone_id = $(this).val();
            get_area(zone_id);
        });

        function get_zones(city_id) {
            $('[name="zone_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-city') }}",
                type: 'POST',
                data: {
                    city_id: city_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="zone_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_area(zone_id) {
            $('[name="area_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-state') }}",
                type: 'POST',
                data: {
                    zone_id: zone_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="area_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
    </script>
@endpush
