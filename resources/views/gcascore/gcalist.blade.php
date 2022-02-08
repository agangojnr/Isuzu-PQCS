
@extends('layouts.app')
@section('title','GCA Score')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">GCA Score</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Daily GCA Score</li>
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
                    <h3 class="mb-0">GCA Score</h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('gcascore.partial.gca-buttons')
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
                                    <th>Date</th>
                                    <th>Section</th>
                                    <th>Car1 Defect</th>
                                    <th>Car2 Defect</th>
                                    <th>MTD WDPV</th>
                                    <th>Sample Size</th>
                                    <th colspan="2">Action</th>
                                </tr>

                            </thead>
                            <tbody>
                                @if (count($gcas) > 0)
                                    @foreach ($gcas as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->date}}</td>
                                        <td>{{$item->lcv_cv}}</td>
                                        <td>{{$item->defectcar1}}</td>
                                        <td>{{$item->defectcar2}}</td>
                                        <td>{{$item->mtdwdpv}}</td>
                                        <td>{{$item->units_sampled}}</td>
                                        <td>
                                            <a href="{{ route('gcascore.edit', $item->id)}}" class="btn btn-outline-success btn-sm"><i
                                                class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                                <a href="{{route('gcascore.destroy', $item->id)}}" class="btn btn-outline-danger btn-sm pull-right delgca"><i
                                                    class="fa fa-trash"></i></a>
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

    $(document).on('click', '.delgca', function(e){
            e.preventDefault();

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You will not be able to recover this staff details!",
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
                                success: function(result){
                                    if(result.success == true){
                                        toastr.success(result.msg);
                                        //routingquery.ajax.reload();
                                        window.location = "gcalist";
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
