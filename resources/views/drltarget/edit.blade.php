
@extends('layouts.app')
@section('title','Set DRL Target')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Set DRL Target</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Set Target</li>
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
                            @include('drrtarget.partial.drrtarget-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



                <div class="card-body">
                     {{ Form::open(['route' => ['drltarget.update',$record->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PUT', 'id' => 'create-drr'])}}


                
                <div class="card-body">

               <div class="form-group row">
                        <label for="target_name" class="col-sm-3 text-right control-label col-form-label">Target Name</label>
                        <div class="col-sm-9">
                             {{ Form::text('target_name', $record->target_name, ['class' => 'form-control', 'placeholder' => 'E.g Q1 2021','required'=>'required','autocomplete'=>'off']) }}    
                        </div>
                    </div>

                       <div class="form-group row">
                        <label for="plant_target" class="col-sm-3 text-right control-label col-form-label">Plant Target</label>
                        <div class="col-sm-9">
                             {{ Form::text('plant_target', $record->plant_target, ['class' => 'form-control', 'placeholder' => 'E.g 20','required'=>'required','autocomplete'=>'off']) }}    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cv_target" class="col-sm-3 text-right control-label col-form-label">CV Target</label>
                        <div class="col-sm-9">
                             {{ Form::text('cv_target', $record->cv_target, ['class' => 'form-control', 'placeholder' => 'E.g 80','required'=>'required','autocomplete'=>'off']) }}    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lcv_target" class="col-sm-3 text-right control-label col-form-label">LCV Target</label>
                        <div class="col-sm-9">
                             {{ Form::text('lcv_target', $record->lcv_target, ['class' => 'form-control', 'placeholder' => 'E.g 80','required'=>'required','autocomplete'=>'off']) }}    
                        </div>
                    </div>


                     <div class="form-group row">
                        <label for="fromdate" class="col-sm-3 text-right control-label col-form-label">From</label>
                        <div class="col-sm-9">
                            {{ Form::text('fromdate', dateFormat(@$record->fromdate), ['class' => 'form-control from_date', 'data-toggle'=>'datepicker', 'placeholder' => 'From','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="to" class="col-sm-3 text-right control-label col-form-label">To</label>
                        <div class="col-sm-9">
                            {{ Form::text('todate', dateFormat(@$record->todate), ['class' => 'form-control to_date', 'data-toggle'=>'datepicker','placeholder' => 'To','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>



          
                        <div class="form-group row">
                        <label for="active" class="col-sm-3 text-right control-label col-form-label">Status</label>
                        <div class="col-sm-9">
                             {!! Form::select('active', ['Active'=>'Active','Inactive'=>'Inactive'],  $record->active, ['placeholder' => 'Select Status', 'class' => 'form-control  ']); !!}  
                        </div>
                    </div>                      
                     <hr>
                      <h4 class="card-title">Shops</h4>  

                     @foreach($shops as $shop)
                   
                    <div class="form-group row">
                        <label for="target_value" class="col-sm-3 text-right control-label col-form-label">{{$shop->shop_name}} </label>
                        <div class="col-sm-9">
                            {{ Form::hidden('shop_id[]', $shop->id) }}
                           {{ Form::text('target_value[]', $shop->drrtargetshop->target_value, ['class' => 'form-control', 'placeholder' => '% Target','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>

                    @endforeach
                   
                 

            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Save', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}


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
{{ Html::style('assets/libs/datepicker/datepicker.min.css') }}
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

    
    
@endsection



@section('after-scripts')
{{ Html::script('assets/libs/datepicker/datepicker.min.js') }}
{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}

    

<script type="text/javascript">
     $(document).on('submit', 'form#create-drr', function(e){
            e.preventDefault();
            $("#submit-data").hide();
            var data = $(this).serialize();
            $.ajax({
                method: "post",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success:function(result){
                    if(result.success == true){
                        //$('div.account_model').modal('hide');
                        toastr.success(result.msg);
                       // capital_account_table.ajax.reload();
                        //other_account_table.ajax.reload();

                        location.href = '{{ route("drltarget.index") }}';
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
      //$('.from_date').datepicker('setDate', 'today');
       //$('.to_date').datepicker('setDate', 'today');
            
        
</script>
    @endsection