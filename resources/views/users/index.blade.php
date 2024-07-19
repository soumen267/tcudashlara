@extends('layouts.app')
@section('content')
<div class="container">
@if (session('success'))
  <div class="alert alert-success message-box alert-dismissible">
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
      <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Back</a></li>
      <li class="breadcrumb-item active">User</li>
    </ol>
  </div>
<div class="col-6 col-md-6 text-end">
  <a class="btn btn-primary" href="{{ route('users.create') }}">CREATE</a>
</div>
</div>
<div class="card-body">
  <div class="responsiveTable">
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Password</th>
          <th>Role</th>
          <th colspan="2">Created At</th>
        </tr>
      </thead>
      <tbody>
        @if($getUsers)
        @forelse ($getUsers as $key => $row)
        <tr>
          <td>{{ ++$key }}.</td>
          <td>{{ $row['name'] }}</td>
          <td>{{ $row['email'] }}</td>
          <td>***********************</td>
          <td>{{ $row['role'] }}</td>
          {{-- <td>
            @if ($row['status'] == 1)
            <span class="text-secondary"><strong>Active</strong></span>
            @elseif($row['status'] == 0)
            <span class="text-secondary"><strong>Deactive</strong></span>
            @endif
          </td> --}}
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
                    <ul>
                      {{-- <li><a href="#" class="link" data-id="{{ $row['id'] }}">View</a></li> --}}
                      <li><a href="{{ route('users.edit', $row['id']) }}" class="link" data-id="{{ $row['id'] }}">Edit</a></li>
                      {{--@if ($row['status'] == 1)
                      <li><a href="{{ route('users.status', [$row['id'], 0]) }}" class="link" data-id="{{ $row['id'] }}">Deactive</a></li>  
                      @elseif($row['status'] == 0)
                      <li><a href="{{ route('users.status', [$row['id'], 1]) }}" class="link" data-id="{{ $row['id'] }}">Active</a></li>
                      @endif --}}
                      <li>
                        <form method="POST" action="{{ route('users.destroy', $row['id']) }}">
                          @csrf
                          <input name="_method" type="hidden" value="DELETE">
                          <button type="submit" class="delete" title='Delete' style="border:none;background:none">Delete</button>
                        </form>
                      </li>
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