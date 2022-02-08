@extends('layouts.app')

@section('content')
<div class="card m-5 p-3">
    <h2 class="card-title">Create Job Description</h2>
    <hr>


            {!! Form::open(['action'=>['App\Http\Controllers\stafftitle\StaffTitleController@store'], 'method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']); !!}
                @csrf;
            <div class="card-body">
                    @include('stafftitle.form')
                </div>

            {!! Form::close() !!}


</div>
@endsection

