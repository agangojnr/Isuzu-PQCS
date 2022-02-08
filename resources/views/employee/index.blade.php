
@extends('layouts.app')
@section('title','Employee List')
@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Employees</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Employees Table</li>
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


    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Employee List</h4>

                        <div class="d-flex float-right m-2">
                            <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                @can('hc-import')
                                    <a href="{{route('importemployee')}}" id="btn-add-contact"
                                    class="btn btn-primary"><i class="mdi mdi-file-import font-16 mr-1"></i> import Staff List</a>
                                @endcan
                        </div>


                    </div>
                            <div class="d-flex float-right m-2">
                                <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                    <a href="/employee/create" id="btn-add-contact" style="background-color:#da251c;"
                                    class="btn btn-danger"><i class="mdi mdi-account-plus font-16 mr-1"></i> Add Staff</a>
                            </div>
                            </div>
                    <div class="table-responsive" >
                        <table class="table table-striped table-bordered datatable-select-inputs">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Staff No.</th>
                                    <th>Staff Name</th>
                                    <th>Detpartment</th>
                                    <th>Category</th>
                                    <th>Shop</th>
                                    <th>Team Leader</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Edit/Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($staffs != null)
                                @foreach ($staffs as $item)
                                <tr>
                                    <td>{{$loop->iteration}}.</td>
                                    <td>{{$item->staff_no}}</td>
                                    <td>{{$item->staff_name}}</td>
                                    <td>{{$item->Department_Description}}</td>
                                    <td>{{$item->Category}}</td>
                                    <td>{{$item->shop->report_name}}</td>

                                    <td>
                                        @if ($item->team_leader == 'yes')
                                            {{'Team Leader'}}
                                        @else
                                            {{''}}
                                        @endif
                                        </td>

                                    <td>
                                        @if ($item->status == 'Active')
                                            {{'Active'}}
                                        @else
                                            {{'Inactive'}}
                                        @endif
                                    </td>
                                    <td>
                                        @can('hc-activate')
                                            @if ($item->status == 'Active')
                                                <a href="{{route('activate', $item->id)}}" data-id="Deactivate" class="btn btn-xs btn-warning activate">
                                                <i class="glyphicon glyphicon-trash"></i> Deactivate</a>
                                            @else
                                                <a href="{{route('activate', $item->id)}}" data-id="Activate" class="btn btn-xs btn-success activate">
                                                <i class="glyphicon glyphicon-trash"></i> Activate</a>
                                            @endif
                                        @endcan
                                    </td>
                                    <td>
                                        @can('hc-edit')
                                            <a href="{{ route('employee.edit', $item->id)}}" class="btn btn-outline-success btn-sm">
                                                <i class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('hc-delete')
                                            <a href="{{route('employee.destroy', $item->id)}}" class="btn btn-outline-danger btn-sm pull-right delemployee">
                                                <i class="fa fa-trash"></i></a>
                                        @endcan

                                    </td>

                                </tr>
                                @endforeach

                                @endif

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
        $(document).on('click', '.delemployee', function(e){
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
                                        window.location = "employee";
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                }
                            });
                        }
                    });
        });



//ACTIVATE USER
$(document).on('click', '.activate', function(e){

    e.preventDefault();
        if($(this).attr("data-id") == 'Activate'){
            var txt = "You will allow the user to access the system!";
            var btntxt = "Yes, Activate";
            var color = "green";
         }else{

            var txt = "You will disable the user from system access!";
            var btntxt = "Yes, Deactivate!";
            var color = "#DD6B55";
         }
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
                        method: "post",
                        url: href,
                        dataType: "json",
                          data:{

                         '_token': '{{ csrf_token() }}',
                               },
                        success: function(result){
                            if(result.success == true){
                                toastr.success(result.msg);
                                //routingquery.ajax.reload();
                                window.location = "employee";
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
});
    </script>

