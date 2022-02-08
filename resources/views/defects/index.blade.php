
@extends('layouts.app')
@section('title','Defect Summary')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">DEFECT SUMMARY</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Defect Summary</li>
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
  <div class="content-header row pb-1">
                <div class="content-header-left col-md-8 col-12 mb-2">
                     <div class="form-group">
                                <!-- basic buttons -->
                                
                                
                                
                               
                                <button type="button"
                                        class="update_chart btn btn-info btn-min-width  btn-lg mr-1 mb-1"
                                        data-val="custom" data-toggle="modal"
                                        data-target="#custom"><i
                                            class="fa fa-clock"></i> Custom Date Range
                                </button>

                            </div>

                </div>
                <div class="content-header-right col-md-4 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            
                        </div>
                    </div>
                </div>
            </div>



    <!-- Individual column searching (select inputs) -->


    <div class="row">
        <div class="col-12">
            <div class="card">


                <div class="card-body">
                   
                  

                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">{{$heading}}</h4>
                        </div>
                      
                    </div>
                    
                       <div class="table-responsive">
                                    <table id="mainTable"
                                        class="table editable-table table-bordered table-striped m-b-0">
                                        <thead>
                                            <tr>
                                                <th>Query  Name</th>
                                                <th>Defect</th>
                                                <th>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <th>GCA WT</th>
                                                <th>Shop Captured</th>
                                                <th>Vin</th>
                                                <th>Job</th>
                                                <th>Lot</th>
                                                <th>Inspection</th>
                                                <td>VS</td>
                                                <td>Float</td>
                                                <th>Drl Score</th>
                                                <th>Corrected</th>
                                                <th>Inpected By</th>

                                                
                                            
                                         



                                            </tr>
                                        </thead>
                                        <tbody id="defectsummary">
                                            
                                            @foreach($defects as $defect)
                                            @php

                                      


  
                                            @endphp

                                           
                                            <tr>
                                            <td class="edit-disabled">{{$defect->getqueryanswer->routing->query_name}}</td>
                                                <td>{{$defect->defect_name}} </td>
                                                <td>{{dateFormat($defect->created_at)}}</td>
                                                <td data-name="value_given" data-title="Update GCA " class="gca" data-type="text" data-pk="{{$defect->id}}" data-url="{{route('updatedefect',[encrypt_data('gca')])}}" >{{$defect->value_given}}</td>
                                                <td data-name="shop_id" class="shop" id="shop" data-type="select" data-source="{{$shops}}" data-value="{{$defect->getqueryanswer->shop->id}}"  data-pk="{{$defect->getqueryanswer->id}}" data-url="{{route('updatedefect',[encrypt_data('shop')])}}" data-title="Update Shop" >{{$defect->getqueryanswer->shop->shop_name}}</td>
                                                 <td>{{$defect->getqueryanswer->vehicle->vin_no}}</td>
                                                <td>{{$defect->getqueryanswer->vehicle->job_no}}</td>
                                                <td>{{$defect->getqueryanswer->vehicle->lot_no}}</td>
                                                <td>{{ check_units_complete($defect->shop_id,$defect->vehicle_id) ? 'Complete' : 'InProcess'}}</td>
                                                <td data-name="stakeholder" data-source="[{'value': 'MATERIAL HANDLING', 'text': 'MATERIAL HANDLING'}, {'value': 'PRODUCTION', 'text': 'PRODUCTION'}, {'value': 'LCD', 'text': 'LCD'}, {'value': 'PE', 'text': 'PE'}, {'value': 'MH/S', 'text': 'MH/S'}]" class="stakeholder" data-value="{{$defect->stakeholder}}" data-type="select" data-pk="{{$defect->id}}" data-title="Select Stakeholder" data-url="{{route('updatedefect',[encrypt_data('stakeholder')])}}">{{$defect->stakeholder}}</td>

                                                <td data-name="defect_category" class="defect_category" id="defect_category" data-type="select" data-source="{{$defectcategory}}" data-value="{{$defect->defect_category}}"  data-pk="{{$defect->id}}" data-url="{{route('updatedefect',[encrypt_data('defect_category')])}}" data-title="Update Defect Category" >{{$defect->defect_category}}</td>
                                                <td data-name="is_defect" class="is_defect" id="is_defect" data-type="select" data-source="[{'value': 'Yes', 'text': 'Yes'}, {'value': 'No', 'text': 'No'}]" data-value="{{$defect->is_defect}}"  data-pk="{{$defect->id}}" data-url="{{route('updatedefect',[encrypt_data('defect_category')])}}" data-title="Update Corrected" >{{$defect->is_defect}}</td>

                                                <td data-name="repaired" class="repaired" id="defect_category" data-type="select" data-source="[{'value': 'Yes', 'text': 'Yes'}, {'value': 'No', 'text': 'No'}]" data-value="{{$defect->repaired}}"  data-pk="{{$defect->id}}" data-url="{{route('updatedefect',[encrypt_data('defect_category')])}}" data-title="Update Corrected" >{{$defect->repaired}}</td>
                                                <td>{{$defect->getqueryanswer->doneby->name}}</td>
                                               
                                                
                                              

                                               
                                            </tr>

                                            @endforeach
                                          
                                        </tbody>
                                        
                                    </table>
                                </div>
                    
                    
                </div>
            </div>
        </div>
    </div>


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
                                              

                                                 {{ Form::open(['route' => 'filterdefect', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}

                                                      {!! Form::hidden('select_id', 2); !!}

                                                       


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
      {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
        {{ Html::style('assets/libs/x-editable/dist/css/bootstrap-editable.css') }}
 
    
@endsection



@section('after-scripts')

{{ Html::script('assets/libs/datepicker/datepicker.min.js') }}

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
{{ Html::script('assets/libs/x-editable/dist/js/bootstrap-editable.js') }}






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








     $('#defectsummary').editable({
        container: 'body',
        selector: 'td.vs',
        value: 2,    
        source: [
              {value: 1, text: 'Active'},
              {value: 2, text: 'Blocked'},
              {value: 3, text: 'Deleted'}
           ]
    });

     

        $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                  $('#defectsummary').editable({
        container: 'body',
        selector: 'td.shop',
         validate: function(value){
   if($.trim(value) == '')
   {
    return 'This field is required';
   }
  },
        success: function (response, newValue) {
                        console.log('Updated', response)
                    }

    });


$('#defectsummary').editable({
  container: 'body',
  selector: 'td.gca',
  validate: function(value){
   if($.trim(value) == '')
   {
    return 'This field is required';
   }
  },
   success: function (response, newValue) {
                        console.log('Updated', response)
                    }
 });

$('#defectsummary').editable({
  container: 'body',
  selector: 'td.defect_category',
  validate: function(value){
   if($.trim(value) == '')
   {
    return 'This field is required';
   }
  }, success: function (response, newValue) {
                        console.log('Updated', response)
                    }
 });


$('#defectsummary').editable({
  container: 'body',
  selector: 'td.stakeholder',
  validate: function(value){
   if($.trim(value) == '')
   {
    return 'This field is required';
   }
  }, success: function (response, newValue) {
                        console.log('Updated', response)
                    }
 });

$('#defectsummary').editable({
  container: 'body',
  selector: 'td.repaired',
  validate: function(value){
   if($.trim(value) == '')
   {
    return 'This field is required';
   }
  }, success: function (response, newValue) {
                        console.log('Updated', response)
                    }
 });


$('#defectsummary').editable({
  container: 'body',
  selector: 'td.is_defect',
  validate: function(value){
   if($.trim(value) == '')
   {
    return 'This field is required';
   }
  }, success: function (response, newValue) {
                        console.log('Updated', response)
                    }
 });


        })



// capital_account_table
var defect = $('#mainTable').DataTable({

});
        

   
</script>
    @endsection