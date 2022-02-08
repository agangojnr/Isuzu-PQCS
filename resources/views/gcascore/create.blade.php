
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
                     {{ Form::open(['route' => 'gcascore.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-user'])}}
                <div class="card-body">

            @include('gcascore.form')

            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Save', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}

                        {{ link_to_route('gcascore.create', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light']) }}
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

