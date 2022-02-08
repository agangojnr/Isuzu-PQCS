
@extends('layouts.app')
@section('title','Set Hours')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">SET DEFAULT HOURS</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">DEFAULT HOURS FOR ATTENDANCE</li>
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
                    <div class="card-block"><h4>CURRENT DEFAULT HOURS ON ATTENDANCE</h4>

                        <hr>
                        <div class="table-responsive">
                                        <table class="table">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Direct Hours</th>
                                                    <th>Indirect Hours</th>
                                                    <th>Overtime Hours</th>
                                                    <th>Hours Limit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>#</td>
                                                    <td>{{$direct}}</td>
                                                    <td>{{$indirect}}</td>
                                                    <td>{{$overtime}}</td>
                                                    <td>{{$hrslimit}}</td>
                                                </tr>

                                            </tbody>
                                        </table>

                        </div>
                    </div>
                </div>

            </div>

            <div class="card">
                    <div class="card-body">
                        <div class="card-block"><h4>DEFAULT HOURS ON ATTENDANCE</h4>

                            <hr>
                            <form action="{{ route('setdefaulthrs') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group col-md-12">
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 text-left control-label col-form-label">Direct Hours.:</label>
                                        <div class="col-sm-9">
                                            {{Form::text('direct', $direct, ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Direct Hours here',
                                            'autocomplete'=>'off', 'required'=>'required'])}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 text-left control-label col-form-label">Indirect Hours.:</label>
                                        <div class="col-sm-9">
                                            {{Form::text('indirect', $indirect, ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Indirect Hours here',
                                            'autocomplete'=>'off', 'required'=>'required'])}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 text-left control-label col-form-label">Overtime Hours.:</label>
                                        <div class="col-sm-9">
                                            {{Form::text('overtime', $overtime, ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Overtime Limit here',
                                            'autocomplete'=>'off', 'required'=>'required'])}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-2 text-left control-label col-form-label">Attendance Hours Limit.:</label>
                                        <div class="col-sm-9">
                                            {{Form::text('hrslimit', $hrslimit, ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Hours Limit here',
                                            'autocomplete'=>'off', 'required'=>'required'])}}
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button class="btn btn-success">Save Default Hours</button>
                            </form>

                        </div>
                        </div>

                    </div>




                    <!--<form action="{{ route('import_Employees') }}" method="POST" enctype="multipart/form-data">

                        {{Form::hidden('_method', 'get')}}
                        @csrf
                        <div class="form-group mt-4" style="max-width: 500px; margin: 0 auto;">
                            <div class="custom-file text-left">
                                <input type="file" name="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <button class="btn btn-primary float-right" type="submit">Save File</button>
                        </div>

                    </form>-->

            </div>





        {!! Toastr::message() !!}

        @endsection

        @section('after-styles')
            {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

        @endsection

        @section('after-scripts')
        {{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
        {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
        {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}

