@php
    $coupons = \App\Models\Coupon::where('end_date', '>', now())->get();
    foreach ($coupons as $key => $coupon) {
        $coupon_code = $coupon->code;
    }
    $total_price = 0;
    $product_id = '';
    $delivery_charge = Session::get('delivery_charge');

@endphp
<div class="container">
    @if ($carts && count($carts) > 0)
        <div class="row mobile_row">
            <div class="col-xxl-8 col-xl-8 mx-auto">
                <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left">
                    <div class="mb-4 border-bottom">

                        <ul class="list-group list-group-flush">
                            @php
                                $total = 0;
                                $offer_discount = Session::get('offer_discount');

                            @endphp
                            @foreach ($carts as $key => $cartItem)
                                @php
                                    $product = \App\Models\Product::find($cartItem['product_id']);
                                    $offer_discount = getOfferDiscount($product->id, $cartItem['quantity']);
                                    $product_stock = $product->stocks
                                        ->where('variant', $cartItem['variation'])
                                        ->first();
                                    $total_price =
                                        $total_price +
                                        ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'] -
                                        $offer_discount;
                                    $subtotal = ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'];
                                    $product_name_with_choice = $product->getTranslation('name');
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice =
                                            $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                                    }
                                @endphp
                                <li class="list-group-item px-0 px-lg-3">
                                    <div class="row gutters-5 mobile_row">
                                        <div class="col-lg-2 col-sm-2 col-2">
                                            <span class="mr-2 ml-0 d-flex">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fit rounded size-60px m-auto"
                                                    alt="{{ $product->getTranslation('name') }}">
                                            </span>

                                        </div>

                                        <div class="col-lg-10 col-sm-10 col-10">
                                            <div class="pl_8">
                                                <h4>{{ $product_name_with_choice }}</h4>
                                            </div>

                                            <div class="d-flex">
                                                <div class="fs-15 pb-2 pl_8">
                                                    @if (home_base_price($cartItem->product) != home_discounted_base_price($cartItem->product))
                                                        <del
                                                            class="fw-600 opacity-50 mr-1">{{ home_base_price($cartItem->product) }}</del>
                                                    @endif
                                                    <span class="fw-700 text-primary">৳{{ $cartItem['price'] }}</span>


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
                                                <div class="d-flex justify-content-between align-items-center">
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
                                                                        max="{{ $product_stock->qty }}"
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
                                                            class="btn btn-sm btn-danger">
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
                    {{-- <div class="sms"> কার্ট সম্পর্কিত সমস্যা হলে যোগাযোগ করুন এই নাম্বারে
                        <a href="tel:{{ get_setting('contact_phone') }}"
                            class="fw-600">{{ get_setting('contact_phone') }}</a>
                    </div> --}}

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
                                                    class="delivery_charge" value="{{ get_setting('inside_dhaka') }}"
                                                    checked />
                                            @else
                                                <input type="radio" id="dhaka" name="dhaka"
                                                    class="delivery_charge"
                                                    value="{{ get_setting('inside_dhaka') }}" />
                                            @endif

                                            <div>
                                                <label for="dhaka" class="mb-0 ml-2">Inside Dhaka</label>
                                            </div>
                                        </div>
                                        <input type="hidden" class="get__total__price" value="{{ $total_price }}">

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
                                                    class="delivery_charge" value="{{ get_setting('outside_dhaka') }}"
                                                    checked />
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
                            {{-- <div class="text-left mb-3">
                                <div class="order__dhaka">
                                    <div class="d-flex justify-content-between align-items-center shipping_input">
                                        <div class="d-flex">
                                            @if (Session::get('delivery_charge') == get_setting('demra_kamrangir_chor'))
                                                <input type="radio" id="outside" name="dhaka"
                                                    class="delivery_charge"
                                                    value="{{ get_setting('demra_kamrangir_chor') }}" checked />
                                            @else
                                                <input type="radio" id="outside" name="dhaka"
                                                    class="delivery_charge"
                                                    value="{{ get_setting('demra_kamrangir_chor') }}" />
                                            @endif
                                            <div>
                                                <label for="outside" class="mb-0 ml-2">
                                                    Demra,Kamrangir Chor</label>
                                            </div>
                                        </div>
                                        <div class="fw-600">
                                            {{ get_setting('demra_kamrangir_chor') }} tk
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-left">
                                <div class="order__dhaka">
                                    <div class="d-flex justify-content-between align-items-center shipping_input">
                                        <div class="d-flex">
                                            @if (Session::get('delivery_charge') == get_setting('sub_dhaka'))
                                                <input type="radio" id="outside" name="dhaka"
                                                    class="delivery_charge" value="{{ get_setting('sub_dhaka') }}"
                                                    checked />
                                            @else
                                                <input type="radio" id="outside" name="dhaka"
                                                    class="delivery_charge" value="{{ get_setting('sub_dhaka') }}" />
                                            @endif
                                            <div>
                                                <label for="outside" class="mb-0 ml-2">
                                                    Sub Dhaka</label>
                                            </div>
                                        </div>
                                        <div class="fw-600">
                                            {{ get_setting('sub_dhaka') }} tk
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="mt-3 order_summery_border">
                        <h3 class="fw-600">Order Summery</h3>
                        <div class="d-flex justify-content-between">
                            <div class="fs-16">Sub Total:</div>
                            <div class="fs-16 fw-600"> {{ $total_price }} tk</div>
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
                        <div class="col-md-4 text-center text-md-left order-1 order-md-0">
                            <a href="{{ route('home') }}" class="btn btn-sm btn-secondary  w-100 fw-600">

                                <i class="las la-arrow-left"></i> {{ translate('Return to shop') }}
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

<script type="text/javascript">
    AIZ.extra.plusMinus();
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
                generate_otp: true,
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                // Hide the "Get OTP" button
                if (response.phone_exists) {
                    // Phone number exists, handle accordingly
                    $('.otp_btn').addClass('d-none');
                    $('.phone_error_message').removeClass('d-none').addClass('text-danger')
                        .text('Phone number already exists!');
                    $('.passwordCheck').removeClass('d-none');
                    // window.location.href = '/check-password/' + response.user.id;
                    $('#loginForm input[name="user_id"]').val(response.user.id);

                } else {
                    $('.otp_btn').addClass('d-none');

                    // Show the success message
                    $('.otp_success_message').removeClass('d-none').text(
                        '{{ translate('OTP has been sent.Check your message') }}');

                    $('.first_form').addClass('d-none');
                    $('.email__option').addClass('d-none');
                    $('.otp_here').removeClass('d-none');

                }


            },
            error: function(error) {
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
        console.log(otp);

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
        setupOtpInputListeners(emailOtpInputs);

        otpInputs[0].focus(); // Set focus on the first OTP input field
        emailOtpInputs[0].focus(); // Set focus on the first email OTP input field

        otpInputs[5].addEventListener("input", function() {
            updateOTPValue(otpInputs);
        });

        emailOtpInputs[5].addEventListener("input", function() {
            updateOTPValue(emailOtpInputs);
        });
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
