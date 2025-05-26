@extends('frontend.layouts.app')

@php
    $coupons = \App\Models\Coupon::where('end_date', '>', now())->get();
    foreach ($coupons as $key => $coupon) {
        $coupon_code = $coupon->code;
    }
    $total_price = 0;
    $product_id = '';
    $delivery_charge = Session::get('delivery_charge');
    $category_ids = [];
    
    foreach ($carts as $cart) {
        
        $category_ids[] = $cart->product->category_id;
    }
    $liked_products = \App\Models\Product::whereIn('category_id', $category_ids)->limit(20)->get();

@endphp

@section('content')



    <section class="mb-4 mt-5" id="cart-summary">
        <div class="container">
            @if ($carts && count($carts) > 0)
                <div class="row mobile_row">
                    <div class="col-xxl-8 col-xl-8 mx-auto">
                        <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left ">
                            <div class="mb-4 border-bottom">

                                <ul class="list-group list-group-flush">
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($carts as $key => $cartItem)
                                        @php
                                            $product = \App\Models\Product::find($cartItem['product_id']);
                                            $offer_quantity = json_decode($product->offer_quantity);

                                            $offer_discount = getOfferDiscount($product->id, $cartItem['quantity']);

                                            $product_stock = $product->stocks
                                                ->where('variant', $cartItem['variation'])
                                                ->first();
                                            $total_price =
                                                $total_price +
                                                ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'] -
                                                $offer_discount;
                                            // dd($cartItem['price']);

                                            // $subtotal = single_price(
                                            //     ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'],
                                            // );
                                            $product_name_with_choice = $product->getTranslation('name');
                                            if ($cartItem['variation'] != null) {
                                                $product_name_with_choice =
                                                    $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                                            }
                                            $variant_color = $cartItem['variation'];
                                            $product_id = $product->id;
                                            $productStock = \App\Models\ProductStock::where('product_id', $product_id)
                                                ->where('variant', $variant_color)
                                                ->first();
                                        @endphp
                                        <li class="list-group-item px-0 px-lg-3">
                                            <div class="row gutters-5 mobile_row">
                                                <div class="col-lg-2 col-sm-2 col-2">
                                                    <span class="mr-2 ml-0 d-flex">
                                                        @if ($variant_color != null)
                                                            <img src="{{ uploaded_asset($productStock->image) ?? uploaded_asset($product->thumbnail_img) }}"
                                                                class="img-fit rounded size-60px m-auto"
                                                                alt="{{ $product->getTranslation('name') }}">
                                                        @else
                                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                                class="img-fit rounded size-60px m-auto"
                                                                alt="{{ $product->getTranslation('name') }}">
                                                        @endif

                                                    </span>

                                                </div>

                                                <div class="col-lg-10 col-sm-10 col-10">
                                                    <div class="cart_product_name pl_8">
                                                        <h4>{{ $product_name_with_choice }}</h4>
                                                    </div>

                                                    <div class="d-flex pl_8">
                                                        <div class="fs-15 pb-2">
                                                            @if (home_base_price($cartItem->product) != home_discounted_base_price($cartItem->product))
                                                                <del
                                                                    class="fw-600 opacity-50 mr-1">{{ home_base_price($cartItem->product) }}</del>
                                                            @endif
                                                            <span
                                                                class="fw-700 text-primary">৳{{ $cartItem['price'] }}</span>


                                                        </div>
                                                        <span class="ml-2">
                                                            @if (discount_in_percentage($cartItem->product) > 0)
                                                                <span
                                                                    class="badge-custom badge_cart">{{ discount_in_percentage($cartItem->product) }}%
                                                                    {{ translate('OFF') }}</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="">
                                                        <div class="d-flex justify-content-between align-items-center ">
                                                            <div class="d-flex set_gap">
                                                                <span
                                                                    class="fs-16 fw-600 d-block mr-2 mt-1 cart_quan_text_res">{{ translate('Quantity') }}
                                                                    : </span>
                                                                <div>
                                                                    @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                                        <div
                                                                            class="row no-gutters align-items-center aiz-plus-minus mr-2 ml-0 mobile_row">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary quantity_btn set_padding_9px"
                                                                                type="button" data-type="minus"
                                                                                data-field="quantity[{{ $cartItem['id'] }}]">
                                                                                <i class="fa-solid fa-minus"></i>
                                                                            </button>
                                                                            <input type="number"
                                                                                name="quantity[{{ $cartItem['id'] }}]"
                                                                                class="col  text-center flex-grow-1 fs-13 input-number btn btn-sm btn-secondary quantity_btn quantity_input_number"
                                                                                placeholder="1"
                                                                                value="{{ $cartItem['quantity'] }}"
                                                                                min="{{ $product->min_qty }}"
                                                                                max="{{ @$product_stock->qty }}"
                                                                                onchange="updateQuantity({{ $cartItem['id'] }}, this)">
                                                                            <button
                                                                                class="btn btn-sm btn-secondary quantity_btn set_padding_9px"
                                                                                type="button" data-type="plus"
                                                                                data-field="quantity[{{ $cartItem['id'] }}]">
                                                                                <i class="fa-solid fa-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    @elseif($product->auction_product == 1)
                                                                        <span class="fw-600 fs-16">1</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <a href="javascript:void(0)"
                                                                    onclick="removeFromCartView(event, {{ $cartItem['id'] }})"
                                                                    class="btn btn-sm btn-danger ">
                                                                    Remove
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>

                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left">
                            <h3 class="fw-600">Shipping Charge</h3>
                            <div class="order__summery">
                                <div class="w-100">
                                    <div class="text-left mb-3">
                                        <div class="order__dhaka">
                                            <div class="d-flex justify-content-between align-items-center shipping_input">
                                                <div class="d-flex">
                                                    @if (Session::get('delivery_charge') == get_setting('inside_dhaka'))
                                                        <input type="radio" id="dhaka" name="dhaka"
                                                            class="delivery_charge"
                                                            value="{{ get_setting('inside_dhaka') }}" checked />
                                                    @else
                                                        <input type="radio" id="dhaka" name="dhaka"
                                                            class="delivery_charge"
                                                            value="{{ get_setting('inside_dhaka') }}" />
                                                    @endif

                                                    <div>
                                                        <label for="dhaka" class="mb-0 ml-2">Inside Dhaka</label>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="get__total__price"
                                                    value="{{ $total_price }}">

                                                <div class="fw-600">
                                                    {{ get_setting('inside_dhaka') }} tk
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-left mb-3">
                                        <div class="order__dhaka">
                                            <div class="d-flex justify-content-between align-items-center shipping_input">
                                                <div class="d-flex">
                                                    @if (Session::get('delivery_charge') == get_setting('outside_dhaka'))
                                                        <input type="radio" id="outside" name="dhaka"
                                                            class="delivery_charge"
                                                            value="{{ get_setting('outside_dhaka') }}" checked />
                                                    @else
                                                        <input type="radio" id="outside" name="dhaka"
                                                            class="delivery_charge"
                                                            value="{{ get_setting('outside_dhaka') }}" />
                                                    @endif
                                                    <div>
                                                        <label for="outside" class="mb-0 ml-2">
                                                            Outside Dhaka </label>
                                                    </div>
                                                </div>
                                                <div class="fw-600">
                                                    {{ get_setting('outside_dhaka') }} tk
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-left ">
                                        <div class="order__dhaka">
                                            <div class="d-flex justify-content-between align-items-center shipping_input">
                                                <div class="d-flex">
                                                    @if (Session::get('delivery_charge') == get_setting('sub_dhaka'))
                                                        <input type="radio" id="sub_dhaka" name="dhaka"
                                                            class="delivery_charge"
                                                            value="{{ get_setting('sub_dhaka') }}" checked />
                                                    @else
                                                        <input type="radio" id="sub_dhaka" name="dhaka"
                                                            class="delivery_charge"
                                                            value="{{ get_setting('sub_dhaka') }}" />
                                                    @endif
                                                    <div>
                                                        <label for="sub_dhaka" class="mb-0 ml-2">
                                                            Sub Dhaka</label>
                                                    </div>
                                                </div>
                                                <div class="fw-600">
                                                    {{ get_setting('sub_dhaka') }} tk
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="sub_dhaka_name d-none">
                                <div><b>Sub-dhaka area:</b></div>
                                <div>(Ashulia,Dhamrai,Dohar,Hemayetpur,Keraniganj,
                                    Nawabhanj,Savar)</div>

                            </div>
                            <div class="mt-3 order_summery_border">
                                <h3 class="fw-600">Order Summery</h3>
                                <div class="d-flex justify-content-between">
                                    <div class="fs-16">Subtotal:</div>
                                    <div class="fs-16 fw-600">

                                        {{ $total_price }} tk
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <div class="fs-16">Delivery Charge:</div>
                                    <div class="fs-16 fw-600 delivery_charge">

                                        @if (isset($delivery_charge))
                                            {{ $delivery_charge }} Tk
                                        @else
                                            00 tk
                                        @endif
                                    </div>
                                </div>
                                @if ($offer_discount > 0)
                                    <div class="d-flex justify-content-between mt-3">
                                        <div class="fs-16">Discount:</div>
                                        <div class="fs-16 fw-600 ">

                                            {{ $offer_discount }} tk
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between border-top total_pay mt-3">
                                    <div class="fs-16">Total Amount to Pay:</div>
                                    <div class="order__text total__amount">
                                        <span
                                            class="new__price fs-16 fw-600">{{ $total_price + ($delivery_charge ? $delivery_charge : 0) }}
                                            tk</span>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                <div class="row mt-4 mobile_row">
                    <div class="col-lg-12">
                        <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left">

                            <div class="row align-items-center justify-content-between mobile_row">
                                <div class="col-md-4 text-center text-md-left order-1 order-md-0 ">
                                    <a href="{{ route('home') }}" class="btn btn-secondary  w-100 fw-600">

                                        <i class="las la-arrow-left"></i> Return to Shop
                                    </a>
                                </div>
                                <div class="col-md-4 text-center text-md-right checkout_btn_res">
                                    @if (Session::has('delivery_charge'))
                                        @if (Auth::check())
                                            <a href="{{ route('checkout.shipping_info') }}"
                                                class="btn btn-primary fw-600 w-100 class__continue">
                                                {{ translate('Continue to Checkout') }}
                                            </a>
                                        @else
                                            <button class="btn btn-primary fw-600 w-100 class__continue"
                                                onclick="showCheckoutModal()">{{ translate('Continue to Checkout') }}</button>
                                        @endif
                                    @else
                                        @if (Auth::check())
                                            <a href="{{ route('checkout.shipping_info') }}"
                                                class="btn btn-primary fw-600 w-100 class__continue disabled-link">
                                                {{ translate('Continue to Checkout') }}
                                            </a>
                                        @else
                                            <button class="btn btn-primary fw-600 w-100 class__continue disabled-link"
                                                onclick="showCheckoutModal()">{{ translate('Continue to Checkout') }}</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mobile_row">
                    <div class="col-xl-8 mx-auto">
                        <div class="shadow-sm bg-white p-4 rounded">
                            <div class="text-center p-3">
                                <i class="las la-frown la-3x opacity-60 mb-3"></i>
                                <h3 class="h4 fw-700">{{ translate('Your Cart is empty') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <section class="pt-3">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded  mb-4">
                <div class="d-flex justify-content-center align-items-baseline set_pb_30">
                    <h3 class="h5 fw-700">
                        <span class="d-inline-block top_client_padding">{{ translate('You may Love This Too') }}</span>
                    </h3>

                </div>

                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="5" data-xl-items="5"
                    data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'
                    data-infinite='true'>
                    @foreach ($liked_products as $key => $product)
                        <div class="carousel-box">
                            @include('frontend.partials.product_box_1', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    <div class="modal fade add_modal" id="login-modal">
        <div class="modal-dialog modal-dialog-zoom login_modal_dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <div class=" text-center">
                        <div style="text-align: right;cursor:pointer;color:red" class="close-icon">
                            <i style="font-weight: 700" class="las la-times"></i>
                        </div>
                       
                        @if (get_setting('system_logo_black') != null)
                            <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" class="mw-100 mt-2 mb-3"
                                height="40">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mt-2 mb-3"
                                height="40">
                        @endif
                         <div class="border__bottom">
                            <h5 class="h5 text-primary mb-0 border__bottom">Enter Your Phone Number</h5>
                        </div>
                        <h1 class="h3 text-primary mb-0"></h1>

                    </div>
                    <div class="p-3 pt-0">
                        <div class="otp_success_message text-success text-center"></div>
                        <form class=" form-default" role="form" action="{{ route('cart.login.submit') }}"
                            method="POST">
                            @csrf
                            @if (addon_is_activated('otp_system') && env('DEMO_MODE') != 'On')
                                <div class="form-group phone-form-group mb-1 first_form">
                                    <input type="tel" id="phone-code"
                                        class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                        value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                                </div>
                                <div class="otp_btn">
                                    <div class="btn btn-sm btn-primary" onclick="getotp()">Submit</div>
                                </div>

                                <input type="hidden" name="country_code" value="">

                                <div class="form-group email-form-group mb-1 d-none">
                                    <input type="email"
                                        class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}"
                                        name="email" id="email" autocomplete="off">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                {{-- <div class="form-group text-left email__option">
                                    <button class="btn btn-link p-0 opacity-50 text-reset" type="button"
                                        onclick="toggleEmailPhone(this)">{{ translate('Use Email Instead') }}</button>
                                </div> --}}
                                <div class="otp_here d-none">
                                    <div class="otp_error_message text-danger text-center"></div>
                                    <div class="form-group mt-2 ">
                                        {{-- <label for="" class="font-weight-bold" style="float: left">OTP*</label> --}}
                                        {{-- <input type="text" name="otp" id="otp" class="form-control"
                                            placeholder="Enter OTP Here"> --}}
                                        <div class="otp-form">

                                            <!-- Mobile OTP Form -->

                                            <div class="otp__title">{{ translate('Confirm With OTP') }}</div>
                                            {{-- <div class="otp_error_message text-danger"></div> --}}
                                            {{-- <div class="otp_success_message text-success"></div> --}}

                                            <div class="otp-container">
                                                <!-- Six input fields for OTP digits -->
                                                <input type="text" class="otp-input" pattern="\d" maxlength="1">
                                                <input type="text" class="otp-input" pattern="\d" maxlength="1"
                                                    disabled>
                                                <input type="text" class="otp-input" pattern="\d" maxlength="1"
                                                    disabled>
                                                <input type="text" class="otp-input" pattern="\d" maxlength="1"
                                                    disabled>
                                                <input type="text" class="otp-input" pattern="\d" maxlength="1"
                                                    disabled>
                                                <input type="text" class="otp-input" pattern="\d" maxlength="1"
                                                    disabled>
                                            </div>



                                            <!-- Button to verify OTP -->
                                            <div class="mb-1 mt-3">
                                                <button type="submit" id="submitBtn"
                                                    class="btn btn-primary btn-block fw-600">{{ translate('Submit') }}</button>
                                            </div>
                                            <div class="resend_sms">
                                                <div onclick="getotp()" class="btn btn-primary resend-otp d-none"
                                                    disabled>Resend
                                                    OTP</div>
                                                <div class="timer-display"></div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="login_pass_here d-none">
                                    <div class="form-group">
                                        <input type="password"
                                            class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="{{ translate('Password') }}" name="password" id="password">
                                    </div>

                                    <div class="row mb-2 mobile_row">
                                        <div class="col-6 text-left">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" name="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <span class=opacity-60>{{ translate('Remember Me') }}</span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                        <div class="col-6 text-right">
                                            <a href="{{ route('password.request') }}"
                                                class="text-reset opacity-60 fs-14">{{ translate('Forgot password?') }}</a>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <button type="submit"
                                            class="btn btn-primary btn-block fw-600">{{ translate('Login') }}</button>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <input type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}"
                                        name="email" id="email" autocomplete="off">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </form>
                        <form class="d-none passwordCheck" id="loginForm" action="{{ route('password_cart_match') }}"
                            method="POST">
                            @csrf

                            <input type="hidden" name="user_id" value="">
                            <div class="form-group">
                                <label class="pass_text" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div style="display: flex;justify-content:space-between">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('forgot_user_login') }}">Forgot password?</a>
                            </div>
                        </form>
                        <form class="d-none RegisterPassword mt-2" id="loginForm"
                            action="{{ route('register_user_cart') }}" method="POST">
                            @csrf

                            <input type="hidden" name="phone_num"  value="">
                            {{-- <div class="form-group ">
                                <input id="name" type="text"
                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                    value="{{ old('name') }}" required autofocus
                                    placeholder="{{ translate('Full Name') }}">

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div> --}}
                            <div class="form-group">
                                <input id="password" type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" required placeholder="{{ translate('password') }}">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required
                                    placeholder="{{ translate('Confrim Password') }}">
                            </div>

                            <div style="display: flex;justify-content:space-between">
                                <button type="submit" class="btn btn-primary">Submit</button>

                            </div>
                        </form>

                    </div>
                    <div class="text-center mb-3 pt-4 register_now">
                        <p class="text-muted mb-0">{{ translate('Dont have an account?') }}</p>
                        <a href="{{ route('user_login') }}">{{ translate('Register Now') }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-summary').html(data.cart_view);
            });
        }

        function showCheckoutModal() {
            $('#login-modal').modal();
        }

        // Country Code
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if (country.iso2 == 'bd') {
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if (selectedCountryData.iso2 == 'bd') {
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            // var currentMask = e.currentTarget.placeholder;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el) {
            if (isPhoneShown) {
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }
    </script>
@endsection
@push('new_script')
    <script>
        var new__price = 0;
        var upohar__price = 0;


        $('input.delivery_charge[type="radio"]').click(function() {
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
    {{-- get otp --}}
    <script>
function getotp() {
    var phoneNum = $('#phone-code').val();

    $.ajax({
        url: "{{ route('user.store_otp') }}",
        method: 'POST',
        data: {
            phone_num: phoneNum,
            "_token": "{{ csrf_token() }}"
        },
        success: function(response) {
            // Check if a redirect URL is provided by the backend
            if (response.redirect_url) {
                // Redirect to the provided URL (e.g., checkout page)
                window.location.href = response.redirect_url;
            } else {
                // Handle any unexpected success case
                console.log('Success, but no redirect URL found.');
            }
        },
        error: function(error) {
            // Handle any errors, such as validation issues
            console.error('Error:', error);
        }
    });
}



        $('#submitBtn').click(function(event) {
            event.preventDefault();

            var otp = '';
            $('.otp-input').each(function() {
                otp += $(this).val();
            });
            // console.log(otp);

            $.ajax({
                url: "{{ route('user.otp_login') }}",
                method: 'POST',
                data: {
                    otp: otp,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    window.location.href = '/checkout';
                },
                error: function(error) {
                    // Display the error message in the "otp_success_message" div
                    $('.otp_error_message').removeClass('d-none').addClass('text-danger')
                        .text('Invalid OTP');

                }
            });
        });
    </script>

    {{-- otp --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var otpInputs = document.querySelectorAll(".otp-input");
            var emailOtpInputs = document.querySelectorAll(".email-otp-input");

            function setupOtpInputListeners(inputs) {
                inputs.forEach(function(input, index) {
                    input.addEventListener("paste", function(ev) {
                        var clip = ev.clipboardData.getData('text').trim();
                        if (!/^\d{6}$/.test(clip)) {
                            ev.preventDefault();
                            return;
                        }

                        var characters = clip.split("");
                        inputs.forEach(function(otpInput, i) {
                            otpInput.value = characters[i] || "";
                        });

                        enableNextBox(inputs[0], 0);
                        inputs[5].removeAttribute("disabled");
                        inputs[5].focus();
                        updateOTPValue(inputs);
                    });

                    input.addEventListener("input", function() {
                        var currentIndex = Array.from(inputs).indexOf(this);
                        var inputValue = this.value.trim();

                        if (!/^\d$/.test(inputValue)) {
                            this.value = "";
                            return;
                        }

                        if (inputValue && currentIndex < 5) {
                            inputs[currentIndex + 1].removeAttribute("disabled");
                            inputs[currentIndex + 1].focus();
                        }

                        if (currentIndex === 4 && inputValue) {
                            inputs[5].removeAttribute("disabled");
                            inputs[5].focus();
                        }

                        updateOTPValue(inputs);
                    });

                    input.addEventListener("keydown", function(ev) {
                        var currentIndex = Array.from(inputs).indexOf(this);

                        if (!this.value && ev.key === "Backspace" && currentIndex > 0) {
                            inputs[currentIndex - 1].focus();
                        }
                    });
                });
            }

            function enableNextBox(input, currentIndex) {
                var inputValue = input.value;

                if (inputValue === "") {
                    return;
                }

                var nextIndex = currentIndex + 1;
                var nextBox = otpInputs[nextIndex] || emailOtpInputs[nextIndex];

                if (nextBox) {
                    nextBox.removeAttribute("disabled");
                }
            }

            function updateOTPValue(inputs) {
                var otpValue = "";

                inputs.forEach(function(input) {
                    otpValue += input.value;
                });

                if (inputs === otpInputs) {
                    document.getElementById("verificationCode").value = otpValue;
                } else if (inputs === emailOtpInputs) {
                    document.getElementById("emailverificationCode").value = otpValue;
                }
            }

            setupOtpInputListeners(otpInputs);
            // setupOtpInputListeners(emailOtpInputs);

            otpInputs[0].focus();
            // emailOtpInputs[0].focus();

            otpInputs[5].addEventListener("input", function() {
                updateOTPValue(otpInputs);
            });

            // emailOtpInputs[5].addEventListener("input", function() {
            //     updateOTPValue(emailOtpInputs);
            // });
        });
    </script>
    {{-- otp --}}
    <script>
        // Add event listener to close icon
        $(document).on('click', '.close-icon', function() {


            // $('.otp_here').remove();
            // Find the closest parent model and hide it
            $('#login-modal').modal('hide');
            $('.otp_here').addClass("d-none");
            $('.otp_btn').removeClass("d-none");
            $('.first_form').removeClass('d-none');
            $('.otp_success_message').addClass('d-none');




            // location.reload();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('input[name="dhaka"]').change(function() {
                if ($('#sub_dhaka').is(':checked')) {
                    $('.sub_dhaka_name').removeClass('d-none');
                } else {
                    $('.sub_dhaka_name').addClass('d-none');
                }
            });

            // Check the initial state on page load
            if ($('#sub_dhaka').is(':checked')) {
                $('.sub_dhaka_name').removeClass('d-none');
            } else {
                $('.sub_dhaka_name').addClass('d-none');
            }
        });
    </script>
@endpush
