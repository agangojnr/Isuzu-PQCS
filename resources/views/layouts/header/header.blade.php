<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png')}}">
    <title>@yield('title')</title>

       <script type="text/javascript">
        var baseurl = '/';
        var crsf_token = 'csrf-token';
        var crsf_hash = '{{ csrf_token() }}';
        window.Laravel = {!! json_encode([ 'csrfToken' => csrf_token() ]) !!};
       
    </script>

 <link rel="canonical" href="https://www.wrappixel.com/templates/xtremeadmin/" />
    <link href="{{asset('assets/libs/chartist/dist/chartist.min.css')}}"  rel="stylesheet">
    <link href="{{asset('dist/js/pages/chartist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/c3/c3.min.css') }}"  rel="stylesheet">
    <!-- Custom CSS -->
     <link href="{{asset('dist/css/style.min.css')}}"  rel="stylesheet">
     <link href="{{asset('assets/libs/tablesaw/dist/tablesaw.css')}} " rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('dist/css/style.css ')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <style>
        td.details-control {
            background: url("{{asset('dist/js/pages/datatable/details_open.png')}}") no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url("{{asset('dist/js/pages/datatable/details_close.png')}}") no-repeat center center;
        }
    </style>


      <!-- people -->
   
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.date.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.time.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/daterangepicker/daterangepicker.css')}}">
 

    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.date.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.time.css')}}">
    <!--Modal-->

 </head>
