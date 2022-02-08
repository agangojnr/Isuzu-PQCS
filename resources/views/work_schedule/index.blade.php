
@extends('layouts.app')
@section('title','Holidays')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Holidays</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Setting Holidays</li>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-primary">SET A HOLIDAY</h2>

                {{ Form::open(['route' => 'workschedule.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post'])}}
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <input type="text" name="holidayname" autocomplete="off" required class="form-control" id="holidayname" placeholder="Name  here...">
                                <small id="holidayname" class="form-text text-muted">Holiday Name</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <div class='input-group'>
                                    <input type='text' name="mdate" class="form-control holiday" />

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="ti-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <small id="holidaydate" class="form-text text-muted">Holiday Date</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {{ Form::submit('Save', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}


                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Holiday Name</th>
                                    <th>Date</th>
                                    <th>Year</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($holis) > 0)
                                    @foreach ($holis as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->holidayname}}</td>
                                            <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $item->date)->format('jS M Y');}}</td>
                                            <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $item->date)->format('Y');}}</td>
                                            <td>
                                                <a href="{{route('workschedule.destroy', $item->id)}}" class="btn btn-danger delholi"
                                                    style='display:inline'>Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Holiday Name</th>
                                    <th>Date</th>
                                    <th>Year</th>
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
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}

    <script>
        $(document).on('click', '.delholi', function(e){
            e.preventDefault();

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You will not be able to recover the record!",
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
                                success: function(result){ //console.log(result);
                                    if(result.success == true){
                                        toastr.success(result.msg);
                                        //routingquery.ajax.reload();
                                        window.location = "workschedule";
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                }
                            });
                        }
                    });
        });
    </script>
