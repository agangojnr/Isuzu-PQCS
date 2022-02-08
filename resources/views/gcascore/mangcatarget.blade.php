

@extends('layouts.app')
@section('title','Set Targets')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">TARGETS</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">GCA TARGETS</li>
            </ol>
        </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="col-sm-8 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-block">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered datatable-select-inputs">
                                <thead>
                                    <tr>
                                    <th>Year</th>
                                    <th>Quarter</th>
                                    <th>CV DPV Target</th>
                                    <th>CV WDPV Target</th>
                                    <th>LCV DPV Target</th>
                                    <th>LCV WDPV Target</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($targets))
                                        @foreach ($targets as $pl)
                                        <tr>
                                            <td>{{$pl->year}}</td>
                                            <td>{{$pl->yearquarter}}</td>
                                            <td class="text-center">{{$pl->cvdpv}}</td>
                                            <td class="text-center">{{$pl->cvwdpv}}</td>
                                            <td class="text-center">{{$pl->lcvdpv}}</td>
                                            <td class="text-center">{{$pl->lcvwdpv}}</td>
                                            <td>
                                                <a href="{{route('destroygcatag', $pl->id)}}"
                                                    class="btn btn-outline-danger btn-sm pull-right deltarget"><i
                                                    class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Year</th>
                                        <th>Quarter</th>
                                        <th>CV DPV Target</th>
                                        <th>LCV WDPV Target</th>
                                        <th>CV DPV Target</th>
                                        <th>LCV WDPV Target</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            </div>

        {!! Toastr::message() !!}

        @endsection

        @section('after-styles')
            {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
            {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}

        @endsection

        @section('after-scripts')
        {{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
        {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
        {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
        {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
        {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}

        <script type="text/javascript">
           //$(".select2").select2();
        </script>

        <script>


$(document).on('click', '.deltarget', function(e){

e.preventDefault();
        var txt = "You will delete the target from the system!";
        var btntxt = "Yes, Delete!";
        var color = "#DD6B55";

        Swal.fire({
            title: "Are you sure?",
            text: txt,
            type: "warning",
          //buttons: true,
        showCancelButton: true,
        confirmButtonColor: color,
        confirmButtonText: btntxt,
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false

          //dangerMode: true,
        }).then((result) => {
            if (Object.values(result) == 'true') {
                var href = $(this).attr('href');
                $.ajax({
                    method: "POST",
                    url: href,
                    dataType: "json",
                      data:{

                     '_token': '{{ csrf_token() }}',
                           },
                    success: function(result){
                        if(result.success == true){
                            toastr.success(result.msg);
                            //routingquery.ajax.reload();
                            window.location = "mangcatarget";
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
    });
        </script>


