<style>
    .otp_btn {
        right: 42px;
        padding-top: 5px;
        position: absolute;
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
                        <div class="card-body" style="padding-bottom:50px">
                            <div class=" text-center">
                                <div class="border__bottom">
                                    <h1 class="h3 text-primary mb-0 border__bottom">Login</h1>
                                </div>
                                @if (get_setting('system_logo_black') != null)
                                    <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}"
                                        class="mw-100 mt-2 mb-3" height="40">
                                @else
                                    <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mt-2 mb-3"
                                        height="40">
                                @endif
                                <h1 class="h3 text-primary mb-0"></h1>

                            </div>

                            @if (addon_is_activated('otp_system') && env('DEMO_MODE') != 'On')
                                <div class="form-group phone-form-group mb-1">
                                    <div class="phone_error_message d-none text-danger"></div>
                                    <div class="otp_success_message d-none text-success"></div>
                                    <input type="tel" id="phone-code"
                                        class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                        value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                                </div>
                                {{-- <button class="btn btn-link p-0 opacity-50 text-reset" type="button"
                                    onclick="toggleEmailPhone(this)">{{ translate('Use Email Instead') }}</button> --}}

                                <div class="otp_btn">
                                    <div class="btn btn-sm btn-primary" onclick="getotp()">Submit</div>
                                </div>

                                <input type="hidden" name="country_code" value="">

                                <div class="form-group email-form-group mb-1 d-none">
                                    <input type="email"
                                        class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                        id="email" autocomplete="off">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                
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

            $.ajax({
                url: "{{ route('user.forgot_store_otp') }}",
                method: 'POST',
                data: {
                    phone_num: phoneNum,
                    generate_otp: true,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {

                    if (response.phone_exists) {
                        // Phone number exists, handle accordingly
                        // $('.phone_error_message').removeClass('d-none').addClass('text-danger')
                        //     .text('Phone number already exists!');
                        window.location.href = '/check-password/' + response.user.id;

                    } else {
                        // Hide the "Get OTP" button
                        $('.otp_btn').addClass('d-none');

                        // Show the success message
                        $('.otp_success_message').removeClass('d-none').text(
                            '{{ translate('Reset password') }}');
                        window.location.href = '/register-page';
                    }


                },
                error: function(error) {

                    $('.phone_error_message').removeClass('d-none').addClass('text-danger')
                        .text('Already Exist!');

                }
            });
        }

        $(document).ready(function() {
            $('#submitBtn').click(function(event) {
                event.preventDefault();

                var otp = $('#otp').val();

                $.ajax({
                    url: "{{ route('user.otp_login_regis') }}",
                    method: 'POST',
                    data: {
                        otp: otp,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        window.location.href = '/register_page';
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
@endsection
