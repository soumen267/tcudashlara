@extends('layouts.app')
@section('content')
<div class="container">
    <div class="content-header">
        <div class="container-fluid">
                <div class="col-sm-6 mt-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('smtp.index') }}">Back</a></li>
                        <li class="breadcrumb-item active">SMTP</li>
                    </ol>
                </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">SMTP Profile Edit</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('smtp.update', $editSMTP['id']) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $editSMTP['name'] }}">
                    @error('name')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Domain <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('domain') is-invalid @enderror" id="domain" name="domain" placeholder="Domain" value="{{ $editSMTP['domain'] }}">
                    @error('domain')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">From Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('fromname') is-invalid @enderror" id="fromname" name="fromname" placeholder="From Name" value="{{ $editSMTP['fromname'] }}">
                    @error('fromname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">From Email ID <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error ('mailfrom') is-invalid @enderror" id="mailfrom" name="mailfrom" placeholder="Mail From" value="{{ $editSMTP['mailfrom'] }}">
                    @error('mailfrom')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Api Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('api') is-invalid @enderror" id="api" name="api" placeholder="Api Key" value="{{ $editSMTP['api'] }}">
                    @error('api')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">SMTP Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('type') is-invalid @enderror" id="type" name="type" placeholder="SMTP Type" value="{{ $editSMTP['type'] }}">
                    @error('type')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Email Template <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('emailtemplatepath') is-invalid @enderror" id="emailtemplatepath" name="emailtemplatepath" placeholder="Email Template" value="{{ $editSMTP['emailtemplatepath'] }}">
                    @error('emailtemplatepath')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection