@extends('layouts.app')
@section('content')
    <div class="container">
      @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if (session('error'))
        <div class="alert alert-success message-box alert-dismissible">
          {{ session('error') }}
          <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
        <div class="row mt-3">
            <div class="col-6 col-md-6">
              <ol class="breadcrumb float-sm-right">
                {{-- <li class="breadcrumb-item"><a href="{{ route('home') }}">Back</a></li> --}}
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div>
          <div class="col-6 col-md-6 text-end">
            <a class="btn btn-primary" href="{{ route('dashboards.create') }}">CREATE</a>
          </div>
          </div>
          <div class="card-body">
            <div class="responsiveTable">
              <table class="table">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>CRM</th>
                    <th>SMTP</th>
                    <th>SHOPIFY</th>
                    <!-- <th>Status</th> -->
                    <th colspan="2">Last Updated</th>
                  </tr>
                </thead>
                <tbody>
                  @if($getDashData)
                  @forelse ($getDashData as $key => $row)
                  <tr>
                    <td>{{ ++$key }}.</td>
                    <td>{{ $row['dashname'] }}</td>
                    <td>{{ $row->crm['providerlabel'] }}</td>
                    <td>{{ $row->shopify['storeurl'] }}</td>
                    <td>{{ $row->smtp['name'] }}</td>
                    <!-- <td>
                      @if ($row['status'] == 1)
                      <span class="text-secondary"><strong>Active</strong></span>
                      @elseif($row['status'] == 0)
                      <span class="text-secondary"><strong>Deactive</strong></span>
                      @endif
                    </td> -->
                    <td>{{ $row['updated_at'] }}</td>
                    <td>
                      <div id="container">
                      <div id="menu-wrap">
                          <input type="checkbox" class="toggler" />
                          <div class="dots">
                            <div></div>
                          </div>
                          <div class="menu">
                            <div>
                              <ul class="menu-items">
                                <!-- <li><a href="#" class="link" data-id="{{ $row['id'] }}">View</a></li> -->
                                <li><a href="{{ route('dashboards.edit', $row['id']) }}" class="link" data-id="{{ $row['id'] }}">Edit</a></li>
                                {{-- @if ($row['status'] == 1)
                                <li><a href="{{ route('crm.status', [$row['id'], 0]) }}" class="link" data-id="{{ $row['id'] }}">Deactive</a></li>  
                                @elseif($row['status'] == 0)
                                <li><a href="{{ route('crm.status', [$row['id'], 1]) }}" class="link" data-id="{{ $row['id'] }}">Active</a></li>
                                @endif
                                <li>
                                  <form method="POST" action="{{ route('crm.destroy', $row['id']) }}">
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="submit" class="delete" title='Delete' style="border:none;background:none">Delete</button>
                                  </form>
                                </li> --}}
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
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
    </div>
    @push('script_src')
    <script type="text/javascript">
      $(document).ready(function() {
          $('.delete').click(function(e) {
              if(!confirm('Are you sure you want to delete this post?')) {
                  e.preventDefault();
              }
          });
    setTimeout(function() {
        $('.message-box').alert('close');
       }, 5000);  
    });
    </script>
    @endpush
@endsection