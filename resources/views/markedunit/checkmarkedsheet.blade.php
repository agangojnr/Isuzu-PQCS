
@extends('layouts.app')
@section('title','Marked Unit')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Marked Routings </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Marked Routings</li>
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
                    <h3 class="mb-0">Vehicle <strong>{{$vehicle->vin_no}}</strong>  Model <strong>{{$vehicle->model->model_name}}</strong></h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('markedunit.partial.markedunit-header-buttons')
                           
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



            @foreach($shops as $shop)
             @if(count($shop->querycategory)>0)
                 <div class="card-header bg-info">
                                <h4 class="mb-0 text-white">{{$shop->shop_name}}</h4>
                            </div>
                           

                          
                           @php
                           $i=0;
                           @endphp
                           
                            @foreach($shop->querycategory as $row)

                           
                             @php
                           $i++;
                           @endphp
                             <div class="card-body">
                                        <h4 class="card-title"> {{$i}}. {{$row->query_code}} : {{$row->category_name}}</h4>
                                    </div>

                            

                           <div class="card-body">
                  
                   
                       <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-warning text-white">
                                        <tr>
                                            <th scope="col">Image</th>
                                            <th scope="col">Signature</th>
                                            <th scope="col">Query Name</th>
                                            <th scope="col">OK/NOK</th>
                                            <th scope="col">Defects</th>
                                            <th scope="col">User</th>
                                            <th scope="col"> Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($row->query_items as $data)
                                        <tr>
                                            @php
                                            $defect_name="--";
                                             $action="--";

                                             $weight="--";
                                             $appuser="--";
                                             $image_defect="--";
                                               $signature="--";

                                           if(!empty($data->quizanswers)){



                                      
                                            if(count($data->quizanswers->defects) > 0){
                 $action='<a title="Check"  class="btn btn-xs btn-primary edit_unit_button"  href="'.route('checkdefects', [$data->quizanswers->id,$vehicle->id,$shop->id]).'"><i class="mdi mdi-eye"></i> Check</a>';
                                                   $defect_name_array=array( );
                                              
                                            foreach($data->quizanswers->defects as $dcaptured){

                                                $defect_name_array[] =$dcaptured->defect_name.'( Weight: '.$dcaptured->value_given.')'; 
                                                $weight =$dcaptured->value_given; 

                                               

                                            }
                                               $defect_name=implode(' , ', $defect_name_array);

                                        }



if(!empty($data->quizanswers->icon)){

    $url= asset('upload/'.$data->quizanswers->icon);

   $image_defect= '<img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" />';

    

}


if(!empty($data->quizanswers->signature)){

    $url= asset('upload/'.$data->quizanswers->signature);

   $signature= '<img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" />';

    

}


     

                                        }


                                             
                                             $endterm="--";
                                             if(!empty($data->quizanswers)){
                                                 $endterm=$data->quizanswers->answer;

                                                  $appuser=$data->quizanswers->doneby->name;
                         
                                                }

                                               @endphp

                          
                                           <td>{!!$image_defect!!}</td>
                                            <td>{!!$signature!!}</td>
                                            <td>{{$data->query_name}}</td>
                                            <td>{{$endterm}}</td>
                                            <td>{{$defect_name}}</td>
                                           
                                            <td>{{$appuser}}</td>
                                            <td> {!!$action!!}</td>
                                        </tr>
                                      @endforeach
                                       
                                    </tbody>
                                </table>
                            </div>
                   
                </div>
               
                @endforeach
                 @endif
                
                @endforeach
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