@extends('layouts.app')



@section('content')

<div class="container">

    <div class="content-header">

            <div class="row">

                <div class="col-sm-6 mt-2">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Back</a></li>

                        <li class="breadcrumb-item active">Create</li>

                    </ol>

                </div>
                </div>


    </div>

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card my-4">

                <div class="card-header">{{ __('Register') }}</div>



                <div class="card-body">

                    <form method="POST" action="{{ route('users.store') }}">

                        @csrf



                        <div class="row mb-3">

                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>



                            <div class="col-md-6">

                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>



                                @error('name')

                                    <span class="invalid-feedback" role="alert">

                                        <strong>{{ $message }}</strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        <div class="row mb-3">

                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>



                            <div class="col-md-6">

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">



                                @error('email')

                                    <span class="invalid-feedback" role="alert">

                                        <strong>{{ $message }}</strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        <div class="row mb-3">

                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>



                            <div class="col-md-6">

                                <div class="row mb-12">

                                <div class="col-md-12 toggle-container">

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror pswd" name="password" required autocomplete="new-password">

                                <i class="fa fa-eye field-icon togglePassword" aria-hidden="true" id="togglePassword"></i>

                                @error('password')

                                    <span class="invalid-feedback" role="alert">

                                        <strong>{{ $message }}</strong>

                                    </span>

                                @enderror

                                </div>

                                <!-- <div class="col-md-3 flex items-center place-content-end ml-1">

                                    <button type="button" class="btn btn-secondary" onclick="generateRandomPassword()">Generate</button>

                                </div> -->

                                </div>

                            </div>

                            

                            

                        </div>



                        <div class="row mb-3">

                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>



                            <div class="col-md-6 toggle-container">

                                <input id="password-confirm" type="password" class="form-control pswd" name="password_confirmation" required autocomplete="new-password">

                                <i class="fa fa-eye field-icon togglePassword" aria-hidden="true" id="togglePasswordConfirm"></i>

                                @error('password')

                                    <span class="invalid-feedback" role="alert">

                                        <strong>{{ $message }}</strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        <div class="row mb-3">

                            <label for="" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>



                            <div class="col-md-6">

                                <select name="role" class="form-control" id="role" required>

                                        <option value="admin">Admin</option>

                                        <option value="superadmin">Super Admin</option>

                                </select>

                            </div>

                        </div>



                        <div class="row mb-0">

                            <div class="col-md-6 offset-md-4">

                                <button type="submit" class="btn btn-primary">

                                    {{ __('Register') }}

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@push('script_src')

<script>

window.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.togglePassword').forEach(el => {
      el.addEventListener('click', e => {
    let password = el.closest('.toggle-container').querySelector('.pswd');
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    e.target.classList.toggle('fa-eye-slash');
  });
});

});

function generateRandomPassword() {

    var pass = '';

    var str='ABCDEFGHIJKLMNOPQRSTUVWXYZ'

    +  'abcdefghijklmnopqrstuvwxyz0123456789@#$';



   for (let i = 1; i <= 8; i++) { 

      var char = Math.floor(Math.random()* str.length + 1); 

            pass += str.charAt(char) 

        } 

  $('#password').val(pass);

}

</script>

@endpush

@endsection