<!DOCTYPE html>
<html>
<head>
    @extends('shopify-app::layouts.default')
    @include('layouts.header')
    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
        <div class="popup_box">
            {{--  Loader code  --}}
            <div class="loader">
                <span><svg viewBox="0 0 32 32">
                        <path
                            d="M16 32c-4.274 0-8.292-1.664-11.314-4.686s-4.686-7.040-4.686-11.314c0-3.026 0.849-5.973 2.456-8.522 1.563-2.478 3.771-4.48 6.386-5.791l1.344 2.682c-2.126 1.065-3.922 2.693-5.192 4.708-1.305 2.069-1.994 4.462-1.994 6.922 0 7.168 5.832 13 13 13s13-5.832 13-13c0-2.459-0.69-4.853-1.994-6.922-1.271-2.015-3.066-3.643-5.192-4.708l1.344-2.682c2.615 1.31 4.824 3.313 6.386 5.791 1.607 2.549 2.456 5.495 2.456 8.522 0 4.274-1.664 8.292-4.686 11.314s-7.040 4.686-11.314 4.686z">
                        </path>
                    </svg>
                </span>
            </div>
            {{-- End Loader code  --}}
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6">
                    <div class="wraps_left_side">
                        <div class="wrap-logo text-center">
                            <img src="{{ asset('public/assets/images/conversionguid.jpg') }}" class="logo" alt="logo">
                        </div>

                        <div class="wraps_heading_texts">

                            <div class="back_btn">
                              <a class="btn btn-primary" href="{{ route('home') }}"> &laquo; Back</a>
                            </div>

                            <h3 class="newsletter_heading heading_3 heading_3--dark mb-3">Global Permission Settings</h3>
                            <p class="dsic mb-5">Configure globle permission for you shopify store</p>
                        </div>
                        <?php
                        $pageview = $pix_stat[0]['page_view'];
                        $productpage = $pix_stat[0]['product_pixel'];
                        $categorypage = $pix_stat[0]['category_pixel'];
                        $checkoutpage = $pix_stat[0]['checkout_pixel'];
                        $guardpage = $pix_stat[0]['guard_pixel'];
                        
                        $ischecked = $pageview == '1' ? 'checked' : '';
                        $pchecked = $productpage == '1' ? 'checked' : '';
                        $catchecked = $categorypage == '1' ? 'checked' : '';
                        $chchecked = $checkoutpage == '1' ? 'checked' : '';
                        $guardpage = $guardpage == '1' ? 'checked' : '';
                        ?>
                        <div class="wraps_switch_toggle">
                            <p>
                                <strong>Enable Product Page Pixel</strong>
                                <span> <label class="switch">
                                        <input type="checkbox" id="product-page" {{ $pchecked }}>
                                        <span class="slider round"></span>
                                    </label></span>
                            </p>
                        </div>

                        <div class="wraps_switch_toggle">
                            <p>
                                <strong>Enable Category Page Pixel</strong> <span> <label class="switch">
                                        <input type="checkbox" id="category-page" {{ $catchecked }}>
                                        <span class="slider round"></span>
                                    </label></span>
                            </p>
                        </div>

                        <div class="wraps_switch_toggle">
                            <p>
                                <strong>Enable Checkout Pixel</strong> <span> <label class="switch">
                                        <input type="checkbox" id="checkout" {{ $chchecked }}>
                                        <span class="slider round"></span>
                                    </label></span>
                            </p>
                        </div>

                        <div class="wraps_switch_toggle">
                            <p>
                                <strong>Enable Page View Pixel</strong> <span> <label class="switch">
                                        <input type="checkbox" id="page-view" {{ $ischecked }}>
                                        <span class="slider round"></span>
                                    </label></span>
                            </p>
                        </div>

                        <div class="wraps_switch_toggle">
                            <p>
                                <strong>Enable Global Guard Pixel</strong> <span> <label class="switch">
                                        <input type="checkbox" id="global-guard" {{ $guardpage }}>
                                        <span class="slider round"></span>
                                    </label></span>
                            </p>
                        </div>
                    </div>

                    <hr class="wraps_line">

                    <div class="wraps_left_side">
                        <div class="butoons_raps">
                            <a href="#">
                                <button type="button" class="btn btn-primary button_wraps-left">Advance Configuration
                                    <span class="arrow_icons"><img src="{{ asset('public/assets/images/arrow icons.jpg') }}"
                                            class="arrow_icons" alt="arrow"></span>
                                </button></a>

                            <a href="#">
                                <button type="button" class="btn btn-primary button_wraps-left">View Reports
                                    <span class="arrow_icons"><img src="{{ asset('public/assets/images/arrow icons.jpg') }}"
                                            class="logo" alt="logo"></span></button>
                            </a>

                            <div class="view_reports">
                                <form id="view-report" method="POST" action={{ env('view_reports_url') }} target="_blank">
                                    <input type="hidden" name="redirect" value="/dashboard" />
                                    <input type="hidden" name="credentials" value={{ $credentialData['credentials'] }} />
                                    <button class="btn btn-primary"> View Report</button>
                                </form>
                            </div>

                        </div>
                        <p class="text-enter footer_line">© All rights reserved by Conversion Guard,Inc.</p>
                    </div>

                </div>

                <div class="col-md-6 col-lg-6 col-sm-6">
                    <div class="wrap_right-side">
                        <div class="wrap_buttons">
                            <a href="#"><button type="button" class="btn btn-primary button_wraps">Advance
                                    Configuration <span class="arrow_icons_white"><img
                                            src="{{ asset('public/assets/images/arrow-icons-white.png') }}"
                                            class="arrow_icons" alt="arrow_white"></span></button></a>
                            <a href="#"><button type="button" class="btn btn-primary button_wraps">View Reports <span
                                        class="arrow_icons_white"><img
                                            src="{{ asset('public/assets/images/arrow-icons-white.png') }}"
                                            class="arrow_icons" alt="arrow_white"></span></button></a>
                            <a href="#"><button type="button" class="btn btn-primary button_wraps">Advance
                                    Configuration <span class="arrow_icons_white"><img
                                            src="{{ asset('public/assets/images/arrow-icons-white.png') }}"
                                            class="arrow_icons" alt="arrow_white"></span></button></a>
                            <a href="#"><button type="button" class="btn btn-primary button_wraps">View Reports
                                    <span class="arrow_icons_white"><img
                                            src="{{ asset('public/assets/images/arrow-icons-white.png') }}"
                                            class="arrow_icons" alt="arrow_white"></span></button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        @parent
        <script>
            actions.TitleBar.create(app, {
                title: 'Global Settings Page'
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="{{ asset('public/assets/js/userCreate.js') }}"></script>
        <script></script>
    @endsection
    </body>

</html>
