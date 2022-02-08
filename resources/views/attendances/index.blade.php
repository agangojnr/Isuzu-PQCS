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
                <li class="breadcrumb-item active text-light">Record Attandance</li>
            </ol>
        </div>


            <div class="col-md-7 col-12 align-self-center d-none d-md-block">
                <div class="d-flex mt-2 justify-content-end">

                    <div class="d-flex ml-2">
                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="/attendance_view" id="btn-add-contact" class="btn btn-info"><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back</a>
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
                            <h4 class="card-title" style="color: #da251c; text-transform: uppercase;">{{$shop}} for {{\Carbon\Carbon::createFromTimestamp(strtotime($date) )->format('d M Y');}}
                                <span style="color:margin-left:20px;">({{$marked}})</span></h4>
                        </div>
                        <div class="col-6">
                            <h4 class="card-title float-right" style="color: #da251c; text-transform: uppercase;">
                                <span class="text-primary" style="margin-left:20px;">({{$dayname}})</span>
                                {{($prodday)? "Production Scheduled":"No Production"}}
                                </h4>
                        </div>
                    </div>
                    @if($shopid == 17)  
                    {!! Form::open(['action'=>['App\Http\Controllers\outsource\OutsourceController@store'], 'method'=>'post']); !!}
                    {{ csrf_field(); }}  
                    <input type="hidden" value="{{$date}}" name="date">
                    <input type="hidden" value="{{$shopid}}" name="shop_id">               
                    <div class="row pb-2">
                        <div class="col-2">
                            <input type="text" class="form-control" name="staffno" autocomplete="off" 
                            required placeholder="Staff No here...">
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" name="staffname" autocomplete="off" 
                             required placeholder="Staff Name here...">
                        </div>
                        
                        @if ($marked == "Not marked")  
                        <div class="col-2">
                                <input type="hidden" value="unmarked" name="button">
                                <button id="btn-add-contact" class="btn btn-lighten-2 text-white" style="background:teal;">
                                <i class="mdi mdi-plus font-16 mr-1"></i>Outsource Staff</button>
                            </div>  
                        @else                    
                            <div class="col-2">
                                <input type="text" class="form-control" name="overtime" autocomplete="off" 
                                required placeholder="Overtime Hrs...">
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control" name="authhrs" autocomplete="off" 
                                required placeholder="Authorised Hrs...">
                            </div>
                            <div class="col-2">
                                <input type="hidden" value="marked" name="button">
                                <button id="btn-add-contact" class="btn btn-lighten-2 text-white" style="background:teal;">
                                <i class="mdi mdi-plus font-16 mr-1"></i>Outsource Staff</button>
                            </div> 
                        
                        @endif  
                                                
                    </div>
                    {!! Form::close(); !!}
                    @endif


                    {!! Form::open(['action'=>['App\Http\Controllers\attendance\AttendanceController@store'], 'method'=>'post']); !!}
                    {{ csrf_field(); }}
                    <input type="hidden" value="{{$date}}" name="date">
                    <input type="hidden" value="{{$shopid}}" name="shop_id">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-select-inputs1">
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Normal Production Hrs</th>
                                    <th>Overtime</th>
                                    <th>Authorised Hrs</th>
                                    @if ($shopid == 19 || $shopid == 20)
                                        <th>OT Work Description</th>
                                    @else
                                        <th>OT Intershop Loaning</th>
                                        <th>Normal Hrs Intershop Loaning</th>
                                    @endif

                                    <th>Total Hrs</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($staffs  != null)

                                    @foreach ($staffs as $item)
                                        <input type="hidden" name="num" value="{{$num++}}">

                                        <!--NOT MARKED-->

                                        @if ($marked == "Not marked")
                                        <input type="hidden" value="{{$item->id}}" name="staff_id[]">
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
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
                                            @if($indirectshop == '')
                                                <input type="hidden" name="direct[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Direct"
                                                id="direct_{{$num}}" value="0" required>

                                                <input type="text" name="indirect[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Indirect"
                                                id="indirect_{{$num}}" value="{{$indirect;}}" required>
                                            @else
                                                @if($item->team_leader == 'yes')
                                                <div class="input-group">
                                                    <input type="text" name="direct[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Direct"
                                                    id="direct_{{$num}}" required>

                                                    <input type="text" name="indirect[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Indirect"
                                                    id="indirect_{{$num}}" value="{{$indirect;}}" required>
                                                </div>
                                                @else
                                                <div class="input-group">
                                                    <input type="text" id="direct_{{$num}}" name="direct[]" value="{{$direct;}}"
                                                    class="form-control normalhrs hrs" autocomplete="off" placeholder="Direct" required>

                                                    <input type="text" name="indirect[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Indirect"
                                                        id="indirect_{{$num}}" required>
                                                </div>
                                                @endif
                                            @endif
                                            </td>
                                            <td>
                                                @if($indirectshop == '')
                                                        <input type="hidden" id="overtime_{{$num}}" name="overtime[]"
                                                    class="form-control hrs" autocomplete="off" placeholder="Direct" value="0" required>

                                                    <input type="text" id="indovertime_{{$num}}" name="indovertime[]"
                                                    class="form-control hrs" autocomplete="off" placeholder="Indirect" required>
                                                @else
                                                    @if($item->team_leader == 'yes')
                                                    <div class="input-group">
                                                        <input type="text" id="overtime_{{$num}}" name="overtime[]"
                                                    class="form-control hrs" autocomplete="off" placeholder="Direct" required>

                                                    <input type="text" id="indovertime_{{$num}}" name="indovertime[]"
                                                    class="form-control hrs" autocomplete="off" placeholder="Indirect" required>
                                                    </div>
                                                    @else
                                                    <div class="input-group">
                                                    <input type="text" id="overtime_{{$num}}" name="overtime[]"
                                                    class="form-control hrs" autocomplete="off" placeholder="Direct" required>

                                                    <input type="text" id="indovertime_{{$num}}" name="indovertime[]"
                                                    class="form-control hrs" autocomplete="off" placeholder="Indirect" required>
                                                    </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" name="authhrs[]" value="{{$overtime}}"
                                                class="form-control" autocomplete="off" placeholder="Hours" required>
                                            </td>


                                            @if ($shopid == 19 || $shopid == 20)
                                            <td>
                                                <input type="hidden" id="overshopto_{{$num}}" name="overshopto[]" value="0">
                                                <input type="hidden" id="dirshopto_{{$num}}" name="dirshopto[]" value="0">
                                                <input type="hidden" id="loanov_{{$num}}" name="loanov[]" value="0">
                                                <input type="hidden" name="loandir[]" id="loandir_{{$num}}"  value="0">
                                                <textarea class="form-control" required placeholder="Work describe here..."
                                                rows="2" cols="50" name="workdescription[]"></textarea>
                                            </td>
                                            @else

                                            <td>
                                                <input type="hidden" name="workdescription[]" value="0">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="input-group mb-3">
                                                      <div class="input-group-prepend">
                                                        <select class="select2 interloanover" id="overshopto_{{$num}}" name="overshopto[]"
                                                        style="width: 100%;">
                                                            @if ($item->shop_loaned_to > 0)
                                                                <option value="{{$item->shop_loaned_to}}">
                                                                    {{ \App\Models\shop\Shop::where('id','=',$item->otshop_loaned_to)->value('report_name'); }}
                                                                </option>
                                                            @else
                                                                <option value="0">Choose shop...</option>
                                                            @endif

                                                            @foreach ($shops as $item1)
                                                                <option value="{{$item1->id}}">{{$item1->report_name}}</option>
                                                            @endforeach
                                                        </select>
                                                      </div>
                                                          <input type="text" readonly autocomplete="off"  id="loanov_{{$num}}" name="loanov[]" class="form-control hrs" placeholder="Hours...">
                                                      </div>
                                                  </div>

                                            </td>

                                            <td>
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="input-group mb-3">
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
                                                          <input type="text" readonly autocomplete="off" name="loandir[]" id="loandir_{{$num}}" class="form-control hrs" placeholder="Hours...">
                                                      </div>
                                                  </div>
                                            </td>
                                            @endif

                                            <td><span  id="total{{$num}}">{{$item->direct_hrs + $item->indirect_hrs + $item->loaned_hrs}}</span> Hrs</td>

                                        </tr>


                                        @else




                                        <!--MARKED-->
                                        <input type="hidden" value="{{$item->staff_id}}" name="staff_id[]">
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
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
                                        @if($indirectshop == '')
                                            <input type="hidden" name="direct[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Direct"
                                            id="direct_{{$num}}" value="0" required>

                                            <input type="text" name="indirect[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Indirect"
                                            id="indirect_{{$num}}" value="{{$item->indirect_hrs}}" required>
                                        @else
                                            @if($item->employee->team_leader == 'yes')
                                            <div class="input-group">
                                                <input type="text" name="direct[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Direct hrs.."
                                                id="direct_{{$num}}" value="{{$item->direct_hrs}}" required>

                                                <input type="text" name="indirect[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Indirect hrs.."
                                                id="indirect_{{$num}}" value="{{$item->indirect_hrs}}" required>
                                              </div>

                                            @else
                                            <div class="input-group">
                                                <input type="text" id="direct_{{$num}}" name="direct[]" value="{{$item->direct_hrs}}"
                                                class="form-control normalhrs hrs" autocomplete="off" placeholder="Hours" required>

                                                <input type="text" name="indirect[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Indirect hrs.."
                                                    id="indirect_{{$num}}" value="{{$item->indirect_hrs}}" required>
                                            </div>
                                            @endif
                                        @endif
                                            </td>
                                            <td>
                                        @if($indirectshop == '')
                                                <input type="hidden" id="overtime_{{$num}}" name="overtime[]"
                                            class="form-control hrs" autocomplete="off" placeholder="Direct" value="0" required>

                                            <input type="text" id="indovertime_{{$num}}" name="indovertime[]" value="{{$item->indirect_othours}}"
                                            class="form-control hrs" autocomplete="off" placeholder="Indirect" required>
                                        @else
                                                @if($item->employee->team_leader == 'yes')
                                                <div class="input-group">
                                                    <input type="text" id="overtime_{{$num}}" name="overtime[]" value="{{$item->othours}}"
                                                class="form-control hrs" autocomplete="off" placeholder="Direct" required>

                                                <input type="text" id="indovertime_{{$num}}" name="indovertime[]" value="{{$item->indirect_othours}}"
                                                class="form-control hrs" autocomplete="off" placeholder="indirect" required>
                                                </div>
                                                @else
                                                <div class="input-group">
                                                <input type="text" id="overtime_{{$num}}" name="overtime[]" value="{{$item->othours}}"
                                                class="form-control hrs" autocomplete="off" placeholder="Direct" required>

                                                <input type="text" id="indovertime_{{$num}}" name="indovertime[]" value="{{$item->indirect_othours}}"
                                                class="form-control hrs" autocomplete="off" placeholder="indirect" required>
                                                </div>
                                                @endif
                                        @endif
                                            </td>
                                            <td>
                                                <input type="text" name="authhrs[]" value="{{$item->auth_othrs}}"
                                                class="form-control" autocomplete="off" placeholder="Hours" required>
                                            </td>

                                            @if ($shopid == 19 || $shopid == 20)
                                            <td>
                                                <input type="hidden" id="overshopto_{{$num}}" name="overshopto[]" value="0">
                                                <input type="hidden" id="dirshopto_{{$num}}" name="dirshopto[]" value="0">
                                                <input type="hidden" id="loanov_{{$num}}" name="loanov[]" value="0">
                                                <input type="hidden" name="loandir[]" id="loandir_{{$num}}"  value="0">
                                                <textarea class="form-control" required placeholder="Work describe here..."
                                                rows="2" cols="50"name="workdescription[]">{{($item->workdescription) ? $item->workdescription : "";}}</textarea>
                                            </td>
                                            @else

                                            <td>
                                                <input type="hidden" name="workdescription[]" value="0">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="input-group mb-3">
                                                      <div class="input-group-prepend">
                                                        <select class="select2 interloanover" id="overshopto_{{$num}}" name="overshopto[]"
                                                        style="width: 100%;">
                                                            @if ($item->otshop_loaned_to > 0)
                                                                <option value="{{$item->otshop_loaned_to}}">
                                                                    {{ \App\Models\shop\Shop::where('id','=',$item->otshop_loaned_to)->value('report_name'); }}
                                                                </option>
                                                            @else
                                                                <option value="0">Choose shop...</option>
                                                            @endif

                                                            @foreach ($shops as $item1)
                                                                <option value="{{$item1->id}}">{{$item1->report_name}}</option>
                                                            @endforeach
                                                        </select>
                                                      </div>
                                                          <input type="text" {{($item->otloaned_hrs > 0) ? "" : "readonly";}} autocomplete="off" id="loanov_{{$num}}" name="loanov[]" class="form-control hrs" placeholder="Hours..."
                                                          value="{{$item->otloaned_hrs}}">
                                                      </div>
                                                  </div>

                                            </td>
                                            <td>
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="input-group mb-3">
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

                                                          <input type="text" autocomplete="off" {{($item->loaned_hrs > 0) ? "" : "readonly";}} id="loandir_{{$num}}"
                                                          name="loandir[]" class="form-control hrs" placeholder="Hours..." value="{{$item->loaned_hrs}}">
                                                      </div>
                                                  </div>
                                            </td>
                                            @endif

                                            <td><span  id="total{{$num}}">{{$item->direct_hrs + $item->indirect_hrs + $item->othours + $item->otloaned_hrs + $item->loaned_hrs}}</span> Hrs</td>

                                        </tr>
                                        @endif

                                    @endforeach
                                @endif


                                @if(count($outsourcestaffs) > 0)
                                    
                                    @foreach($outsourcestaffs as $item1)
                                    @if($marked == "Not marked")
                                     <tr>
                                        <td>{{"#"}}</td>
                                        <input type="hidden" value="{{$item1->id}}" name="staff_id[]">
                                            <td>{{$item1->staff_no}}</td>
                                            <td>
                                                {{$item1->staff_name}}
                                            </td>
                                            <td>
                                                <input type="hidden" name="direct[]" class="form-control normalhrs hrs" autocomplete="off" placeholder="Direct"
                                                id="direct_{{$num}}" value="0" required>
                                                <input type="hidden" name="indirect[]" value="0" id="indirect_{{$num}}" >                                            
                                            </td>
                                            <td>
                                                <input type="hidden" id="overtime_{{$num}}" name="overtime[]" value="0"> 

                                                <input type="text" id="indovertime_{{$num}}" name="indovertime[]"
                                                class="form-control hrs" autocomplete="off" placeholder="Direct" required>

                                                                                                  
                                                   
                                            </td>
                                            <td>
                                                <input type="text" name="authhrs[]" value="{{$overtime}}"
                                                class="form-control" autocomplete="off" placeholder="Hours" required>
                                            </td>
                                            <td>
                                                <a href="{{route('outsource.destroy', $item1->id)}}" class="btn btn-danger deloutsource">Delete</a>
                                                <input type="hidden" id="overshopto_{{$num}}" name="overshopto[]" value="0">
                                                <input type="hidden" id="dirshopto_{{$num}}" name="dirshopto[]" value="0">
                                                <input type="hidden" id="loanov_{{$num}}" name="loanov[]" value="0">
                                                <input type="hidden" name="loandir[]" id="loandir_{{$num}}"  value="0">
                                                <input type="hidden" name="workdescription[]"  value="0">
                                            </td>
                                            <td>
                                                <input type="hidden" name="workdescription[]" value="0">
                                                <input type="hidden" name="overshopto[]" value="0">
                                                <input type="hidden" value="0" name="loanov[]" >                                           
                                            </td>
                                            <td>
                                            </td>

                                        </tr>
                                        @else
                                        
                                        @endif
                                    @endforeach
                                    
                                    
                                @endif


                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>No.</td>
                                    <th>Staff No</th>
                                    <th>Staff Name</th>
                                    <th>Normal Production Hrs</th>
                                    <th>Overtime</th>
                                    <th>Authorised Hrs</th>
                                    @if ($shopid == 19 || $shopid == 20)
                                        <th>Work Description</th>
                                    @else
                                        <th>OT Intershop Loaning</th>
                                        <th>Normal Hrs Intershop Loaning</th>
                                    @endif

                                    <th>Total Hrs</th>
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
                                @if ($attstatus == "" || $attstatus->status_name == "saved")

                                    <div class="d-flex ml-2">
                                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                                <button name="button" value="submitted" id="btn-add-contact" class="btn btn-primary"><i class="mdi mdi-content-save-all font-16 mr-1"></i>Save & Submit</button>
                                        </div>
                                    </div>

                                    <div class="d-flex ml-2">
                                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button value="saved" name="button" id="btn-add-contact" class="btn btn-lighten-2 text-white" style="background:teal;">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Save</button>
                                        </div>
                                    </div>
                                @elseif($attstatus->status_name == "review" || $attstatus->status_name == "savedreveiw")
                                <div class="d-flex ml-2">
                                    <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button type="button" id="btn-add-contact" data-toggle="modal"
                                            data-target="#myModal" class="btn btn-lighten-2 text-white btn-warning">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Check Response & Resubmit</button>
                                    </div>
                                </div>

                                    <div class="d-flex ml-2">
                                        <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button type="submit" value="savedreveiw" name="button" id="btn-add-contact" class="btn btn-lighten-2 text-white" style="background:teal;">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Save</button>
                                        </div>
                                    </div>

                                @elseif($attstatus->status_name == "submitted")
                                <h3>Awaiting Approval...</h3>
                                @elseif($attstatus->status_name == "approved")
                                <h3>Approved</h3>
                                <div class="d-flex ml-2">
                                    <div class="text-right d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                                            <button type="button" id="btn-add-contact" data-toggle="modal"
                                            data-target="#myModal" class="btn btn-lighten-2 text-white btn-warning">
                                                <i class="mdi mdi-content-save-all font-16 mr-1"></i>Conversation</button>
                                    </div>
                                </div>
                                @endif

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
                                                    @if($attstatus=="" || $attstatus->status_name != "approved")
                                                    <div class="d-flex flex-row comment-row p-3 active">
                                                        <div class="p-2"><span class="round text-white d-inline-block text-center"><img src="../assets/images/users/user3.jpg" alt="user" width="50" class="rounded-circle"></span></div>
                                                        <div class="comment-text active w-100">
                                                            <h5>{{Auth()->User()->name}}</h5>
                                                            <input type="hidden" name="statusid" value="{{($attstatus) ? $attstatus->id:'';}}">
                                                            <input type="hidden" name="sender" value="teamleader">
                                                            <input type="hidden" name="status" value="submitted">
                                                            <textarea name="message" rows="3" placeholder="Reveiw instructions here..."
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
                                        @if($attstatus=="" || $attstatus->status_name != "approved")
                                        <button type="submit" name="button" value="reveiwsubmitted" class="btn btn-primary">Send</button>
                                        @endif
                                    </div>

                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->


                        </div>
                    </div>

                {!! Form::close(); !!}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer.script')
    @yield('after-scripts')
    @yield('extra-scripts')
    @section('after-styles')
    {{ Html::script('dist/js/pages/datatable/datatable-basic.init.js') }}
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
$(document).on('change keyup blur', '.normalhrs', function () {
    var id_arr = $(this).attr('id');

        var id = id_arr.split('_');

            var directmh = $('#direct_' + id[1]).val();
            var indirectmh = $('#indirect_' + id[1]).val();
            if (directmh == '') {
                directmh = 0;
            }
            if (indirectmh == '') {
                indirectmh = 0;
            }

            var tot = parseInt(directmh) + parseInt(indirectmh);
            if(tot > 8){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hours exceeds the normal years (8 hours)',
                    footer: 'Please enter correct values.'
                })

                $(this).val("");
            }
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

//CHECKING TOTAL
$(document).on('change keyup blur', '.hrs', function () {
    var id_arr = $(this).attr('id');

        var id = id_arr.split('_');

            var directmh = $('#direct_' + id[1]).val();
            var indirectmh = $('#indirect_' + id[1]).val();
            var overtime = $('#overtime_' + id[1]).val();
            var indovertime = $('#indovertime_' + id[1]).val();
            var loandir = $('#loandir_' + id[1]).val();
            var loanov = $('#loanov_' + id[1]).val();
            if (directmh == '') {
                directmh = 0;
            }
            if (indirectmh == '') {
                indirectmh = 0;
            }
            if (overtime == '') {
                overtime = 0;
            }
            if (indovertime == '') {
                indovertime = 0;
            }
            if (loanov == '') {
                loanov = 0;
            }
            if (loandir == '') {
                loandir = 0;
            }

            var tot = parseInt(directmh) + parseInt(indirectmh)+ parseInt(overtime)+ parseInt(indovertime)+ parseInt(loanov)+ parseInt(loandir);
            $('#total' + id[1]).html(tot);
            var limit = parseInt("{{round($hrslimit)}}");
            //alert(limit);
            if(tot > limit){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hours exceeds the acceptable hous of work.',
                    footer: 'Please enter correct values.'
                })

                $(this).val("");

            }

    });
});


$(document).on('click', '.deloutsource', function(e){
            e.preventDefault();

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You will not be able to recover this staff details!",
                        type: "warning",
                      //buttons: true,

                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Delete!",
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
                                        window.location = "attendance_view";
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                }
                            });
                        }
                    });
        });

</script>
