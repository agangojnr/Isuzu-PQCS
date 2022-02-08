
@extends('layouts.app')
@section('title','Employee Category List')
@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Employee Category</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Category</li>
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
                    <h4 class="card-title">Employee Category List</h4>
                    <div class="d-flex float-right mb-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="/employeecategory/create" id="btn-add-contact" class="btn btn-danger"
                            style="background-color:#da251c; "><i class="mdi mdi-plus font-16 mr-1"></i> Add Category</a>
                    </div>
                </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs">
                            <thead>
                                <tr>
                                    <th>Create date</th>
                                    <th>Category Code</th>
                                    <th>Category</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cats as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('d M Y');}}</td>
                                    <td>{{$item->category_code}}</td>
                                    <td>{{$item->emp_category}}</td>
                                    <td class="text-right">
                                        <a href="{{ route('employeecategory.edit', $item->id)}}" class="btn btn-outline-success btn-sm"><i
                                            class="fa fa-edit"></i>  Edit</a>

                                        <a href="{{route('employeecategory.destroy', $item->id)}}"
                                            class="btn btn-outline-danger btn-sm pull-right delcategory"><i
                                            class="fa fa-trash"></i>  Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Create date</th>
                                    <th>Code</th>
                                    <th>Job Description</th>
                                    <th></th>
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
    $(document).on('click', '.delcategory', function(e){
        e.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                        text: "You will not be able to recover this category details!",
                        type: "warning",
                      //buttons: true,

                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: false,
                    closeOnCancel: false
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
                                    window.location = "employeecategory";
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
    });
</script>

