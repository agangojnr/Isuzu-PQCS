
@extends('layouts.app')
@section('title','Edit Unit Scheduled')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Edit Unit Scheduled</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit Sheduled Unit</li>
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
                            @include('vehicleunits.partial.vehicleunits-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



                <div class="card-body">
                     {{ Form::open(['route' => ['vehicleunits.update',$vehicleunits->id], 'class' => 'form-material mt-4', 'role' => 'form', 'method' => 'PUT', 'id' => 'update-vehicle-unit'])}}


                
                <div class="card-body">

                  <div class="form-group row">
                        <label for="model_id" class="col-sm-3 text-right control-label col-form-label">Model  </label>
                        <div class="col-sm-9">
                            {!! Form::select('model_id', $models,  $vehicleunits->model_id, ['placeholder' => 'Select Model', 'class' => 'form-control custom-select ']); !!}   
                        </div>
                    </div>  
                               
                     <div class="form-group row">
                        <label for="lot_no" class="col-sm-3 text-right control-label col-form-label">Lot No</label>
                        <div class="col-sm-9">
                             {{ Form::text('lot_no', $vehicleunits->lot_no, ['class' => 'form-control', 'placeholder' => 'Lot Number','required'=>'required','autocomplete'=>'off']) }}    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="job_no" class="col-sm-3 text-right control-label col-form-label">Job No</label>
                        <div class="col-sm-9">
                            {{ Form::text('job_no', $vehicleunits->job_no, ['class' => 'form-control', 'placeholder' => 'Job  Number','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vin_no" class="col-sm-3 text-right control-label col-form-label">Chasis No</label>
                        <div class="col-sm-9">
                            {{ Form::text('vin_no', $vehicleunits->vin_no, ['class' => 'form-control', 'placeholder' => 'Vin No','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rate" class="col-sm-3 text-right control-label col-form-label">Engine No</label>
                        <div class="col-sm-9">
                           {{ Form::text('engine_no', $vehicleunits->engine_no, ['class' => 'form-control', 'placeholder' => 'Engine No','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>

                       <div class="form-group row">
                        <label for="rate" class="col-sm-3 text-right control-label col-form-label">Color</label>
                        <div class="col-sm-9">
                           {{ Form::text('color', $vehicleunits->color, ['class' => 'form-control', 'placeholder' => 'Color','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>

                      <div class="form-group row">
                        <label for="route" class="col-sm-3 text-right control-label col-form-label">Route </label>
                        <div class="col-sm-9">
                           {{ Form::text('route', $vehicleunits->route, ['class' => 'form-control', 'placeholder' => 'Route','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="offline_date" class="col-sm-3 text-right control-label col-form-label">Offline Date </label>
                        <div class="col-sm-9">
                           {{ Form::text('offline_date', dateFormat($vehicleunits->offline_date), ['class' => 'form-control', 'data-toggle'=>'datepicker', 'placeholder' => 'Route','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>

               
                 

            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}


                        {{ link_to_route('vehicleunits.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light']) }}
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
{{ Html::style('assets/libs/datepicker/datepicker.min.css') }}
{{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

    
    
@endsection



@section('after-scripts')

{{ Html::script('assets/libs/datepicker/datepicker.min.js') }}
{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}

    

<script type="text/javascript">
     $(document).on('submit', 'form#update-vehicle-unit', function(e){
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

                        location.href = '{{ route("vehicleunits.index") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });


           $('[data-toggle="datepicker"]').datepicker({
            autoHide: true,
            format: 'dd-mm-yyyy',

            
        });
</script>
    @endsection