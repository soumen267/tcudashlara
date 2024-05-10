@extends('layouts.admin.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(assets/login/images/bg-01.jpg);">
					<span class="login100-form-title-1">
						Sign In
					</span>
				</div>

				<form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                    @csrf
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">{{ __('Email') }}</span>
						<input class="input100 @error('email') is-invalid @enderror" type="email" name="email" placeholder="Enter email">
						<span class="focus-input100"></span>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
						<span class="label-input100">Password</span>
						<input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Enter password">
						<span class="focus-input100"></span>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>

					{{-- <div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
                            @if (Route::has('password.request'))
                                <a class="txt1" href="{{ route('password.request') }}" style="text-decoration: none">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
						</div>
					</div> --}}

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
