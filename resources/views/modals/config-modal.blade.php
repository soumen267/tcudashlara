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
                      <td class="shopifystoreurl">{{ $getDashboards->shopify['storeurl'] ?? ''}}</td>
                  </tr>
                  <tr>
                      <td>Shopify API Version</td>
                      <td>2014-10</td>
                  </tr>
                  <tr>
                      <td>Shopify Domain Name</td>
                      <td class="shopifydomainname">{{ $getDashboards->shopify['shopifydomainname'] ?? '' }}</td>
                  </tr>
                  <tr>
                      <td>Shopify Store Name</td>
                      <td class="shopifyshopname">{{ $getDashboards->shopify['shopifyshopname'] ?? ''}}</td>
                  </tr>
                  <tr>
                      <td>From Email ID</td>
                      <td class="mailfrom">{{ $getDashboards->smtp['mailfrom'] ?? ''}}</td>
                  </tr>
                  <tr>
                      <td>From Email Name</td>
                      <td class="username">{{ $getDashboards->smtp['username'] ?? ''}}</td>
                  </tr>
                  <tr>
                      <td>Product ID Allowed For Coupon (Sticky)</td>
                      <td class="product">{{ $getAllowedProduct ?? ''}}</td>
                  </tr>
                  
                  <tr>
                      <td>CRM Sticky Endpoint</td>
                      <td class="apiendpoint">{{ $getDashboards->crm['apiendpoint'] ?? ''}}</td>
                  </tr>
                  <tr>
                      <td>SMTP Type</td>
                      <td class="domain">{{ $getDashboards->smtp['domain'] ?? ''}}</td>
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