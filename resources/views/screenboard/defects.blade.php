<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Defect List</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/xtremeadmin/" />
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../../dist/js/pages/chartist/chartist-init.css" rel="stylesheet">
    <link href="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="../assets/libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/css-chart/css-chart.css" rel="stylesheet">
    <!-- Vector CSS -->
    <link href="../assets/libs/jvectormap/jquery-jvectormap.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid" style="background: #000000;">
            <!-- Row -->

            <div class="row mb-4">
                <div class="col-md-1 align-self-right text-center text-md-left">
                    <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-secondary"><i class="mdi mdi-arrow-left font-16 mr-1"></i> </a>
                </div>
                <div class="col-6 pt-3">
                    <h3 class="card-title text-center text-uppercase text-white">{{$shopname}}</h3>
                </div>

                <div class="col-lg-4 mt-3">
                    <h4 class="card-title text-center text-uppercase text-white">
                     
                        {{\Carbon\Carbon::today()->format('j M Y')}}
                        &nbsp;&nbsp;&nbsp;&nbsp;<span id="time"></span></h4>
                </div>
                <div class="col-lg-1 text-right" style="font-size: 30px;">
                    <span id="full" onclick="activate(document.documentElement);" ><i class="mdi mdi-fullscreen"></i></span>
                    <span id="exitfull" onclick="deactivate();"><i class="mdi mdi-fullscreen-exit"></i></span>
                </div>
            </div>




      
            <!-- Row -->
            <div class="row">
                <!-- Column -->

                @php
                $first_unit ='Null';
                 if (count($units)){
                    $first_unit = $units[0]['vehicle_id'];
                 }
                    
                @endphp

               @if (count($units))
               

                @foreach ($units as $unit)
                    <div class="col-md-2 p-1 ">


                        <button type="submit" id="active_unit" data-id="{{ $unit->vehicle->id }}" style="width: 100%; "
                            class="active_unit btn btn-lg  btn-info   mb-3"><h5 class="text-light">{{ $unit->vehicle->model->model_name }}<br>Lot: {{ $unit->vehicle->lot_no }} | Job: {{ $unit->vehicle->job_no }}</h5></button>

                    </div>

                @endforeach
                @endif
                {!! Form::hidden('vehicle_id', $first_unit, ['id' => 'current_vid']) !!}
            </div>


            <!-- Row -->
            <div class="row">
               <!---<div class="col-lg-4">
                    <div class="card bg-orange">
                        <div class="card-body">
                            <div class="d-md-flex no-block">
                                <h4 class="card-title">VEHICLE DETAILS</h4>
                            </div>
                           
                            <div class="month-table" >

                               
                                <div class="table-responsive mt-3">
                                   
                                </div>
                            </div>

                        </div>
                    </div>
                </div>-->


                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex no-block">
                                <h4 class="card-title">Chassis Number: <span class="ml-50" id="vehicle_record"></span></h4>
                            </div>
                            <div class="month-table">
                                <div class=" mt-3">
                                    <table class="table stylish-table v-middle mb-0 text-white text-wrap" id="defects" width="100%">
                                        <thead>
                                            <tr>
                                             
                                                <th width="26%" class="border-0 font-weight-normal">ROUTING&nbsp;QUERY</th>
                                                <th width="20%" class="border-0 font-weight-normal">DEFECT</th>
                                                <th width="13%" class="border-0 font-weight-normal">CAPTURED&nbsp;SHOP</th>
                                                <th width="12%" class="border-0 font-weight-normal">CAPTURED&nbsp;BY</th>
                                                <th  width="9%" class="border-0 font-weight-normal">STATUS</th>
                                                <th width="12%" class="border-0 font-weight-normal">CORRECTED&nbsp;BY</th>
                                                <th width="12%" class="border-0 font-weight-normal">VERIFIED&nbsp;BY</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->

    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- customizer Panel -->
    <!-- ============================================================== -->
    <div class="chat-windows"></div>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script type="text/javascript">
        function showTime() {
            var date = new Date(),
                utc = new Date(Date.UTC(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate(),
                    date.getHours(),
                    date.getMinutes(),
                    date.getSeconds()
                ));

            document.getElementById('time').innerHTML = utc.toLocaleTimeString();
        }

        setInterval(showTime, 1000);
    </script>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <script src="../../dist/js/app.min.js"></script>
    <script src="../../dist/js/app.init.horizontal.js"></script>
    <script src="../../dist/js/app-style-switcher.horizontal.js"></script>
    <script src="../../dist/js/app.init.dark.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../../dist/js/waves.js"></script>
    <!--Menu sidebar -->

    <!--Custom JavaScript -->
    <script src="../../dist/js/custom.min.js"></script>
    <!--This page JavaScript -->

    <!--c3 JavaScript -->
    <script src="../assets/libs/d3/dist/d3.min.js"></script>
    <script src="../assets/libs/c3/c3.min.js"></script>
    <!-- Vector map JavaScript -->
    <script src="../assets/libs/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="../assets/extra-libs/jvector/jquery-jvectormap-us-aea-en.js"></script>
        <!--Custom JavaScript -->
       
        <script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
        <script src="{{ asset('dist/js/pages/datatable/datatable-api.init.js') }}"></script>

    <script>
        $(function() {

                        // on selecting lead
    $('.active_unit').click(function() {

        var shop_id = {{ $shopid }};
var vehicle_id=$(this).attr('data-id');
var action = 1;
draw_data(shop_id, vehicle_id, action );




      
    });

            setTimeout(function() {
                var vehicle_id = $('#current_vid').val();
                var shop_id = {{ $shopid }};
                var action = 0;



                draw_data(shop_id, vehicle_id,action);
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            function draw_data(shop_id, vehicle_id, action) {


                $.ajax({
                    method: 'POST',
                    url: '{{ route('load_defects') }}',
                    dataType: 'json',
                    data: {
                        shop_id: shop_id,
                        vehicle_id: vehicle_id
                    },
                    success: function(result) {
                        if (result) {

                            console.log(result.data.vehicle_id);
                       
                            $('#vehicle_record').html(`
       
            ${result.data.vehicle_data.vin_no}
 `);
                            //toggle_dsp_input();
                        }
                    },
                });




                var defects = $('#defects').DataTable({
                        processing: false,
                        serverSide: false,
                        paging: false,
                        bFilter: false,
                        bInfo: false,
                        
                        
                        
                        ajax: {
                            url: '{{ route("load_datable_defects") }}',
                            type: 'post',
                            data:{
                                shop_id: shop_id,
                               vehicle_id: vehicle_id
                            }
                        },
                        columnDefs:[{
                                "targets": 0,
                                "orderable": false,
                                "searchable": false,
                                "searching": false,
                                 "paging": false, 
                                 "filter": false
                                 
                            }],
                        columns: [
                           
                             
                             {data: 'routing_query', name: 'routing_query'},
                             {data: 'defect_name', name: 'defect_name'},
                             {data: 'captured_shop', name: 'captured_shop'},
                             {data: 'captured_by', name: 'captured_by'},
                             {data: 'status', name: 'status'},
                             {data: 'corrected_by', name: 'corrected_by'},
                             {data: 'verified_by', name: 'verified_by'},
                           
                          
                          
                        ],
                        
                    });

                    $('#defects').dataTable().fnDestroy();
              

            }


           







        });
       

      
               
        function activate(ele) {
        if (ele.requestFullscreen) {
            ele.requestFullscreen();
            $('#exitfull').show();
            $('#full').hide();
        }
    }

    // Function for full screen activation
    function deactivate() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
            $('#exitfull').hide();
            $('#full').show();
        }
    }


    </script>
</body>

</html>
