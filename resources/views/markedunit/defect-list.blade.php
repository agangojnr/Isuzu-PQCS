
@extends('layouts.app')
@section('title','Defect List')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Defect Lists </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Defect List</li>
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
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="mb-0"> Vehicle <strong>{{$vehicle->vin_no}}</strong>  Model <strong>{{$vehicle->model->model_name}}</strong></h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('markedunit.partial.defectlist-header-buttons')
                           
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



  
                 <div class="card-header bg-info">
                                <h4 class="mb-0 text-white">{{$shop->shop_name}}</h4>
                            </div>
                           

                          
                          
                             <div class="card-body">
                                        <h4 class="card-title"> {{$quiz->routing->query_name}}</h4>
                                    </div>

                            

                           <div class="card-body">
                  
                   
                       <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-warning text-white">
                                        <tr>
                                         

                                          <th scope="col">Image</th>
                                        <th scope="col">Defect Name</th>
                                           <th scope="col">Drl Score</th>
                                            <th scope="col">Weight</th>
                                            <th scope="col">Repaired</th>
                                            <th scope="col">Repaired By</th>
                                              <th scope="col">Repair Confirmed By</th>
                                            <th scope="col">Inspected By</th>
                                            <!--<th scope="col">Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($defects as $defect)
                                        <tr>
                                             @php
 $image_defect='--';
                                             if(!empty($defect->defect_image)){

    $url= asset('upload/'.$defect->defect_image);

   $image_defect= '<img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" />';

    

}
$corrected="";
if(!empty($defect->corrected_by)){
$corrected=$defect->confirmed->name;
}
                                             @endphp
                                           <td>{!!$image_defect!!}</td>
                                            <td>{{$defect->defect_name}}</td>
                                            <td>{{$defect->is_defect}}</td>
                                            <td>{{$defect->value_given}}</td>
                                           
                                            <td>{{$defect->repaired}}</td>
                                           <td>{{$defect->defect_corrected_by}}</td>
                                          
                                            <td> {{$corrected}}</td>

                                            <td> {{$defect->getqueryanswer->doneby->name}}</td>

                                            
                                            <!--<td>  <button data-href="{{route('changedefect', [$defect->id])}}" title="Change"  class="btn btn-xs btn-primary edit_unit_button"><i class="mdi mdi-tooltip-edit"></i> Edit </button>-->



                                               
                                        </tr>
                                      @endforeach
                                       
                                    </tbody>
                                </table>
                            </div>
                   
                </div>
              
             
            </div>
        </div>
    </div>


<div class="modal fade unit_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    
@endsection



@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}

<script type="text/javascript">




  
// capital_account_table
       var appusers = $('#unitmarked').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route("markedunit") }}',
                            data: function(d){
                               // d.account_status = $('#account_status').val();
                            }
                        },
                        columnDefs:[{
                                "targets": 7,
                                "orderable": false,
                                "searchable": false
                            }],
                        columns: [
                        
                            {data: 'vin_no', name: 'vin_no'},
                            {data: 'model_id', name: 'model_id'},
                            {data: 'status', name: 'status'},
                            {data: 'total_queries', name: 'total_queries'},
                            {data: 'answered', name: 'answered'},
                            {data: 'correct_answered', name: 'correct_answered'},
                            {data: 'percentage', name: 'percentage'},
                            {data: 'action', name: 'action'}
                        ],
                        
                    });





          
        $(document).on('click', 'button.edit_unit_button', function() {   
        $('div.unit_modal').load($(this).data('href'), function() {
            $(this).modal('show');

           $('form#change_answer_form').submit(function(e) {
                e.preventDefault();
                $("#submit-data").attr('disabled', true);
                var data = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('div.unit_modal').modal('hide');
                            toastr.success(result.msg);
                            appusers.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                             $("#submit-data").attr('disabled', false);
                        }
                    },
                });
            });
        });
    });


</script>


    @endsection