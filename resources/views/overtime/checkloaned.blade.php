
@extends('layouts.app')
@section('title','Overtime')
@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0" style="color: #da251c;">{{$shopname}}</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Authorize Overtime Hours</li>
            </ol>
        </div>


            <div class="col-md-7 col-12 align-self-center d-none d-md-block">
                <div class="d-flex mt-2 justify-content-end">
                    <a href="/attendancepreview" class="btn btn-info"><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
                    <!--<div class="d-flex ml-2">
                        {{ Form::open(['route' => 'checkattendance', 'method' => 'GET'])}}
                            @csrf
                            <input type="hidden" name="shop" value="{{$shopid}}">
                            <input type="hidden" name="mdate" value="{{\Carbon\Carbon::createFromFormat('d M Y', $date)->format('Y-m-d');}}">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <button id="btn-add-contact" class="btn btn-info"><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</button>
                        </div>
                        {{ Form::close() }}
                    </div>-->
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


        <div class="row">
    <!-- Individual column searching (select inputs) -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title text-primary"><span style="text-transform: uppercase;"><u>
                                STAFF ON OVERTIME LOANED TO {{$shopname}} ON {{$date}}</span></u>
                            </h4>

                        </div>
                        <div class="col-4">
                                {!! Form::open(['action'=>'App\Http\Controllers\overtime\OvertimeController@approveloaned', 'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                                <button type="submit" {{$disabled}} class="btn btn-{{$color}} mb-3 float-right">
                                    <i class="mdi mdi-{{$icon}} font-16 mr-1"></i>{{$text}}</button>
                                @foreach ($lonees as $item)
                                    <input type="hidden" name="overtimeid[]" value="{{$item->id}}">
                                @endforeach
                                {{Form::hidden('_method', 'GET')}}
                                {!! Form::close() !!}

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs1">
                            <thead>
                                <tr>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Shop</th>
                                    <th>Hours Worked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($lonees) > 0)
                                    @foreach ($lonees as $item)
                                    <tr>
                                        <td>{{$item->employee->staff_no}}</td>
                                        <td>{{$item->employee->staff_name}}</td>
                                        <td>{{$item->shop->report_name}}</td>
                                        <td>{{$item->otloaned_hrs}} Hrs</td>
                                    </tr>
                                    @endforeach
                                @endif

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Shop</th>
                                    <th>Hours Worked</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {!! Toastr::message() !!}
    @endsection

    @section('after-styles')
        {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
         {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
         {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    @endsection

    @section('after-scripts')
    {{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
     {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}



<script>



</script>
