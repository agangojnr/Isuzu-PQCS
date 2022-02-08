
@extends('layouts.app')
@section('title','Import Schedule')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Import Unit Schedule </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Import Unit Schedule</li>
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
                            @include('vehicleunits.partial.vehicleunits-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
 <div class="col-12">
               <div class="content-body">
               <div class="card card-block">
                    <div id="notify" class="alert" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                     
                        <div class="message"></div>
                    </div>@if ($response == 1)
                        <div id="ups" class="card-body">
                            <h6>Import Started</h6>
                            <hr>

                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="card card-block">

                        <span id="ldBar" class="ldBar text-center success text-bold-700"
                              style="width:100%;height:80px"></span>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="card-body"><a class="btn btn-primary btn-lg"
                                                  href="">{!!  trans('pagination.previous') !!}</a>
                        </div>
                    @else
                        <div class="card-body">
                            <h6>Import Process Failed! Either you have uploaded an incorrect file format or invalid
                                template for
                                uploading!</h6>
                            <hr>

                            <div class="row sameheight-container">
                                <div class="col-md-12">
                                    <div class="card card-block">

                                        <span id="ldBar" class="ldBar text-xs-center "
                                              style="width:100%;height:30px"></span>

                                    </div>
                                </div>

                            </div>
                            <h6>Import Process Failed! Either you have uploaded an incorrect file format or invalid
                                template for
                                uploading!</h6>

                        </div>
                     @endif
                </div>
 </div>
            </div>
        
    </div>
    




@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}


     
    
@endsection



@section('after-scripts')

  {{ Html::script('js/loading-bar.js') }}
    @if ($response == 1)
        <script type="text/javascript">
            var bar1 = new ldBar("#ldBar");

            setInterval(function () {
                bar1.set(Math.floor((Math.random() * 70) + 30));
            }, 500);

            setTimeout(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{route('import_process')}}',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        'name': '{{$file_value}}',
                        'unit_id': '{{$data["unit_id"]}}',
                        @foreach($data as $key  => $row)'{{$key}}': '{{$row}}', @endforeach
                    },
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);

                        $("#ldBar").hide();
                        if (data.status == 'Success') {
                            $("#notify").addClass("alert-info white").fadeIn();
                            $("html, body").scrollTop($("body").offset().top);
                            setTimeout(function () {
                                window.location.href = '';
                            }, 2000);
                        } else {
                            $("#notify").addClass("alert-danger white").fadeIn();
                            $("html, body").scrollTop($("body").offset().top);
                        }


                    },
                    error: function (data) {
                        var message = '';
                        $.each(data.responseJSON.errors, function (key, value) {
                            message += value + ' ';
                        });
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + message);
                        $("#notify").removeClass("alert-info").addClass("alert-danger").fadeIn();
                        $("html, body").scrollTop($("body").offset().top);
                        $("#submit-data").show();
                        $("#ups").hide();
                    }
                });
            }, 2000);



    
</script>
 @endif
  
    @endsection