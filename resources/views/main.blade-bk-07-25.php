@extends('layouts.app')

@section('content')


<style type="text/css">
.container {max-width: 1480px;}
 
#loading{position: fixed;}

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
.content-wrapper{
        padding: 40px 0px 80px;
}
.container-inner{
  background: #fff;
    padding: 20px;
    border-radius: 8px;
}

label{
  font-weight: 600 !important;
  margin-right: 10px;
}
.dropdown-menu{
  background: #f8fafc !important;
}
.dashhover {
    background: #e5e6e7 !important;
    transition: 0.4s;
}
.dashhover.selected{
  background: #244baf !important;
}
.dashhover:hover {
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    background: #244baf !important;
    color: #fff !important;
}

a.btn,button{

  font-size: 13px !important;
  color: #fff !important;

}

th{
  background: #00305a !important;
  color: #fff !important;
}
h3{
  font-weight: 600;
}
.form-control:focus {
    box-shadow: none;
}
.text-end{
 text-align: right;
}
.modal-content button.close{
  color: #000 !important;
    font-size: 30px !important;
}
@media (max-width:767px) {

.searchFrm{
  display: block !important;
  text-align: center;
}
.searchFrm .form-group:last-child{
  margin-top: 15px !important;
}
.col-lg-5.text-end{
  text-align: center !important;
}
.col-lg-5.text-end button{
  margin-bottom: 10px !important;
}
#dataTable1_length{
  margin-bottom: 10px !important;
}
.dataTables_scroll{
  margin-top: 20px !important;
}
div.dt-buttons {
    float: none;
    width: 100%;
    text-align: center;
}
}
.paginate_button:active {
  color: red !important;
}
#liveToast .close{
      color: #000 !important;
    position: absolute;
    right: 10px;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 12px;
}

</style>

<div class="container">
<div class="container-inner">

  <h3 class="text-center mt-3 mb-5">List Of Customer</h3>

      <div class="row mb-4">
        <div class="col-lg-7 mb-3">

          <form class="d-inline-flex searchFrm" action="" method="get">

            <div class="form-group mt-0" style="margin: 6px;">

              <label for="name" class="">Status:</label>

              <a href="{{ url("dashboard", \Helper::getIdfromUrl()) }}" type="button" class="btn btn-primary mr-3 mb-2 success"><i class="fa fa-check"></i> Success</a>

              <a href="{{ url("dashboard/failed", \Helper::getIdfromUrl()) }}" type="button" class="btn btn-danger mr-3 mb-2 failed"><i class="fa fa-exclamation"></i> Failed</a>

            </div>

            <div class="form-group mt-0">

              <label for="date" class="sr-only">date</label>

              <input type="text" readonly class="form-control" name="dates" id="dates" value="" placeholder="Date Range">

            </div>

            <div class="form-group mt-0">

            <button value="Search" class="btn btn-primary mr-3 mb-2 filter" type="buttton" style="display: inline;margin-left: 7px;"><i class="fa fa-search" aria-hidden="true"></i> Search</button>

            <button class="btn btn-danger mr-3 mb-2" id="reset" type="button" style="margin-left: 2px;"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>

            </div>

            <!-- <a href="error_data.php"  id="reset" class="btn btn-info mr-3 mb-2" >Failed Data</a> -->

          </form>

        </div>

        <div class="col-lg-5 text-end">
          
            <button class="btn btn-danger customerAdd" type="button" data-dashid="{{ \Helper::getIdfromUrl() }}" data-val="0"><i class="fa fa-plus" aria-hidden="true"></i> Add Customer</button>

            <button class="btn btn-danger customerAdd" type="button" data-dashid="{{ \Helper::getIdfromUrl() }}" data-val="1">Credit Customer</button>

            <a href="javascript:void(0)" class="btn btn-info mx-1 config-details" role="button" data-id="{{ \Helper::getIdfromUrl() }}"><i class="fa fa-info-circle" aria-hidden="true"></i> Config Details</a>

        </div>

      </div>

      <div class="table-responsive">

        <table class="table table-bordered table-striped dataTable" id="dataTable1" width="100%">

          <thead>

            <tr>

            <th>ID</th>

            <th>Name</th>

            <th>Contact Details</th>

            <th style="display: none">Phone</th>

            <th>Password</th>

            <th>Coupon Code</th>

            <th>Balance</th>

            <th>Mail Status</th>

            <th>Created At</th>

            <th>Action</th>

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

          <button type="button" class="close" data-dismiss="toast" aria-label="Close">

              <span aria-hidden="true">&times;</span>

          </button>

      </div>

      <div class="toast-body resend-msg-body">

          Hello, world! This is a toast message.

      </div>

  </div>

</div>

</div>
</div>

@include('modals.config-modal')

@include('modals.update-modal')

@include('modals.create-modal')

@push('script_src')

<script>

$(document).ready(function () {

  $('input[value="success"]').trigger('click');

  if($.urlParam('dates')==false){

    $("#dates").val('');

  }

  $('.success').trigger('click');

  $('.success').addClass('actives');

  var today = moment().format('YYYY-MM-DD');
  $('#dates').attr('max', today);

});

$(".config-details").click(function (e) {

  e.preventDefault();

  var id = $(this).data("id");

  $.ajax({

    type: "POST",

    url: "{{ route('getDashData') }}",

    data: {"id":id, "_token":"{{csrf_token()}}"},

    dataType: "json",

    async: false,

    success: function (response) {

        console.log(response);

        $("#modal-xl").show();

        $(".shopifystoreurl").text(response.getDashboards.shopify.storeurl);

        $(".shopifydomainname").text(response.getDashboards.shopify.shopifydomainname);

        $(".shopifyshopname").text(response.getDashboards.shopify.shopifyshopname);

        $(".username").text(response.getDashboards.smtp.email);

        $(".mailfrom").text(response.getDashboards.smtp.mailfrom);

        $(".apiendpoint").text(response.getDashboards.crm.apiendpoint);

        $(".domain").text(response.getDashboards.smtp.type);

        $(".storeurl").val(response.getDashboards.shopify.storeurl);

        $(".product").text(response.getAllowedProduct);

    },

  });  

});

$(".filter").click(function (e) {

    e.preventDefault();

    const type = $('input[name=type]:checked').val();

    var from_date = $('input[name="dates"]').data('daterangepicker').startDate.format('YYYY-MM-DD 00:00:00');

    var to_date = $('input[name="dates"]').data('daterangepicker').endDate.format('YYYY-MM-DD 23:59:59');

    if(from_date != '' && to_date != ''){

      table.destroy();

      load_data(from_date, to_date, type);

    }else{

      alert('Both Date is required');

    }

});

$("#reset").click(function(e){

    e.preventDefault()

    $("#dates").val('');

    table.destroy();

    //table.clear().draw()

    $('input[value="success"]').trigger('click');

    load_data()

})

$("body").on("click",".sendmail", function(e){

  e.preventDefault();

  var id = $(this).data("id");

  var crmId = $(this).data("crmid");

  $.ajax({

    type: "POST",

    url: "{{ route('sendEmail') }}",

    data: {

      "id": id,

      'crmId':crmId,

      "_token":"{{csrf_token()}}",

      "isForced": "1"

    },

    dataType: "json",

    beforeSend: function() {

      $("#loading").show()

    },

    success: function (response) {

      $("#loading").hide()

      $(".resend-msg-body").html('');

      $(".resend-msg-body").html(response.msg);

      $('#liveToast').toast('show');

    },

    error:function(jqXHR, exception){

      console.log(jqXHR.status);

    },

    complete: function() {

      $("#loading").hide()

    },

  });

})

var str = (window.location).href; // You can also use document.URL

var Id = str.split('?')[0].split('/').reverse()[0];

$('input[name="dates"]').daterangepicker({

        startDate: moment().subtract(1, 'M'),

        endDate: moment(),

        //maxDate:new Date()

});



load_data()

function load_data(from_date = '', to_date = '', type = '')

{

  table = $('#dataTable1').DataTable({

        processing: true,

        serverSide: true,

        responsive: false,

        destroy: true,

        scrollCollapse: true,

        scrollY: '500px',

        "bSortable": true,
        
        "autoWidth":false,

        ajax: {

          url: Id,

          data:function (d) {

                d.from_date = from_date;

                d.to_date = to_date;

                d.type = $('input[name=type]:checked').val();

          }

        },

        drawCallback: function (setting) {

          //$('#dataTable2 thead tr').remove();

        },
        columnDefs: [
            {
                "targets": [2], // Target the first column for merging
                "render": function(data, type, row) {
                    // Example: Merge 'first_name' and 'last_name' into a single column
                    return row.email_address + '<br/>' + row.phone
                }
            },
            {
                "targets": [3], // Index of the first hidden column (zero-based index)
                "visible": false, // Hide the 'first_name' column
                "searchable": true // Enable searching on the 'first_name' column
            },

        ],
        columns:[

          {data: 'id', name: 'shopify_customers.id', searchable: true, sortable : true},

          {data: 'name', name: 'shopify_customers.name', searchable: true, sortable : true},

          {data: 'email_address', name: 'shopify_customers.email_address',

                  // render: function (data, type, row, meta) {

                  //   return type === 'display' ? data.replace(/<br\s*\/?>/gi, ' ') : data;

                  // },
                  searchable: true, sortable : true
          },
          {data: 'phone', searchable: true, sortable : true, "visible": false},

          {data: 'password', name: 'shopify_customers.password', searchable: true, sortable : true},

          {data: 'coupon_code', name: 'shopify_customers.coupon_code', searchable: true, sortable : true},

          {data: 'balance', name: 'shopify_customers.balance', searchable: true, sortable : true},

          {data: 'mail_status', name: 'shopify_customers.mail_status', searchable: true, sortable : true},

          {data: 'created_at', name: 'shopify_customers.created_at'},

          {data: 'action', name: 'action', searchable: false, sortable : false}

        ],

        language: {

          processing: "Loading... <i style='font-size:20px' class='fa fa-refresh fa-spin'></i>"

        },

        lengthMenu: [[10, 20,25,50,100, -1], [10, 20,25,50,100, "All"]],

        dom: 'RlBfrtlip',

        orientation : 'landscape',

        pageSize : 'A0',

        buttons: [

          {

              extend: 'excelHtml5',

              text: '<i class="fa fa-file-excel-o"></i> Excel',

              titleAttr: 'Export to Excel',

              customize: function( xlsx ) {
                    $(xlsx.xl["styles.xml"]).find('numFmt[numFmtId="164"]').attr('formatCode', '[$$-45C] #,##0.00_-');
              },

              exportOptions: {

                columns: 'th:not(:last-child)',

              }

          },

          {

              extend: 'csvHtml5',

              text: '<i class="fa fa-file-text-o"></i> CSV',

              titleAttr: 'CSV',

              exportOptions: {

                  columns: 'th:not(:last-child)',

              }

          },

          {

              extend: 'pdfHtml5',

              text: '<i class="fa fa-file-pdf-o"></i> PDF',

              titleAttr: 'PDF',

              exportOptions: {

                  columns: 'th:not(:last-child)',

                  orientation: 'landscape',

                  pageSize: 'A0'

              },

          },

          {

              extend: 'copy',

              text: '<i class="fa fa-file-pdf-o"></i> Copy',

              titleAttr: 'Copy',

              exportOptions: {

                  columns: 'th:not(:last-child)',

              },

          },

          {

              extend: 'print',

              text: '<i class="fa fa-file-pdf-o"></i> Print',

              titleAttr: 'Print',

              exportOptions: {

                  columns: 'th:not(:last-child)',

              },

          },

        ],

        "pagingType": "full_numbers",

    });

  }



$("body").on("click",".edit-details", function(e){

  e.preventDefault();
  $(".update-fname").val('');
  $(".update_lname").val('');
  $(".update-email").val('');
  $(".update-phone").val('');
  $(".texterror").empty();
  var id = $(this).data("id");

  var dashID = $(this).data("dashid");

  $.ajax({

    type: "POST",

    url: "getdata",

    data: {

      "id": id,

      "dashID": dashID,

      "_token":"{{csrf_token()}}"

    },

    dataType: "json",
    beforeSend: function(data){
      $("#loading").show();
    },

    success: function (response) {

      $("#upd-modal").show();

      $(".update-id").val(id);

      $(".shopifyemail").val(response.email_address);

      var name = response.name;

      const myArray = name.split(" ");

      $(".dashboard").val(dashID);

      $(".update-fname").val(myArray[0]);

      $(".update-lname").val(myArray[1]);

      $(".update-email").val(response.email_address);

      $(".update-phone").val(response.phone);

      console.log(response);

    },
    complete: function(data){
        $("#loading").hide();
    }

  });

});

$.ajaxSetup({

    headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

});

$(document).on("click", ".updChanges", function(){

  $("#loading").show();
  $(".texterror").remove();

  $(".form-control").removeClass('is-invalid');

  var id = $(this).data('dashid');

  var fname = $(".update-fname").val();
  var lname = $(".update-lname").val();
  var email = $(".update-email").val();
  var phone = $(".update-phone").val();
  var regphone = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
  var err = 0;
  if(fname == ''){
    $(document).find('.update-fname').after('<span class="texterror" style="color:red">Firstname field is required.</span>')
    err = 1;
  }
  if(lname == ''){
    $(document).find('.update-lname').after('<span class="texterror" style="color:red">Lastname field is required.</span>')
    err = 1;
  }
  if(email == ''){
    $(document).find('.update-email').after('<span class="texterror" style="color:red">Email field is required.</span>')
    err = 1;
  }else if(!regphone.test(email)){
    $(document).find('.update-email').after('<span class="texterror" style="color:red">Please enter valid email.</span>')
    err = 1;
  }
  if(phone == ''){
    $(document).find('.update-phone').after('<span class="texterror" style="color:red">Phone field is required.</span>')
    err = 1;
  }
  if(err == 0){
  $.ajax({

    type: "PUT",

    async: false,

    url: "{{ route('home.update') }}",

    data: $("#update-details").serialize(),

    dataType: "json",
    // beforeSend: function(data){
    //   $("#loading").show();
    // },

    success: function (data) {
      if(data.status == 'false'){
        //$("#loading").hide();
        //$(".update-data-msg").show();
        $("#loading").hide();
        Swal.fire({

          icon: "error",

          title: "Oops...",

          text: data.error_reason

        })
        //$(".update-data-msg").text(data.error_reason);

        //$(".update-data-msg").css({'color':'red','font-weight': 'bold'});

      }else{
        //$("#loading").hide();
        //$(".update-data-msg").text('Customer Data Updated.');

        //$(".update-data-msg").css({'color':'green','font-weight': 'bold'});
        $("#loading").hide();
        setTimeout(() => {

          $("#upd-modal").hide();
          Swal.fire({
              icon: 'success',

              title: 'Success',

              text: "Customer updated successfully!",
          }).then((result) => {
            // Reload the Page
            $('#dataTable1').DataTable().ajax.reload();
          });          
        }, 500);

      }      

    },

    error: function (err) {
      $("#loading").hide();
      let error = err.responseJSON;

      $.each(error.errors, function (index, value) {

         $(`#${index}`).removeClass("is-invalid");

         $(`#${index}`).addClass("is-invalid");

         $(document).find('[name='+index+']').after('<span class="texterror" style="color:red">' +value+ '</span>')

      });

    },
    //complete: function(data){
      //  $("#loading").hide();
    //},

  });

  }else{
    $("#loading").hide();
  }

})



$(".config-details").click(function (e) { 

  e.preventDefault();

  $("#modal-xl").show();

});

$(".close").click(function(){

  $("#modal-xl").hide();

})

$(".updClose").click(function(){

  $("#upd-modal").hide();

})

$(".createClose").click(function(){

  $("#create-modal").hide();

})

$(".updClose").click(function(){

  $("#create-modal").hide();

})







$.urlParam = function (name) {

    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.search);

    return (results !== null) ? results[1] || 0 : false;

}

$(".customerAdd").click(function (e) {

  e.preventDefault();

  $("#create-modal").show();

  $(".error-msg").hide();

  $(".modal-body1").hide();

  $(".orderID").val('');

  $("#dashid").val($(this).data("dashid"));

  $("#credit").val($(this).data("val"));

  if($(this).data("val") == 1){

    $(".heading").text("Credit Customer")

  }else{

    $(".heading").text("Add Customer")

  }

});

$(document).on("click","#search",function(){

  $(".error-msg").hide();

  var orderid = $("#order_id").val();

  var creditVal = $("#credit").val();

  console.log(creditVal);

  // var crmtype = $("[name=crmtype]").val();

      if(orderid != ""){

          $("#search_results").html("");

          $("#create_results").html("");

                    $.ajax({

                        url: '{{ route("home.check") }}',

                        method: 'POST',

                        data: $('[name=search-form]').serialize(),

                        beforeSend: function(data){

                          $("#loading").show();

                        },

                        success: function (data) {
                            if(data.error_code == 350){
                              $(".error-msg").show();
                              $(".error-msg").html(data.getMessage);
                            }
                            $(".modal-body1").show();

                            $("#search_results").html("");

                            $("#search_results").html(data);

                            // $("#creatediv").hide();

                        },

                        error: function(err){
                          console.log(err)
                        },

                        complete: function(data){

                          $("#loading").hide();

                        }

                    });

          }else{

            $(".error-msg").show();

            $(".error-msg").text('Order ID is required');

            $(".error-msg").css({'color':'red','font-weight': 'bold'});

          }

      });

$(document).on("click",".create_customer",function(){

                var orderid = $(this).data('id');

                // var crmtype = $(this).data('crm');

                $(this).hide();

                $("#create_results").html("");

                if(orderid != ""){
                var coupon_val = $("#coupon_val").val();
                    $.ajax({

                        url: '{{ route("dashboard.create-account") }}',

                        method: 'post',

                        dataType: "json",

                        data: {order_id:orderid,coupon_val:coupon_val,return_type:'json',"_token":"{{csrf_token()}}"},
                        beforeSend: function(data){
                          $("#loading").show();
                        },
                        success: function (data) { 
                            if(data.error_code == 422 || data.error_code == 401){
                              $(".error-msg").show();
                              $(".error-msg").html(data.error_reason);
                              $("#search_results").html("");
                            }else{
                              $("#create-modal").hide();
                              $("#search_results").html("");
                              Swal.fire({

                              icon: 'success',

                              title: 'Success',

                              text: "Customer created successfully!",

                              }).then((result) => {
                                // Reload the Page
                                    $('#dataTable1').DataTable().ajax.reload();
                                });
                            }                            
                            //$("#create_results").html("");
                            //$("#create_results").html(data);
                            //search_results
                        },
                        error:function(err){
                          $(".error-msg").show();
                          $(".error-msg").text(err);
                          console.log(err);
                        },
                        complete: function(data){
                          $("#loading").hide();
                        }

                    });

                }

});

</script>
<script>
$(document).ready(function() {
    $('#liveToast .close').click(function() {
        $('#liveToast').toast('hide'); 
    });
});
</script>


@endpush

@endsection