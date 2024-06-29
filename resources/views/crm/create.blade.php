@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="content-header">
                    <div class="col-sm-6 mt-2">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('crm.index') }}">Back</a></li>
                            <li class="breadcrumb-item active">CRM Create</li>
                        </ol>          
                    </div>
        </div>
    <div class="col-12 col-md-8">
        
        <!-- general form elements -->
        <div class="card card-primary my-4">
            <div class="card-header custom_card_header">
                <h3 class="card-title">CRM Create</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('crm.store') }}" method="post">
    @csrf
    <div class="card-body">
        <div class="form-group mb-3">
            <label for="exampleInputEmail1">Provider Label <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('providerlabel') is-invalid @enderror" id="providerlabel" name="providerlabel" placeholder="Provider Label" value="{{ old('providerlabel') }}">
            @error('providerlabel')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="exampleInputEmail1">API Endpoint <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('apiendpoint') is-invalid @enderror" id="apiendpoint" name="apiendpoint" placeholder="API Endpoint" value="{{ old('apiendpoint') }}">
            @error('apiendpoint')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="exampleInputEmail1">API Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('apiusername') is-invalid @enderror" id="apiusername" name="apiusername" placeholder="API Username" value="{{ old('apiusername') }}">
            @error('apiusername')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="exampleInputPassword1">API Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control @error('apipassword') is-invalid @enderror" id="apipassword" name="apipassword" placeholder="API Password" value="{{ old('apipassword') }}">
            @error('apipassword')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Select Provider <span class="text-danger">*</span></label>
            <select class="form-control" name="crmtype">
                <option value="1">Sticky CRM</option>
                <option value="2">Konecktive CRM</option>
            </select>
            @error('crmtype')
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
        <!-- /.card -->
    </div>
    </div>
</div>
@endsection