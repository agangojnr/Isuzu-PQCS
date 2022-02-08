

@extends('layouts.app')
@section('title','Authorised Hours')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">AUTHORISED OVERTIME HOURS</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">OVERTIME HOURS</li>
            </ol>
        </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">

        <div class="col-sm-8 col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="card-block">
                        <form action="{{ route('saveauthhrs') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h4>UPDATE AUTHORISED OVERTIME HOURS</h4>
                            <hr>
                            <div class="form-group col-md-12">
                            <div class="form-group row">

                                <div class="col-sm-3">
                                    <label for="description" class="text-left control-label col-form-label">Date Range:</label>
                                    <div class='input-group'>
                                        <input type='text' name="daterange" class="form-control shawCalRanges" />

                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="ti-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="description" class="text-left control-label col-form-label">Authorised Weekdays Hrs.:</label>
                                    {{Form::text('weekday', '', ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Weekday Hrs here...',
                                    'autocomplete'=>'off', 'required'=>'required'])}}
                                </div>
                                <div class="col-sm-3">
                                    <label for="description" class="text-left control-label col-form-label">Weekend & Holidays Hrs.:</label>
                                    {{Form::text('weekend', '', ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Weekends & Holoday Hrs...',
                                    'autocomplete'=>'off', 'required'=>'required'])}}
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-success mt-5" id="savetarget">Save Hours</button>
                                </div>
                            </div>
                            </div>

                    </form>

                    <hr>
                    <div class="card-block"><h4>CURRENT AUTHORISED OVERTIME HOURS</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th></th>
                                        <th>DATE RANGE</th>
                                        <th>WEEKDAY OVERTIME HRS</th>
                                        <th>WEEKENDS & HOLIDAY HRS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($plantauthhrs))
                                        @foreach ($plantauthhrs as $pl)
                                        <tr>
                                            <td>{{'#'}}</td>
                                            <th>
                                                {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pl->datefrom)->format('jS M Y').' - '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pl->dateto)->format('jS M Y')}}
                                            </th>
                                            <th>{{$pl->weekdayhrs}}</th>
                                            <th>{{$pl->wknd_hdayhrs}}</th>

                                        </tr>
                                        @endforeach
                                    @endif


                                </tbody>
                            </table>
                        </div>
                        <div class="card-block"><h4>AUTHORISED OVERTIME HOURS LIST</h4>
                        <hr>
                        <div class="table-responsive">
                                        <table class="table">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>DATE RANGE</th>
                                                    <th>WEEKDAY OVERTIME HRS</th>
                                                    <th>WEEKENDS & HOLIDAY HRS</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($authhrs))
                                                @foreach ($authhrs as $hrs)
                                                    <tr>
                                                        <td>
                                                            {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $hrs->datefrom)->format('jS M Y').' - '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $hrs->dateto)->format('jS M Y')}}
                                                        </td>
                                                        <td>{{$hrs->weekdayhrs}}</td>
                                                        <td>{{$hrs->wknd_hdayhrs}}</td>
                                                        <td>
                                                            <a href="{{route('destroyauthhrs', $hrs->id)}}"
                                                                class="btn btn-outline-danger btn-sm pull-right delauthhrs"><i
                                                                class="fa fa-trash"></i></a>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                        </div>
                    </div>
                </div>

            </div>

            </div>

        {!! Toastr::message() !!}

        @endsection

        @section('after-styles')
            {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
            {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}

        @endsection

        @section('after-scripts')
        {{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
        {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
        {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
        {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
        {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}

        {{ Html::script('assets/libs/daterangepicker/daterangepicker.js') }}

        <script type="text/javascript">
            $(function(){
        'use strict'
        $('.shawCalRanges').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
                alwaysShowCalendars: true,
            });
        });
        </script>

        <script>
//ACTIVATE USER

$(document).on('click', '.delauthhrs', function(e){

e.preventDefault();
        var txt = "You will delete the Authorised hours from the system!";
        var btntxt = "Yes, Delete!";
        var color = "#DD6B55";

        Swal.fire({
            title: "Are you sure?",
            text: txt,
            type: "warning",
          //buttons: true,
        showCancelButton: true,
        confirmButtonColor: color,
        confirmButtonText: btntxt,
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false

          //dangerMode: true,
        }).then((result) => {
            if (Object.values(result) == 'true') {
                var href = $(this).attr('href');
                $.ajax({
                    method: "post",
                    url: href,
                    dataType: "json",
                      data:{

                     '_token': '{{ csrf_token() }}',
                           },
                    success: function(result){
                        if(result.success == true){
                            toastr.success(result.msg);
                            //routingquery.ajax.reload();
                            window.location = "authorisedhrs";
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
    });
        </script>


