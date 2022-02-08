
@extends('layouts.app')
@section('title','Defect Summary')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">DIRECT RUN LOSS REPORT</h3>
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
  <div class="content-header row pb-1">
                <div class="content-header-left col-md-8 col-12 mb-2">
                     <div class="form-group">
                                <!-- basic buttons -->
                                <a class="update_chart btn btn-primary btn-min-width  btn-lg mr-1 mb-1 text-white"
                                        data-val="month" href="" ><i
                                            class="fa fa-calendar"></i> Month To Date</a>
                                
                                
                                <button type="button"
                                        class="update_chart btn btn-success btn-min-width  btn-lg mr-1 mb-1"
                                        data-val="year" data-toggle="modal"
                                        data-target="#daily-modal"><i
                                            class="fa fa-book"></i> Daily Report
                                </button>
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
                   
                    {!! Form::open(['action'=>'App\Http\Controllers\stdworkinghrs\StdWorkingHrController@store', 'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Defects Summary</h4>
                        </div>
                        <div class="col-6">
                            <button id="savestdhr" disabled type="submit" class="btn btn-primary float-right mb-3">Save Changes</button>
                        </div>
                    </div>
                       <div class="table-responsive">
                                    <table id="mainTable"
                                        class="table editable-table table-bordered table-striped m-b-0">
                                        <thead>
                                            <tr>
                                                <th>Query  Name</th>
                                                <th>Defect</th>
                                                <th>Date</th>
                                                <th>GCA WT</th>
                                                <th>Shop</th>
                                                <th>Model</th>
                                                <th>Captured By</th>
                                                <th>Corrected</th>
                                                <th>Responsible</th>



                                            </tr>
                                        </thead>
                                        <tbody id="employee_data">
                                            <tr>
                                            <td class="edit-disabled">Car</td>
                                                <td data-name="name" class="name" data-type="text" data-pk="333">100 edit</td>
                                                <td>200</td>
                                                <td>0</td>
                                            </tr>
                                          
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><strong>TOTAL</strong></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>







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





 $('#employee_data').editable({
  container: 'body',
  selector: 'td.name',
  url: "update.php",
  title: 'Employee Name',
  type: "POST",
  //dataType: 'json',
  validate: function(value){
   if($.trim(value) == '')
   {
    return 'This field is required';
   }
  }
 });
</script>
    @endsection