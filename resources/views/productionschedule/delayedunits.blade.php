
@extends('layouts.app')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Vehicle Assembling Report</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Delayed Units</li>
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
                    <h4 class="card-title">Delayed Units (Units that have stayed <span class="text-danger">more than one day</span>)</h4>
                    <div class="d-flex float-right mb-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="/stafftitle/create" id="btn-add-contact" class="btn btn-danger" style="background-color:#da251c; "><i class="mdi mdi-plus font-16 mr-1"></i> Add Staff Title</a>
                    </div>
                </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs no-wrap">
                            <thead>
                                <tr>
                                    <th>Date In</th>
                                    <th>Vin No.</th>
                                    <th>Shop</th>
                                    <th>Model Name.</th>
                                    <th>Lot No.</th>
                                    <th>Job No.</th>
                                    <th>No. of days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($delays) > 0)
                                    @foreach ($delays as $item)
                                    <tr>
                                        <td>{{$item->datetime_in}}</td>
                                        <td>{{$item->vehicle->vin_no}}</td>
                                        <td>{{$item->shop->shop_name}}</td>
                                        <td>{{$item->models->model_name}}</td>
                                        <td>{{$item->vehicle->lot_no}}</td>
                                        <td>{{$item->vehicle->job_no}}</td>
                                        <td>{{\Carbon\carbon::parse($item->datetime_in)->diffInDays(\Carbon\carbon::parse($today))}}</td>
                                    </tr>
                                    @endforeach
                                @endif

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

