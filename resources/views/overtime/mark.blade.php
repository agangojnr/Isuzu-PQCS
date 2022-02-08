
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
                            <a href="{{route('overtime.index')}}" id="btn-add-contact" class="btn btn-info"><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
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

                    <h4 class="card-title" style="color: #da251c;">{{$shop}} for {{\Carbon\Carbon::createFromTimestamp(strtotime($date) )->format('d M Y');}}
                    <span style="color: green; margin-left:20px;">({{$marked}})</span></h4>

                    {!! Form::open(['action'=>['App\Http\Controllers\overtime\OvertimeController@store'], 'method'=>'post']); !!}
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
                                    <th>Authorised Hours</th>
                                    <th>Recepient Shop</th>
                                    <th>Loaned Hours</th>
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($staffs  != null)

                                    @foreach ($staffs as $item)
                                        <input type="hidden" name="num" value="{{$num++}}">

                                        <input type="hidden" value="{{$item->id}}" name="staff_id[]">

                                        <tr>
                                        <td>{{$item->staff_no}}</td>
                                        <td>
                                        @if($item->team_leader == 'yes')
                                            <span style="color:#da251c;">
                                                {{$item->staff_name}} (TeamLeader)</span>
                                        @else
                                            {{$item->staff_name}}

                                        @endif
                                        </td>
                                        <td>
                                            @if($item->team_leader == 'yes')
                                                <div class="input-group">
                                                    <input type="text" id="overtime_{{$num}}" name="overtime[]" value="{{$item->othours}}"
                                                    class="form-control othrs" autocomplete="off" placeholder="Hours" required>

                                                    <input type="text" id="indovertime_{{$num}}" name="indovertime[]" value="{{$item->indirect_othours}}"
                                                    class="form-control hrs" autocomplete="off" placeholder="Hours" required>
                                                </div>
                                                @else
                                                    <input type="text" id="overtime_{{$num}}" name="overtime[]" value="{{$item->othours}}"
                                                    class="form-control hrs" autocomplete="off" placeholder="Hours" required>

                                                    <input type="hidden" class="normalhrs hrs" name="indovertime[]" id="indovertime_{{$num}}" value="0">
                                                @endif
                                        </td>
                                        <td>
                                            <input type="text" id="authorised_{{$num}}" name="authorised[]" value="{{($item->othours)?$item->othours:$overtime;}}"
                                            class="form-control othrs" autocomplete="off" placeholder="Hours" required>

                                        </td>

                                        <td>
                                            <select class="form-control select2 recshop" id="recshop_{{$num}}" style="width: 100%;" name="shoptoid[]">
                                                @if ($item->shop_loaned_to > 0)
                                                    <option value="{{$item->shop_loaned_to}}">
                                                        {{ \App\Models\shop\Shop::where('id','=',$item->shop_loaned_to)->value('report_name'); }}
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
                                            @if ($item->shop_loaned_to > 0)
                                                <input type="text" id="loaned_{{$num}}" name="loaned[]" value="{{$item->loaned_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Hours">
                                            @else
                                                <input type="text" id="loaned_{{$num}}" readonly="readonly" name="loaned[]" value="{{$item->loaned_hrs}}"
                                                class="form-control hrs" autocomplete="off"  placeholder="Hours">
                                            @endif
                                        </td>
                                        <td><span  id="total{{$num}}">{{$item->othours}}</span> Hrs</td>
                                    </tr>
                                    @endforeach
                                @endif


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Overtime Hours</th>
                                    <th>Recepient Shop</th>
                                    <th>Loaned Hours</th>
                                    <th>Total Hours</th>

                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="col-md-7 col-12 align-self-center d-none d-md-block">
                        <div class="d-flex mt-2 justify-content-end">

                            <div class="d-flex ml-2">
                                <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                    <button id="btn-add-contact" class="btn btn-{{$btncolor}}"><i class="mdi mdi-content-save-all font-16 mr-1"></i> {{$btntext}} Overtime</button>
                            </div>
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
             <h4 class="modal-title" id="myModalLabel">Reveiw Instuction/Concern</h4>
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
                             <div class="comment-text w-100 py-1 py-md-3 pr-md-3 pl-md-4 px-2">
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
                             <div class="comment-text w-100 py-1 py-md-3 pr-md-3 pl-md-4 px-2">
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
@endsection
@section('after-styles')
{{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
{{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
@endsection

@section('after-scripts')
{{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
 {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
{!! Toastr::message() !!}

<script type="text/javascript">
    $(".select2").select2();
</script>

<script>
//SUM DIRECT AND INDIRECT
$(document).on('change keyup blur', '.othrs', function () { //console.log("Yess");

    var id_arr = $(this).attr('id');
    var id = id_arr.split('_');
    function calcHours(){
        var overtime = $('#overtime_' + id[1]).val();
        if (overtime == '') {
            overtime = 0;
        }

        var tot = parseInt(overtime);
        return tot;
    }
    var tot = calcHours();
    $('#total' + id[1]).html(tot);
    var limit = "<?php echo $hrslimit; ?>";
    if(tot > limit){
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

    $('#total' + id[1]).html(tot);

});

</script>
