<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/home') }}" style="text-decoration:none">
            Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @if (Auth::user()->name == 'superadmin')
            <ul class="navbar-nav me-auto">
                @if (Request::segment(1) === 'home')
                    <div class="btn-group">
                        <li class="nav-item d-none d-sm-inline-block"><a href="{{route('crm.index')}}" class="nav-link bg-primary text-white mr-2">CRM</a></li>
                        <li class="nav-item d-none d-sm-inline-block"><a href="{{route('smtp.index')}}" class="nav-link bg-secondary text-white">SMTP</a></li>
                        <li class="nav-item d-none d-sm-inline-block"><a href="{{route('shopify.index')}}" class="nav-link bg-danger text-white">SHOPIFY</a></li>
                    </div>
                @endif                
            </ul>
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

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        @if (Auth::user()->name == 'superadmin' || Auth::user()->name == 'admin')
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
                @endguest
            </ul>
        </div>
    </div>
</nav>