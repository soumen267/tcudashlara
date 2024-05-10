@extends('layouts.app')
@section('content')
<div class="container">
    <h3 class="mt-3">CRM</h3>
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">CRM Edit</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('crm.update', $editCRM['id']) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Provider Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('providerlabel') is-invalid @enderror" id="providerlabel" name="providerlabel" placeholder="Provider Label" value="{{ $editCRM['providerlabel'] }}">
                    @error('providerlabel')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">API Endpoint <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('apiendpoint') is-invalid @enderror" id="apiendpoint" name="apiendpoint" placeholder="API Endpoint" value="{{ $editCRM['apiendpoint'] }}">
                    @error('apiendpoint')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">API Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('apiusername') is-invalid @enderror" id="apiusername" name="apiusername" placeholder="API Username" value="{{ $editCRM['apiusername'] }}">
                    @error('apiusername')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">API Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error ('apipassword') is-invalid @enderror" id="apipassword" name="apipassword" placeholder="API Password" value="{{ $editCRM['apipassword'] }}">
                    @error('apipassword')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Select Provider <span class="text-danger">*</span></label>
                        <select class="form-control" name="crmtype">
                          <option value="1" @if ($editCRM['crmtype'] == '1') selected="selected"@endif>Sticky CRM</option>
                          <option value="2" @if ($editCRM['crmtype'] == '2') selected="selected"@endif>Konecktive CRM</option>
                        </select>
                    @error('crmtype')
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