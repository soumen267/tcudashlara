@extends('layouts.app')
@section('content')
<div class="container">
    <div class="content-header">
         <div class="row">
                <div class="col-12 mt-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('shopify.index') }}">Back</a></li>
                        <li class="breadcrumb-item active">Shopify Edit</li>
                    </ol>
                </div>
        </div>
    </div>
    <div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <!-- general form elements -->
        <div class="card card-primary my-4">
            <div class="card-header">
                <h3 class="card-title">Shopify Edit</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('shopify.update', $editShopify['id']) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="exampleInputEmail1">Store URL <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('storeurl') is-invalid @enderror" id="storeurl" name="storeurl" placeholder="Store URL" value="{{ $editShopify['storeurl'] }}">
                    @error('storeurl')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputEmail1">API Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('shopifyapikey') is-invalid @enderror" id="shopifyapikey" name="shopifyapikey" placeholder="API Key" value="{{ $editShopify['shopifyapikey'] }}">
                    @error('shopifyapikey')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1">API Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error ('shopifyapipassword') is-invalid @enderror" id="shopifyapipassword" name="shopifyapipassword" placeholder="API Password" value="{{ $editShopify['shopifyapipassword'] }}">
                    @error('shopifyapipassword')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1">Shop Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('shopifyshopname') is-invalid @enderror" id="shopifyshopname" name="shopifyshopname" placeholder="Shop Name" value="{{ $editShopify['shopifyshopname'] }}">
                    @error('shopifyshopname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputPassword1">Domain Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('shopifydomainname') is-invalid @enderror" id="shopifydomainname" name="shopifydomainname" placeholder="Domain Name" value="{{ $editShopify['shopifydomainname'] }}">
                    @error('shopifydomainname')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="exampleInputEmail1">WebhookHash <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error ('shopifywebhookhash') is-invalid @enderror" id="shopifywebhookhash" name="shopifywebhookhash" placeholder="WebhookHash" value="{{ $editShopify['shopifywebhookhash'] }}">
                    @error('shopifywebhookhash')
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
</div>
@endsection