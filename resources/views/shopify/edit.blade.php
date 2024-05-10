@extends('layouts.app')
@section('content')
<div class="container">
    <h3 class="mt-3">Shopify</h3>
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Shopify Edit</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('shopify.update', $editShopify['id']) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Store URL <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('storeurl') is-invalid @enderror" id="storeurl" name="storeurl" placeholder="Store URL" value="{{ $editShopify['storeurl'] }}">
                    @error('storeurl')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">API Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('shopifyapikey') is-invalid @enderror" id="shopifyapikey" name="shopifyapikey" placeholder="API Key" value="{{ $editShopify['shopifyapikey'] }}">
                    @error('shopifyapikey')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">API Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error ('shopifyapipassword') is-invalid @enderror" id="shopifyapipassword" name="shopifyapipassword" placeholder="API Password" value="{{ $editShopify['shopifyapipassword'] }}">
                    @error('shopifyapipassword')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Shop Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('shopifyshopname') is-invalid @enderror" id="shopifyshopname" name="shopifyshopname" placeholder="Shop Name" value="{{ $editShopify['shopifyshopname'] }}">
                    @error('shopifyshopname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Domain Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('shopifydomainname') is-invalid @enderror" id="shopifydomainname" name="shopifydomainname" placeholder="Domain Name" value="{{ $editShopify['shopifydomainname'] }}">
                    @error('shopifydomainname')
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