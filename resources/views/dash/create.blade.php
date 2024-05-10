@extends('layouts.app')
@section('content')
<div class="container">
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Dashboard Create</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
                <form action="{{ route("home.store") }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Dashboard Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('dashname') is-invalid @enderror" id="dashname" name="dashname" placeholder="Dashboard Name" value="{{ old('dashname') }}">
                    @error('dashname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label>Select CRM <span class="text-danger">*</span></label>
                        <select class="form-control" name="crmname">
                        @foreach ($getCRMData as $key => $crm)
                          <option value="{{ $crm['id'] }}">{{ $crm['providerlabel'] }}</option>
                        @endforeach
                        </select>
                    @error('crmname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label>Select SMTP <span class="text-danger">*</span></label>
                        <select class="form-control" name="smtpname">
                            @foreach ($getSMTPData as $key => $smtp)
                            <option value="{{ $smtp['id'] }}">{{ $smtp['name'] }}</option>
                            @endforeach
                        </select>
                    @error('smtpname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label>Select SHOPIFY <span class="text-danger">*</span></label>
                        <select class="form-control" name="shopifyname">
                            @foreach ($getShopifyData as $key => $shopify)
                            <option value="{{ $shopify['id'] }}">{{ $shopify['storeurl'] }}</option>
                            @endforeach
                        </select>
                    @error('shopifyname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label>Select Product ID Allowed For Coupon (Sticky) <span class="text-danger">*</span></label>
                        <input type="text" value="" data-role="tagsinput" id="products" name="products" class="form-control">
                    @error('products')
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
@endsection