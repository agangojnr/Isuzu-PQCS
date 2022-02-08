
@extends('layouts.app')
@section('title','Edit System User')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">System Users</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>THIS MONTH</small></h6>
                        <h4 class="mt-0 text-info">40</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="monthchart"></div>
                    </div>
                </div>
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>LAST MONTH</small></h6>
                        <h4 class="mt-0 text-primary">50</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="lastmonthchart"></div>
                    </div>
                </div>
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
  <div class="content-header row pb-1">
                <div class="content-header-left col-md-6 col-12 mb-2">


                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('systemusers.partial.systemusers-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">

                     {!! Form::open(['action'=>['App\Http\Controllers\systemusers\SystemUsersController@update', $user->id],
                     'method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']); !!}

                <div class="card-body">
                     <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label">Employee Name</label>
                        <div class="col-sm-9">
                             {{ Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => 'Employee Name','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pname" class="col-sm-3 text-right control-label col-form-label">Phone Number</label>
                        <div class="col-sm-9">
                            {{ Form::text('phone_no', $user->phone_no, ['class' => 'form-control', 'placeholder' => 'Phone Number','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date" class="col-sm-3 text-right control-label col-form-label">Email</label>
                        <div class="col-sm-9">
                            {{ Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Email Address','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>

                    {{ Form::hidden('username', 'username') }}


                    @if ($user->superAdmin != 1)

                    <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label">User Role:</label>
                        <div class="col-sm-9">
                            {!! Form::select('role_id', $roles,$userRole, ['placeholder' => 'Select Role',
                            'class' => 'form-control custom-select select2']); !!}


                        </div>
                    </div>
                    @else

                    {{ Form::hidden('role_id', 'superadmin') }}
                    @endif


            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Save', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}


                        {{ link_to_route('systemusers.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light']) }}
                    </div>
                </div>

                </div>
                {{Form::hidden('_method', 'PUT')}}
         {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
@endsection

@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}



<script type="text/javascript">
     /*$(document).on('submit', 'form#create-user', function(e){
            e.preventDefault();
            $("#submit-data").hide();
            var data = $(this).serialize();

            $.ajax({
                method: "post",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success:function(result){ alert("Yess");
                    if(result.success == true){
                        //$('div.account_model').modal('hide');
                        toastr.success(result.msg);
                       // capital_account_table.ajax.reload();
                        //other_account_table.ajax.reload();

                        location.href = '{{ route("systemusers.index") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });*/
</script>
@endsection

@section('after-scripts')

  <!-- This Page JS -->

 {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
        {!! Toastr::message() !!}


<script type="text/javascript">
    $(".select2").select2();

</script>
