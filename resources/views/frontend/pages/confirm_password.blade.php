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
                                    <h1 class="h3 text-primary mb-0 border__bottom">Update Password</h1>
                                </div>

                                @if (get_setting('system_logo_black') != null)
                                    <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}"
                                        class="mw-100 mt-3 mb-4" height="40">
                                @else
                                    <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mt-3 mb-4"
                                        height="40">
                                @endif

                            </div>
                            @if ($errors->has('password'))
                                <div class="alert alert-danger mt-3" role="alert">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('forgot-password.update') }}">
                                @csrf

                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                                <input type="password" class="form-control mt-1" placeholder="Confirm Password"
                                    name="password_confirmation" required>

                                <button type="submit" class="btn btn-sm btn-secondary btn-lg btn-block mt-3">
                                    {{ translate('Update password') }}
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
