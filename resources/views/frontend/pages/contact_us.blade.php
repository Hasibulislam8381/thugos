@extends('frontend.layouts.app')
@section('content')
    <section class="pt-4 mb-4">
        <div class="container">
            <div class="row mobile_row">

                <div class="col-lg-6">
                    <div class="bg-white shadow-md rounded px-4 py-4">
                        <h1 class="contact_head">
                            Contact Us
                        </h1>

                        <form action="{{ route('sendMail') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Name:</label>
                                <input type="text" name="name" class="form-control" id=""
                                    placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="">Phone:</label>
                                <input type="text" name="phone" class="form-control" id=""
                                    placeholder="Enter Phone Number">
                            </div>
                            <div class="form-group">
                                <label for="">Email:</label>
                                <input type="text" name="email" class="form-control" id=""
                                    placeholder="Enter Email">
                            </div>

                            <div class="form-group">
                                <label for="">Message:</label>
                                <textarea name="message" class="form-control" placeholder="Enter Subject" rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white shadow-md rounded px-4 py-4">
                        <div class="logo-contact text-center">
                            @php
                                $header_logo = get_setting('header_logo');
                            @endphp
                            <img width="100%" src="{{ uploaded_asset($header_logo) }}" alt="">
                        </div>

                        <div class="contact-text">If You have Any Questions , Contact with us

                        </div>

                        <div class="phone"> Phone : {{ get_setting('contact_phone') }}</div>
                        <div class="phone"> Email : {{ get_setting('contact_email') }}</div>
                        {{-- <div class="phone"> Address : {{ get_setting('contact_address') }}</div> --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
