
@extends('layouts.app')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Staff Titles</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Staff Titles</li>
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
                    <h4 class="card-title">Staff Titles (Staff Job Description)</h4>
                    <div class="d-flex float-right mb-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="/stafftitle/create" id="btn-add-contact" class="btn btn-danger" style="background-color:#da251c; "><i class="mdi mdi-plus font-16 mr-1"></i> Add Staff Title</a>
                    </div>
                </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs">
                            <thead>
                                <tr>
                                    <th>Create date</th>
                                    <th>Code</th>
                                    <th>Job Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($titles as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('d M Y');}}</td>
                                    <td>{{$item->code}}</td>
                                    <td>{{$item->description}}</td>
                                    <td class="text-right">



                                            {!!Form::open(['action'=>['App\Http\Controllers\stafftitle\StaffTitleController@destroy', $item->id], 'method'=>'POST'])!!}
                                                {!! Form::hidden('_method', 'DELETE') !!}

                                                <a href="{{ route('stafftitle.edit', $item->id)}}" class="btn btn-outline-success btn-sm"><i
                                                    class="fa fa-edit"></i>  Edit</a>

                                                <button type="submit" class="btn btn-outline-danger btn-sm pull-right"><i
                                                    class="fa fa-trash"></i>  Delete</button>

                                            {!! Form::close() !!}
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




@endsection

