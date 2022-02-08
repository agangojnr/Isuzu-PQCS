<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Performance</title>

    @include('layouts.header.header')
    @yield('after-styles')

    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles" style="background: #da251c;">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-white mb-0">PLANT BUFFER STATUS REPORT</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-white">PLANT BUFFER STATUS</li>
            </ol>

        </div>
        <div class="col-md-7">
            <div class="row float-left w-100">
                <div class="col-lg-7">
                    <span  class="btn waves-effect waves-light btn-lg"
                    style="background-color: #DAF7A6; opacity: 0.9; font-familiy:Times New Roman;">

                    <h5 class="float-right mt-2">{{\Carbon\Carbon::today()->format('j M Y')}}</h5></span>
                </div>
                <div class="col-5">
                    <a href="/home" id="btn-add-contact" class="btn btn-primary float-right"
                ><i class="mdi mdi-arrow-left font-16"></i> Back to Home</a>
                </div>
            </div>

        </div>
    </div>
    <div class="row">

    </div>
    <!-- End Row -->

    <div class="container-fluid">

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <ol class="breadcrumb mb-2  bg-grey">
                                <li class="breadcrumb-item">
                                    <h3 class="card-title"><u>PLANT BUFFER STATUS REPORT  FOR<br><span style="text-transform: uppercase;"></span>
                                        ({{\Carbon\Carbon::createFromFormat('Y-m-d', $first)->format('M Y')}})</u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-7">
                            {!! Form::open(['action'=>'App\Http\Controllers\productiontarget\ProductiontargetController@bufferstatus',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">

                                <div class="col-4">
                                    <h4 class="card-title">Choose Month:</h4>
                                    <div class='input-group'>
                                        <input type="text" name="mdate" id="datepicker" class="form-control bg-white" readonly
                                        value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $first)->format('F Y')}}" autocomplete="off" />

                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="ti-calendar"></span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-success mt-4">Filter Hours</button>
                            </div>

                            </div>
                            {{Form::hidden('_method', 'GET')}}
                            {!! Form::close() !!}

                        </div>
                    </div>


                    <div class="ContenedorTabla1">
                        <table data-toggle="table" data-height="600" data-mobile-responsive="true"
                                    class="table-striped">
                            <thead>
                            <tr style="background-color:rgb(252, 248, 8);">
                                <th class="text-primary" rowspan="2">SECTION</th>
                                <th class="text-primary" rowspan="2">DM</th>
                                <th class="text-primary">Analysis</th>
                                @for ($i = 0; $i < count($dayname); $i++)
                                    <th>{{$dayname[$i]}}</th>
                                @endfor
                            </tr>
                            <tr style="background-color:rgb(252, 248, 8);">
                                <th></th>
                                @for ($i = 0; $i < count($dayname); $i++)
                                    <th>{{$dates[$i]}}</th>

                                @endfor
                            </tr>
                        </thead>

                            <!--Table body-->
                            <tbody style="color: black; font-weight:bold;">


                                <!-- TRIM LINE DAILY BUFFER MONITORING  -->
                                <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="4">TRIM LINE DAILY BUFFER MONITORING </td>
                                        <td class="text-primary" rowspan="4" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Max Buffer</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{"12"}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Min Buffer</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{"8"}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$trimbuffers[$i]}}</td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($trimvarience[$i] > 0 && $trimvarience[$i] != "")
                                        <td class="bg-danger">{{$trimvarience[$i] * (-1)}}</td>
                                        @elseif($trimvarience[$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$trimvarience[$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$trimvarience[$i] * (-1)}}</td>
                                        @endif

                                        @endfor
                                    </tr>


                                <!-- PAINTSHOP DAILY BUFFER MONITORING  -->
                                <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="4">PAINTSHOP DAILY BUFFER MONITORING </td>
                                        <td class="text-primary" rowspan="4" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Max Buffer</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{"27"}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Min Buffer</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{"9"}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$paintbuffers[$i]}}</td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($paintvarience[$i] > 0 && $paintvarience[$i] != "")
                                        <td class="bg-danger">{{$paintvarience[$i] * (-1)}}</td>
                                        @elseif($paintvarience[$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$paintvarience[$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$paintvarience[$i] * (-1)}}</td>
                                        @endif

                                        @endfor
                                    </tr>

                                <!-- RIVETING DAILY BUFFER MONITORING  -->
                                <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="4">RIVETING DAILY BUFFER MONITORING </td>
                                        <td class="text-primary" rowspan="4" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Max Buffer</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{"4"}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Min Buffer</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{"2"}}</td>
                                        @endfor
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$rivbuffers[$i]}}</td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($rivvarience[$i] > 0 && $rivvarience[$i] != "")
                                        <td class="bg-danger">{{$rivvarience[$i] * (-1)}}</td>
                                        @elseif($rivvarience[$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$rivvarience[$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$rivvarience[$i] * (-1)}}</td>
                                        @endif

                                        @endfor
                                    </tr>

                            </tbody>

                            <!--Table footer-->
                            <tfoot style="background-color:rgb(252, 248, 8);">
                                <th>SHOP</th>
                                <th></th>
                                <th></th>
                                @for ($i = 0; $i < count($dayname); $i++)
                                    <td>{{$dates[$i]}}</td>
                                @endfor

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        .table-bordered{
            border: 1px solid black;
        }
    </style>
{{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
{{ Html::style('assets/libs/bootstrap-table/dist/bootstrap-table.min.css') }}
{{ Html::style('css/ScrollTabla.css') }}

    {{ Html::script('js/jquery-1.11.0.min.js') }}
    {{ Html::script('assets/libs/moment/moment.js') }}
     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
     {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
     {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('js/jquery.CongelarFilaColumna.js') }}

    {{ Html::script('assets/libs/bootstrap-table/dist/bootstrap-table.min.js') }}

    <script type="text/javascript">
        $(".select2").select2();
    </script>
    <script>
        $(function(){
        var today = new Date();
        $("#datepicker").datepicker({
            showDropdowns: true,
            format: "MM yyyy",
            viewMode: "years",
            minViewMode: "months",
            maxDate: today,
            });
        });

        $(document).ready(function(){
			$("#pruebatabla").CongelarFilaColumna({Columnas:3,coloreacelda:true});
		});
    </script>
    {!! Toastr::message() !!}

