<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance & Overtime</title>

    @include('layouts.header.header')
    @yield('after-styles')

    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->

    <div class="row page-titles" style="background: #da251c;">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0 text-light" style="text-transform: uppercase;">{{$shop}}</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item text-light"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-light">Attandance & OT Preview</li>
            </ol>
        </div>


            <div class="col-md-7 col-12 align-self-center d-none d-md-block">
                <div class="d-flex mt-2 justify-content-end">

                    <div class="d-flex ml-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="/attendancepreview" id="btn-add-contact" class="btn btn-info"><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="card-title" style="color: #da251c; text-transform: uppercase;">{{$shop}} for {{\Carbon\Carbon::createFromTimestamp(strtotime($date) )->format('d M Y');}}
                                    <span style="color:margin-left:20px;"></span></h4>
                            </div>
                            <div class="col-6">
                                <h4 class="card-title float-right" style="color: #da251c; text-transform: uppercase;">
                                    <span class="text-primary" style="margin-left:20px;">({{$dayname}})</span>
                                    {{($prodday) ? "Production Scheduled":"No Production"}}
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

                    {!! Form::open(['action'=>['App\Http\Controllers\attendancepreview\AttendancePreviewController@store'], 'method'=>'post']); !!}
                    {{ csrf_field(); }}
                    <input type="hidden" value="{{$date}}" name="date">
                    <input type="hidden" value="{{$shopid}}" name="shop_id">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs1">
                            <thead>
                                <tr>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Mormal Production Hrs</th>
                                    <th>Overtime Hrs</th>
                                    <th>Authorized Hrs</th>
                                    @if ($shopid == 19 || $shopid == 20)
                                        <th>Work Description</th>
                                    @else
                                    <th>OT Intershop Loaning</th>
                                    <th>Normal Hrs Intershop Loaning</th>
                                    @endif
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($staffs  != null)
                                    @foreach ($staffs as $item)
                                        <input type="hidden" name="num" value="{{$num++}}">

                                        <input type="hidden" value="{{$item->staff_id}}" name="staff_id[]">

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
                                        @if($indirectshop != '')
                                            <div class="input-group">
                                                <input type="text" id="direct_{{$num}}" name="direct[]" value="{{$item->direct_hrs}}"
                                                class="form-control hrs" autocomplete="off" placeholder="Direct">

                                                <input type="text" id="indirect_{{$num}}" name="indirect[]" value="{{$item->indirect_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Indirect">
                                            </div>
                                        @else
                                                <input type="hidden" id="direct_{{$num}}" name="direct[]" value="0"
                                                class="form-control hrs" autocomplete="off" placeholder="Direct">

                                                <input type="text" id="indirect_{{$num}}" name="indirect[]" value="{{$item->indirect_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Indirect">
                                        @endif
                                        </td>

                                        <td>
                                        @if($indirectshop != '')
                                            <div class="input-group">
                                                <input type="text" id="overtime_{{$num}}" name="overtime[]" value="{{$item->othours}}"
                                                class="form-control hrs" autocomplete="off" placeholder="Direct" required>

                                                <input type="text" id="indovertime_{{$num}}" name="indovertime[]" value="{{$item->indirect_othours}}"
                                                class="form-control hrs" autocomplete="off" placeholder="indirect" required>
                                            </div>
                                        @else
                                                <input type="hidden" id="overtime_{{$num}}" name="overtime[]" value="0"
                                                class="form-control hrs" autocomplete="off" placeholder="Direct" required>

                                                <input type="text" id="indovertime_{{$num}}" name="indovertime[]" value="{{$item->indirect_othours}}"
                                                class="form-control hrs" autocomplete="off" placeholder="indirect" required>
                                        @endif
                                        </td>
                                        <td>
                                            <input type="text" id="overtime_{{$num}}" name="overtime[]" value="{{$item->auth_othrs}}"
                                            class="form-control hrs" autocomplete="off" placeholder="Direct" required>
                                        </td>

                                        @if ($shopid == 19 || $shopid == 20)
                                            <td>
                                                <input type="hidden" name="shoptoid[]" value="0">
                                                <input type="hidden" name="loaned[]" value="0">
                                                <textarea class="form-control" required placeholder="Work describe here..."
                                                rows="2" cols="50"name="workdescription[]">{{($item->workdescription) ? $item->workdescription : "";}}</textarea>
                                            </td>
                                        @else

                                        <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <input type="hidden" name="workdescription[]" value="0">
                                            <select class="form-control select2 interloanover" id="overshopto_{{$num}}" style="width: 100%;" name="overshoptoid[]">
                                                @if ($item->otshop_loaned_to > 0)
                                                    <option value="{{$item->otshop_loaned_to}}">
                                                        {{ \App\Models\shop\Shop::where('id','=',$item->otshop_loaned_to)->value('report_name'); }}
                                                    </option>
                                                @else
                                                    <option value="0">Recepient shop</option>
                                                @endif

                                                @foreach ($shops as $item1)
                                                    <option value="{{$item1->id}}">{{$item1->report_name}}</option>
                                                @endforeach
                                            </select>
                                            </div>

                                            @if ($item->otshop_loaned_to > 0)
                                                <input type="text" id="loanov_{{$num}}" name="loanov[]" value="{{$item->otloaned_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Hours">
                                            @else
                                                <input type="text" id="loanov_{{$num}}" readonly name="loanov[]" value="{{$item->otloaned_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Hours">
                                            @endif
                                        </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    <select class="form-control select2 interloandir" id="dirshopto_{{$num}}" name="dirshopto[]"
                                                    style="width: 100%;">
                                                        @if ($item->shop_loaned_to > 0)
                                                            <option value="{{$item->shop_loaned_to}}">
                                                                {{ \App\Models\shop\Shop::where('id','=',$item->shop_loaned_to)->value('report_name'); }}
                                                            </option>
                                                        @else
                                                            <option value="0">Choose shop...</option>
                                                        @endif

                                                        @foreach ($shops as $item1)
                                                            <option value="{{$item1->id}}">{{$item1->report_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                    @if ($item->shop_loaned_to > 0)
                                                        <input type="text" autocomplete="off" name="loandir[]" id="loandir_{{$num}}" class="form-control hrs" value="{{$item->loaned_hrs}}">
                                                    @else
                                                        <input type="text" readonly autocomplete="off" name="loandir[]" id="loandir_{{$num}}" class="form-control hrs" placeholder="Hours...">
                                                    @endif
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <input type="hidden" id="disrupt_{{$num}}" name="disrupt[]"value="0"></td>
                                        <!--<td>
                                            <input type="text" id="disrupt_" name="disrupt[]" value=""
                                                 class="form-control hrs" autocomplete="off"  placeholder="Hours">
                                        </td>-->
                                        <td><span  id="total{{$num}}">{{$item->direct_hrs + $item->indirect_hrs + $item->loaned_hrs}}</span> Hrs</td>

                                    </tr>
                                    @endforeach
                                @endif


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Mormal Production Hrs</th>
                                    <th>Overtime Hrs</th>
                                    <th>Authorized Hrs</th>
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

                    <!-- sample modal content -->
                    <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Reveiw Instruction/Concern</h4>
                                <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">×</button>
                            </div>

                            {!! Form::open(['action'=>['App\Http\Controllers\reviewconversation\ReviewConversationController@store'], 'method'=>'post']); !!}
                                {{ csrf_field(); }}
                            <div class="modal-body">
                                <div class="card">
                                    <div class="comment-widgets scrollable position-relative" style="height: 350px;">
                                            <!-- Comment Row -->
                                            @if (count($conversation) > 0)
                                        @foreach ($conversation as $item)
                                        @if ($item->sender == "groupleader")
                                            <div class="d-flex flex-row comment-row p-3">
                                                <div class="p-2"><span class="round text-white d-inline-block text-center"><img src="../assets/images/users/user3.jpg"alt="user" width="50" class="rounded-circle"></span></div>
                                                <div class="comment-text w-100 py-1 py-md-3 pr-md-3 pl-md-4 px-2 bg-light-success">
                                                    <h5>{{$item->user->name}}</h5>
                                                    <p class="mb-1">{{$item->message}}</p>
                                                    <div class="comment-footer">
                                                        <span class="text-muted float-right">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('M d, Y H:i:s')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($item->sender == "teamleader")
                                            <div class="d-flex flex-row comment-row p-3">
                                                <div class="p-2"><span class="round text-white d-inline-block text-center"><img src="../assets/images/users/user3.jpg"alt="user" width="50" class="rounded-circle"></span></div>
                                                <div class="comment-text w-100 py-1 py-md-3 pr-md-3 pl-md-4 px-2 bg-light-info">
                                                    <h5>{{$item->user->name}}</h5>
                                                    <p class="mb-1">{{$item->message}}</p>
                                                    <div class="comment-footer">
                                                        <span class="text-muted float-right">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('M d, Y H:i:s')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @endforeach
                                        @endif
                                            <!-- Comment Row -->
                                            @if($attstatus->status_name != "approved")
                                            <div class="d-flex flex-row comment-row p-3 active">
                                                <div class="p-2"><span class="round text-white d-inline-block text-center"><img src="../assets/images/users/user3.jpg" alt="user" width="50" class="rounded-circle"></span></div>
                                                <div class="comment-text active w-100">
                                                    <h5>{{Auth()->User()->name}}</h5>
                                                    <input type="hidden" name="statusid" value="{{$attstatus->id}}">
                                                    <input type="hidden" name="sender" value="groupleader">
                                                    <input type="hidden" name="status" value="review">
                                                    <textarea name="message" rows="3" required placeholder="Reveiw instructions here..."
                                                    class="form-control"></textarea>
                                                    <div class="comment-footer ">
                                                        <span class="text-muted float-right">{{\Carbon\Carbon::today()->format('M d, Y')}}</span>

                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light"
                                    data-dismiss="modal">Close</button>
                                @if($attstatus->status_name != "approved")
                                <button type="submit" class="btn btn-primary">Send</button>
                                @endif
                            </div>
                            {!! Form::close(); !!}
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

            </div>
        </div>
    </div>

    @include('layouts.footer.script')
    @yield('after-scripts')
    @yield('extra-scripts')
    @section('after-styles')
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

     {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
     {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
    {{ Html::script('assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}

    <script type="text/javascript">
        $(".select2").select2();
    </script>
    {!! Toastr::message() !!}



<script>
$(function () {
    "use strict";
//SUM DIRECT AND INDIRECT
$(document).on('change keyup blur', '.hrs', function () {
    var id_arr = $(this).attr('id');
    var id = id_arr.split('_');
    function calcHours(){
        var directmh = $('#direct_' + id[1]).val();
        var indirectmh = $('#indirect_' + id[1]).val();
        var loanedmh = $('#loaned_' + id[1]).val();
        if (directmh == '') {
            directmh = 0;
        }
        if (indirectmh == '') {
            indirectmh = 0;
        }
        if (loanedmh == '') {
            loanedmh = 0;
        }
        var tot = parseInt(directmh) + parseInt(indirectmh) + parseInt(loanedmh);
        return tot;
    }
    var tot = calcHours();
    $('#total' + id[1]).html(tot);

    if(tot > 12){
        //$('#direct_' + id[1]).val("");
        //$('#indirect_' + id[1]).val("");
        //$('#loaned_' + id[1]).val("");

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Hours entered exceed the expected!',
            footer: 'Please enter correct values.'
          })

        $(this).val("");

        var tot = calcHours();
        $('#total' + id[1]).html(tot);
    }

    //console.log(tot);

    $('#total' + id[1]).html(tot);
});

//INTERSHOP LOANING DIRECT
$(document).on('change', '.interloandir', function () {
    var id_arr = $(this).attr('id');

var id = id_arr.split('_');

    var over = $('#dirshopto_' + id[1]).val();


    if(over == 0){
        $('#loandir_' + id[1]).attr('readonly', true);
    }else{
        $('#loandir_' + id[1]).attr('readonly', false);
    }
});

//INTERSHOP LOANING OVERTIME
$(document).on('change', '.interloanover', function () {
    var id_arr = $(this).attr('id');

        var id = id_arr.split('_');

            var over = $('#overshopto_' + id[1]).val();


            if(over == 0){
                $('#loanov_' + id[1]).attr('readonly',true);
            }else{
                $('#loanov_' + id[1]).attr('readonly',false);
            }
});


//LOADING MODAL MARKING ATTENDANCE
$('.add-loan').on('click', function(event) {
    event.preventDefault();
    $('.add-tsk').show();
    $('.edit-tsk').hide();
    $('#addTaskModal').modal('show');
  });
});

//Select two
$(".select2").select2();
</script>

