
@extends('layouts.app')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">SHOP SECTIONS</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">SECTIONS</li>
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
            <form action="{{ route('savesections') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field(); }}
                <div class="card-body">
                    <h4 class="card-title">SHOP SECTIONS</h4>
                    <div class="d-flex float-right mb-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <button id="btn-add-contact" class="btn btn-primary"><i class="mdi mdi-content-save-all font-16 mr-1"></i>Save Sections</button>
                    </div>
                </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Shop Name</th>
                                    <th>No. of Sections</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shops as $item)
                                <tr>
                                    <input type="hidden" name="shopid[]" value="{{$item->id}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->shop_name}}</td>
                                    <td>{{$item->no_of_sections}}</td>
                                    <td>
                                        <input type="text" autocomplete="off" name="noofsections[]"
                                        class="form-control hrs" placeholder="Hours..." value="{{$item->no_of_sections}}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No. </th>
                                    <th>Shop Name</th>
                                    <th>No. of Sections</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

@endsection

@section('after-scripts')
 {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{!! Toastr::message() !!}
@endsection

