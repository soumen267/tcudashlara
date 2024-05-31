@extends('layouts.main.app')
@section('content')
<div class="container">
  <h3 class="text-center mt-3 mb-5">List Of Customer</h3>
      <div class="row">
        <div class="col-lg-8 mb-3">
          <form class="d-inline-flex searchFrm" action="" method="get">
            {{-- <div class="form-group mr-3 mb-2">
              <label for="name" class="sr-only">name</label>
              <input type="text" class="form-control" name="str" value="" placeholder="Keyword">
            </div> --}}
            <div class="form-group" style="margin: 6px;">
              <label for="name" class="">Status:</label>
              <input type="radio" name="type" value="success"> Success
              <input type="radio" name="type" value="failed"> Failed
            </div>
            <div class="form-group mr-3 mb-2">
              <label for="date" class="sr-only">date</label>
              <input type="text" class="form-control" name="dates" id="dates" value="" placeholder="Date Range">
            </div>
            <button value="Search" class="btn btn-primary mr-3 mb-2 filter" type="buttton" style="display: inline;margin-left: 7px;">Search</button>
      
            <button class="btn btn-danger mr-3 mb-2" id="reset" type="button" style="margin-left: 2px;">Reset</button>
      
            <!-- <a href="error_data.php"  id="reset" class="btn btn-info mr-3 mb-2" >Failed Data</a> -->
      
      
          </form>
        </div>
        <div class="col-lg-4">
            @php
            $mylink = $_SERVER['PHP_SELF'];
            $link_array = explode('/',$mylink);
            $lastpart = end($link_array);
            @endphp
            <button class="btn btn-danger customerAdd" type="button" data-dashid="{{ $lastpart }}" data-val="0">Add Customer</button>
            <button class="btn btn-danger customerAdd" type="button" data-dashid="{{ $lastpart }}" data-val="1">Credit Customer</button>
            <a href="javascript:void(0)" class="btn btn-info mx-2" role="button">Config Details</a>
        </div>
      </div>
  <div class="table-responsive">
    <table class="table table-bordered table-striped dataTable" id="dataTable" width="100%">
      <thead>
        
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
@include('modals.update-modal')
@include('modals.create-modal')
@push('script_src')
<script>
$(document).ready(function () {
  $('input[value="success"]').trigger('click');
  if($.urlParam('dates')==false){
    $("#dates").val('');
  }
});
$("body").on("click",".sendmail", function(e){
  e.preventDefault();
  var id = $(this).data("id");
  $.ajax({
    type: "POST",
    url: "{{ route('sendEmail') }}",
    data: {
      "id": id,
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
    complete: function() {
      $("#loading").hide()
    },
  });
})
var str = (window.location).href; // You can also use document.URL
var Id = str.split('?')[0].split('/').reverse()[0];
$('input[name="dates"]').daterangepicker({
        startDate: moment().subtract(1, 'M'),
        endDate: moment()
});

load_data()
var table;
function load_data(from_date = '', to_date = '', type = '')
{
  var columns;
  if(type == 'success' || type == ''){
  columns =  [
          {data: 'id', name: 'id', searchable: true, sortable : true},
          {data: 'name', name: 'shopify_customers.name', searchable: true, sortable : true},
          {data: 'email_address', name: 'shopify_customers.email_address', searchable: true, sortable : true,
                    render: function (data, type, row, meta) {
                      return row.email_address + '<br/>' + row.phone
                  }},
          {data: 'password', name: 'shopify_customers.password', searchable: true, sortable : true},
          {data: 'coupon_code', name: 'shopify_customers.coupon_code', searchable: true, sortable : true},
          {data: 'balance', name: 'shopify_customers.balance', searchable: false, sortable : false},
          {data: 'mail_status', name: 'action'},
          {data: 'created_at', name: 'created_at'},
          {data: 'action', name: 'action', searchable: false, sortable : false}
        ];
  }else if(type == 'failed'){
    columns =  [
      {data: 'id', name: 'id', searchable: true, sortable : true},
      {data: 'order_id', name: 'order_id', searchable: true, sortable : true},
      {data: 'email', name: 'email', searchable: true, sortable : true},
      {data: 'error_msg', name: 'error_msg', searchable: true, sortable : true},
      {data: 'created_at', name: 'created_at'}
    ];
  }
  table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "bDestroy": true,
        "bSortable": true,
        ajax: {
          url: Id,
          data:function (d) {
                d.from_date = from_date;
                d.to_date = to_date;
                d.type = $('input[name=type]:checked').val();
          }
        },
        drawCallback: function (data) {
          $('#dataTable thead tr').remove();
          // $("#header").remove();
          // if(data.aoData.length > 0) {
            // this.api().columns().every(function() {
            //     var column = this;
            //     var header = $(this.columns());
            //     header.addClass('sortable');

            //     header.off('click').on('click', function() {
            //     var order = column.order();
            //     if (order[0] === 'asc') {
            //       column.order('desc').draw();
            //     } else {
            //       column.order('asc').draw();
            //     }
            //   });
            // })
            let parseData = data.json;
            var columns = parseData.columns.map(function(column) {
              return { title: column};
            });
            $('#dataTable thead').append('<tr role="row" id="header">' + columns.map(function(col) {
                          return "<th>" + col.title + "</th>";
            }).join('') + '</tr>');
            // $('#dataTable thead tr th').append(columns.map(function(col) {
            //               return col.title;
            // }).join('') + '</tr>');
            // $('#dataTable thead tr:eq(0) th').append(columns.map(function(col) {
            //               return col.title;
            // }).join(''));
            // var myArray = [];
            // $('#dataTable thead tr:eq(0) th').each(function(index,item) {
            //     columns.map(function(col) {
            //       myArray.push(col.title);
            //     });
            //   console.log(myArray);
            // })
            // $('#dataTable tfoot').append('<tr role="row">' + columns.map(function(col) {
            //               return "<th>" + col.title + "</th>";
            // }).join('') + '</tr>');
          // }
        },
        columns:columns,
        error: function(xhr, error) {
          console.log(xhr);
          console.log(error);
          alert("An error occurred while fetching data.");
        },
        language: {
          processing: "Loading... <i style='font-size:20px' class='fa fa-refresh fa-spin'></i>"
        },
        order: [[3, 'asc']],
        lengthMenu: [[10, 20,25,50,100, -1], [10, 20,25,50,100, "All"]],
        dom: 'RlBfrtlip',
        orientation : 'landscape',
        pageSize : 'A0',
        buttons: [
          'copy', 'csv', 'excel', 'pdf'
        ],
        "pagingType": "full_numbers"
    });
  }
  $(".filter").click(function (e) {
    e.preventDefault();
    const type = $('input[name=type]:checked').val();
    var from_date = $('input[name="dates"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var to_date = $('input[name="dates"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
    if(from_date != '' &&  to_date != ''){
      table.destroy();
      $('#dataTable tbody').empty()
      $('#dataTable thead').empty()
      // $('#dataTable').DataTable().destroy();
      load_data(from_date, to_date, type);
    }else{
      alert('Both Date is required');
    }
  });
  $("#reset").click(function(e){
    e.preventDefault()
    $("#dates").val('');
    table.destroy();
    $('#dataTable tbody').empty()
    $('#dataTable thead').empty()
    $('input[value="success"]').trigger('click');
    // $('#order_table').DataTable().destroy();
    load_data()
  })

$("body").on("click",".edit-details", function(e){
  e.preventDefault();
  var id = $(this).data("id");
  $.ajax({
    type: "POST",
    url: "getdata",
    data: {
      "id": id,
      "_token":"{{csrf_token()}}"
    },
    dataType: "json",
    success: function (response) {
      $("#upd-modal").show();
      $(".update-id").val(id);
      var name = response.name;
      const myArray = name.split(" ");
      $(".update-fname").val(myArray[0]);
      $(".update-lname").val(myArray[1]);
      $(".update-email").val(response.email_address);
      $(".update-phone").val(response.phone);
      console.log(response);
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
  $.ajax({
    type: "PUT",
    async: false,
    url: "{{ route('home.update') }}",
    data: $("#update-details").serialize(),
    dataType: "json",
    success: function (data) {
      console.log(data);
      if(data.status == 'false'){
        $(".update-data-msg").show();
        $(".update-data-msg").text(data.error_reason);
        $(".update-data-msg").css({'color':'red','font-weight': 'bold'});
      }else{
        $(".update-data-msg").text('Customer Data Updated.');
        $(".update-data-msg").css({'color':'green','font-weight': 'bold'});
        setTimeout(() => {
          $("#upd-modal").hide();
          $('#dataTable').DataTable().ajax.reload();
          $("#loading").hide();
          Swal.fire({
              icon: 'success',
              title: 'Success',
              text: "Customer updated successfully!",
          })
        }, 500);
      }      
    },
    error: function (err) {
      let error = err.responseJSON;
      $.each(error.errors, function (index, value) {
         console.log(index);
         $(`#${index}`).removeClass("is-invalid");
         $(`#${index}`).addClass("is-invalid");
         $(document).find('[name='+index+']').after('<span class="texterror" style="color:red">' +value+ '</span>')
      });
    },
  });
})

$(".mx-2").click(function (e) { 
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


$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.search);
    return (results !== null) ? results[1] || 0 : false;
}
$(".customerAdd").click(function (e) { 
  e.preventDefault();
  $("#create-modal").show();
  $("#dashid").val($(this).data("dashid"));
  $("#credit").val($(this).data("val"));
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
                        success: function (data) { 
                            $("#search_results").html("");
                            $("#search_results").html(data);
                            // $("#creatediv").hide();
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
                    $.ajax({
                        url: '{{ route("dashboard.create-account") }}',
                        method: 'post',
                        data: {order_id:orderid,return_type:'html',"_token":"{{csrf_token()}}"},
                        success: function (data) { 
                            console.log(data);
                            $("#create_results").html("");
                            $("#create_results").html(data);
                            $('#dataTable').DataTable().ajax.reload();
                        }
                    });
                }
      });
</script>
@endpush
@endsection