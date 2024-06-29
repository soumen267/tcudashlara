<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dashboard') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset("assets/login/images/icons/favicon.ico")}}"/>
    {{-- <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.2/css/dataTables.responsive.css"/>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .dropdown-item a:hover {background-color: #ddd;}
        .dataTables_wrapper .dataTables_processing {
          z-index: 99999;
          background: white;
          border: 1px solid black;
          border-radius: 3px;
        }
        .modal-header .close, .modal-header .mailbox-attachment-close {
            padding: 1rem;
            margin: -1rem -1rem -1rem auto;
            border: none;
            background: none;
        }
        #loading {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 100;
            width: 100vw;
            height: 100vh;
            background-color: rgba(192, 192, 192, 0.5);
            background-image: url("http://www.jqueryscript.net/demo/Simple-Customizable-jQuery-Loader-Plugin-Center-Loader/img/loader1.gif");
            background-repeat: no-repeat;
            background-position: center;
        }
        /* .selected{
            color: white;
        } */
        .dashhover{
            background: lightsteelblue;border-radius: 17px;font-weight:bold;margin-bottom:5px!important;
        }
        .dashhover:hover{
            -webkit-box-shadow: -1px 8px 14px 4px rgba(0,0,0,0.58);
            -moz-box-shadow: -1px 8px 14px 4px rgba(0,0,0,0.58);
            box-shadow: -1px 8px 14px 4px rgba(0,0,0,0.58);
        }
        .selected{
            color: white;
            background: lightsteelblue;border-radius: 17px;font-weight:bold;margin-bottom:5px!important;
        }
        .buttons-copy{
            background-color: #04AA6D!important;
            color: #ffffff!important;
        }
        .buttons-csv{
            background-color: #008CBA!important;
            color: #ffffff!important;
        }
        .buttons-excel{
            background-color: #f44336!important;
            color: #ffffff!important;
        }
        .buttons-pdf{
            background-color: #e7e7e7!important;
            color: #000000!important;
        }
        .buttons-print{
            background-color: #555555!important;
            color: #ffffff!important;
        }
        /* .actives{
            background-color:#000000;
            color:#ddd;
        } */
        .main-footer{
            margin-left: 0px; position:absolute;
            bottom:0;
            width:100%;
            height:60px; text-align:center;background: #00305a;
                color: #fff;
                padding: 15px 10px;
                font-size: 14px;
                margin-top: auto;
        }
        .nav-link{
            display:inline!important;
            padding: 10px!important;
        }
        .dataTable th {
            background: lightblue;
            padding: 3px;
        }
        .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -25px;
            position: relative;
            z-index: 2;
        }
        </style>
    @stack('style_src')
</head>
<body>
<div id="app">
        <div id="loading" style="display: none; z-index:1056"></div>
        <div class="wrapper">
            @if (Auth::user())
            @if (Auth::user()->role === 'superadmin')
                @include('layouts.partials.header')
                {{-- @include('layouts.partials.sidebar') --}}
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('layouts.partials.footer')
            </div>
            @else
                @include('layouts.partials.header')
                <div class="content-wrapper" style="margin-left:0px;">
                    @yield('content')
                </div>
                @include('layouts.partials.footer')
            </div>
            @endif
            @else
            @include('layouts.partials.header')
                {{-- @include('layouts.partials.sidebar') --}}
                <div class="content-wrapper">
                    @yield('content')
                </div>
            @include('layouts.partials.footer')
            @endif            
        </div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/1.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @stack('script_src')
</body>
</html>