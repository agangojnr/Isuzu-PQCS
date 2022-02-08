
@extends('layouts.app')
@section('title','Overtime')
@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0" style="color: #da251c;">{{$shop}}</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Record Overtime</li>
            </ol>
        </div>


            <div class="col-md-7 col-12 align-self-center d-none d-md-block">
                <div class="d-flex mt-2 justify-content-end">

                    <div class="d-flex ml-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="{{route('otpreview')}}" id="btn-add-contact" class="btn btn-info"><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
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
    <!-- Individual column searching (select inputs) -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="card-title" style="color: #da251c;"><span style="text-transform: uppercase;">
                                {{$shop}}</span> Overtime on {{$date}}
                            </h3>
                        </div>
                        <div class="col-6">
                            <h4 class="card-title float-right" style="color: #da251c; text-transform: uppercase;">
                                <span class="text-primary" style="margin-left:20px;">({{$dayname}})</span>
                                {{($prodday)? "Production Scheduled":"No Production"}}
                                </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">


                        </div>
                        <div class="col-6">
                        @if ($loanee)
                            {{ Form::open(['route' => 'checkloaned', 'method' => 'GET'])}}
                                @csrf
                                <input type="hidden" name="shopid" value="{{$shopid}}">
                                <input type="hidden" name="date" value="{{$date}}">
                                <button class="btn btn-{{$color1}} mb-3 float-right">
                                <i class="mdi mdi-{{$icon1}} font-16 mr-1"></i>{{$text1}}</button>
                            {{ Form::close() }}
                        @endif

                        </div>
                    </div>

                    {!! Form::open(['action'=>['App\Http\Controllers\overtime\OvertimeController@previewstore'], 'method'=>'post']); !!}
                    {{ csrf_field(); }}
                    <input type="hidden" value="{{$date}}" name="date">
                    <input type="hidden" value="{{$shopid}}" name="shop_id">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs1">
                            <thead>
                                <tr>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Overtime Hours</th>
                                    <th>Authorised OT Hrs</th>
                                    @if ($shopid == 19 || $shopid == 20)
                                        <th>Work Description</th>
                                    @else
                                        <th>Recepient Shop</th>
                                        <th>Loaned Hours</th>
                                    @endif
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($staffs  != null)

                                    @foreach ($staffs as $item)
                                        <input type="hidden" name="num" value="{{$num++}}">

                                        <input type="hidden" value="{{$item->id}}" name="staff_id[]">

                                        <tr>
                                        <td>{{$item->employee->staff_no}}</td>
                                        <td>
                                        @if($item->employee->team_leader == 'yes')
                                            <span style="color:#da251c;">
                                                {{$item->employee->staff_name}} (TeamLeader)</span>
                                        @else
                                            {{$item->employee->staff_name}}

                                        @endif
                                        </td>
                                        <td>
                                            <input type="text" id="indovertime_{{$num}}" name="indovertime[]" value="{{$item->indirect_othours}}"
                                            class="form-control hrs" autocomplete="off" placeholder="Hours" required>


                                            <input type="hidden" id="overtime_{{$num}}" name="overtime[]" value="{{$item->othours}}"
                                            class="form-control hrs" autocomplete="off" placeholder="Hours" required>

                                            <!--<input type="hidden" class="normalhrs hrs" name="indovertime[]" id="indovertime_{{$num}}" value="0">-->


                                        </td>
                                        <td>
                                            <input type="text" name="authhrs[]" value="{{$item->auth_othrs}}"
                                                class="form-control" autocomplete="off" placeholder="Hours" required>
                                        </td>
                                        @if ($shopid == 19 || $shopid == 20)
                                            <td>
                                                <input type="hidden" name="shoptoid[]" id="recshop_{{$num}}"  value="0">
                                                <input type="hidden" name="loaned[]" id="loaned_{{$num}}"  value="0">
                                                <textarea class="form-control" required placeholder="Work describe here..."
                                                rows="2" cols="50" name="workdescription[]">{{($item->workdescription) ? $item->workdescription : "";}}</textarea>
                                            </td>
                                        @else
                                        <td>
                                            <input type="hidden" name="workdescription[]"  value="0">
                                            <select class="form-control select2 recshop" id="recshop_{{$num}}" style="width: 100%;" name="shoptoid[]">
                                                @if ($item->otshop_loaned_to > 0)
                                                    <option value="{{$item->shop_loaned_to}}">
                                                        {{ \App\Models\shop\Shop::where('id','=',$item->otshop_loaned_to)->value('report_name'); }}
                                                    </option>
                                                @else
                                                    <option value="0">Recepient shop</option>
                                                @endif

                                                @foreach ($shops as $item1)
                                                    <option value="{{$item1->id}}">{{$item1->report_name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            @if ($item->otshop_loaned_to > 0)
                                                <input type="text" id="loaned_{{$num}}" name="loaned[]" value="{{$item->otloaned_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Hours">
                                            @else
                                                <input type="text" id="loaned_{{$num}}" readonly="readonly" name="loaned[]" value="{{$item->otloaned_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Hours">
                                            @endif
                                        </td>
                                        @endif


                                        <td><span  id="total{{$num}}">{{$item->othours + $item->otloaned_hrs}}</span> Hrs</td>

                                    </tr>
                                    @endforeach
                                @endif


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Overtime Hours</th>
                                    <th>Authorised OT Hrs</th>
                                    @if ($shopid == 19 || $shopid == 20)
                                        <th>Work Description</th>
                                    @else
                                        <th>Recepient Shop</th>
                                        <th>Loaned Hours</th>
                                    @endif
                                    <th>Total Hours</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12 align-self-center d-none d-md-block">
                                <div class="form-input">
                                    <textarea name="workdescriptionall" rows="3" required placeholder="Work description here..."
                                    class="form-control">{{($attstatus != "") ? $attstatus->workdescription :"" ;}}</textarea>
                                </div>
                        </div>
                        <div class="col-md-6 col-12 align-self-center d-none d-md-block">
                            <div class="d-flex mt-2 justify-content-end">
                                @if ($attstatus->status_name == "submitted")

                                <div class="d-flex ml-2">
                                    <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button name="button" value="approved" id="btn-add-contact" class="btn btn-lighten-2 text-white" style="background:rgb(98, 128, 0);">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Approve</button>
                                    </div>
                                </div>
                                <div class="d-flex ml-2">
                                    <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button type="button" id="btn-add-contact" data-toggle="modal"
                                            data-target="#myModal" class="btn btn-lighten-2 text-white btn-warning">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Send to Reveiw</button>

                                    </div>
                                </div>
                                @elseif($attstatus->status_name == "approved")
                                <h2 class="mr-5 text-success"><i class="mdi mdi-check"></i> Approved</h2>
                                <div class="d-flex ml-2">
                                    <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button type="button" id="btn-add-contact" data-toggle="modal"
                                            data-target="#myModal" class="btn btn-lighten-2 text-white btn-warning">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Conversation</button>
                                    </div>
                                </div>
                                <div class="d-flex ml-2">
                                    <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button name="button" value="updated" id="btn-add-contact" class="btn btn-lighten-2 text-white btn-warning">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Update</button>
                                    </div>
                                </div>
                                @elseif($attstatus->status_name == "review" || $attstatus->status_name == "savedreveiw")
                                <h2 class="mr-5 text-primary">Sent for review!</h2>
                                @endif

                            </div>
                        </div>
                    </div>

                {!! Form::close(); !!}
                </div>
            </div>
        </div>
    </div>
    {!! Toastr::message() !!}
    @endsection

    @section('after-styles')
        {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
        {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
         {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    @endsection

    @section('after-scripts')
    {{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
     {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}

    <script type="text/javascript">
        $(".select2").select2();
    </script>
