
@extends('layouts.app')
@section('title','Marked Unit')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Filled Routings </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Filled Routings</li>
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
                    <h3 class="mb-0">Filled Routings</h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
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
                   
                   
                        <table class="table table-striped table-bordered " id="unitmarked">
                            <thead>
                                <tr>

                                    <th>Unit Vin</th>
                                    <th>Unit Model</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Answered</th>
                                    <th>Correct</th>
                                    <th>% Score</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $units as $unit )
                                    
                            
                                <tr>
                                    <td>{{  $unit->vin_no  }}</td>
                                    <td>{{  $unit->model->model_name  }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><a title="Change"  class="btn btn-xs btn-primary edit_unit_button"  href="{{ route('checkmarkedsheet', [$unit->id])}} "><i class="mdi mdi-eye"></i> Check</a></td>
                                    

                                </tr>

                                @endforeach
                                
                            </tbody>
                        
                        </table>
                   
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

var appusers = $('#unitmarked').DataTable({

});


  
// capital_account_table
   /*    var appusers = $('#unitmarked').DataTable({
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
                        
                    });*/





          
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