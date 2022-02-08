<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FCW Schedule</title>

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
            <h3 class="text-themecolor mb-0 text-light" style="text-transform: uppercase;">MONTHLY FACTORY FCW SCHEDULE</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item text-light"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-light">FACTORY FCW SCHEDULE</li>
            </ol>
        </div>
        <div class="col-lg-3">
            <span  class="btn waves-effect waves-light btn-lg"
            style="background-color: #DAF7A6; opacity: 0.9; font-familiy:Times New Roman;">

            <h5 class="float-right mt-2">{{\Carbon\Carbon::today()->format('j M Y')}}</h5></span>
        </div>


            <div class="col-md-4 col-12 align-self-center d-none d-md-block">
                <div class="d-flex mt-2 justify-content-end">

                    <div class="d-flex ml-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="/home" id="btn-add-contact" class="btn btn-info"><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
                    </div>
                    </div>
                </div>
            </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="content-header row pb-1">
            <div class="content-header-left col-md-6 col-12 mb-2">


            </div>
            <div class="content-header-right col-md-6 col-12">
                <div class="media width-250 float-right">

                    <div class="media-body media-right text-right">
                        @include('productionschedule.partial.schedule-buttons')
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        <!-- Individual column searching (select inputs) -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                        {!! Form::open(['action'=>'App\Http\Controllers\productiontarget\ProductiontargetController@fcwschedule',
                        'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                        <div class="row">
                            <div class="col-4">
                                <h4 class="card-title">Filter Schedule</h4>
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
                        <div class="col-2">
                            <button type="submit" class="btn btn-success mt-4">Apply Filter</button>
                        </div>
                        <div class="col-6">

                        </div>

                        </div>
                        {{Form::hidden('_method', 'GET')}}
                        {!! Form::close() !!}



                    {!! Form::open(['action'=>['App\Http\Controllers\productiontarget\ProductiontargetController@storefcwschedule'], 'method'=>'post']); !!}
                    {{ csrf_field(); }}
                    <div class="row pt-4 pb-2">
                        <div class="col-md-2">
                            <input type="hidden" name="month" value="{{$first}}">
                            <select class="form-control select2" name="issue" id="issue">
                                <option value="1">Issue {{$issueno}}</option>
                                <option value="0">New Issue</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control float-right w-70" id="issueinp" name="issueinput" placeholder="Issue no..."
                            value="Issue {{$issueno}}" readonly required autocomplete="off">
                        </div>
                    <div class="col-md-6">
                        <textarea class="form-control" required placeholder="Scheduling revision comment here..."
                        rows="2" cols="50" name="comment">{{$comment}}</textarea>
                    </div>


                    <div class="col-md-2">
                        <button id="saveunits" disabled class="btn btn-primary float-right  mb-1"><i class="mdi mdi-content-save-all font-16"></i> Save Schedule</button>
                    </div>

                </div>

                    <div class="ContenedorTabla" style="height:400px;">
                        <table class="tablesaw table-bordered table-hover no-wrap" id="pruebatabla" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="text-white">No.</th>
                                    <th class="text-white">VehicleRoutes</th>
                                    @for ($i = 0; $i < count($dates); $i++)
                                        <th style="width:60px;">{{$dates[$i]}} <br> {{$wkdys[$i]}}</th>
                                    @endfor
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($routes) > 0)
                                    @for ($n = 0; $n < count($routes); $n++)
                                        <tr>
                                            <td class="text-white">{{$x++}}</td>
                                            <td class="text-white">
                                                    {{$routes[$n]}}                                                
                                            </td>
                                            @for ($i = 0; $i < count($dates); $i++)
                                                <td style="font-size: 14px;" id="units_{{$dateid[$i]}}_{{$n}}" class="units" data-editable>
                                                    {{$noofunits[$dateid[$i]][$n]}}</td>
                                            @endfor
                                            <td>{{$unitsperroute[$n]}}</td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <td></td>
                                            <td class="text-white">
                                                Total
                                            </td>
                                            @for ($i = 0; $i < count($dates); $i++)
                                                <td style="font-size: 14px;">{{$sumunits[$i]}}</td>
                                            @endfor
                                            <td>{{$ttall}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                            <td class="text-white">
                                                Cummulative
                                            </td>
                                            @for ($i = 0; $i < count($dates); $i++)
                                                <td style="font-size: 14px;">{{$cumunits[$i]}}</td>
                                            @endfor
                                            <td></td>
                                    </tr>
                                @endif

                            </tbody>

                        </table>
                    </div>

                {!! Form::close(); !!}
                </div>
            </div>
        </div>
    </div>
    {{ Html::style('css/ScrollTabla.css') }}

    {{ Html::script('js/jquery-1.11.0.min.js') }}
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
   {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
   {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('js/jquery.CongelarFilaColumna.js') }}

    <script type="text/javascript">
        $(".select2").select2();
    </script>
    <script type="text/javascript">
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
			$("#pruebatabla").CongelarFilaColumna({Columnas:2,coloreacelda:true});
		});
    </script>
    {!! Toastr::message() !!}


<script>
    $('body').on('click', '[data-editable]', function(){

    var $el = $(this);
    //console.log($el.text());

    var $input = $('<input type="text" class="form-control" style="font-size: 14px; width: 70px;"/>').val( $el.text() );
    $el.html( $input );
    var $id_arr = $el.attr('id');

    var $id = $id_arr.split('_');

    var $dateid = $id[1];
    var $routeid = $id[2];


    var save = function(){
        var $p =  $input.val();
    var $units = $('<input type="hidden" name="unitsedited[]" value="'+$p+'"/>');
    var $dates = $('<input type="hidden" name="dateid[]" value="'+$dateid+'"/>');
    var $routes = $('<input type="hidden" name="routeid[]" value="'+$routeid+'"/>');

    $el.html( $p );
    $el.append( $units );
    $el.append( $dates );
    $el.append( $routes );

    $saveunits = $('#saveunits');
    $saveunits.attr('disabled', false);
    };

    $input.one('blur', save).focus();

    });

    $('body').on('change', '#issue', function(){
        var currissue = parseInt("{{$issueno}}");
        var issueno = $(this).val();
        if(issueno == 1){
            $('#issueinp').val("Issue "+currissue);
        }else{
            var newissue = currissue + 1;
            $('#issueinp').val("Issue "+newissue);
        }

    });

</script>

