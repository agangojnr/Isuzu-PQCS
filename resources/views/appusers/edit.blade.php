
@extends('layouts.app')
@section('title','Add App User')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">App Users</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Add User</li>
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
                            @include('appusers.partial.appusers-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



                <div class="card-body">
                     {{ Form::open(['route' => ['appusers.update',$user->id], 'class' => 'form-material mt-4', 'role' => 'form', 'method' => 'PUT', 'id' => 'create-user'])}}


                
                <div class="card-body">


             <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label">Shop  </label>
                        <div class="col-sm-9">
                            {!! Form::select('shop_id', $shops,  $user->shop_id, ['placeholder' => 'Select Shop', 'class' => 'form-control custom-select ']); !!}   
                        </div>
                    </div> 

                       <div class="form-group row">
                        <label for="pname" class="col-sm-3 text-right control-label col-form-label">Device Serial Number</label>
                        <div class="col-sm-9">
                            {{ Form::text('device_token',  $user->device_token, ['class' => 'form-control', 'placeholder' => 'Device Serial  Number','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>                       
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
                    <div class="form-group row">
                        <label for="rate" class="col-sm-3 text-right control-label col-form-label">Username</label>
                        <div class="col-sm-9">
                           {{ Form::text('username', $user->username, ['class' => 'form-control', 'placeholder' => 'Username','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
               
                 

            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}


                        {{ link_to_route('appusers.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light']) }}
                    </div>
                </div>

                </div>
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
     $(document).on('submit', 'form#create-user', function(e){
            e.preventDefault();
            $("#submit-data").hide();
            var data = $(this).serialize();
            $.ajax({
                method: "POST",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success:function(result){
                    if(result.success == true){
                        //$('div.account_model').modal('hide');
                        toastr.success(result.msg);
                       // capital_account_table.ajax.reload();
                        //other_account_table.ajax.reload();

                        location.href = '{{ route("appusers.index") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });
</script>
    @endsection