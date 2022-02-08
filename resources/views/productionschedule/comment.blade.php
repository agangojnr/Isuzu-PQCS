
@extends('layouts.app')
@section('title','Schedule Revision Comments')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Schedule Revision Comments</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Monthly Revision</li>
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
                    <h3 class="mb-0">Schedule Revision Comments</h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('productionschedule.partial.schedule-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">

                    <div class="table-responsive" >
                        <table class="table table-striped table-bordered datatable-select-inputs w-100">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Section</th>
                                    <th>Month</th>
                                    <th>Revision Date</th>
                                    <th>Issue No.</th>
                                    <th>Issue Revision Comment</th>
                                    <th>Done By</th>
                                    <th colspan="2">Action</th>
                                </tr>

                            </thead>
                            <tbody>
                                @if (count($issues) > 0)
                                    @foreach ($issues as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->level}}</td>
                                        <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $item->month)->format('F')}}</td>
                                        <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('d/m/Y')}}</td>
                                        <td>Issue {{$item->issue_no}}</td>
                                        <td>{{$item->comment}}</td>
                                        <td>{{$item->user->name}}</td>
                                        <td>
                                            <a href="{{ route('editchedule', $item->id)}}" class="btn btn-outline-success btn-sm"><i
                                                class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                            @if ($item->level == "offline" && $item->issue_no == $offmaxissue)
                                                <a href="{{ route('department.destroy', $item->id)}}"  class="btn btn-outline-danger btn-sm pull-right delissue"><i
                                                    class="fa fa-trash"></i></a>

                                                    
                                            @elseif($item->level == "fcw" && $item->issue_no == $fcwmaxissue)
                                                <a href="{{ route('department.destroy', $item->id)}}"  class="btn btn-outline-danger btn-sm pull-right delissue"><i
                                                    class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('after-styles')
    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
@endsection

@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
 {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
 {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('assets/libs/daterangepicker/daterangepicker.js') }}
 {!! Toastr::message() !!}

 <script>
    $(function(){
    'use strict'
    $('.shawCalRanges').daterangepicker({
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
            alwaysShowCalendars: true,
        });
    });

    $(document).on('click', '.delissue', function(e){
            e.preventDefault();

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You will not be able to recover these details!",
                        type: "warning",
                      //buttons: true,

                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: false,
                    closeOnCancel: false

                      //dangerMode: true,
                    }).then((result) => {
                        if (Object.values(result) == 'true') {
                            var href = $(this).attr('href');
                            $.ajax({
                                method: "DELETE",
                                url: href,
                                dataType: "json",
                                  data:{

                                 '_token': '{{ csrf_token() }}',
                                       },
                                success: function(result){ console.log(result);
                                    if(result.success == true){
                                        toastr.success(result.msg);
                                        //routingquery.ajax.reload();
                                        window.location = "comments";
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                }
                            });
                        }
                    });
        });

</script>

    @endsection
