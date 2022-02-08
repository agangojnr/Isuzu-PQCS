@extends('layouts.app')

@section('content')
<div class="card m-5 p-3">
    <h2 class="card-title">Edit Job Description</h2>
    <hr>

            {!! Form::open(['action'=>['App\Http\Controllers\employeecategory\EmployeeCategoryController@update', $cats->id], 'method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']); !!}
                <div class="card-body">
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Category Code</label>
                        <div class="col-sm-9">
                            {{Form::text('code', $cats->category_code, ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Division Code here'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Category Name</label>
                        <div class="col-sm-9">
                            {{Form::text('description', $cats->emp_category, ['class'=>'form-control', 'id'=>'description', 'placeholder'=>'Division Name here'])}}
                        </div>
                    </div>

                <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-info waves-effect waves-light" id="close-button1">Save</button>
                        <button type="reset" class="btn btn-dark waves-effect waves-light">Cancel</button>
                    </div>
                </div>
                </div>
                {{Form::hidden('_method', 'PUT')}}
            {!! Form::close() !!}

</div>
@endsection
