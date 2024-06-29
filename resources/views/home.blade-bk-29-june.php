@extends('layouts.app')

@section('content')
@push('style_src')
<style>
span{
    font-size:15px;
}
a{
  text-decoration:none !important;
  color: #0062cc;
  border-bottom:2px solid #0062cc;
}
/* .dashdiv:hover {
    -webkit-box-shadow: -1px -1px 0px 6px rgba(0,0,0,0.12);
    -moz-box-shadow: -1px -1px 0px 6px rgba(0,0,0,0.12);
    box-shadow: -1px -1px 0px 6px rgba(0,0,0,0.12);
} */
.box{
    padding:60px 0px;
}

.box-part{
    background:#FFF;
    border-radius:0;
    padding:60px 10px;
    margin:10px 0px;
}
.text{
    margin:20px 0px;
}

.fa{
     color:#4183D7;
}
</style>
@endpush
<div class="container">
    <div class="row justify-content-center">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @if ($getData)
                    @foreach ($getData as $key => $row)
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 dashdiv" data-id="{{ $row['id'] }}" style="cursor: pointer">
                        {{-- <a href="{{ url('dashboard',$row['id']) }}" style="text-decoration:none"> --}}
                        <div class="box-part text-center">
                            
                            <i class="fa fa-dashboard fa-3x" aria-hidden="true"></i>
                            
                            <div class="title">
                                <h4>Dash {{ ++$key }}</h4>
                            </div>
                            
                            <div class="text">
                                <span>{{ $row['dashname'] }}</span>
                            </div>
                            
                            <a href="javascript:void(0)" class="cnfg" data-id="{{ $row['id'] }}">Learn More</a>
                            
                         </div>
                        {{-- </a> --}}
                    </div>
                    @endforeach
                    @endif
                    @if (Auth::user()->name == 'superadmin')
                    <!-- ./col -->
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 dashdiv" data-id="{{ $row['id'] }}" style="cursor: pointer">
                        <a href="{{ route('dashboards.create') }}">
                        <div class="box-part text-center">
                            <div class="title">
                                <h4>+</h4>
                            </div>
                         </div>
                        </a>
                    </div>
                    
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@include('modals.config-modal')
@push('script_src')
<script>
$(".cnfg").click(function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  $.ajax({
    type: "POST",
    url: "{{ route('getDashData') }}",
    data: {"id":id, "_token":"{{csrf_token()}}"},
    dataType: "json",
    success: function (response) {
        console.log(response.getDashboards.shopify.storeurl);
        $("#modal-xl").show();
        $(".shopifystoreurl").text(response.getDashboards.shopify.storeurl);
        $(".shopifydomainname").text(response.getDashboards.shopify.shopifydomainname);
        $(".shopifyshopname").text(response.getDashboards.shopify.shopifyshopname);
        $(".mailfrom").text(response.getDashboards.smtp.mailfrom);
        $(".username").text(response.getDashboards.smtp.username);
        $(".apiendpoint").text(response.getDashboards.crm.apiendpoint);
        $(".domain").text(response.getDashboards.smtp.domain);
        $(".storeurl").val(response.getDashboards.shopify.storeurl);
        $(".product").text(response.getAllowedProduct);
    }
  });  
});
$(".close").click(function(){
  $("#modal-xl").hide();
})
$(".dashdiv").click(function () { 
    var id = $(this).data("id");
    var baseURL = window.location.origin;
    window.location.href = `${baseURL}/dashboard/${id}`;
});
</script>
@endpush
@endsection
