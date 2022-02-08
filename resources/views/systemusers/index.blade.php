
@extends('layouts.app')
@section('title','Manage System Users')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Manage System Users</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">View System Users</li>
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
                    <h3 class="mb-0">System Users</h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('systemusers.partial.systemusers-header-buttons')
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
                        <table class="table table-striped table-bordered datatable-select-inputs">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                     <th class="w-25">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>{{$item->phone_no}}</td>
                                    <td>
                                        @if(!empty($item->getRoleNames()))
                                          @foreach($item->getRoleNames() as $v)
                                             <label class="badge badge-success">{{ $v }}</label>
                                          @endforeach
                                        @endif
                                    </td>
                                    <td>{{$item->status}}</td>
                                    <td>
                                        @can('sysuser-edit')
                                            <a href="{{ route('systemusers.edit', $item->id)}}" class="btn btn-xs btn-primary
                                                edit_brand_button"><i class="glyphicon glyphicon-edit"></i><i class="fa fa-edit"></i></a>
                                        @endcan
                                            &nbsp;
                                        @can('sysuser-reset')
                                            <a href="{{ route('resetpasswordsu', [$item->id])}}" style="background-color:teal; color:white;"
                                                class="btn btn-xs resetpassword" ><i class="glyphicon glyphicon-edit"></i>Reset</a>
                                        @endcan
                                            &nbsp;
                                            &nbsp;

                                            @if ($item->superAdmin != 1)
                                            @can('sysuser-activate')
                                                @if ($item->status == 'Active')
                                                    <a href="{{route('systemusers.destroy', $item->id)}}" data-id="Deactivate" class="btn btn-xs btn-warning deluser">
                                                    <i class="glyphicon glyphicon-trash"></i> Deactivate</a>
                                                @else
                                                    <a href="{{route('systemusers.destroy', $item->id)}}" data-id="Activate" class="btn btn-xs btn-success deluser">
                                                    <i class="glyphicon glyphicon-trash"></i> Activate</a>
                                                @endif
                                            @endcan
                                            @can('sysuser-delete')
                                                &nbsp;
                                                &nbsp;
                                                <a href="{{ route('deleteUser', [0])}}" class="btn btn-xs btn-danger deluser1">
                                                    <i class="glyphicon glyphicon-trash"></i> <i class="fa fa-trash"></i></a>
                                            @endcan
                                            @endif

                                    </td>
                                </tr>
                                @endforeach
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
 {!! Toastr::message() !!}

<script type="text/javascript">

        //DELETE USER
        $(document).on('click', '.deluser', function(e){

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
                                        window.location = "systemusers";
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                }
                            });
                        }
                    });
        });




            $(document).on('click', '.deluser1', function(e){
                e.preventDefault();
                Swal.fire({
                    type: 'warning',
                  title: "Are You Sure",
                  showCancelButton: true,
                  buttons: true,
                  dangerMode: true,
                }).then((result) => {
                    if (Object.values(result) == 'true') {
                        var href = $(this).attr('href');
                        $.ajax({
                            method: "GET",
                            url: href,
                            dataType: "json",
                            success: function(result){
                                if(result.success == true){
                                    toastr.success(result.msg);
                                    window.location = "systemusers";
                                    //appusers.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });


            $(document).on('click', '.resetpassword', function(e){
                e.preventDefault();
                Swal.fire({
                    type: 'warning',
                  title: "Are You Sure",
                  showCancelButton: true,
                  buttons: true,
                  dangerMode: true,
                }).then((result) => {
                    if (Object.values(result) == 'true') {
                        var href = $(this).attr('href');
                        $.ajax({
                            method: "GET",
                            url: href,
                            dataType: "json",
                            success: function(result){
                                if(result.success == true){
                                    toastr.success(result.msg);
                                    window.location = "systemusers";
                                    //appusers.ajax.reload();
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
