

@extends('layouts.app')
@section('title','Set Targets')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">PRODUCTION TARGETS</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">SET TARGETS</li>
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

        <div class="col-sm-8 col-md-12">



            <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <h4>SET PRODUCTION TARGETS</h4>
                            <form action="{{ route('savetargets') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                            <div class="row">

                                <div class="col-4"></div>
                                <label for="description" class="col-sm-2 text-right control-label col-form-label">Target Date Range:</label>
                                <div class="col-6">
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
                            </div>
                            <hr>
                            <div class="form-group col-md-12">
                            <div class="form-group row">
                                <label for="description" class="col-sm-3 text-left control-label col-form-label">Plant Targets:</label>
                                <div class="col-sm-4">
                                    {{Form::text('pefficiency', '', ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Efficiency target here',
                                    'autocomplete'=>'off','required'=>'required'])}}
                                </div>
                                <div class="col-sm-4">
                                    {{Form::text('pabsentieesm', '', ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Absentieesm target here',
                                    'autocomplete'=>'off','required'=>'required'])}}
                                </div>
                                <div class="col-sm-4">
                                    {{Form::hidden('ptlavailability', 00, ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'T/L Availability target here',
                                    'autocomplete'=>'off','required'=>'required'])}}
                                </div>
                            </div>
                            </div>

                            <hr>

                                <button class="btn btn-success" id="savetarget">Save Targets</button>
                            </form>

                        </div>
                        </div>
                    </div>

                    <hr>

                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>YEAR</th>
                                                <th>QUARTER</th>
                                                <th>EFFICIENCY TARGET</th>
                                                <th>ABSENTIEESM TARGET</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($thisyeartargets))
                                                @foreach ($thisyeartargets as $pl)
                                                <tr>
                                                    <td>{{'#'}}</td>
                                                    <td>{{$pl->year}}</td>
                                                    <td>{{$pl->yearquarter}}</td>
                                                    <td>{{$pl->efficiency}}</td>
                                                    <td>{{$pl->absentieesm}}</td>
                                                    <td>
                                                        <a href="{{route('destroytag', $pl->id)}}"
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
        {!! Toastr::message() !!}


        <script type="text/javascript">
            $(".select2").select2();

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
                                window.location = "createtargets";
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



