<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Production Schedule</title>

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
            <h3 class="text-themecolor mb-0 text-light" style="text-transform: uppercase;">MONTHLY FACTORY OFFLINE SCHEDULE</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item text-light"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-light">FACTORY OFFLINE SCHEDULE</li>
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



                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                {!! Form::open(['action'=>'App\Http\Controllers\productiontarget\ProductiontargetController@productionschedule',
                                'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                                <div class="row mb-2">
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
                                <ul class="nav nav-tabs mb-3">
                                    <li class="nav-item">
                                        <a href="#home" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Full Schedule</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#profile" data-toggle="tab" aria-expanded="false"
                                            class="nav-link">
                                            <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Upstream Schedule</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                            <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Downstream Schedule</span>
                                        </a>
                                    </li>
                                </ul>


                                <!--FULL SCHEDULE-->
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="home">
                                        <div class="card">
                                            <div class="card-body">

                                                    {!! Form::open(['action'=>['App\Http\Controllers\productiontarget\ProductiontargetController@store'], 'method'=>'post']); !!}
                                                    {{ csrf_field(); }}
                                                    <div class="row pt-4 pb-2">
                                                        <div class="col-md-2">
                                                            <input type="hidden" name="schedule" value="entire">
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
                                                            rows="3" cols="50" name="comment">{{$comment}}</textarea>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <button id="saveunits" disabled class="btn btn-primary float-right  mb-1"><i class="mdi mdi-content-save-all font-16"></i> Save Full Schedule</button>
                                                        </div>

                                                    </div>


                                                    <div class="ContenedorTabla" style="height:330px;">
                                                        <table class="tablesaw table-bordered table-hover no-wrap" id="pruebatabla" style="font-size: 14px;" >
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
                                                                    @foreach ($routes as $item)
                                                                        <tr>
                                                                            <td class="text-white">{{$loop->iteration}}</td>
                                                                            <td class="text-white">
                                                                                @if ($item->name == 'Route 1')
                                                                                    {{"F-Series"}}
                                                                                @elseif($item->name == 'Route 2')
                                                                                    {{"N-Series"}}
                                                                                @elseif($item->name == 'Route 3')
                                                                                    {{"YAGURA"}}
                                                                                @elseif($item->name == 'Route 4')
                                                                                    {{"LCV"}}
                                                                                @elseif($item->name == 'Route 5')
                                                                                    {{"N-Series LCV"}}
                                                                                @endif
                                                                            </td>
                                                                            @for ($i = 0; $i < count($dates); $i++)
                                                                                <td style="font-size: 14px;" id="units_{{$dateid[$i]}}_{{$item->id}}" class="units" data-editable>
                                                                                    {{$noofunits[$dateid[$i]][$item->id]}}</td>
                                                                            @endfor
                                                                            <td>
                                                                            {{$unitsperroute[$item->id]}}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
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
                                    <!--END-->


                                    <!--UPSTREAM SCHEDULE-->
                                    <div class="tab-pane" id="profile">
                                        <div class="card">
                                            <div class="card-body">

                                                    {!! Form::open(['action'=>['App\Http\Controllers\productiontarget\ProductiontargetController@store'], 'method'=>'post']); !!}
                                                    {{ csrf_field(); }}
                                                    <div class="row pt-4 pb-2">
                                                        <div class="col-md-2">
                                                            <input type="hidden" name="schedule" value="up">
                                                            <input type="hidden" name="month" value="{{$first}}">
                                                            <select class="form-control select2" name="issueup" id="issueup" style="width: 100%;">
                                                                <option value="1">Issue {{$upissueno}}</option>
                                                                <option value="0">New Issue</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control float-right w-70" id="issueinpup" name="issueinput" placeholder="Issue no..."
                                                            value="Issue {{$upissueno}}" readonly required autocomplete="off">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <textarea class="form-control" required placeholder="Scheduling revision comment here..."
                                                            rows="3" cols="50" name="comment">{{$upcomment}}</textarea>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <button id="saveunitsup" disabled class="btn btn-primary float-right  mb-1"><i class="mdi mdi-content-save-all font-16"></i> Save Upstream Schedule</button>
                                                        </div>

                                                    </div>


                                                    <div class="ContenedorTabla" style="height:330px; width:100%;">
                                                        <table class="tablesaw table-bordered table-hover no-wrap" id="pruebatabla" style="font-size: 14px; width:100%;" >
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-blue">No.</th>
                                                                    <th class="text-blue">VehicleRoutes</th>
                                                                    @for ($i = 0; $i < count($dates); $i++)
                                                                        <th style="width:60px;">{{$dates[$i]}} <br> {{$wkdys[$i]}}</th>
                                                                    @endfor
                                                                    <td></td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (count($routes) > 0)
                                                                    @foreach ($routes as $item)
                                                                        <tr>
                                                                            <td class="text-blue">{{$loop->iteration}}</td>
                                                                            <td class="text-blue">
                                                                                @if ($item->name == 'Route 1')
                                                                                    {{"F-Series"}}
                                                                                @elseif($item->name == 'Route 2')
                                                                                    {{"N-Series"}}
                                                                                @elseif($item->name == 'Route 3')
                                                                                    {{"YAGURA"}}
                                                                                @elseif($item->name == 'Route 4')
                                                                                    {{"LCV"}}
                                                                                @elseif($item->name == 'Route 5')
                                                                                    {{"N-Series LCV"}}
                                                                                @endif
                                                                            </td>
                                                                            @for ($i = 0; $i < count($dates); $i++)
                                                                                <td style="font-size: 14px;" id="unitsup_{{$dateid[$i]}}_{{$item->id}}" class="unitsup" data-editableup>
                                                                                    {{$upnoofunits[$dateid[$i]][$item->id]}}</td>
                                                                            @endfor
                                                                            <td>
                                                                            {{$upunitsperroute[$item->id]}}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td></td>
                                                                            <td class="text-blue">
                                                                                Total
                                                                            </td>
                                                                            @for ($i = 0; $i < count($dates); $i++)
                                                                                <td style="font-size: 14px;">{{$upsumunits[$i]}}</td>
                                                                            @endfor
                                                                            <td>{{$upttall}}</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                            <td class="text-blue">
                                                                                Cummulative
                                                                            </td>
                                                                            @for ($i = 0; $i < count($dates); $i++)
                                                                                <td style="font-size: 14px;">{{$upcumunits[$i]}}</td>
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
                                    <!--END-->

                                    <!--DOWNSTREAM SCHEDULE-->
                                    <div class="tab-pane" id="settings">
                                        <div class="card">
                                            <div class="card-body">

                                                    {!! Form::open(['action'=>['App\Http\Controllers\productiontarget\ProductiontargetController@store'], 'method'=>'post']); !!}
                                                    {{ csrf_field(); }}
                                                    <div class="row pt-4 pb-2">
                                                        <div class="col-md-2">
                                                            <input type="hidden" name="schedule" value="down">
                                                            <input type="hidden" name="month" value="{{$first}}">
                                                            <select class="form-control select2" name="issuedown" id="issuedown" style="width: 100%;">
                                                                <option value="1">Issue {{$downissueno}}</option>
                                                                <option value="0">New Issue</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control float-right w-70" id="issueinpdown" name="issueinput" placeholder="Issue no..."
                                                            value="Issue {{$downissueno}}" readonly required autocomplete="off">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <textarea class="form-control" required placeholder="Scheduling revision comment here..."
                                                            rows="3" cols="50" name="comment">{{$downcomment}}</textarea>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <button id="saveunitsdown" disabled class="btn btn-primary float-right  mb-1"><i class="mdi mdi-content-save-all font-16"></i> Save Downstream Schedule</button>
                                                        </div>

                                                    </div>


                                                    <div class="ContenedorTabla" style="height:330px;">
                                                        <table class="tablesaw table-bordered table-hover no-wrap" id="pruebatabla"
                                                        style="font-size: 14px;" >
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-blue">No.</th>
                                                                    <th class="text-blue">VehicleRoutes</th>
                                                                    @for ($i = 0; $i < count($dates); $i++)
                                                                        <th style="width:60px;">{{$dates[$i]}} <br> {{$wkdys[$i]}}</th>
                                                                    @endfor
                                                                    <td></td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (count($routes) > 0)
                                                                    @foreach ($routes as $item)
                                                                        <tr>
                                                                            <td class="text-blue">{{$loop->iteration}}</td>
                                                                            <td class="text-blue">
                                                                                @if ($item->name == 'Route 1')
                                                                                    {{"F-Series"}}
                                                                                @elseif($item->name == 'Route 2')
                                                                                    {{"N-Series"}}
                                                                                @elseif($item->name == 'Route 3')
                                                                                    {{"YAGURA"}}
                                                                                @elseif($item->name == 'Route 4')
                                                                                    {{"LCV"}}
                                                                                @elseif($item->name == 'Route 5')
                                                                                    {{"N-Series LCV"}}
                                                                                @endif
                                                                            </td>
                                                                            @for ($i = 0; $i < count($dates); $i++)
                                                                                <td style="font-size: 14px;" id="units_{{$dateid[$i]}}_{{$item->id}}" class="unitsdown" data-editabledown>
                                                                                    {{$downnoofunits[$dateid[$i]][$item->id]}}</td>
                                                                            @endfor
                                                                            <td>
                                                                            {{$downunitsperroute[$item->id]}}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td></td>
                                                                            <td class="text-blue">
                                                                                Total
                                                                            </td>
                                                                            @for ($i = 0; $i < count($dates); $i++)
                                                                                <td style="font-size: 14px;">{{$downsumunits[$i]}}</td>
                                                                            @endfor
                                                                            <td>{{$downttall}}</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                            <td class="text-blue">
                                                                                Cummulative
                                                                            </td>
                                                                            @for ($i = 0; $i < count($dates); $i++)
                                                                                <td style="font-size: 14px;">{{$downcumunits[$i]}}</td>
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

                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div>
                <!-- end row-->

    {{ Html::style('css/ScrollTabla.css') }}

    {{ Html::script('js/jquery-1.11.0.min.js') }}
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    {{ Html::style('assets/extra-libs/prism/prism.css') }}

    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
   {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
   {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('js/jquery.CongelarFilaColumna.js') }}

    {{ Html::script('assets/extra-libs/prism/prism.js') }}
    {{ Html::script('assets/libs/popper.js/dist/umd/popper.min.js') }}
    {{ Html::script('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}
    <!-- apps -->
    <script src="../../dist/js/app.min.js"></script>

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
        $(document).ready(function(){
			$("#pruebatablaup").CongelarFilaColumna({Columnas:2,coloreacelda:true});
		});
        $(document).ready(function(){
			$("#pruebatabladown").CongelarFilaColumna({Columnas:2,coloreacelda:true});
		});
    </script>
    {!! Toastr::message() !!}


<script>
    //FULL SCHEDULE
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


//UPSTREAMSCHEDULE
$('body').on('click', '[data-editableup]', function(){

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

$saveunits = $('#saveunitsup');
$saveunits.attr('disabled', false);
};

$input.one('blur', save).focus();

});

//DOWNSTREAMSCHEDULE
$('body').on('click', '[data-editabledown]', function(){

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

$saveunits = $('#saveunitsdown');
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

    $('body').on('change', '#issueup', function(){
        var currissue = parseInt("{{$upissueno}}");
        var issueno = $(this).val();
        if(issueno == 1){
            $('#issueinpup').val("Issue "+currissue);
        }else{
            var newissue = currissue + 1;
            $('#issueinpup').val("Issue "+newissue);
        }

    });

    $('body').on('change', '#issuedown', function(){
        var currissue = parseInt("{{$downissueno}}");
        var issueno = $(this).val();
        if(issueno == 1){
            $('#issueinpdown').val("Issue "+currissue);
        }else{
            var newissue = currissue + 1;
            $('#issueinpdown').val("Issue "+newissue);
        }

    });

</script>

