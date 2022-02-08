
@extends('layouts.app')
@section('title','Unit Model Edit')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Edit Unit Model </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit Vehicle Model</li>
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
                            @include('unitmodel.partial.unitmodel-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



                <div class="card-body">
                     {{ Form::open(['route' => ['vehiclemodels.update',$models->id], 'class' => 'form-material mt-4', 'role' => 'form', 'method' => 'PUT', 'id' => 'create-model'])}}




                
                <div class="card-body">


          <div class="card-body">
                              
                               <div class="row">
                             
                                    <div class="form-group col-md-12">
                                        <label>Vehicle Type <span class="help"> e.g. "N-SERIES"</span></label>

                                        {!! Form::select('vehicle_type_id', $vehicletypes,  $models->vehicle_type_id, ['placeholder' => 'Select Type', 'class' => 'form-control form-control-line ']); !!}

                                     </div> 
                                 
                                </div>


 <div class="row">
   <div class="form-group col-md-12">
                                        <label>Model Name <span class="help"> e.g. "NLR77U-EE1AYN"</span></label>

                                        {!! Form::text('model_name',  $models->model_name, ['placeholder' => 'Model Name', 'class' => 'form-control form-control-line','autocomplete'=>'off']); !!}
                             </div>
 </div>

  <div class="row">
       <div class="form-group col-md-12">
                                        <label>Model Code <span class="help"> e.g. "BS-19278"</span></label>

                                        {!! Form::text('model_number',  $models->model_number, ['placeholder' => 'Model Number', 'class' => 'form-control form-control-line','autocomplete'=>'off']); !!}
                             </div>
 </div>
           
                               </div>

          
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-info btn-md']) }}


                        {{ link_to_route('vehiclemodels.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light','id'=>'submit-data']) }}
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

    

<script type="text/javascript">
     
 $(document).on('submit', 'form#create-model', function(e){
            e.preventDefault();
            //var data = $(this).serialize();
            $("#submit-data").hide();
            $.ajax({
                method: "POST",
                url: $(this).attr("action"),
                data:  new FormData(this),
               contentType: false,
               cache: false,
               processData:false,
                success:function(result){
                    if(result.success == true){
                        //$('div.account_model').modal('hide');
                        toastr.success(result.msg);
                       // capital_account_table.ajax.reload();
                        //other_account_table.ajax.reload();

                        location.href = '{{ route("vehiclemodels.index") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });








    
</script>

  
    @endsection