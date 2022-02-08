
@extends('layouts.app')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Import List of Staff</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">STAFF IMPORT</li>
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
                        <div class="d-flex float-right mb-3">
                            <div class="text-right d-flex justify-content-md-end justify-content-center  mt-md-0">
                                <a href="/employee" id="btn-add-contact" class="btn btn-info">
                                    <i class="mdi mdi-arrow-left font-16 mr-1"></i>Back to STAFF LIST</a>
                            </div>
                        </div>
                        <div class="card-block"><h4>IMPORT STAFF LIST </h4>
                            <br>
                            <hr>
                            <!--<p class="alert alert-light mb-3">Your import data file should as exactly per this template.  <a
                                href="{{route('empsample',[$template.'.csv'])}}"
                                target="_blank"><strong>Download Template </strong>
                                (Import Employee)</a>. </p>-->
                                {{ Form::open(['route' => 'exportstafftoexcel', 'method' => 'GET'])}}
                                {{ csrf_field(); }}

                                <p class="alert alert-light mb-3">Your import data file should as exactly per this template. <button style="background-color:teal; color:white;"
                                        class="btn btn-sm" ><i class="glyphicon glyphicon-edit"></i>Download Excel template</button></strong>
                                        </a>. </p>

                                {!! Form::close(); !!}
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
                    <div class="col-12">
                        <div class="card">


                        <div class="card-body">
                            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group col-md-12">
                                    <label>File : csv, xls or xlsx</label>
                                     <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="icon"  >
                                        <label class="custom-file-label" for="coin">Choose file</label>
                                    </div>
                                </div>
                                </div>
                                <br>
                                <button class="btn btn-success">Import Staff Data</button>
                            </form>
                        </div>

                </div>

                </div>
                <div>
                   @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <strong>Validation Errors:</strong>
                        <ul>
                            @foreach($errors->all() as $errorMessages)
                                    <li>
                                        {{ $errorMessages }}
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    </li>

                            @endforeach
                        </ul>
                    </div>@endif
                </div>
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

