
@extends('layouts.app')

@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">GCA SCORE TARGET</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">SETTING GCA TARGET</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">

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
                    {{ Form::open(['route' => 'gcatarget', 'method' => 'GET'])}}
                            {{ csrf_field(); }}
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4"><h5>DPV</h5></div>
                        <div class="col-sm-4 float-right"><h5>WDPV</h5></div>
                        <div class="col-sm-2"></div>

                        <div class="col-sm-2"><h4 class="float-right">CV:</h4></div>
                        <div class="col-sm-4 mb-2">
                            {{ Form::text('cvdpv', null, ['class' => 'form-control', 'placeholder' => 'CV DPV target here...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                        <div class="col-sm-4 mb-2">
                            {{ Form::text('cvwdpv', null, ['class' => 'form-control', 'placeholder' => 'CV WDPV target here...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                        <div class="col-sm-2"></div>

                        <div class="col-sm-2 mb-2"><h4 class="float-right">LCV:</h4></div>
                        <div class="col-sm-4 mb-2">
                            {{ Form::text('lcvdpv', null, ['class' => 'form-control', 'placeholder' => 'LCV DPV target here...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                        <div class="col-sm-4 mb-2">
                            {{ Form::text('lcvwdpv', null, ['class' => 'form-control', 'placeholder' => 'LCV WDPV target here...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                        <div class="col-sm-2"></div>

                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <div class='input-group'>
                                <select name="yearquarter" id="" class="form-control select2" required="required" style="width:100%;">
                                    <option value="">Choose Year Quarter</option>
                                    @for ($i = 0; $i < count($years); $i++)
                                        @for ($n = 0; $n < count($quarters); $n++)
                                            <option value="{{$years[$i]}}-{{$n+1}}">{{$years[$i]}} - {{$quarters[$n]}}</option>
                                        @endfor
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                                <button type="submit" style="width: 80%;" class="btn btn-primary btn-md">Save Target</button>
                        </div>
                    </div>
                    {!! Form::close(); !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <div class="table-responsive">
                        <table class="table">
                            <thead class="bg-primary text-white">
                                <tr>
                                        <th>#</th>
                                        <th>YEAR</th>
                                        <th>QUARTER</th>
                                        <th>CV DPV TARGET</th>
                                        <th>CV WDPV TARGET</th>
                                        <th>LCV DPV TARGET</th>
                                        <th>LCV WDPV TARGET</th>
                                        <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($gcatargts != "")
                                    @foreach ($gcatargts as $item)
                                    <tr>
                                        <td>{{'#'}}</td>
                                        <td>{{$item->year}}</td>
                                        <td>{{$item->yearquarter}}</td>
                                        <td>{{$item->cvdpv}}</td>
                                        <td>{{$item->cvwdpv}}</td>
                                        <td>{{$item->lcvdpv}}</td>
                                        <td>{{$item->lcvwdpv}}</td>
                                        <td>
                                            <a href="{{route('destroygcatag', $item->id)}}"
                                                class="btn btn-outline-danger btn-sm pull-right deltarget"><i
                                                class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>

                                    @endforeach
                                @endif

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
    {{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
    {{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
    <script>
         $(".select2").select2();
    </script>
     <script>


        $(document).on('click', '.deltarget', function(e){

        e.preventDefault();
                var txt = "You will delete the target from the system!";
                var btntxt = "Yes, Delete!";
                var color = "#DD6B55";

                Swal.fire({
                    title: "Are you sure?",
                    text: txt,
                    type: "warning",
                  //buttons: true,
                showCancelButton: true,
                confirmButtonColor: color,
                confirmButtonText: btntxt,
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false

                  //dangerMode: true,
                }).then((result) => {
                    if (Object.values(result) == 'true') {
                        var href = $(this).attr('href');
                        $.ajax({
                            method: "POST",
                            url: href,
                            dataType: "json",
                              data:{

                             '_token': '{{ csrf_token() }}',
                                   },
                            success: function(result){
                                if(result.success == true){
                                    toastr.success(result.msg);
                                    //routingquery.ajax.reload();
                                    window.location = "gcatarget";
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
                </script>



    {!! Toastr::message() !!}
    @endsection

