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
                                    {{-- <h1 class="h3 text-primary mb-0 border__bottom">Login</h1> --}}
                                </div>

                                @if (get_setting('system_logo_black') != null)
                                    <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}"
                                        class="mw-100 mt-3 mb-4" height="40">
                                @else
                                    <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mt-3 mb-4"
                                        height="40">
                                @endif

                            </div>
                            {{-- {{ route('register_user') }} --}}
                            <form method="POST" action="{{ route('register_user') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <input id="name" type="text"
                                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                        value="{{ old('name') }}" required autofocus
                                        placeholder="{{ translate('Full Name') }}">

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
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

                                {{-- <div class="form-group">
                                    <label for="profile_image">{{ translate('Profile Image') }}</label>
                                    <input type="file" class="form-control-file" id="profile_image" name="profile_image"
                                        required>
                                    @if ($errors->has('profile_image'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('profile_image') }}</strong>
                                        </span>
                                    @endif
                                </div> --}}

                                {{-- <div class="form-group">
                                    <label class="">{{ translate('Photo') }}</label>
                                 
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                    {{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="photo" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                   
                                </div> --}}
                                <div class="checkbox pad-btm text-left">
                                    <input id="demo-form-checkbox" class="magic-checkbox" type="checkbox" required>
                                    <label for="demo-form-checkbox">

                                        {{ translate('I agree with the ') }}<a href="{{ url('/terms') }}">terms and
                                            Conditions
                                        </a>
                                    </label>

                                </div>

                                <button type="submit" class="btn btn-sm btn-secondary btn-lg btn-block">
                                    {{ translate('Create Acoount') }}
                                </button>
                            </form>
                            {{-- <div class="mt-3">
                                {{ translate('Already have an account') }} ? <a href="{{ route('login') }}"
                                    class="btn-link mar-rgt text-bold">{{ translate('Sign In') }}</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
