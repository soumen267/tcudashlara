@extends('layouts.admin.app')
@section('content')
<!DOCTYPE html>
<html lang="en">


<style type="text/css">
	
.sign-up-limiter{
      background: #0250c5;
    background: -webkit-linear-gradient(bottom, #0250c5, #d43f8d);
    background: -o-linear-gradient(bottom, #0250c5, #d43f8d);
    background: -moz-linear-gradient(bottom, #0250c5, #d43f8d);
    background: linear-gradient(bottom, #0250c5, #d43f8d);
   
}
.sign-up-wrapper{
	 background: url(https://vipupselldashboard.com/public/assets/login/images/log-in-bg.png) no-repeat no-repeat;
	 background-size: cover;
}

.sign-in-box{
	    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}
.label-input100 {
    font-family: Poppins-Regular;
    font-size: 16px;
    line-height: 1.2;
    text-align: left;
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    display: block;
    font-weight: 600;
    color: #000;
}
.login100-form {
    padding: 0px;
}
.s-box-left{
    padding: 60px;
}
.s-box-right,.s-box-left{
	width: 50%;
}
.s-box-left h1{
	font-weight: 600;
    margin-bottom: 50px;
}
.login100-form-btn {
    background-color: #124cb2;
    border-radius: 4px;
    font-size: 18px;
}
.s-box-right img{
	    width: 100%;
    height: 100%;
    object-fit: cover;
}
.wrap-login100 {
    width: 1050px;
    max-width: 100%;
}

.input-wrapper{
	    position: relative;
}
.input-wrapper span{
position: absolute;
    top: 11px;
    right: 0px;
}
input.input100{
	padding-right: 20px;
	padding-left: 0px;
}
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active{
    -webkit-background-clip: text;
    -webkit-text-fill-color: #999999;
    transition: background-color 5000s ease-in-out 0s;
    box-shadow: none;
}
@media (max-width:767px) {


	.sign-in-box{
		    flex-direction: column-reverse;
	}
	.s-box-right, .s-box-left {
    width: 100%;
}
.s-box-left {
    padding: 30px;
}


}

</style>

	<div class="limiter sign-up-limiter">
		<div class="container-login100 sign-up-wrapper">
			<div class="wrap-login100">


				<div class="sign-in-box">
					<div class="s-box-left">
						<!-- <div class="login100-form-title" style="background-image: url(assets/login/images/bg-01.jpg);">
							<span class="login100-form-title-1">
								Sign In
							</span>
						</div> -->

						

							<h1>Sign In</h1>

							<form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
			                    @csrf
								<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
									<span class="label-input100">{{ __('Email') }}</span>

									<div class="input-wrapper">
										<span><i class="fa fa-envelope" aria-hidden="true"></i></span>
										<input class="input100 @error('email') is-invalid @enderror" type="email" name="email" placeholder="Enter email" value="{{ old('email') }}">
									</div>
									<span class="focus-input100"></span>
			                        @error('email')
			                            <span class="invalid-feedback" role="alert">
			                              <strong>{{ $message }}</strong>
			                            </span>
			                        @enderror
								</div>

								<div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
									<span class="label-input100">Password</span>
									<div class="input-wrapper">
										<span><i class="fa fa-key" aria-hidden="true"></i></span>
										<input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Enter password">
									</div>
									<span class="focus-input100"></span>
			                        @error('password')
			                            <span class="invalid-feedback" role="alert">
			                                <strong>{{ $message }}</strong>
			                            </span>
			                        @enderror
								</div>

								<div class="flex-sb-m w-full p-b-30">
									<div class="contact100-form-checkbox">
										{{-- <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
										<label class="label-checkbox100" for="ckb1">
											Remember me
										</label> --}}
									</div>

									<div>
			                            @if (Route::has('password.request'))
			                                <a class="txt1" href="{{ route('password.request') }}" style="text-decoration: none">
			                                    {{ __('Forgot Your Password?') }}
			                                </a>
			                            @endif
									</div>
								</div>

								<div class="container-login100-form-btn">
									<button class="login100-form-btn" type="submit">
										Login
									</button>
								</div>
							</form>
					</div>
					<div class="s-box-right">
							
							<img src="https://vipupselldashboard.com/public/assets/login/images/log-in.png" alt="sign-in">

					</div>
				</div>
				
			</div>
		</div>
	</div>
@endsection
