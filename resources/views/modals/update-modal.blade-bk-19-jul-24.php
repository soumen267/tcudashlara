<!--Model-->
<div class="modal fade show" id="upd-modal" style="display: none; padding-right: 17px;" aria-modal="true" role="dialog">
  <form name="update_details" action="{{ route('home.update') }}" method="POST" id="update-details">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="update_id" class="update-id">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Details</h4>
          <button type="button" class="close updClose" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <p class="text-center w-100 update-data-msg" style="display:none;" >Customer Data Updated.</p>
        <div class="modal-body">
          <div class="container-fluid">
            <input type="hidden" class="form-control dashboard" name="dashboard">
            <div class="form-group mb-3">
              <label class="col-12 col-form-label">First Name:</label>
              <div class="col-md-12">
                <input type="text" class="form-control update-fname" name="update_fname" required>
              </div>
            </div>
            <div class="form-group mb-3">
              <label class="col-12 col-form-label">Last Name:</label>
              <div class="col-md-12">
                <input type="text" class="form-control update-lname" name="update_lname" required>
              </div>
            </div>
            <div class="form-group mb-3">
              <label class="col-12 col-form-label">Email:</label>
              <div class="col-md-12">
                <input type="text" class="form-control update-email" name="update_email" required>
              </div>
            </div>
            <div class="form-group mb-3">
              <label class="col-12 col-form-label">Phone Number:</label>
              <div class="col-md-12">
                <input type="text" class="form-control update-phone" name="update_phone" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default updClose" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary updChanges">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </form>
</div>
<!--EndModel-->