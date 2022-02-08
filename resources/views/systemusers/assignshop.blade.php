
@extends('layouts.app')
@section('title','Asign Shop')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">ASSIGN USER TO A SECTION</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Assign Section</li>
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
                    <h3 class="mb-0">ALLOCATE SECTIONS</h3>

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
                                    <th>Role</th>
                                    <th>Section</th>

                                    <th class="w-25">Action</th>
                                    <th>Status</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>
                                        @if(!empty($item->getRoleNames()))
                                          @foreach($item->getRoleNames() as $v)
                                             <label class="badge badge-success">{{ $v }}</label>
                                          @endforeach
                                        @endif
                                    </td>
                                    <td>{{($item->section == 'ALL') ? "ALL" : \App\Models\shop\Shop::where('id','=',$item->section)->value('report_name');}}
                                    </td>
                                    {{ Form::open(['route' => 'assignsection', 'method' => 'GET'])}}
                                    {{ csrf_field(); }}
                                    <td>
                                        <div class="row">
                                            <div class="col-8">

                                              <select class="select2 interloanover form-control" name="shopid"
                                              style="width: 100%;">
                                                  @if ($item->section == 'ALL')
                                                  <option value="ALL">All Sections</option>
                                                  @elseif($item->section > 0)
                                                      <option value="{{$item->section}}">
                                                          {{ \App\Models\shop\Shop::where('id','=',$item->section)->value('report_name'); }}
                                                      </option>
                                                  @else
                                                      <option value="">Choose section...</option>

                                                  @endif
                                                  <option value="ALL">All Sections</option>
                                                  @foreach ($shops as $item1)
                                                      <option value="{{$item1->id}}">{{$item1->report_name}}</option>
                                                  @endforeach
                                                  <option value="0">No Section</option>
                                              </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="hidden" name="userid" value="{{$item->id}}">
                                                @if ($item->section == '0')
                                                    <button class="btn btn-xs btn-warning float-right">
                                                         Assign</button>
                                                @else
                                                    <button style="background-color:teal; color:white;"
                                                        class="btn btn-xs  float-right" ><i class="glyphicon glyphicon-edit"></i>Update</button>
                                                @endif
                                        </div>
                                        </div>
                                    </td>
                                    {!! Form::close(); !!}
                                    <td>{{$item->status}}</td>
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
    $(".select2").select2();
</script>
<script type="text/javascript">

            /*$(document).on('click', '.assignsection', function(e){
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
            });*/


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
