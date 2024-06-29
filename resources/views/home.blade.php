@extends('layouts.app')

@section('content')
@push('style_src')
<style>
span{
       font-size: 18px;
    font-weight: 600;
}
h4{
    font-weight: 600;
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
    padding:20px 10px;
    margin:10px 0px;
}
.text{
       margin: 0px 0px 15px;
}

.fa{
     color:#4183D7;
}

#app{
      background: #0250c5;
    background: -webkit-linear-gradient(bottom, #0250c5, #d43f8d);
    background: -o-linear-gradient(bottom, #0250c5, #d43f8d);
    background: -moz-linear-gradient(bottom, #0250c5, #d43f8d);
    background: linear-gradient(bottom, #0250c5, #d43f8d);
   
}
.wrapper{
     background: url(https://vipupselldashboard.com/public/assets/login/images/log-in-bg.png) no-repeat no-repeat;
     background-size: cover;
     height: 100vh;
         overflow: auto;
}
section.content{
        padding: 40px 0px 80px;
}


.hvr-bounce-to-bottom {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  box-shadow: 0 0 1px rgba(0, 0, 0, 0);
  position: relative;
  -webkit-transition-property: color;
  transition-property: color;
  -webkit-transition-duration: 0.4s;
  transition-duration: 0.4s;
  background: #244baf;
    color: #fff;
    padding: 8px 25px;
    border-radius: 4px;
    font-size: 18px;

overflow: hidden;
}
.hvr-bounce-to-bottom:before {
  content: "";
  position: absolute;
  z-index: -1;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #000;
  -webkit-transform: scaleY(0);
  transform: scaleY(0);
  -webkit-transform-origin: 50% 0;
  transform-origin: 50% 0;
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transition-duration: 0.4s;
  transition-duration: 0.4s;
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}
.hvr-bounce-to-bottom:hover, .hvr-bounce-to-bottom:focus, .hvr-bounce-to-bottom:active {
  color: white !important;
}
.hvr-bounce-to-bottom:hover:before, .hvr-bounce-to-bottom:focus:before, .hvr-bounce-to-bottom:active:before {
  -webkit-transform: scaleY(1);
  transform: scaleY(1);
  -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
  transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
}

.dashboard-icon{
   max-width: 100%;
    width: 80px;
    margin-bottom: 15px;
}
/* Ripple Out */
@-webkit-keyframes hvr-ripple-out {
  100% {
    top: -12px;
    right: -12px;
    bottom: -12px;
    left: -12px;
    opacity: 0;
  }
}
@keyframes hvr-ripple-out {
  100% {
    top: -12px;
    right: -12px;
    bottom: -12px;
    left: -12px;
    opacity: 0;
  }
}
.hvr-ripple-out {
  display: block;
  vertical-align: middle;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  box-shadow: 0 0 1px rgba(0, 0, 0, 0);
  position: relative;
}
.hvr-ripple-out:before {
  content: '';
  position: absolute;
  border: #fff solid 6px;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
}
.hvr-ripple-out:hover:before, .hvr-ripple-out:focus:before, .hvr-ripple-out:active:before {
  -webkit-animation-name: hvr-ripple-out;
  animation-name: hvr-ripple-out;
}

</style>
@endpush
<div class="container">
    <div class="row justify-content-center">
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center" >
                    @if ($getData)
                    @foreach ($getData as $key => $row)
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 dashdiv" data-id="{{ $row['id'] }}" style="cursor: pointer">
                        {{-- <a href="{{ url('dashboard',$row['id']) }}" style="text-decoration:none"> --}}
                        <div class="box-part text-center hvr-ripple-out">
                            
                            <img src="https://vipupselldashboard.com/public/assets/login/images/dashboard-icon.png?v=<?= rand()?>" class="dashboard-icon" alt="sign-in">
                            
                            <div class="title">
                                <h4>Dash {{ ++$key }}</h4>
                            </div>
                            
                            <div class="text">
                                <span>{{ $row['dashname'] }}</span>
                            </div>
                            
                            <a href="javascript:void(0)" class="cnfg hvr-bounce-to-bottom" data-id="{{ $row['id'] }}">Learn More</a>
                            
                         </div>
                        {{-- </a> --}}
                    </div>
                    @endforeach
                    @endif
                    @if (Auth::user())
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
