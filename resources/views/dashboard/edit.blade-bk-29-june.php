@extends('layouts.app')
@section('content')
<div class="container">
    <div class="content-header">
        <div class="container-fluid">
                <div class="col-sm-6 mt-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Back</a></li>
                        <li class="breadcrumb-item active">Dashboard Edit</li>
                    </ol>
                </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Dashboard Edit</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ url('dashboards/update') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $editDashboard['id'] }}">
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('dashname') is-invalid @enderror" id="dashname" name="dashname" placeholder="Name" value="{{ $editDashboard['dashname'] }}">
                        @error('dashname')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">CRM <span class="text-danger">*</span></label>
                        <select name="crm_id" id="crm_id" class="form-control">
                            @foreach ($getCRMData as $crm)
                            <option value="{{ $crm['id'] }}" class="{{ $crm['id'] === $editDashboard['crm_id'] ? 'selected' : '' }}">{{ $crm['providerlabel'] }}</option>
                            @endforeach
                        </select>
                        @error('crm_id')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">SMTP <span class="text-danger">*</span></label>
                        <select name="smtp_id" id="smtp_id" class="form-control">
                            @foreach ($getSMTPData as $smtp)
                            <option value="{{ $smtp['id'] }}" class="{{ $smtp['id'] === $editDashboard['smtp_id'] ? 'selected' : '' }}">{{ $smtp['name'] }}</option>
                            @endforeach
                        </select>
                        @error('smtp_id')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Shopify <span class="text-danger">*</span></label>
                        <select name="shopify_id" id="shopify_id" class="form-control">
                            @foreach ($getShopifyData as $shopify)
                            <option value="{{ $shopify['id'] }}" class="{{ $shopify['id'] === $editDashboard['shopify_id'] ? 'selected' : '' }}">{{ $shopify['storeurl'] }}</option>
                            @endforeach
                        </select>
                        @error('shopify_id')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Select Product ID Allowed For Coupon (Sticky) <span class="text-danger">*</span></label>
                        <input type="text" value="{{ $getProductsData }}" data-role="tagsinput" id="products" name="products" class="form-control">
                        @error('products')
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