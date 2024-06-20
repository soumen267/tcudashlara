<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        @if (auth()->user())
        @if (Auth::user()->role == 'superadmin')
        <a class="navbar-brand" href="{{ route('dashboard.index') }}" style="text-decoration:none">
            Dashboard
        </a>
        @else
        <a class="navbar-brand" href="{{ url('/home') }}" style="text-decoration:none">
            Dashboard
        </a>
        @endif
        @endif
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @if (Auth::user())
            @if (Auth::user()->role == 'superadmin')
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="padding: 10px;">
                @if (Request::segment(1) === 'dashboard')
                    <li class="nav-item" style="margin-right:5px;"><a href="{{route('users.index')}}" class="nav-link bg-primary text-white mr-2" style="border-radius: 40%;">USER</a></li>
                    <li class="nav-item" style="margin-right:5px;"><a href="{{route('crm.index')}}" class="nav-link bg-secondary text-white btn-lg mr-2" style="border-radius: 40%;">CRM</a></li>
                    <li class="nav-item" style="margin-right:5px;"><a href="{{route('smtp.index')}}" class="nav-link bg-success text-white" style="border-radius: 40%;">SMTP</a></li>
                    <li class="nav-item" style="margin-right:5px;"><a href="{{route('shopify.index')}}" class="nav-link bg-danger text-white" style="border-radius: 40%;"><i class="fa fa-shopify"></i>SHOPIFY</a></li>
                    <li class="nav-item" style="margin-right:5px;"><a href="{{route('dashboard.index')}}" class="nav-link bg-info text-white" style="border-radius: 40%;">DASH</a></li>
                @endif
            </ul>
            @endif
            @endif
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    {{-- @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif --}}
                @else
                    @if(auth()->user()->role)
                    <li class="nav-item dropdown">
                        @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                            @if (isset($getDashboard))
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Dashboard
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="padding: 10px;background: cadetblue;">
                                @foreach ($getDashboard as $row)
                                <a class="dropdown-item {{ $row['id'] == \Helper::getIdfromUrl() ? 'selected' : '' }} dashhover" href="{{ url('dashboard',$row['id']) }}">
                                    {{ $row['dashname'] }}
                                </a>
                                @endforeach
                            </div>
                            @endif
                        @endif
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endif
                @endguest
            </ul>
        </div>
    </div>
</nav>