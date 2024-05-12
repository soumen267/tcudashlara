@extends('layouts.main.app')
@section('content')
<div class="container">
<h3 class="text-center mt-2">List Of Customer</h3>
<div class="container-fluid my-4">
  <div class="d-md-flex justify-content-between">
    <form class="form-inline searchFrm" action="" method="get">
      <div class="form-group mr-3 mb-2">
        <label for="name" class="sr-only">name</label>
        <input type="text" class="form-control" name="str" value="" placeholder="Keyword">
      </div>
      <div class="form-group mr-3 mb-2">
         <label for="name" class="">Status:</label>
        <input type="radio" name="type" value="success">Success
        <input type="radio"  name="type" value="failed">Failed
      </div>
      <div class="form-group mr-3 mb-2">
        <label for="date" class="sr-only">date</label>
        <input type="text" class="form-control" name="dates" id="dates" value="" placeholder="Date Range">
      </div>
      <button type="submit" name="search" value="Search" class="btn btn-primary mr-3 mb-2">Search</button>

      <a href="index.php"  id="reset" class="btn btn-danger mr-3 mb-2" >Reset</a>

       <!-- <a href="error_data.php"  id="reset" class="btn btn-info mr-3 mb-2" >Failed Data</a> -->


    </form>
    <div>
        <a href="add-customer.php" class="btn btn-danger" role="button" data-bs-toggle="button">Add Customer</a>
        <a href="credit-customer.php" class="btn btn-danger" role="button" data-bs-toggle="button">Credit Customer</a>
        
        <a href="config-details.php" class="btn btn-info mx-2" role="button" data-bs-toggle="button">Config Details</a>
    </div>
</div>
</div>
<div class="card-body">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Contact Details</th>
          <th>Password</th>
          <th>Coupon Code</th>
          <th>Balance</th>
          <th>Mail Status</th>
          <th>Created Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @if($getDatas)
        @forelse ($getDatas as $key => $row)
        <tr>
          <td>{{ $row['id'] }}</td>
          <td>{{ $row->shopifyCustomers['name']}}</td>
          <td>{{ $row->shopifyCustomers['email_address'] }}</td>
          <td>{{ $row->shopifyCustomers['password'] }}</td>
          <td>{{ $row->shopifyCustomers['coupon_code'] }}</td>
          <td>{{ $row->shopifyCustomers['balance'] }}</td>
          <td>
            <?php if($row['mail_status'] == 'Sent') { ?>
                <a href="javascript:void(0);" class="btn btn-success btn-sm re-send-email" data-email="{{ $row->shopifyCustomers['email_address'] }}"  role="button">Re-Send Mail</a>
            <?php } else { ?>                                
                <a href="javascript:void(0);" class="btn btn-danger btn-sm re-send-email" data-email="{{ $row->shopifyCustomers['email_address'] }}" role="button">Send Mail</a>
            <?php } ?>
          </td>
          <td><?= date('M d,Y',strtotime($row['created_at']))?></td>
          <td>
            <a href="javascript:void(0);" class="btn btn-success btn-sm" data-email="{{ $row->shopifyCustomers['email_address'] }}"  role="button">Edit</a>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No data found</td>
        </tr>
        @endforelse
        @endif
      </tbody>
    </table>
  </div>
</div>
</div>
<!--Model-->
<div class="modal fade show" id="modal-xl" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Config Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid my-4">
          <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
              <thead>
                  <tr>
                      <th width="40%">Key</th>
                      <th>Value</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>Shopify Store</td>
                      <td>
                          <input type="text" class="form-control" name="__SHOPIFYSHOPNAME__" placeholder="Shopify Store" value="{{ $getDashboards->shopify['storeurl'] }}">
                      </td>
                  </tr>
                  <tr>
                      <td>Shopify API Version</td>
                      <td>2014-10</td>
                  </tr>
                  <tr>
                      <td>Shopify Domain Name</td>
                      <td>{{ $getDashboards->shopify['shopifydomainname'] }}</td>
                  </tr>
                  <tr>
                      <td>Shopify Store Name</td>
                      <td>{{ $getDashboards->shopify['shopifyshopname'] }}</td>
                  </tr>
                  <tr>
                      <td>From Email ID</td>
                      <td>{{ $getDashboards->smtp['mailfrom'] }}</td>
                  </tr>
                  <tr>
                      <td>From Email Name</td>
                      <td>{{ $getDashboards->smtp['username'] }}</td>
                  </tr>
                  <tr>
                      <td>Product ID Allowed For Coupon (Sticky)</td>
                      <td>{{ $getAllowedProduct }}</td>
                  </tr>
                  
                  <tr>
                      <td>CRM Sticky Endpoint</td>
                      <td>{{ $getDashboards->crm['apiendpoint'] }}</td>
                  </tr>
                  <tr>
                      <td>SMTP Type</td>
                      <td>{{ $getDashboards->smtp['domain'] }}</td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      {{-- <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!--EndModel-->
@push('script_src')
<script>
$(".mx-2").click(function (e) { 
  e.preventDefault();
  $("#modal-xl").show();
});
$(".close").click(function(){
  $("#modal-xl").hide();
})
$(document).ready(function() {
  $('input[name="dates"]').daterangepicker();
  if($.urlParam('dates')==false){
    $("#dates").val('');
  }
})

$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.search);
    return (results !== null) ? results[1] || 0 : false;
}
</script>
@endpush
@endsection