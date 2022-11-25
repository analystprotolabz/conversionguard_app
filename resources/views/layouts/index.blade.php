<!DOCTYPE html>
<html>
<head>
@extends('shopify-app::layouts.default')
@include('layouts.header')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
{{-- <link href="{{ asset('public/assets/css/custom.css') }}" rel="stylesheet"> --}}
<div class="popup_box">
{{-- loader code --}}
    <div class="loader"><span><svg viewBox="0 0 32 32">
        <path
            d="M16 32c-4.274 0-8.292-1.664-11.314-4.686s-4.686-7.040-4.686-11.314c0-3.026 0.849-5.973 2.456-8.522 1.563-2.478 3.771-4.48 6.386-5.791l1.344 2.682c-2.126 1.065-3.922 2.693-5.192 4.708-1.305 2.069-1.994 4.462-1.994 6.922 0 7.168 5.832 13 13 13s13-5.832 13-13c0-2.459-0.69-4.853-1.994-6.922-1.271-2.015-3.066-3.643-5.192-4.708l1.344-2.682c2.615 1.31 4.824 3.313 6.386 5.791 1.607 2.549 2.456 5.495 2.456 8.522 0 4.274-1.664 8.292-4.686 11.314s-7.040 4.686-11.314 4.686z">
        </path>
    </svg></span></div>
{{--end loader code --}}
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <div class="wrap_left_sides">
                <div class="wrap_flex-logos">
                    <div class="wrap_logos">
                        <img class="imgRes" src="{{ asset('public/assets/images/DummyLogo.jpg') }}" alt="logo-image">
                    </div>
                    <div class="arrow_imgs">
                        <img class="imgRes" src="{{ asset('public/assets/images/arrows.jpg') }}" alt="arrow">
                    </div>
                    <div class="conversion_logo">
                        <img class="imgRes" src="{{ asset('public/assets/images/conversionguid.jpg') }}"
                            alt="image-logo">
                    </div>
                </div>
                <div>
                <h3 class="newsletter_heading heading_3 heading_3--dark mb-3">Domain & Owner Information</h3>
                <p class="dsic mb-5">Collect Domain & Owner Information</p>

                <form>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Domain-Should Be Provided By Shopify"
                        id="domain" name="domain">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="First Name" id="first_name"
                        name="first_name">
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                </div>

                <div class="form-group">
                    <input type="number" class="form-control" placeholder="Phone Number" id="phone_number"
                        name="phone_number">
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" id="policy" value="" required>&nbsp;&nbsp;&nbsp;&nbsp;
                        I agree with <a class="signup_link linkSty" href="#">Sign Up</a> and <a class="signup_link linkSty" href="#">Privacy</a>
                    </label>
                </div>

                <button type="submit" id="submit" class="btn btn-primary next_wraps">Next</button>
                </form>
                <p class="mt-4">© All rights reserved by Conversion Guard,Inc.
            </div>
        </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            <div class="wrap_right">
                <img class="newsletter_img imgRes" src="{{ asset('public/assets/images/rightSide.png') }}"
                    alt="img">
                <div class="wrap_text text-center">
                    <article class="wrappers">
                        <p class="msg">
                            "Lorem ipsum dolor sit amet, consectetur adipiscing elit Quis ipsum suspendisse ultrices
                            gravida.
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit."
                        </p>
                        <div class="indicators color-light text-center">
                            <i class="indiActive fas fa-circle"></i>
                            <i class="fas fa-circle"></i>
                            <i class="fas fa-circle"></i>
                        </div>
                        <h4 class="heading_6 heading_6--dark mb-1">John Deo</h4>
                        <p class="disc">Client</p>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    @parent
    <script>
        actions.TitleBar.create(app, { title: 'Welcome' });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('public/assets/js/userCreate.js') }}"></script>
@endsection
</body>
</html>

