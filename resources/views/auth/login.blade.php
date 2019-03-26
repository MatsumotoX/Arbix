@extends('layouts.app') @section('title', 'Assets')
@section('style')
    <style>
        .cd-signin-modal__input {
            margin: 0;
            padding: 0;
            border-radius: 0.25em;
        }

        .cd-signin-modal__input.cd-signin-modal__input--full-width {
            width: 100%;
        }

        .cd-signin-modal__input.cd-signin-modal__input--has-padding {
            padding: 12px 20px 12px 50px;
        }

        .cd-signin-modal__input.cd-signin-modal__input--has-border {
            border: 1px solid #d2d8d8;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .cd-signin-modal__input.cd-signin-modal__input--has-border:focus {
            border-color: #343642;
            -webkit-box-shadow: 0 0 5px rgba(52, 54, 66, 0.1);
            box-shadow: 0 0 5px rgba(52, 54, 66, 0.1);
            outline: none;
        }

        .cd-signin-modal__input.cd-signin-modal__input--has-error {
            border: 1px solid #d76666;
        }

        .cd-signin-modal__input[type=submit] {
            padding: 16px 0;
            cursor: pointer;
            background: #262262;
            color: #FFF;
            font-weight: bold;
            border: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .cd-signin-modal__input[type=submit]:hover, .cd-signin-modal__input[type=submit]:focus {
            background: black;
            outline: none;
        }

        .cd-signin-modal__input.cd-signin-modal__input--full-width {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <section class="cid-r0kOe0qdGK" id="formeec">

        <div class="row" id="app">
            <div class="container">
                <div class="col-md-12">
                    <h4 class="section-content-title align-center mbr-fonts-style display-5">
                        Login
                    </h4>

                </div>
            </div>
        </div>
        <div class="container" style="margin-top: 25px">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        <div class="card-body"
                             style="background: white; padding: 50px 20px 50px 20px; border-radius: 4px; border: 1px solid #e8e8e8;">
                            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                                @csrf

                                <div class="form-group row ">
                                    <div class="col-md-8 offset-md-2">
                                        <input id="email" type="email" class="input" name="email"
                                               value="{{ old('email') }}" placeholder="Email" required autofocus>

                                        @if ($errors->has('email'))
                                            <p class="help is-danger">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-8 offset-md-2">
                                        <input id="password" type="password"
                                               class="input {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                               name="password" placeholder="Password" required>

                                        @if ($errors->has('password'))
                                            <p class="help is-danger">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-2 offset-md-2">
                                        <div class="form-check" style="margin-top: 5px">
                                            <input class="" type="checkbox" name="remember"
                                                   id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0" style="margin-top: 35px">
                                    <div class="col-md-8 offset-md-2">
                                        <button type="submit"
                                                class="cd-signin-modal__input cd-signin-modal__input--full-width">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                    <div class="col-md-4 offset-md-2" style="margin-top: 5px">
                                        <a href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

