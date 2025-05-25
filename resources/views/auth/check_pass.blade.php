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

                            <form id="loginForm" action="{{ route('password_match') }}" method="POST">
                                @csrf

                                <input type="hidden" name="user_id" value="{{ $user->id }}">
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
