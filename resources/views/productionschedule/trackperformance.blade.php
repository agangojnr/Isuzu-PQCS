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
            <h3 class="text-white mb-0">PLANT PERFORMANCE TRACKING</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-white">REPORTS</li>
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
                                    <h3 class="card-title"><u>PLANT PERFORMANCE TRACKING REPORT  FOR<br><span style="text-transform: uppercase;"></span>
                                        ({{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('M Y')}})</u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-7">
                            {!! Form::open(['action'=>'App\Http\Controllers\productiontarget\ProductiontargetController@trackperformance',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">

                                <div class="col-4">
                                    <h4 class="card-title">Choose Month:</h4>
                                    <div class='input-group'>
                                        <input type="text" name="mdate" id="datepicker" class="form-control bg-white" readonly
                                        value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('F Y')}}" autocomplete="off" />

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
                                <th></th>
                            </tr>
                            <tr style="background-color:rgb(252, 248, 8);">
                                <th></th>
                                @for ($i = 0; $i < count($dayname); $i++)
                                    <th>{{$dates[$i]}}</th>
                                @endfor
                                <th></th>
                            </tr>
                        </thead>

                            <!--Table body-->
                            <tbody style="color: black; font-weight:bold;">
                                @foreach ($shopnames as $shop)
                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="6">{{$shop->report_name}}</td>
                                        <td class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{$countsched[$shop->id][$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Focast</td>
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$actual[$shop->id][$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Actual</td>
                                    </tr>
                                    <tr>

                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($varience[$shop->id][$i] > 0 && $varience[$shop->id][$i] != "")
                                        <td class="bg-danger">{{$varience[$shop->id][$i] * (-1)}}</td>
                                        @elseif($varience[$shop->id][$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$varience[$shop->id][$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$varience[$shop->id][$i] * (-1)}}</td>
                                        @endif

                                        @endfor
                                        <td class="text-primary">Daily Variance</td>
                                    </tr>

                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <td  class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            MTD</td>
                                        <td class="text-primary">Forecast</td>


                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td>{{$allMTDfcst[$shop->id][$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>
                                            @if ($allMTDactual[$shop->id][$i] != "")
                                                {{$allMTDactual[$shop->id][$i] + $allVarie[$shop->id][$i]}}
                                            @else
                                                {{$allMTDactual[$shop->id][$i]}}
                                            @endif
                                        </td>
                                        @endfor
                                        <td class="text-primary">MTD Actual</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            @if($allVarie[$shop->id][$i] == '')
                                                <td style="background-color:rgb(214, 246, 246);">{{$allVarie[$shop->id][$i]}}</td>
                                            @elseif($allVarie[$shop->id][$i] > 0)
                                                <td class="bg-danger">{{$allVarie[$shop->id][$i]}}</td>
                                            @else
                                                <td class="bg-success">{{$allVarie[$shop->id][$i]}}</td>
                                            @endif
                                        @endfor
                                        <td class="text-primary">MTD Variance</td>
                                    </tr>
                                @endforeach

                                <!-- MPA OFFLINE -->
                                <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="6">Plant Offline - MPA</td>
                                        <td class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{$countmpasched[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$mpaactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Actual</td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($mpavarience[$i] > 0 && $mpavarience[$i] != "")
                                        <td class="bg-danger">{{$mpavarience[$i] * (-1)}}</td>
                                        @elseif($mpavarience[$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$mpavarience[$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$mpavarience[$i] * (-1)}}</td>
                                        @endif

                                        @endfor
                                        <td class="text-primary">Daily Vaiance</td>
                                    </tr>

                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <td  class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            MTD</td>
                                        <td class="text-primary">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td>{{$shMTDmpafcst[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$shMTDmpaactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Actual</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            @if($MTDmpaVarie[$i] == '')
                                                <td style="background-color:rgb(214, 246, 246);">{{$MTDmpaVarie[$i]}}</td>
                                            @elseif($MTDmpaVarie[$i] > 0)
                                                <td class="bg-danger">{{$MTDmpaVarie[$i] * (-1)}}</td>
                                            @else
                                                <td class="bg-success">{{$MTDmpaVarie[$i] * (-1)}}</td>
                                            @endif
                                        @endfor
                                        <td class="text-primary">MTD Variance</td>
                                    </tr>


                                <!-- MPC CV DELIVERY -->
                                <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="6">FCW DELIVERY - CV</td>
                                        <td class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{$countcvsched[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$lcvactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Actual</td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($cvvarience[$i] > 0 && $cvvarience[$i] != "")
                                        <td class="bg-danger">{{$cvvarience[$i] * (-1)}}</td>
                                        @elseif($cvvarience[$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$cvvarience[$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$cvvarience[$i] * (-1)}}</td>
                                        @endif
                                        @endfor
                                        <td class="text-primary">Daily Vaiance</td>
                                    </tr>

                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <td  class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            MTD</td>
                                        <td class="text-primary">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td>{{$shMTDcvfcst[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$shMTDcvactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Actual</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            @if($MTDcvVarie[$i] == '')
                                                <td style="background-color:rgb(214, 246, 246);">{{$MTDcvVarie[$i]}}</td>
                                            @elseif($MTDcvVarie[$i] > 0)
                                                <td class="bg-danger">{{$MTDcvVarie[$i] * (-1)}}</td>
                                            @else
                                                <td class="bg-success">{{$MTDcvVarie[$i] * (-1)}}</td>
                                            @endif
                                        @endfor
                                        <td class="text-primary">MTD Variance</td>
                                    </tr>

                                <!-- MPC LCV DELIVERY -->
                                <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="6">FCW DELIVERY - LCV</td>
                                        <td class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{$countlcvsched[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$lcvactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Actual</td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($lcvvarience[$i] > 0 && $lcvvarience[$i] != "")
                                        <td class="bg-danger">{{$lcvvarience[$i] * (-1)}}</td>
                                        @elseif($lcvvarience[$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$lcvvarience[$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$lcvvarience[$i] * (-1)}}</td>
                                        @endif
                                        @endfor
                                        <td class="text-primary">Daily Vaiance</td>
                                    </tr>

                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <td  class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            MTD</td>
                                        <td class="text-primary">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td>{{$shMTDlcvfcst[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Actual</td>
                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$shMTDlcvactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Actual</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            @if($MTDlcvVarie[$i] == '')
                                                <td style="background-color:rgb(214, 246, 246);">{{$MTDlcvVarie[$i]}}</td>
                                            @elseif($MTDlcvVarie[$i] > 0)
                                                <td class="bg-danger">{{$MTDlcvVarie[$i] * (-1)}}</td>
                                            @else
                                                <td class="bg-success">{{$MTDlcvVarie[$i] * (-1)}}</td>
                                            @endif
                                        @endfor
                                        <td class="text-primary">MTD Variance</td>
                                    </tr>

                                <!-- MPC FCW DELIVERY -->
                                <tr style="background-color:rgb(199, 228, 204);">
                                        <td class="text-primary" rowspan="6">Plant FCW Delivery - MPC</td>
                                        <td class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            Daily</td>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td style="background-color:rgb(211, 217, 236); width: 40px;">
                                                {{$countmpcsched[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(211, 217, 236);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$mpcactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">Daily Actual</td>
                                    </tr>
                                    <tr>
                                        <td class="text-primary" style="background-color:rgb(211, 217, 236);">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        @if($mpcvarience[$i] > 0 && $mpcvarience[$i] != "")
                                        <td class="bg-danger">{{$mpcvarience[$i] * (-1)}}</td>
                                        @elseif($mpcvarience[$i] == '')
                                        <td style="background-color:rgb(211, 217, 236)">{{$mpcvarience[$i]}}</td>
                                        @else
                                        <td class="bg-success">{{$mpcvarience[$i] * (-1)}}</td>
                                        @endif
                                        @endfor
                                        <td class="text-primary">Daily Vaiance</td>
                                    </tr>

                                    <tr style="background-color:rgb(199, 228, 204);">
                                        <td  class="text-primary" rowspan="3" style="writing-mode: vertical-rl;text-orientation: mixed;">
                                            MTD</td>
                                        <td class="text-primary">Forecast</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            <td>{{$shMTDmpcfcst[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Forecast</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Actual</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                        <td>{{$shMTDmpcactual[$i]}}</td>
                                        @endfor
                                        <td class="text-primary">MTD Actual</td>
                                    </tr>
                                    <tr style="background-color:rgb(214, 246, 246);">
                                        <td class="text-primary">Variance</td>

                                        @for ($i = 0; $i < count($dayname); $i++)
                                            @if($MTDmpcVarie[$i] == '')
                                                <td style="background-color:rgb(214, 246, 246);">{{$MTDmpcVarie[$i]}}</td>
                                            @elseif($MTDmpcVarie[$i] > 0)
                                                <td class="bg-danger">{{$MTDmpcVarie[$i] * (-1)}}</td>
                                            @else
                                                <td class="bg-success">{{$MTDmpcVarie[$i] * (-1)}}</td>
                                            @endif
                                        @endfor
                                        <td class="text-primary">MTD Variance</td>
                                    </tr>
                            </tbody>


                            <!--Table footer-->
                            <tfoot style="background-color:rgb(252, 248, 8);">
                                <th>SHOP</th>
                                <th></th>
                                <th></th>

                                @for ($i = 0; $i < count($dayname); $i++)
                                    <th>{{$dayname[$i]}}</th>
                                @endfor
                                <th></th>

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

