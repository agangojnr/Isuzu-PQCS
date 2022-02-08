
@extends('layouts.app')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Staff Summary </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Staff Summary Table</li>
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
                    <h4 class="card-title">Staff Summary</h4>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs">
                            <thead>
                                <tr>
                                    <th>Shop</th>
                                    <th>Team Members</th>
                                    <th>Team Leader</th>
                                    <th>Total HC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shops as $shop)
                                <tr>
                                    <td>{{$shop->report_name}}</td>
                                    <td>{{$hc[$shop->id] - $tl[$shop->id]}}</td>
                                    <td>{{$tl[$shop->id]}}</td>
                                    <td>{{$hc[$shop->id]}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Shop</th>
                                    <th>Team Members</th>
                                    <th>Team Leader</th>
                                    <th>Total HC</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

