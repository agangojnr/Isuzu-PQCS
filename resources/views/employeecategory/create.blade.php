@extends('layouts.app')

@section('title','Create Employee Category')

@section('content')
<div class="card m-5 p-3">
    <h2 class="card-title">Create Employee Category</h2>
            <div>
                <a href="/employeecategory" id="btn-add-contact" class="btn btn-danger float-right"
            style="background-color:#da251c; "><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
            </div>


    <hr>

            {!! Form::open(['action'=>'App\Http\Controllers\employeecategory\EmployeeCategoryController@store', 'method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']); !!}
                <div class="card-body">
                    @include('employeecategory.form')
                </div>
            {!! Form::close() !!}

</div>
@endsection

