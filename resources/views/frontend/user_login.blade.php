@extends('frontend.layouts.app')

@section('content')
    <style>
        .auth-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: calc(100vh - 60px);
            padding: 3.5rem 1rem;
            background: radial-gradient(circle at 15% 15%, #f7f0fc 0%, #f1e9f8 55%, #ece2f4 100%);
        }
        .auth-section .profile {
            width: 100%;
        }
        .auth-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 16px 50px rgba(96, 32, 128, 0.14);
            overflow: hidden;
            background: #fff;
        }
        .auth-card-head {
            padding: 2.25rem 2rem 1rem;
            text-align: center;
        }
        .auth-head-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6a2a8f 0%, #8f3fb8 100%);
            color: #fff;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(143, 63, 184, 0.35);
            margin-bottom: 1rem;
        }
        .auth-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d1b3a;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }
        .auth-subtitle {
            font-size: 0.9rem;
            color: #94879e;
            margin: 0;
        }
        .auth-card-body {
            padding: 0.5rem 2rem 2.25rem;
        }

        .login-input-group .login-prepend {
            background: #fff;
            border: 1px solid #e7e0ef;
            border-right: 0;
            color: #8f3fb8;
            padding: 0 0.85rem;
            border-top-left-radius: 0.7rem;
            border-bottom-left-radius: 0.7rem;
            font-size: 1.05rem;
        }
        .login-input-group .form-control {
            height: 52px;
            border: 1px solid #e7e0ef;
            border-left: 0;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            font-size: 0.95rem;
            color: #33223f;
            box-shadow: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .login-input-group .form-control::-webkit-input-placeholder { color: #b4a8c0; }
        .login-input-group .form-control::-moz-placeholder { color: #b4a8c0; }
        .login-input-group .form-control::placeholder { color: #b4a8c0; }
        .login-input-group .login-append {
            background: #fff;
            border: 1px solid #e7e0ef;
            border-left: 0;
            color: #8f3fb8;
            padding: 0 0.9rem;
            border-top-right-radius: 0.7rem;
            border-bottom-right-radius: 0.7rem;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .login-input-group .login-append:hover { color: #6a2a8f; }
        .login-input-group:focus-within .login-prepend,
        .login-input-group:focus-within .form-control,
        .login-input-group:focus-within .login-append { border-color: #8f3fb8; }
        .login-input-group:focus-within { box-shadow: 0 0 0 0.2rem rgba(143, 63, 184, 0.1); border-radius: 0.7rem; }

        .phone-form-group .intl-tel-input {
            flex: 1 1 0%;
            min-width: 0;
        }
        .phone-form-group .intl-tel-input input {
            width: 100%;
            height: 52px;
            border: 1px solid #e7e0ef;
            border-left: 0;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 0.7rem;
            border-bottom-right-radius: 0.7rem;
            font-size: 0.95rem;
            color: #33223f;
            background: #fff;
            box-shadow: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .phone-form-group .intl-tel-input input::-webkit-input-placeholder { color: #b4a8c0; }
        .phone-form-group .intl-tel-input input::-moz-placeholder { color: #b4a8c0; }
        .phone-form-group .intl-tel-input input::placeholder { color: #b4a8c0; }
        .phone-form-group:focus-within .intl-tel-input input { border-color: #8f3fb8; }

        .auth-remember { display: flex; align-items: center; font-size: 0.875rem; }
        .auth-forgot { font-size: 0.875rem; font-weight: 600; color: #6a2a8f; }
        .auth-forgot:hover { color: #8f3fb8; text-decoration: none; }

        .auth-submit {
            height: 50px;
            border-radius: 10px;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-outline-secondary.auth-submit {
            border-color: #e0d6ea;
            color: #6a2a8f;
        }
        .btn-outline-secondary.auth-submit:hover {
            background: #f4edfa;
            border-color: #8f3fb8;
            color: #6a2a8f;
        }

        .auth-separator {
            display: flex;
            align-items: center;
            margin: 1.25rem 0;
        }
        .auth-separator::before,
        .auth-separator::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee7f4;
        }
        .auth-separator span {
            padding: 0 0.9rem;
            font-size: 0.8rem;
            color: #8b7c97;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @media (max-width: 575.98px) {
            .auth-card-body { padding-left: 1.5rem; padding-right: 1.5rem; }
        }
    </style>

    <section class="auth-section">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                        <div class="card auth-card">
                            <div class="auth-card-head">
                                <span class="auth-head-icon"><i class="las la-user-lock"></i></span>
                                <h1 class="auth-title">{{ translate('Welcome back') }}</h1>
                                <p class="auth-subtitle">{{ translate('Login to your account.') }}</p>
                            </div>

                            <div class="auth-card-body">
                                <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    @if (addon_is_activated('otp_system') && env("DEMO_MODE") != "On")
                                        <div class="form-group phone-form-group mb-1">
                                            <div class="input-group login-input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text login-prepend"><i class="las la-mobile-alt"></i></span>
                                                </div>
                                                <input type="tel" id="phone-code" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{ translate('Mobile Number') }}" name="phone" autocomplete="off">
                                            </div>
                                            @if ($errors->has('phone'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <input type="hidden" name="country_code" value="">

                                        <div class="form-group email-form-group d-none">
                                            <div class="input-group login-input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text login-prepend"><i class="las la-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email" id="email" autocomplete="off">
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group text-right mb-3">
                                            <button class="btn btn-link p-0 opacity-50 text-reset fs-13" type="button" onclick="toggleEmailPhone(this)">{{ translate('Use Email Instead') }}</button>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <div class="input-group login-input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text login-prepend"><i class="las la-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email" id="email" autocomplete="off">
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <div class="input-group login-input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text login-prepend"><i class="las la-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ translate('Password') }}" name="password" id="password" autocomplete="current-password">
                                            <div class="input-group-append">
                                                <button type="button" class="input-group-text login-append" id="togglePassword" onclick="togglePasswordVisibility()" aria-label="{{ translate('Show or hide password') }}" tabindex="-1">
                                                    <i class="las la-eye" id="togglePasswordIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <label class="aiz-checkbox mb-0">
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <span class="opacity-60">{{ translate('Remember Me') }}</span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                        <div class="col-6 text-right">
                                            <a href="{{ route('password.request') }}" class="auth-forgot">{{ translate('Forgot password?') }}</a>
                                        </div>
                                    </div>

                                    <div class="row gutters-5 mb-4">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-brand btn-block fw-700 auth-submit">
                                                <i class="las la-sign-in-alt mr-1"></i>{{ translate('Login') }}
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('user.registration') }}" class="btn btn-outline-secondary btn-block fw-700 auth-submit">
                                                <i class="las la-user-plus mr-1"></i>{{ translate('Registration') }}
                                            </a>
                                        </div>
                                    </div>
                                </form>

                                @if (env("DEMO_MODE") == "On")
                                    <div class="mb-4">
                                        <table class="table table-bordered mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>{{ translate('Seller Account') }}</td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm" onclick="autoFillSeller()">{{ translate('Copy credentials') }}</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ translate('Customer Account') }}</td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm" onclick="autoFillCustomer()">{{ translate('Copy credentials') }}</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ translate('Delivery Boy Account') }}</td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm" onclick="autoFillDeliveryBoy()">{{ translate('Copy credentials') }}</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1)
                                    <div class="auth-separator"><span>{{ translate('Or Login With') }}</span></div>
                                    <ul class="list-inline social colored text-center mb-0">
                                        @if (get_setting('facebook_login') == 1)
                                            <li class="list-inline-item">
                                                <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                                    <i class="lab la-facebook-f"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if(get_setting('google_login') == 1)
                                            <li class="list-inline-item">
                                                <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                                    <i class="lab la-google"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if (get_setting('twitter_login') == 1)
                                            <li class="list-inline-item">
                                                <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
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
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        var isPhoneShown = false;
        var input = document.querySelector("#phone-code");

        if (input && typeof window.intlTelInputGlobals !== 'undefined') {
            var countryData = window.intlTelInputGlobals.getCountryData();

            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                if (country.iso2 == 'bd') {
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

            isPhoneShown = true;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

            input.addEventListener("countrychange", function(e) {
                var country = iti.getSelectedCountryData();
                $('input[name=country_code]').val(country.dialCode);
            });
        }

        function toggleEmailPhone(el) {
            if (isPhoneShown) {
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var eyeIcon = document.getElementById('togglePasswordIcon');
            if (!passwordInput || !eyeIcon) {
                return;
            }
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('la-eye');
                eyeIcon.classList.add('la-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('la-eye-slash');
                eyeIcon.classList.add('la-eye');
            }
        }

        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
        function autoFillDeliveryBoy(){
            $('#email').val('deliveryboy@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection