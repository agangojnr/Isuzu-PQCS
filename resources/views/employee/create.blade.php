@extends('layouts.app')
@section('title','Add Employee')
@section('content')
<div class="card m-5 p-3">
    <h2 class="card-title">ADD EMPLOYEE TO STAFF LIST</h2>
    <div>
        <a href="/employee" id="btn-add-contact" class="btn btn-danger float-right"
    style="background-color:#da251c; "><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
    </div>
    <hr>
            {!! Form::open(['action'=>'App\Http\Controllers\employee\EmployeeController@store',
             'method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']); !!}
                <div class="card-body">
                    @include('employee.form')
                </div>
            {!! Form::close() !!}

</div>
@endsection
@section('after-styles')
    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
@endsection

@section('after-scripts')
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}

<script type="text/javascript">
    $(".select2").select2();
</script>
@endsection

