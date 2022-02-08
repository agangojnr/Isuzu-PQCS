
@extends('layouts.app')
@section('title','DRR Report')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">DIRECT RUN RATE REPORT</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Drl Report</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>THIS MONTH</small></h6>
                        <h4 class="mt-0 text-info">$58,356</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="monthchart"></div>
                    </div>
                </div>
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>LAST MONTH</small></h6>
                        <h4 class="mt-0 text-primary">$48,356</h4>
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

             <!-- Row -->
             <div class="row">
                <div class="col-lg-3">
                    <a href="{{route('drr',[''.encrypt_data(''.this_month().'').'',''.encrypt_data('this_month').''])}}">
                    <div class="card bg-inverse text-white">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                               <i class="display-6 cc DASH text-white" title="DASH"></i>
                                <div class="ml-3 mt-2">
                                    <h4 class="font-weight-medium mb-0 text-white">Month To Date</h4>
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-3">
    
                    <a href="{{route('drr',[''.encrypt_data(''.this_day().'').'',''.encrypt_data('today').''])}}">
                    <div class="card bg-cyan text-white">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <i class="display-6 cc DASH-alt text-white" title="LTC"></i>
                                <div class="ml-3 mt-2">
                                    <h4 class="font-weight-medium mb-0 text-white">Today</h4>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-3">
    
                    <a href="{{route('drr',[''.encrypt_data(''.this_year().'').'',''.encrypt_data('this_year').''])}}">
                    <div class="card bg-orange text-white">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <i class="display-6 cc DASH-alt text-white" title="DASH"></i>
                                <div class="ml-3 mt-2">
                                    <h4 class="font-weight-medium mb-0 text-white">Year To date</h4>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <a href="JavaScript: void(0);"><i class="display-6 cc DASH-alt text-white" title="DASH"></i></a>
                                <div class="ml-3 mt-2">
                                    <h4 class="font-weight-medium mb-0 text-white">Export</h4>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            @php
       
                
            if($section=='this_month'){
         @endphp

             {{ Form::open(['route' => 'drrfiltertoday', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}
             {!! Form::hidden('section', 'this_month'); !!}
             <div class="row">
             <div class="col-4">
             <div class="form-group">
                 <label for="date">Choose Month</label>
                 <input class="form-control from_custom_date" type="text" id="datepicker"
                     required="" name="month_date" value="{{$date}}"  data-toggle="datepicker" autocomplete="off"  >
             </div>
             </div>




            
         <div class="col-4">
             <button type="submit" class="btn btn-success mt-4">Filter By Month</button>
         </div>
     </div>
         {{ Form::close() }}

         @php
 }else if($section=='this_year'){
             @endphp


             {{ Form::open(['route' => 'drrfiltertoday', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}
             {!! Form::hidden('section', 'this_year'); !!}
             <div class="row">
             <div class="col-4">
             <div class="form-group">
                 <label for="date">Choose Year</label>
                 <input class="form-control from_custom_date" type="text" id="year_datepicker"
                     required="" name="month_date" value="{{$date}}"  data-toggle="datepicker" autocomplete="off"  >
             </div>
             </div>




            
         <div class="col-4">
             <button type="submit" class="btn btn-success mt-4">Filter By Year</button>
         </div>
     </div>
         {{ Form::close() }}


             @php
 }else if($section=='today'){
             @endphp

             {{ Form::open(['route' => 'drrfiltertoday', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}
             {!! Form::hidden('section', 'today'); !!}
             <div class="row">
             <div class="col-4">
             <div class="form-group">
                 <label for="date">Choose Day</label>
                 <input class="form-control from_custom_date" type="text" id="today"
                     required="" name="month_date" value="{{$date}}"  data-toggle="datepicker" autocomplete="off"  >
             </div>
             </div>




            
         <div class="col-4">
             <button type="submit" class="btn btn-success mt-4">Filter By Date</button>
         </div>
     </div>
         {{ Form::close() }}


             @php
 }
             @endphp    


    <!-- Individual column searching (select inputs) -->


    <div class="row">
        <div class="col-12">
            <div class="card">


                <div class="card-body">
                   
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" >
                            <thead>

                                   <tr>
                                    <th colspan = "{{($shopcount*3)+1}}" >{{$heading}}</th>
                                  

                                </tr>


                                <tr>
                                    <th  rowspan = "2" >Model&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
                                      @foreach ($shops as $item)
                                    <th colspan = "3">{{$item->shop_name}}</th>
                                    @endforeach
                                     
                              

                                </tr>

                                 <tr>
                                    @foreach ($shops as $item)
                                    <th style="background-color: {{$item->color_code}}" >No. of units produced</th>
                                    <th style="background-color: {{$item->color_code}}" >OK UNITS</th>
                                      <th style="background-color: {{$item->color_code}}" >SCORE %</th>
                                    @endforeach
                                    
                                    
                                 
                                </tr>

                            </thead>

                            <tbody>
                                @if(count($vehicles) > 0)
                                @foreach($vehicles as $vehicle)
                                    <tr >
                                        <td>
                                            {{$vehicle->model->model_name}} LOT {{$vehicle->lot_no}}
                                        </td>
                                      
                                        @foreach ($shops as $shop)
                                            <td style="background-color: {{$shop->color_code}}">{{$drr_arr[$vehicle->model_id][$vehicle->lot_no][$shop->id]['units']}}</td>
                                               <td style="background-color: {{$shop->color_code}}">{{$drr_arr[$vehicle->model_id][$vehicle->lot_no][$shop->id]['drr']}}</td>
                                                <td style="background-color: {{$shop->color_code}}">{{$drr_arr[$vehicle->model_id][$vehicle->lot_no][$shop->id]['score']}}</td>
                                        @endforeach
                                       
                                        
                                    </tr>
                                @endforeach
                                @endif
                             </tbody>


                             <tfoot>
                                <tr class="table-success">
                                    <th><strong>TOTAL</strong></th>
                                     @foreach ($shops as $item)
                                     @php
                                       $plantdrr=$totalunits[$item->id]['total_units']*  $totalunits[$item->id]['total_ok_units']*$totalunits[$item->id]['total_score'];
                                       $plantdrr=$plantdrr/10000;
                                     @endphp
                                     
                        <th>  {{ $totalunits[$item->id]['total_units'] }} </th>
                        <th  >{{ $totalunits[$item->id]['total_ok_units'] }}</th>
                        <th  >{{ $totalunits[$item->id]['total_score'] }}</th>
                        @endforeach
                                </tr>


                                  <tr class="table-warning">
                                    <th><strong>ACTUAL MTD SCORE</strong></th>
                                     @foreach ($shops as $item)
                        <th class=" text-center" colspan="3"  >{{ $totalunits[$item->id]['total_score'] }}</th>
                       
                        @endforeach
                                </tr>

                                 <tr class="table-primary">
                                    <th ><strong>{{$target_name}}</strong></th>
                                     @foreach ($shops as $item)
                        <th class=" text-center" colspan="3" >33</th>
                       
                        @endforeach
                                </tr>

                                <tr >

                                 <th colspan = "{{($shopcount)+2}}" >PLANT DRR : <strong>{{ $plantdrr }}</strong></th>

                                 <th colspan = "{{($shopcount)+2}}" >PLANT TARGET : <strong>66</strong></th>
                      

                    </tr>

                            </tfoot>
                        



                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


 <!-- Signup modal content -->
                                <div id="daily-modal" class="modal fade" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                             <div class="modal-header">
                                                <h4 class="modal-title" id="fullWidthModalLabel">Select Dily  Date</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>

                                            <div class="modal-body">
                                              

                                                 {{ Form::open(['route' => 'drrfiltertoday', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}

                                                       {!! Form::hidden('select_id', 1); !!}

                                                         <div class="form-group">
                                                        <label for="date">Select Target</label>
                                                         {!! Form::select('target_id', $target,  null, ['placeholder' => 'Select Target', 'required'=>'required', 'class' => 'form-control']); !!}


                                                       
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="date">Date</label>
                                                        <input class="form-control from_date" type="text" id="from"
                                                            required="" name="from_date_single" data-toggle="datepicker" autocomplete="off"  >
                                                    </div>




                                                     

                                                   

                                                    


                                                    <div class="form-group text-center">
                                                        <button class="btn btn-primary" type="submit">Filter Record      </button>
                                                    </div>

                                                 {{ Form::close() }}

                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->



                                 <!-- Custom content -->
                                <div id="custom" class="modal fade" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                             <div class="modal-header">
                                                <h4 class="modal-title" id="fullWidthModalLabel">Select Custom Date</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>

                                            <div class="modal-body">
                                              

                                                 {{ Form::open(['route' => 'drrfiltertoday', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}

                                                      {!! Form::hidden('select_id', 2); !!}

                                                         <div class="form-group">
                                                        <label for="date">Select Target</label>
                                                         {!! Form::select('custom_target_id', $target,  null, ['placeholder' => 'Select Target', 'required'=>'required', 'class' => 'form-control']); !!}


                                                       
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="date">From</label>
                                                        <input class="form-control from_custom_date" type="text" id="from"
                                                            required="" name="from_custom_date_single" data-toggle="datepicker" autocomplete="off"  >
                                                    </div>

                                                        <div class="form-group">
                                                        <label for="date">To</label>
                                                        <input class="form-control to_custom__date" type="text" id="to"
                                                            required="" name="to_custom_date_single" data-toggle="datepicker" autocomplete="off"  >
                                                    </div>




                                                     

                                    
                                                    


                                                    <div class="form-group text-center">
                                                        <button class="btn btn-primary" type="submit">Filter Record      </button>
                                                    </div>

                                                 {{ Form::close() }}

                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

@endsection
@section('after-styles')
{{ Html::style('assets/libs/datepicker/datepicker.min.css') }}
   {{ Html::style('assets/libs/datepicker/datepicker.min.css') }}
      {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    {{ Html::style('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}

 
    
@endsection



@section('after-scripts')

{{ Html::script('assets/libs/datepicker/datepicker.min.js') }}

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
{{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}

<script type="text/javascript">




      $('#daily-modal').on('shown.bs.modal', function () {
      
           $('[data-toggle="datepicker"]').datepicker({
            autoHide: true,
            format: 'dd-mm-yyyy',

            
        });
      $('.from_date').datepicker('setDate', 'today');
            
         });
   
    $('#custom').on('shown.bs.modal', function () {
      
           $('[data-toggle="datepicker"]').datepicker({
            autoHide: true,
            format: 'dd-mm-yyyy',

            
        });
      $('.from_custom_date').datepicker('setDate', 'today');
       $('.to_custom__date').datepicker('setDate', 'today');
            
         });


         $(function(){


var today = new Date();
$("#datepicker").datepicker({
    showDropdowns: true,
    format: "MM yyyy",
    viewMode: "years",
    minViewMode: "months",
    maxDate: today,
    }).on('changeDate', function(e){
$(this).datepicker('hide');
});

$("#today").datepicker({
    showDropdowns: true,
    format: "dd-mm-yyyy",
    viewMode: "days",
    minViewMode: "days",
    maxDate: today,
    }).on('changeDate', function(e){
$(this).datepicker('hide');
})
$("#year_datepicker").datepicker({
    showDropdowns: true,
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    maxDate: today,
    }).on('changeDate', function(e){
$(this).datepicker('hide');
})




});
        

</script>
    @endsection