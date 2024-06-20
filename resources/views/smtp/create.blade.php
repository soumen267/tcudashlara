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
                    <div class="form-group">
                        <label for="exampleInputEmail1">Domain <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('domain') is-invalid @enderror" id="domain" name="domain" placeholder="Domain" value="{{ old('domain') }}">
                    @error('domain')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">From Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('fromname') is-invalid @enderror" id="fromname" name="fromname" placeholder="From Name" value="{{ old('fromname') }}">
                    @error('fromname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">From Email ID <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error ('mailfrom') is-invalid @enderror" id="mailfrom" name="mailfrom" placeholder="Mail From" value="{{ old('mailfrom') }}">
                    @error('mailfrom')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Api Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('api') is-invalid @enderror" id="api" name="api" placeholder="Api Key" value="{{ old('api') }}">
                    @error('api')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">SMTP TYPE <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('type') is-invalid @enderror" id="type" name="type" placeholder="SMTP TYPE" value="{{ old('type') }}">
                    @error('type')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
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