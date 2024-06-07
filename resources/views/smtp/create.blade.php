@extends('layouts.app')
@section('content')
<div class="container">
    <div class="content-header">
        <div class="container-fluid">
                <div class="col-sm-6 mt-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('smtp.index') }}">Back</a></li>
                        <li class="breadcrumb-item active">SMTP Create</li>
                    </ol>
                </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">SMTP Profile Create</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <div class="card-body">
            <form action="{{ route('smtp.store') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                    @error('name')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="exampleInputEmail1">HOST <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('host') is-invalid @enderror" id="host" name="host" placeholder="HOST" value="{{ old('host') }}">
                    @error('host')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="exampleInputEmail1">Domain <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('domain') is-invalid @enderror" id="domain" name="domain" placeholder="Domain" value="{{ old('domain') }}">
                    @error('domain')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="exampleInputEmail1">Port <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('port') is-invalid @enderror" id="port" name="port" placeholder="Port" value="{{ old('port') }}">
                    @error('port')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error ('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    @error('email')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mail From <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error ('mailfrom') is-invalid @enderror" id="mailfrom" name="mailfrom" placeholder="Mail From" value="{{ old('mailfrom') }}">
                    @error('mailfrom')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="exampleInputEmail1">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('username') is-invalid @enderror" id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                    @error('username')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error ('password') is-invalid @enderror" id="password" name="password" placeholder="Password" value="{{ old('password') }}">
                    @error('password')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="exampleInputPassword1">Email Template <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('emailtemplatepath') is-invalid @enderror" id="emailtemplatepath" name="emailtemplatepath" placeholder="Email Template" value="{{ old('emailtemplatepath') }}">
                    @error('emailtemplatepath')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>                    
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection