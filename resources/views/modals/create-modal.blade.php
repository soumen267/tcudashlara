<!--Model-->
<div class="modal fade show" id="create-modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
  <form name="search-form" action="{{ route('home.check') }}" method="POST" id="create-details">
    @csrf
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title heading">Create Customer</h4>
          <button type="button" class="close updClose" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid my-4">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Order Id</label>
              <div class="col-sm-9">
                <input type="hidden" class="form-control" id="dashid" name="dashid">
                <input type="hidden" class="form-control" id="credit" name="credit" value="">
                <input type="text" class="form-control orderID" id="order_id" name="order_id" required placeholder="Order Id">
                <span class="error-msg" style="display: none"></span>
              </div>
            </div>
            <div class="col-12 my-4 text-center">
              <table class="table table-hover table-bordered modal-body1">
                  <tbody id="search_results">
                      
                  </tbody>
                  <tbody id="create_results">
                      
                  </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between" id="creatediv">
          <button type="button" class="btn btn-default createClose" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="search">Search</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </form>
</div>
<!--EndModel-->