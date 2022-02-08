
@extends('layouts.app')
@section('title','Build GCA Score')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">GCA Score</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Build GCA Score</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>THIS MONTH</small></h6>
                        <h4 class="mt-0 text-info">40</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="monthchart"></div>
                    </div>
                </div>
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>LAST MONTH</small></h6>
                        <h4 class="mt-0 text-primary">50</h4>
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


                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('gcascore.partial.gca-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">

        <div class="col-12">
            <span><b>LCV:</b></span>
            @for ($i = 0; $i < count($dates); $i++)
                <span class="todo-badge badge badge-{{$checklcv[$i]}} mb-2">{{$dates[$i]}}</span>
            @endfor
        </div>

        <div class="col-12">
            <span><b>CV:</b></span>
            @for ($i = 0; $i < count($dates); $i++)
                <span class="todo-badge badge badge-{{$checkcv[$i]}} mb-2">{{$dates[$i]}}</span>
            @endfor
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                     {{ Form::open(['route' => 'update', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}
                <div class="card-body">
                    <input type="hidden" name="gcaid" value="{{$gcas->id}}">

                    <div class="form-group row">
                        <label for="date" class="col-sm-6 text-right control-label col-form-label">Section CV/LCV:</label>
                        <div class="col-sm-6">
                            <select name="lcvcv" class="form-control select2" id="cvlcv" style="width: 100%;">
                                <option value="{{$gcas->lcv_cv}}">{{($gcas->lcv_cv == "lcv") ? "LCV Section": "CV Section";}}</option>
                                <option value="lcv">LCV Section</option>
                                <option value="cv">CV Section</option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="ename" class="col-sm-6 text-right control-label col-form-label">Entry Date</label>
                        <div class="col-sm-6">
                            <div class='input-group'>
                                <input type='text' name="mdate" value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $gcas->date)->format('m/d/Y');}}" class="form-control singledate" />

                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ti-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="date" class="col-sm-6 text-right control-label col-form-label">Sample Size</label>
                        <div class="col-sm-6">
                            <select name="samplesize" class="form-control select2" id="samplesize" style="width: 100%;">
                                <option value="{{$gcas->units_sampled}}">{{($gcas->units_sampled == 1) ? "One (1) Unit audited" : "Two (2) Units audited";}}</option>
                                <option value="1">One (1) Unit audited</option>
                                <option value="2">Two (2) Units audited</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pname" class="col-sm-6 text-right control-label col-form-label">Total No. of Defects</label>
                        <div class="col-sm-3">
                            {{ Form::text('ttdefectsc1', $gcas->defectcar1, ['class' => 'form-control', 'placeholder' => 'Defects in Car 1...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                        <div class="col-sm-3">
                            {{ Form::text('ttdefectsc2', $gcas->defectcar2, ['class' => 'form-control car2', 'placeholder' => 'Defects in Car 2...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pname" class="col-sm-6 text-right control-label col-form-label">Cummulative WDPV </label>
                        <div class="col-sm-3">
                            {{ Form::text('mtdwdpv', $gcas->mtdwdpv, ['class' => 'form-control', 'placeholder' => 'MTD WDPV score...','required'=>'required','autocomplete'=>'off']) }}
                        </div>

                    </div>


            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}
                    </div>
                </div>

                </div>
         {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    @endsection
    @section('after-styles')
    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}
     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

@endsection

@section('after-scripts')
 {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
{!! Toastr::message() !!}

<script type="text/javascript">
    $(".select2").select2();

    $(document).on('change', '#samplesize', function () {
        var samplesize = $('#samplesize').val();
        console.log(samplesize);
            if (samplesize == 2) {
                $('.car2').attr('readonly', false);
                $('.car2').val('');
            }else if(samplesize == 1){
                $('.car2').attr('readonly', true);
                $('.car2').val('0.00');
            }else{
                $('.car2').attr('readonly', false);
                $('.car2').val('');
            }
    });
</script>
@endsection

