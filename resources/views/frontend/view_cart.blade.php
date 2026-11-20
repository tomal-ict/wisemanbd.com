@extends('frontend.layouts.app')
@if(Auth::user())

@section('content')

<section class="pt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="row aiz-steps arrow-divider">
                    <div class="col active">
                        <div class="text-center text-primary">
                            <i class="la-3x mb-2 las la-shopping-cart"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-map"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('2. Shipping info')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-truck"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('3. Delivery info')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Payment')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4" id="cart-summary">
    <div class="container">
        @if( $carts && count($carts) > 0 )
            <div class="row">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    @include('frontend.partials.cart_page_box', ['carts' => $carts, 'variant' => 'logged'])
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="shadow-sm bg-white p-4 rounded">
                        <div class="text-center p-3">
                            <i class="las la-frown la-3x opacity-60 mb-3"></i>
                            <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@else

@section('content')

<section class="pt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="row aiz-steps arrow-divider">
                    <div class="col active">
                        <div class="text-center text-primary">
                            <i class="la-3x mb-2 las la-shopping-cart"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart')}}</h3>
                        </div>
                    </div>
                    <!--<div class="col">-->
                    <!--    <div class="text-center">-->
                    <!--        <i class="la-3x mb-2 opacity-50 las la-map"></i>-->
                    <!--        <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('2. Shipping info')}}</h3>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col">-->
                    <!--    <div class="text-center">-->
                    <!--        <i class="la-3x mb-2 opacity-50 las la-truck"></i>-->
                    <!--        <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('3. Delivery info')}}</h3>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col">-->
                    <!--    <div class="text-center">-->
                    <!--        <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>-->
                    <!--        <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Payment')}}</h3>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<form class="form-default" data-toggle="validator" action="{{ route('order_store') }}" role="form" method="POST">
@csrf
<section class="mb-4" id="cart-summary">
    <div class="container">

        @if( $carts && count($carts) > 0 )
            <div class="row mt-3">
                <div class="col-xxl-5 col-xl-5 mx-auto">
                    <div class="mb-4 box" >
                        <style>
                            .box{
                                border:2px solid silver;
                            }
                            .heading{
                                background:#fff;
                                height:40px;
                               padding-left:8px;
                               padding-top:3px;
                                border-bottom:1px solid silver;
                            }
                        </style>

                        <h4 class="heading"><i class="fa-solid fa-user"></i> Customer Information</h4>

                        <div class="col mb-3 mt-3">
                            <input class="form-control" type="text" placeholder="Enter Your Name" name="name" value="@if(Auth()->check()){{Auth()->user()->name}}@endif" required>
                        </div>

                        <div class="col mb-3 mt-3">
                            <input class="form-control" type="hidden" name="email" value="@if(Auth()->check()){{Auth()->user()->email}}
                                                                         @else
                                                                         customer@gmail.com
                                                                         @endif">
                        </div>

                        <div class="col mb-3">
                            @if(Auth()->check())
                              @foreach (Auth::user()->addresses as $key => $address)
                            <input class="form-control" type="text" placeholder="Enter Your Address" name="address" value="@if(Auth()->check()){{$address->address}}@endif" required>
                            @endforeach
                            @else
                            <input class="form-control" type="text" placeholder="Enter Your Address" name="address" value="" required>
                            @endif
                        </div>

                        <div class="col mb-3">
                            <input class="form-control" type="text" placeholder="Enter Your phone" name="phone" value="@if(Auth()->check()){{Auth()->user()->phone}}@endif" required>
                        </div>

                        <div class="col mb-3">
                            <select class="form-control" name="city" required id="city" onchange="citys()">
                                <option value="">Select Your Area</option>
                                <option value="In Dhaka City">In Dhaka City (60 Tk)</option>
                                <option value="sub city Of Dhaka">Sub city Of Dhaka (100 Tk)</option>
                                <option value="Out Of Dhaka City">Out Of Dhaka City (120 Tk)</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="col-xxl-7 col-xl-7 mx-auto">
                    @include('frontend.partials.cart_page_box', ['carts' => $carts, 'variant' => 'guest'])
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="shadow-sm bg-white p-4 rounded">
                        <div class="text-center p-3">
                            <i class="las la-frown la-3x opacity-60 mb-3"></i>
                            <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</section>

</form>

@endsection


@endif

@section('modal')
    <div class="modal fade" id="login-modal">
        <div class="modal-dialog modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login') }}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            @if (addon_is_activated('otp_system') && env('DEMO_MODE') != 'On')
                                <div class="form-group phone-form-group mb-1">
                                    <input type="tel" id="phone-code"
                                        class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                        value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
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

                                <div class="form-group text-right">
                                    <button class="btn btn-link p-0 opacity-50 text-reset" type="button"
                                        onclick="toggleEmailPhone(this)">{{ translate('Use Email Instead') }}</button>
                                </div>
                            @else
                                <div class="form-group">
                                    <input type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                        id="email" autocomplete="off">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group">
                                <input type="password"
                                    class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    placeholder="{{ translate('Password') }}" name="password" id="password">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
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
                        </form>

                    </div>
                    <div class="text-center mb-3">
                        <p class="text-muted mb-0">{{ translate('Dont have an account?') }}</p>
                        <a href="{{ route('user.registration') }}">{{ translate('Register Now') }}</a>
                    </div>
                    @if (get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1)
                        <div class="separator mb-3">
                            <span class="bg-white px-3 opacity-60">{{ translate('Or Login With') }}</span>
                        </div>
                        <ul class="list-inline social colored text-center mb-3">
                            @if (get_setting('facebook_login') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}"
                                        class="facebook">
                                        <i class="lab la-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if (get_setting('google_login') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'google']) }}"
                                        class="google">
                                        <i class="lab la-google"></i>
                                    </a>
                                </li>
                            @endif
                            @if (get_setting('twitter_login') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'twitter']) }}"
                                        class="twitter">
                                        <i class="lab la-twitter"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        function removeFromCartView(e, key){
            e.preventDefault();
            removeFromCart(key);
            window.location.reload();
        }

        function updateQuantity(key, element){
            $.post('{{ route('cart.updateQuantity') }}', {
                _token   :  AIZ.data.csrf,
                id       :  key,
                quantity :  element.value
            }, function(data){
                updateNavCart(data.nav_cart_view,data.cart_count);
                if (data.page_box) {
                    $('#cart-box').replaceWith(data.page_box);
                    if (window.AIZ && AIZ.extra) { AIZ.extra.plusMinus(); }
                }
            });
        }

        function showCheckoutModal(){
            $('#login-modal').modal();
        }

        $(document).on('click', '#cart-coupon-apply', function(){
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                code: $('#apply-coupon-form-cart input[name="code"]').val(),
                owner_id: $('#apply-coupon-form-cart input[name="owner_id"]').val()
            };
            $.ajax({
                type: 'POST',
                url: '{{ route('checkout.apply_coupon_code') }}',
                data: data,
                success: function(d){
                    if (d.response_message) {
                        if (d.response_message.response == 'success') {
                            if (d.box_html) { $('#coupon_box_cart').replaceWith(d.box_html); }
                            AIZ.plugins.notify('success', d.response_message.message);
                        } else {
                            AIZ.plugins.notify(d.response_message.response, d.response_message.message);
                        }
                    }
                }
            });
        });

        $(document).on('click', '#cart-coupon-remove', function(){
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                owner_id: $('#remove-coupon-form-cart input[name="owner_id"]').val()
            };
            $.ajax({
                type: 'POST',
                url: '{{ route('checkout.remove_coupon_code') }}',
                data: data,
                success: function(d){
                    if (d.box_html) { $('#coupon_box_cart').replaceWith(d.box_html); }
                    if (d.response_message) {
                        AIZ.plugins.notify('success', d.response_message.message);
                    }
                }
            });
        });

        // Country Code
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if(country.iso2 == 'bd'){
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if(selectedCountryData.iso2 == 'bd'){
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);
        });

        function toggleEmailPhone(el){
            if(isPhoneShown){
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            }
            else{
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }

    </script>

    <script>
        $('#outcity').hide();
        $('#cityy').hide();
        function citys(){
            var city=$('#city').val();
           if(city=="Out Of Dhaka City"){
                $('#outcity').show();
                $('#cityy').hide();
           }else if(city=="In Dhaka City"){
                $('#cityy').show();
                $('#outcity').hide();
           }else if(city==""){
                $('#outcity').hide();
                $('#cityy').hide();
           }
        }
    </script>
@endsection