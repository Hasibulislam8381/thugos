<style>
    .otp_btn {
        right: 42px;
        padding-top: 5px;
        position: absolute;
    }

    /* otp */
    .otp-form {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-width: 400px;
        width: 100%;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    /* OTP input styles */
    .otp-container,
    .email-otp-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .otp-input,
    .email-otp-input {
        width: 40px;
        height: 40px;
        text-align: center;
        font-size: 18px;
        margin: 0 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.3s;
    }

    .otp-input:focus,
    .email-otp-input:focus {
        border-color: #007bff;
    }

    #verificationCode,
    #emailverificationCode {
        width: 100%;
        margin-top: 15px;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
        transition: border-color 0.3s;
    }

    #verificationCode:focus,
    #emailverificationCode:focus {
        border-color: #007bff;
    }

    .email-otp {
        margin-top: 25px;
    }

    /* Button styles */
    button {
        margin-top: 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

@extends('frontend.layouts.app')
@section('content')
    <div class="h-100 bg-cover bg-center py-5 d-flex align-items-center"
        style="background-image: url({{ uploaded_asset(get_setting('admin_login_background')) }})">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-4 mx-auto">
                    <div class="card text-left">
                        <div class="card-body">
                            <div class=" text-center">
                                <div class="border__bottom">
                                    <h1 class="h3 text-primary mb-0 border__bottom">Login</h1>
                                </div>
                                @if (get_setting('system_logo_black') != null)
                                    <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" class="mw-100 mt-2"
                                        height="40">
                                @else
                                    <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mt-2" height="40">
                                @endif


                            </div>
                            {{-- <form class="pad-hor" method="POST" role="form" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input id="email" type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                        value="{{ old('email') }}" required autofocus
                                        placeholder="{{ translate('Email') }}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        name="password" required placeholder="{{ translate('Password') }}">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="text-left">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" name="remember" id="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <span>{{ translate('Remember Me') }}</span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                    @if (env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null)
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <a href="{{ route('password.request') }}"
                                                    class="text-reset fs-14">{{ translate('Forgot password ?') }}</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ translate('Login') }}
                                </button>
                            </form>
                            @if (env('DEMO_MODE') == 'On')
                                <div class="mt-4">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>admin@example.com</td>
                                                <td>123456</td>
                                                <td><button class="btn btn-info btn-xs"
                                                        onclick="autoFill()">{{ translate('Copy') }}</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif --}}

                            @if (addon_is_activated('otp_system') && env('DEMO_MODE') != 'On')
                                {{-- Otp --}}

                                <div class="otp-form">

                                    <!-- Mobile OTP Form -->

                                    <div class="otp__title">{{ translate('Confirm With OTP.') }}</div>
                                    <div class="otp_error_message text-danger"></div>
                                    {{-- <div class="otp_success_message text-success"></div> --}}

                                    <div class="otp-container">
                                        <!-- Six input fields for OTP digits -->
                                        <input type="text" class="otp-input" pattern="\d" maxlength="1">
                                        <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled>
                                        <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled>
                                        <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled>
                                        <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled>
                                        <input type="text" class="otp-input" pattern="\d" maxlength="1" disabled>
                                    </div>



                                    <!-- Button to verify OTP -->
                                    <div class="mb-1 mt-3">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-primary btn-block fw-600">{{ translate('Submit') }}</button>
                                    </div>
                                    <div class="resend_sms">
                                        <div onclick="getotp()" class="btn btn-primary resend-otp d-none" disabled>Resend
                                            OTP</div>
                                        <div class="timer-display"></div>
                                    </div>

                                </div>
                                {{-- <div class="otp_here">
                                    <div class="otp_error_message text-danger"></div>
                                    <div class="form-group mt-2 ">
                                        <label for="" class="font-weight-bold" style="float: left">OTP*</label>
                                        <input type="text" name="otp" id="otp" class="form-control"
                                            placeholder="Enter OTP Here">
                                    </div>
                                    <div class="mb-5">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-primary btn-block fw-600">{{ translate('Submit') }}</button>
                                    </div>
                                </div> --}}

                                {{-- Otp close --}}
                                <div class="login_pass_here d-none">
                                    <div class="form-group">
                                        <input type="password"
                                            class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="{{ translate('Password') }}" name="password" id="password">
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-6 text-left">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" name="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <span class=opacity-60>{{ translate('Remember Me') }}</span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                        <div class="col-6 text-right">
                                            <a href=""
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
                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                        id="email" autocomplete="off">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function autoFill() {
            $('#email').val('admin@example.com');
            $('#password').val('123456');
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
            onlyCountries: @php
                echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray());
            @endphp,
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
                $('.otp_btn').addClass('d-none');
                $('.otp_here').addClass('d-none');
                $('.login_pass_here').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('.otp_btn').removeClass('d-none');
                $('.otp_here').removeClass('d-none');
                $('.login_pass_here').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }
    </script>
    <script>
        function getotp() {
            var phoneNum = $('#phone-code').val();
            console.log(phoneNum);

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
                    $('.otp_btn').addClass('d-none');

                    // Show the success message
                    $('.otp_success_message').removeClass('d-none').text(
                        '{{ translate('OTP has been sent.Check your message') }}');
                    window.location.href = '/check-otp';

                },
                error: function(error) {

                    $('.phone_error_message').removeClass('d-none').addClass('text-danger')
                        .text('Already Exist!');

                }
            });
        }

        $(document).ready(function() {

            // Function to start the timer
            function startTimer() {
                var timer; // Variable to hold the timer
                var duration = 60; // Timer duration in seconds (1.5 minutes)
                var timerDisplay = $('.timer-display');

                timer = setInterval(function() {
                    duration--;
                    var minutes = Math.floor(duration / 60);
                    var seconds = duration % 60;
                    timerDisplay.text(minutes + "m " + seconds + "s");

                    if (duration <= 0) {
                        clearInterval(timer);
                        var otp = '';
                        $('.resend-otp').removeClass('d-none'); // Enable resend button
                        timerDisplay.text(''); // Clear timer display
                    }
                }, 1000);
            }

            // Function to reset the timer
            function resetTimer() {
                clearInterval(timer);
                startTimer();
            }

            // Initial call to start the timer when the page is loaded
            startTimer();




            $('#submitBtn').click(function(event) {
                event.preventDefault();

                var otp = '';
                $('.otp-input').each(function() {
                    otp += $(this).val();
                });
                // console.log(otp);

                $.ajax({
                    url: "{{ route('user.otp_login_regis') }}",
                    method: 'POST',
                    data: {
                        otp: otp,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        window.location.href = '/confirm-password';
                    },
                    error: function(error) {
                        // Display the error message in the "otp_success_message" div
                        $('.otp_error_message').removeClass('d-none').addClass('text-danger')
                            .text('Invalid OTP');

                    }
                });
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
@endsection
