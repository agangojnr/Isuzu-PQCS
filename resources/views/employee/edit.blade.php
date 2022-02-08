@extends('layouts.app')

@section('content')
<div class="card m-5 p-3">
    <h2 class="card-title">Edit Employee Details</h2>
    <div>
        <a href="/employee" id="btn-add-contact" class="btn btn-danger float-right"
    style="background-color:#da251c; "><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
    </div>
    <hr>

            {!! Form::open(['action'=>['App\Http\Controllers\employee\EmployeeController@update', $staffs->id],
            'method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']); !!}
                <div class="card-body">

                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Staff No.:</label>
                        <div class="col-sm-9">
                            {{Form::text('staffno', $staffs->staff_no, ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Job Code here', 'autofill'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Staff Name.:</label>
                        <div class="col-sm-9">
                            {{Form::text('staffname', $staffs->staff_name, ['class'=>'form-control', 'id'=>'description', 'placeholder'=>'Staff Name here', 'autofill'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Department Description.:</label>
                        <div class="col-sm-9">
                            {{Form::text('description', $staffs->Department_Description, ['class'=>'form-control', 'id'=>'description', 'placeholder'=>'Department description here', 'autofill'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Category.:</label>
                        <div class="col-sm-9">
                            {{Form::text('category', $staffs->Category, ['class'=>'form-control', 'id'=>'description', 'placeholder'=>'Staff Name here', 'autofill'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Shop.:</label>
                        <div class="col-sm-9">
                            <select name="shop" class="form-control select2" style="width: 100%;"  required>
                                <option value="{{$staffs->shop_id}}">{{$staffs->shop->report_name}}</option>
                                @foreach ($shops as $item)
                                    <option value="{{$item->id}}">{{$item->report_name}}</option>
                                @endforeach
                            </select>
                         </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Is Staff Team Leader.:</label>
                        <div class="col-sm-9">
                            <input name="teamleader" value="yes" type="radio" @if($staffs->team_leader == 'yes') checked @endif class="with-gap material-inputs radio-col-blue" id="radio_3" required='required' />
                            <label for="radio_3">Yes</label>

                            <input name="teamleader" value="no" type="radio" @if($staffs->team_leader == 'no') checked @endif  id="radio_4" class="with-gap material-inputs radio-col-pink" required='required' />
                            <label for="radio_4">No</label>
                        </div>

                    </div>

                    <!--<div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Gender.:</label>
                        <div class="col-sm-9">
                            <input name="gender" value="Male" type="radio" @if($staffs->gender == 'Male') checked @endif class="with-gap material-inputs radio-col-green" id="radio_5" required='required' />
                            <label for="radio_5">Male</label>

                            <input name="gender" value="Female" type="radio" @if($staffs->gender == 'Female') checked @endif  id="radio_6" class="with-gap material-inputs radio-col-yellow" required='required' />
                            <label for="radio_6">Female</label>
                        </div>

                    </div>-->

                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Staff Status.:</label>
                        <div class="col-sm-9">
                            <input name="status" value="Active" type="radio" @if($staffs->status == 'Active') checked @endif class="with-gap material-inputs radio-col-indigo" id="radio1" />
                            <label for="radio1">Active</label>

                            <input name="status" value="Inactive" type="radio" @if($staffs->status == 'Inactive') checked @endif  id="radio2" class="with-gap material-inputs radio-col-red" />
                            <label for="radio2">Inactive</label>
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
{!! Toastr::message() !!}

    @endsection

    @section('after-styles')
        {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
        {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}

    @endsection

    @section('after-scripts')
    {{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
