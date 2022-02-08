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
            <div class="container-fluid" style="background: #000000;">
                 <!-- Row -->
                <div class="row mb-4">
                    <div class="col-md-1 align-self-right text-center text-md-left">
                        <a href="{{route('screenboard')}}" class="btn btn-secondary"><i class="mdi mdi-arrow-left font-16 mr-1"></i> </a>
                    </div>
                    <div class="col-6 pt-3">
                        <h3 class="card-title text-center text-uppercase text-white">{{$sectionname}} PRODUCTION</h3>
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

                <div class="row text-center">
                    <!-- Column -->
                    <div class="col-sm-12 col-md-4">
                        <h2 class="mb-0 text-white align-self-center">PEOPLE</h2>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-sm-12 col-md-4">
                        <h2 class="mb-0 text-white align-self-center">QUALITY</h2>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-sm-12 col-md-4">
                        <h2 class="mb-0 text-white align-self-center">RESPONSIVENESS</h2>
                    </div>
                    <!-- Column -->

                </div>
                <!-- Row -->
                    <div class="row m-2" >

                <!--PEOPLE-->
                <div class="col-lg-4 col-md-12" >
                    <!-- card -->
                    <div class="card bg-primary mb-3">
                    <div class="card-body w-100">
                        <h2 class="card-title text-white">ABSENTEEISM</h2>
                            <div class="row">
                                <div class="col-12" style="height:90px;">
                                    <table width="100%" class="text-center text-white w-100" style="color: white;">
                                        <tr style="font-size: 20px;">
                                            <td></td>
                                            <td>Target</td>
                                            <td>Actual</td>
                                            <td rowspan="3" class="pt-2">
                                                <span id="absfrown"><i style="font-size:70px; color: red;" class="far fa-frown float-right"></i></span>
                                                <span id="abssmile"><i style="font-size:70px; color:rgb(3, 206, 3);" class="far fa-smile float-right"></i></span>

                                            </td>
                                        </tr>
                                        <tr style="font-size: 24px;">
                                            <td style="font-size: 16px;">Daily</td>
                                            <td><span id="dayabsetarget"></span>%</td>
                                            <td><span id="dayabse"></span>%</td>
                                        </tr>
                                        <tr style="font-size: 24px;">
                                            <td style="font-size: 16px;">MTD</td>
                                            <td><span id="mtdabsetarget"></span>%</td>
                                            <td><span id="mtdabse"></span>%</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- card -->
                <div class="card bg-primary">
                    <div class="card-body">
                        <h2 class="card-title text-white">T/L AVAILABILITY</h2>
                        <div class="row">
                            <div class="col-12" style="height:90px;">
                                <table width="100%" class="text-center text-white w-100" style="color: white;">
                                    <tr style="font-size: 20px;">
                                        <td></td>
                                        <td>Target</td>
                                        <td>Actual</td>
                                        <td rowspan="3" class="pt-2">
                                            <span id="tlfrown"><i style="font-size:70px; color: red;" class="far fa-frown float-right"></i></span>
                                            <span id="tlsmile"><i style="font-size:70px; color:rgb(3, 206, 3);" class="far fa-smile float-right"></i></span>

                                        </td>
                                    </tr>
                                    <tr style="font-size: 24px;">
                                        <td style="font-size: 16px;">Daily</td>
                                        <td><span id="daytltarget"></span>%</td>
                                        <td><span id="daytl"></span>%</td>
                                    </tr>
                                    <tr style="font-size: 24px;">
                                        <td style="font-size: 16px;">MTD</td>
                                        <td><span id="mtdtltarget"></span>%</td>
                                        <td><span id="mtdtl"></span>%</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                </div>


                    <!--QUALITY-->
                    <div class="col-lg-4 col-md-12" >
                         <!-- card -->
                         <div class="card bg-orange mb-3">
                            <div class="card-body">
                                <h2 class="card-title text-white">DRL SCORE (PPH)</h2>
                                <div class="row">
                                    <div class="col-12" style="height:90px;">
                                        <table width="100%" class="text-center text-white w-100" style="color: white;">
                                            <tr style="font-size: 20px;">
                                                <td></td>
                                                <td>Target</td>
                                                <td>Actual</td>
                                                <td rowspan="3" class="pt-2">
                                                    <span id="drlfrown"><i style="font-size:70px; color: red;" class="far fa-frown float-right"></i></span>
                                                    <span id="drlsmile"><i style="font-size:70px; color:rgb(3, 206, 3);" class="far fa-smile float-right"></i></span>

                                                </td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">Daily</td>
                                                <td><span id="daydrltarget"></span></td>
                                                <td><span id="daydrl"></span></td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">MTD</td>
                                                <td><span id="mtddrltarget"></span></td>
                                                <td><span id="mtddrl"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                         <!-- card -->
                         <div class="card bg-orange mb-3">
                            <div class="card-body">
                                <h2 class="card-title text-white">DRR SCORE</h2>
                                <div class="row">
                                    <div class="col-12" style="height:90px;">
                                        <table width="100%" class="text-center text-white w-100" style="color: white;">
                                            <tr style="font-size: 20px;">
                                                <td></td>
                                                <td>Target</td>
                                                <td>Actual</td>
                                                <td rowspan="3" class="pt-2">
                                                    <span id="drrfrown"><i style="font-size:70px; color: red;" class="far fa-frown float-right"></i></span>
                                                    <span id="drrsmile"><i style="font-size:70px; color:rgb(3, 206, 3);" class="far fa-smile float-right"></i></span>

                                                </td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">Daily</td>
                                                <td><span id="daydrrtarget">%</span></td>
                                                <td><span id="daydrr">%</span></td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">MTD</td>
                                                <td><span id="mtddrrtarget">%</span></td>
                                                <td><span id="mtddrr">%</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card bg-orange">
                            <div class="card-body">
                                <h2 class="card-title text-white">GCA SCORE MTD</h2>
                                <div class="row">
                                    <div class="col-12" style="height:115px;">
                                        <table width="100%" class="text-center text-white w-100" style="color: white;">
                                            <tr style="font-size: 20px;">
                                                <td></td>
                                                <td colspan="2">CV</td>
                                                <td colspan="2">LCV</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>Target</td>
                                                <td>Actual</td>
                                                <td>Target</td>
                                                <td>Actual</td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">DPV:</td>
                                                <td id="cvdpvtarget"></td>
                                                <td id="cvdpv"></td>
                                                <td id="lcvdpvtarget"></td>
                                                <td id="lcvdpv"></td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">WDPV:</td>
                                                <td id="cvwdpvtarget"></td>
                                                <td id="cvwdpv"></td>
												<td id="lcvwdpvtarget"></td>
                                                <td id="lcvwdpv"></td>
                                                
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!--RESPONSIVENESS-->
                    <div class="col-lg-4 col-md-12">
                        <!-- card -->
                        <div class="card bg-info mb-3">
                            <div class="card-body">
                                <h2 class="card-title text-white">OFFLINE</h2>
                                <div class="row">
                                    <div class="col-12" style="height:90px;">
                                        <table width="100%" class="text-center text-white w-100" style="color: white;">
                                            <tr style="font-size: 20px;">
                                                <td></td>
                                                <td>Target</td>
                                                <td>Actual</td>
                                                <td rowspan="3" class="pt-2">
                                                    <span id="offfrown"><i style="font-size:70px; color: red;" class="far fa-frown float-right"></i></span>
                                                    <span id="offsmile"><i style="font-size:70px; color:rgb(3, 206, 3);" class="far fa-smile float-right"></i></span>

                                                </td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">Daily</td>
                                                <td><span id="dayofftarget"></span></td>
                                                <td><span id="dayoff"></span></td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">MTD</td>
                                                <td><span id="mtdofftarget"></span></td>
                                                <td><span id="mtdoff"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="card bg-info mb-3">
                            <div class="card-body">
                                <h2 class="card-title text-white">FCW</h2>
                                <div class="row">
                                    <div class="col-12" style="height:90px;">
                                        <table width="100%" class="text-center text-white w-100" style="color: white;">
                                            <tr style="font-size: 20px;">
                                                <td></td>
                                                <td>Target</td>
                                                <td>Actual</td>
                                                <td rowspan="3" class="pt-2">
                                                    <span id="fcwfrown"><i style="font-size:70px; color: red;" class="far fa-frown float-right"></i></span>
                                                    <span id="fcwsmile"><i style="font-size:70px; color:rgb(3, 206, 3);" class="far fa-smile float-right"></i></span>

                                                </td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">Daily</td>
                                                <td><span id="dayfcwtarget"></span></td>
                                                <td><span id="dayfcw"></span></td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">MTD</td>
                                                <td><span id="mtdfcwtarget"></span></td>
                                                <td><span id="mtdfcw"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <!-- card -->
                        <div class="card bg-info">
                            <div class="card-body">
                                <h2 class="card-title text-white">EFFICIENCY</h2>
                                <div class="row">
                                    <div class="col-12" style="height:115px;">
                                        <table width="90%" class="text-center text-white w-100" style="color: white;">
                                            <tr style="font-size: 20px;">
                                                <td></td>
                                                <td>Target</td>
                                                <td>Actual</td>
                                                <td rowspan="3" class="pt-2">
                                                    <span id="efffrown"><i style="font-size:70px; color: red;" class="far fa-frown float-right"></i></span>
                                                    <span id="effsmile"><i style="font-size:70px; color:rgb(3, 206, 3);" class="far fa-smile float-right"></i></span>
                                                </td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">Daily</td>
                                                <td><span id="dayefftarget"></span>%</td>
                                                <td><span id="dayeff"></span>%</td>
                                            </tr>
                                            <tr style="font-size: 24px;">
                                                <td style="font-size: 16px;">MTD</td>
                                                <td><span id="mtdefftarget"></span>%</td>
                                                <td><span id="mtdeff"></span>%</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card -->

                    </div>

                    <!-- Card -->

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
                        url: '{{route('screenboardindexReload')}}',
                        method: "GET",
                        dataType: 'json',
                        data:{'section':"{{$_GET['section']}}",'shift':"{{$_GET['shift']}}"},
                        success: function(response) {
                            $('#time').html(response.data.time);
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
                url: '{{route('screenboardindexReload')}}',
                method: "GET",
                dataType: 'json',
                data:{'section':"{{$_GET['section']}}",'shift':"{{$_GET['shift']}}"},
                success: function(response) {
                    //ABSENTIEESM
                    var actualab = response.data.MTDabsentiesm;
                    var targetab = response.data.plantAB_target;
                    if(actualab > targetab){
                        $('#abssmile').hide();
                        $('#absfrown').show();
                    }else{
                        $('#abssmile').show();
                        $('#absfrown').hide();
                    }

                    $('#dayabsetarget').html(response.data.plantAB_target);
                    $('#mtdabsetarget').html(response.data.plantAB_target);
                    $('#dayabse').html(response.data.TDabsentiesm);
                    $('#mtdabse').html(response.data.MTDabsentiesm);

                    //TL AVAILABILITY
                    var actualtl = response.data.MTDTLavail;
                    var targettl = response.data.plantTL_target;
                    if(actualtl < targettl){
                        $('#tlsmile').hide();
                        $('#tlfrown').show();
                    }else{
                        $('#tlsmile').show();
                        $('#tlfrown').hide();
                    }

                    $('#daytltarget').html(response.data.plantTL_target);
                    $('#mtdtltarget').html(response.data.plantTL_target);
                    $('#daytl').html(response.data.TDTLavail);
                    $('#mtdtl').html(response.data.MTDTLavail);

                    //DRL
                    var MTDactualdrl = response.data.MTDdrl;
                    var MTDtargetdrl = response.data.MTDdrltarget;
                    if(MTDactualdrl >= MTDtargetdrl){
                        $('#drlsmile').hide();
                        $('#drlfrown').show();
                    }else{
                        $('#drlsmile').show();
                        $('#drlfrown').hide();
                    }

                    $('#daydrltarget').html(response.data.TDdrltarget);
                    $('#mtddrltarget').html(response.data.MTDdrltarget);
                    $('#daydrl').html(response.data.TDdrl);
                    $('#mtddrl').html(response.data.MTDdrl);

                    //DRR
                    var MTDactualdrr = response.data.MTDdrr;
                    var MTDtargetdrr = response.data.MTDdrrtarget;
                    if(MTDactualdrr < MTDtargetdrr){
                        $('#drrsmile').hide();
                        $('#drrfrown').show();
                    }else{
                        $('#drrsmile').show();
                        $('#drrfrown').hide();
                    }

                    $('#daydrrtarget').html(response.data.TDdrrtarget);
                    $('#mtddrrtarget').html(response.data.MTDdrrtarget);
                    $('#daydrr').html(response.data.TDdrr);
                    $('#mtddrr').html(response.data.MTDdrr);

                    //GCA
                    $('#cvdpvtarget').html(response.data.cvdpvtarget);
                    $('#lcvdpvtarget').html(response.data.lcvdpvtarget);
                    $('#cvwdpvtarget').html(response.data.cvwdpvtarget);
                    $('#lcvwdpvtarget').html(response.data.lcvwdpvtarget);

                    $('#cvdpv').html(response.data.cvdpv);
                    $('#lcvdpv').html(response.data.lcvdpv);
                    $('#cvwdpv').html(response.data.cvwdpv);
                    $('#lcvwdpv').html(response.data.lcvwdpv);

                    //OFFLINE
                    var actualoff = response.data.MTDoffline;
                    var targetoff = response.data.MTDofflinetarget;
                    if(actualoff < targetoff){
                        $('#offsmile').hide();
                        $('#offfrown').show();
                    }else{
                        $('#offsmile').show();
                        $('#offfrown').hide();
                    }

                    $('#dayofftarget').html(response.data.TDofflinetarget);
                    $('#mtdofftarget').html(response.data.MTDofflinetarget);
                    $('#dayoff').html(response.data.TDoffline);
                    $('#mtdoff').html(response.data.MTDoffline);

                    //FCW
                    var actualfcw = response.data.MTDfcw;
                    var targetfcw = response.data.MTDfcwtarget;
                    if(actualfcw < targetfcw){
                        $('#fcwsmile').hide();
                        $('#fcwfrown').show();
                    }else{
                        $('#fcwsmile').show();
                        $('#fcwfrown').hide();
                    }

                    $('#dayfcwtarget').html(response.data.TDfcwtarget);
                    $('#mtdfcwtarget').html(response.data.MTDfcwtarget);
                    $('#dayfcw').html(response.data.TDfcw);
                    $('#mtdfcw').html(response.data.MTDfcw);

                    //EFFICIENCY
                    var actualef = response.data.MTDplant_eff;
                    var targetef = response.data.planteff_target;
                    if(actualef < targetef){
                        $('#effsmile').hide();
                        $('#efffrown').show();
                    }else{
                        $('#effsmile').show();
                        $('#efffrown').hide();
                    }

                    $('#dayefftarget').html(response.data.planteff_target);
                    $('#mtdefftarget').html(response.data.planteff_target);
                    $('#dayeff').html(response.data.TDplant_eff);
                    $('#mtdeff').html(response.data.MTDplant_eff);
            }
        });
    };
    </script>
</body>

</html>
