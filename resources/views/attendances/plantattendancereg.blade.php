<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>

    @include('layouts.header.header')
    @yield('after-styles')

    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
   <div class="row page-titles" style="background-color:#da251c;">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-white mb-0">MONTHLY ATTENDANCE HOURS REPORT PER SHOP</h3>
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
    <!-- End Row -->

    <div class="container-fluid">

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <ol class="breadcrumb mb-2  bg-grey">
                                <li class="breadcrumb-item">
                                    <h3 class="card-title"><u>PLANT MONTHLY ATTENDANCE REGISTER
                                        ({{\Carbon\Carbon::createFromFormat('Y-m-d', $today)->format('M Y')}})</u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-6">
                            {!! Form::open(['action'=>'App\Http\Controllers\attendance\AttendanceController@plantattendancereg',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">
                                <div class="col-7">
                                    <h4 class="card-title">Filter HC by Date</h4>
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
                            <div class="col-5">
                                <button type="submit" class="btn btn-success mt-4">Filter Hours</button>
                            </div>

                            </div>
                            {{Form::hidden('_method', 'GET')}}
                            {!! Form::close() !!}

                        </div>
                    </div>



                    <div class="table-responsive">
                        <table class="tablesaw table-bordered table-hover table no-wrap" data-tablesaw-mode="swipe"
                                    data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap
                                    data-tablesaw-mode-switch>
                            <thead>
                                <th>SHOP</th>
                                @for ($i = 0; $i < $count; $i++)
                                    <th>{{$dates[$i]}}</th>
                                @endfor
                                <th>SHOP</th>
                            </thead>
                            <tbody>
                                @foreach ($shops as $shop)
                                <tr>
                                    <td>{{$shop->report_name}}</td>
                                    @for ($i = 0; $i < $count; $i++)
                                        <td>@if($marked[$shop->id][$i] == 1)
                                            <span class="text-success">&#10003;</span>

                                            @else
                                            <span class="text-danger"><i>&#x2715;</i></span>
                                            @endif</td>
                                    @endfor
                                    <td>{{$shop->report_name}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th>SHOP</th>
                                @for ($i = 0; $i < $count; $i++)
                                    <th>{{$dates[$i]}}</th>
                                @endfor
                                <th>SHOP</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer.script')
    @yield('after-scripts')
    @yield('extra-scripts')
    {{ Html::script('dist/js/pages/datatable/datatable-basic.init.js') }}


     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}

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
    </script>
    {!! Toastr::message() !!}

