@extends('layouts.app')
@section('content')
<div class="container">
  <h3 class="text-center mt-3 mb-5">List Of Customer</h3>
      <div class="row">
        <div class="col-lg-8 mb-3">
          <form class="d-inline-flex searchFrm" action="" method="get">
            <div class="form-group" style="margin: 6px;">
              <label for="name" class="">Status:</label>
              <a href="{{ url("dashboard", \Helper::getIdfromUrl()) }}" type="button" class="btn btn-primary mr-3 mb-2 success">Success</a>
              <a href="{{ url("dashboard/failed", \Helper::getIdfromUrl()) }}" type="button" class="btn btn-danger mr-3 mb-2 failed">Failed</a>
            </div>
            <div class="form-group mt-1">
              <label for="date" class="sr-only">date</label>
              <input type="text" class="form-control" name="dates" id="dates" value="" placeholder="Date Range">
            </div>
            <div class="form-group mt-1">
              <button value="Search" class="btn btn-primary mr-3 mb-2 filter1" type="buttton" style="display: inline;margin-left: 7px;">Search</button>        
              <button class="btn btn-danger mr-3 mb-2" id="reset1" type="button" style="margin-left: 2px;">Reset</button>
            </div>
          </form>
        </div>
        <div class="col-lg-4">
            <button class="btn btn-danger customerAdd" type="button" data-dashid="{{ \Helper::getIdfromUrl() }}" data-val="0">Add Customer</button>
            <button class="btn btn-danger customerAdd" type="button" data-dashid="{{ \Helper::getIdfromUrl() }}" data-val="1">Credit Customer</button>
            <a href="javascript:void(0)" class="btn btn-info mx-2" data-id="{{ \Helper::getIdfromUrl() }}" role="button">Config Details</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped dataTable" id="dataTable2" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Order ID</th>
              <th>Email</th>
              <th>Errors</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
    
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
      <div class="position-fixed top-0 right-0 p-3" style="z-index: 5; right: 0; top: 0;">
      <div id="liveToast" class="toast hide bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
      <div class="toast-header">
          <strong class="mr-auto">Message</strong>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="toast-body resend-msg-body">
          Hello, world! This is a toast message.
      </div>
  </div>
</div>
</div>
@include('modals.config-modal')
@push('script_src')
<script>
$(document).ready(function () {
  if($.urlParam('dates')==false){
    $("#dates").val('');
  }
  $('.failed').trigger('click');
  $('.failed').addClass('actives');
});
$(".mx-2").click(function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  $.ajax({
    type: "POST",
    url: "{{ route('getDashData') }}",
    data: {"id":id, "_token":"{{csrf_token()}}"},
    dataType: "json",
    success: function (response) {
        console.log(response);
        $("#modal-xl").show();
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
$(".filter1").click(function (e) {
    e.preventDefault();
    const type = $('input[name=type]:checked').val();
    var from_date = $('input[name="dates"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var to_date = $('input[name="dates"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
    if(from_date != '' && to_date != ''){
      table2.destroy();
      load_data1(from_date, to_date, type);
    }else{
      alert('Both Date is required');
    }
});
$("#reset1").click(function(e){
    e.preventDefault()
    $("#dates").val('');
    $('input[value="failed"]').trigger('click');
    table2.destroy();
    load_data1()
})
var str = (window.location).href; // You can also use document.URL
var Id = str.split('?')[0].split('/').reverse()[0];
$('input[name="dates"]').daterangepicker({
        startDate: moment().subtract(1, 'M'),
        endDate: moment()
});

load_data1()
function load_data1(from_date = '', to_date = '', type = '')
{
  
    table2 = $('#dataTable2').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        destroy: true,
        "bSortable": true,
        ajax: {
          url: Id,
          data:function (d) {
                d.from_date = from_date;
                d.to_date = to_date;
                d.type = $('input[name=type]:checked').val();
          }
        },
        // drawCallback: function (setting) {
        //   $('#dataTable2 thead tr').show();
        // },
        columns:[
          {data: 'id', name: 'id', searchable: true, sortable : true},
          {data: 'order_id', name: 'order_id', searchable: true, sortable : true},
          {data: 'email', name: 'email', searchable: true, sortable : true},
          {data: 'error_msg', name: 'error_msg', searchable: true, sortable : true},
          {data: 'created_at', name: 'created_at'}
        ],
        language: {
          processing: "Loading... <i style='font-size:20px' class='fa fa-refresh fa-spin'></i>"
        },
        lengthMenu: [[10, 20,25,50,100, -1], [10, 20,25,50,100, "All"]],
        dom: 'RlBfrtlip',
        orientation : 'landscape',
        pageSize : 'A0',
        buttons: [
          'copy', 'csv', 'excel', 'print', { extend: 'pdf',exportOptions: {
            columns: 'th:not(:last-child)'
            }
          }
        ],
        "pagingType": "full_numbers"        
    });
}
$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.search);
    return (results !== null) ? results[1] || 0 : false;
}
</script>
@endpush
@endsection