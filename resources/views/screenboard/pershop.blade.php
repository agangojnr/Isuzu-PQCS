<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Screenboard</title>
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
            <div class="container-fluid" style="background: #000000; height: 100%;">
                <!-- Row -->
                <div class="row mb-4">
                    <div class="col-md-1 align-self-right text-center text-md-left">
                        <a href="{{route('screenboard')}}" class="btn btn-secondary"><i class="mdi mdi-arrow-left font-16 mr-1"></i> </a>
                    </div>
                    <div class="col-6 pt-3">
                        <h1 class="card-title text-center text-uppercase text-warning" style="font-size: 54px;">{{$shopname}}</h1>
                    </div>

                    <div class="col-lg-4 mt-3">
                        <h4 class="card-title text-center text-uppercase text-white">{{($shifthrs == 9)? "8Hr SHIFT" : "10Hr SHIFT";}}
                            &nbsp;&nbsp;&nbsp;&nbsp;
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

                    <div class="col-lg-6">
                        <div style="margin-top: 10%;">
                            <h1 class="text-right" style="font-size: 75px; color:white text-muted">REALTIME DAILY PRODUCTION</h1>
                        </div>
                    </div>
                    <div class="col-lg-6" id="units">
                        <div class="card"  style="background: #000000;">
                            <div class="card-body align-self-center">
                                <div class="d-md-flex no-block no-wap">
                                    <h1>
                                        <span id="div-goodactual" style="font-size: 200px; color:green"></span>
                                        <span id="div-badactual" style="font-size: 200px; color:red"></span>
                                        <span style="color:rgb(60, 61, 60); font-size: 200px;">|</span></span>
                                        <span id="div-target" style="font-size: 200px; color:white"></span>
                                    </h1>
                                </div>
                                <div class="row text-center p-0">
                                    <div class="col-lg-6">
                                        <span><h2>ACTUAL</h2></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span style=""><h2>TARGET</h2></span>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>


                </div>
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-sm-12 col-md-3">
                        <div class="card bg-info">
                            <div class="card-body text-white">
                                <div class="d-flex flex-row">
                                    <div class="p-2 align-self-center">
                                        <h2 class="mb-0 text-white font-weight-medium">PRODUCTION MTD</h2>
                                        <div class="row">
                                            <div class="col-9">
                                                <table class="text-center w-100">
                                                    <tr style="font-size: 30px;">
                                                        <td><span id="mtdunits"></span></td>
                                                        <td><span id="mtdtargetunits"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Actual</td>
                                                        <td>Target</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-3 mt-2">
                                                    <span id="unitsfrown" style="font-size:50px; color:red;"><i class="far fa-frown"></i></span>
                                                    <span id="unitssmile" style="font-size:50px; color:rgb(3, 206, 3);"><i class="far fa-smile"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
					<!-- Column -->
                    <div class="col-sm-12 col-md-3">
                        <div class="card bg-info">
                            <div class="card-body text-white">
                                <div class="d-flex flex-row">
                                    <div class="p-2 align-self-center">
                                        <h2 class="mb-0 text-white font-weight-medium">EFFICIENCY MTD</h2>
                                        <div class="row">
                                            <div class="col-9">
                                                <table class="text-center w-100">
                                                    <tr style="font-size: 30px;">
                                                        <td><span id="mtdeff"></span>%</td>
                                                        <td><span id="mtdtargeteff"></span>%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Actual</td>
                                                        <td>Target</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-3 mt-2">
                                                <span id="efffrown" style="font-size:50px; color:red;"><i class="far fa-frown"></i></span>
                                                <span id="effsmile" style="font-size:50px; color:rgb(3, 206, 3);"><i class="far fa-smile"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-sm-12 col-md-3">
                        <div class="card bg-orange">
                            <a href="{{route('screenboarddefects',[$shopid])}}">
                            <div class="card-body text-white">
                                <div class="d-flex flex-row">
                                    <div class="p-2 align-self-center">
                                        <h2 class="mb-0 text-white font-weight-medium">DRL TODAY <span style="font-size:20px;">(PPH)</span></h2>
                                        <div class="row">
                                            <div class="col-9">
                                                <table class="text-center w-100">
                                                    <tr style="font-size: 30px;">
                                                        <td><span id="TDdrl"></span></td>
                                                        <td><span id="TDdrltarget"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Actual</td>
                                                        <td>Target</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-3 mt-2">
                                                <span id="TDdrlfrown" style="font-size:50px; color:red;"><i class="far fa-frown"></i></span>
                                                <span id="TDdrlsmile" style="font-size:50px; color:rgb(3, 206, 3);"><i class="far fa-smile"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                    <!-- Column -->
                     <!-- Column -->
                     <div class="col-sm-12 col-md-3">
                        <div class="card bg-orange">
                            <div class="card-body text-white">
                                <div class="d-flex flex-row">
                                    <div class="p-2 align-self-center">
                                        <h2 class="mb-0 text-white font-weight-medium">DRL MTD <span style="font-size:20px;">(PPH)</span></h2>
                                        <div class="row">
                                            <div class="col-9">
                                                <table class="text-center w-100">
                                                    <tr style="font-size: 30px;">
                                                        <td><span id="MTDdrl"></span></td>
                                                        <td><span id="MTDdrltarget"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Actual</td>
                                                        <td>Target</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-3 mt-2">
                                                <span id="MTDdrlfrown" style="font-size:50px; color:red;"><i class="far fa-frown"></i></span>
                                                <span id="MTDdrlsmile" style="font-size:50px; color:rgb(3, 206, 3);"><i class="far fa-smile"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->


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

    @section('after-scripts')
   <script>
    // Function for full screen activation

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
    <script>
        //Time
            $(document).ready(function() {
            setInterval(runningTime, 1000);
            });

            function runningTime() {
                $.ajax({
                        url: '{{route('screenboardpershopReload')}}',
                        method: "GET",
                        dataType: 'json',
                        data:{'section':"{{$_GET['section']}}",'shift':"{{$_GET['shift']}}"},
                        success: function(response) {
                            $('#time').html(response.data.time);
							console.log("");
                    }
                });
            }


        //for a every 3 second refresh
     $(document).ready(function () {
        $('#exitfull').hide();
            refresher();
         setInterval(function () {
            refresher();
         }, 3000);
     });


      function refresher() {
            $.ajax({
                url: '{{route('screenboardpershopReload')}}',
                method: "GET",
                dataType: 'json',
                data:{'section':"{{$_GET['section']}}",'shift':"{{$_GET['shift']}}"},
                success: function(response) {
                    var actual = response.data.actual;
                    var target = response.data.target;
                    if(actual < target){
                        $('#div-goodactual').hide();
                        $('#div-badactual').show();
                    }else{
                        $('#div-badactual').hide();
                        $('#div-goodactual').show();
                    }

                    $('#div-goodactual').html(response.data.actual);
                    $('#div-badactual').html(response.data.actual);
                    $('#div-target').html(response.data.target);

                    //ACTUAL PRODUCTION
                    var MTDactual = response.data.MTDactualunits;
                    var MTDtarget = response.data.mtdtarget;
                    if(MTDactual < MTDtarget){
                        $('#unitssmile').hide();
                        $('#unitsfrown').show();
                    }else{
                        $('#unitsfrown').hide();
                        $('#unitssmile').show();
                    }

                    $('#mtdunits').html(response.data.MTDactualunits);
                    $('#mtdtargetunits').html(response.data.mtdtarget);

                    //EFFICIENCY
                    var MTDeff = response.data.MTDshop_eff;
                    var MTDefftarget = response.data.MTDeff_target;

                    if(MTDeff < MTDefftarget){
                        $('#effsmile').hide();
                        $('#efffrown').show();
                    }else{
                        $('#efffrown').hide();
                        $('#effsmile').show();
                    }

                    $('#mtdeff').html(response.data.MTDshop_eff);
                    $('#mtdtargeteff').html(response.data.MTDeff_target);

                    //TD DRL
                    var TDdrl = response.data.TDdrlactual;
                    var TDdrltarget = response.data.TDdrltarget;
                    if(TDdrl >= TDdrltarget){
                        $('#TDdrlsmile').hide();
                        $('#TDdrlfrown').show();
                    }else{
                        $('#TDdrlfrown').hide();
                        $('#TDdrlsmile').show();
                    }

                    $('#TDdrl').html(response.data.TDdrlactual);
                    $('#TDdrltarget').html(response.data.TDdrltarget);

                    //MTD DRL
                    var MTDdrl = response.data.MTDdrlactual;
                    var MTDdrltarget = response.data.MTDdrltarget;
                    if(MTDdrl >= MTDdrltarget){
                        $('#MTDdrlsmile').hide();
                        $('#MTDdrlfrown').show();
                    }else{
                        $('#MTDdrlfrown').hide();
                        $('#MTDdrlsmile').show();
                    }
                    $('#MTDdrl').html(response.data.MTDdrlactual);
                    $('#MTDdrltarget').html(response.data.MTDdrltarget);
            }
        });
    };
</script>
</body>

</html>
