<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>

    @include('layouts.header.header')
    @yield('after-styles')
    <style>
        * {
          margin: 0;
          padding: 0;
          font-family: sans-serif;
        }
        .chartMenu {
          width: 100vw;
          height: 40px;
          background: #1A1A1A;
          color: rgba(255, 26, 104, 1);
        }
        .chartMenu p {
          padding: 10px;
          font-size: 20px;
        }
        .chartCard {
          width: 100vw;
          height: calc(100vh - 40px);
          background: rgba(255, 26, 104, 0.2);
          display: flex;
          align-items: center;
          justify-content: center;
        }
        .chartBox {
          width: 700px;
          padding: 20px;
          border-radius: 20px;
          border: solid 3px rgba(255, 26, 104, 1);
          background: white;
        }
      </style>

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles" style="background-color:#da251c; color:white;">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">PLANT EFFICIENCY DASHBOARD</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">EFFICIENCY GRAPHS</li>
            </ol>


        </div>
        <div class="col-md-7">
            <div>
                <a href="/home" id="btn-add-contact" class="btn btn-primary float-right">
                    <i class="mdi mdi-arrow-left font-16 mr-1"></i> Back to Home</a>
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

    <!-- Individual column searching (select inputs) -->

          <div class="container-fluid note-has-grid">
			     <div class="nav nav-pills p-3 mb-3 rounded-pill align-items-center" style="background-color:#DCDCDC;">

                            <div class="col-lg-2 mr-5">
                                <div>
                                    <div class="demo-gauge">
                                      <div id="gauge0"></div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-lg-2 mr-4">
                                <div>
                                    <div class="demo-gauge">
                                      <div id="gauge"></div>
                                  </div>
                                </div>
                            </div>

                            <div class="col-lg-2 mr-5">
                              <div>
                                <div class="demo-gauge">
                                  <div id="gauge1"></div>
                                </div>
                            </div>
                            </div>

                             <div class="col-lg-2 mr-4">

                              <div>
                                <div class="demo-gauge">
                                  <div id="gauge2"> </div>
                                </div>
                            </div>
                            </div>

                             <div class="col-lg-2 mr-4">

                              <div>
                                <div class="demo-gauge">
                                  <div id="gauge3"></div>
                                </div>
                            </div>
                          </div>

                      </div>
                      </div>
                        <div class="row m-2" style="background-color:#DCDCDC; border: rounded;">

                            <div class="col-lg-6">
                                <div class="card m-4" style="background-color: #C0C0C0;">
                                    <div class="card-body">
                                        <div>
                                            <canvas id="bar-chart-horizontal" height="200" style="background-color: #AAF0D1;"> </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card m-4" style="background-color: #C0C0C0;">
                                    <div class="card-body">
                                        <div>
                                            <canvas id="bar-chart-horizontal1" height="200" style="background-color: #AAF0D1;"> </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card m-4" style="background-color: #C0C0C0;">
                                    <div class="card-body" >
                                        <div>
                                            <canvas id="bar-chart-horizontal2" height="200" style="background-color: #AAF0D1;"> </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card m-4" style="background-color: #C0C0C0;">
                                    <div class="card-body" >
                                        <div>
                                            <canvas id="bar-chart-horizontal3" height="200" style="background-color: #AAF0D1;"> </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="col-lg-6">
                                <div class="card m-4" style="background-color: #C0C0C0;">
                                    <div class="card-body">
                                        <div>
                                            <canvas id="bar-chart-horizontal3"></canvas>
                                            <!--<canvas id="bar-chart-horizontal3" height="200" style="background-color: #AAF0D1;"> </canvas>-->
                                        <!--</div>
                                    </div>
                                    <div class="chartCard">
                                        <div class="chartBox">
                                          <canvas id="bar-chart-horizontal3"></canvas>
                                        </div>
                                      </div>
                                </div>
                            </div>-->


    </div>

@include('layouts.footer.script')
@yield('after-scripts')
@yield('extra-scripts')
<script src="{{ asset('assets/libs/jQWidget/jqxcore.js') }}"></script>
<script src="{{ asset('assets/libs/jQWidget/jqxdata.js') }}"></script>
<script src="{{ asset('assets/libs/jQWidget/jqxdraw.js') }}"></script>
<script src="{{ asset('assets/libs/jQWidget/jqxgauge.js') }}"></script>

<script src="{{ asset('assets/libs/jQWidget/chart.js') }}"></script>
<script src="{{ asset('assets/libs/jQWidget/chartjs-plugin-datalabels.min.js') }}"></script>
  <!--<script src="https://unpkg.com/chartjs-gauge@0.3.0/dist/chartjs-gauge.js"></script>
    <script src="https://unpkg.com/chart.js@2.8.0/dist/Chart.bundle.js"></script>
    -->


<script>

    //GAUGE CHARTS

    //YTD PLANT EFFICIENCY
    $(document).ready(function () {
            var labels = { visible: true, position: 'inside'};
             var value = <?php echo $YTDplantEff; ?>;
            var value1 = value+"%<br>YTD Plant<br>Efficiency";

            //Create jqxGauge
            $('#gauge0').jqxGauge({
                ranges: [{ startValue: 0, endValue: 25, style: { fill: '#d02841', stroke: '#d02841' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 25, endValue: 50, style: { fill: '#FF6347', stroke: '#FF6347' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 50, endValue: 75, style: { fill: '#f6de54', stroke: '#f6de54' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 75, endValue: 100, style: { fill: '#00FF7F', stroke: '#00FF7F' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 }
                ],
                cap: { radius: 0.04 },
                caption: { offset: [10, -35], value: '100%', position: 'top' },
                caption: { offset: [0, -25], value: value1, position: 'bottom' },
                value: 5,
                style: { stroke: '#ffffff', 'stroke-width': '1px', fill: '#00CED1' },
                animationDuration: 1500,
                colorScheme: 'scheme04',
                //labels: labels,
                ticksMinor: { interval: 5, size: '10%' },
                ticksMajor: { interval: 1, size: '5%' },
            });

            $('#gauge0').jqxGauge('setValue', value);
        });

    //DAILY PLANT EFFICIENCY
        $(document).ready(function () {
            var labels = { visible: true, position: 'inside'};
             var value = <?php echo $plantEff; ?>;
            var value1 = value+"%<br>Daily Plant<br>Efficiency";

            //Create jqxGauge
            $('#gauge').jqxGauge({
                ranges: [{ startValue: 0, endValue: 25, style: { fill: '#d02841', stroke: '#d02841' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 25, endValue: 50, style: { fill: '#FF6347', stroke: '#FF6347' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 50, endValue: 75, style: { fill: '#f6de54', stroke: '#f6de54' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 75, endValue: 100, style: { fill: '#00FF7F', stroke: '#00FF7F' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 }
                ],
                cap: { radius: 0.04 },
                caption: { offset: [10, -35], value: '100%', position: 'top' },
                caption: { offset: [0, -25], value: value1, position: 'bottom' },
                value: 5,
                style: { stroke: '#ffffff', 'stroke-width': '1px', fill: '#00CED1' },
                animationDuration: 1500,
                colorScheme: 'scheme04',
                //labels: labels,
                ticksMinor: { interval: 5, size: '10%' },
                ticksMajor: { interval: 1, size: '5%' },
            });

            $('#gauge').jqxGauge('setValue', value);
        });

        //MTD PLANT EFFICIENCY
        $(document).ready(function () {
            var labels = { visible: true, position: 'inside'};
            var value = <?php echo $MTDplantEff; ?>;
            var value1 = value+"%<br> MTD Plant<br>Efficiency";

            //Create jqxGauge
            $('#gauge1').jqxGauge({
                ranges: [{ startValue: 0, endValue: 25, style: { fill: '#d02841', stroke: '#d02841' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 25, endValue: 50, style: { fill: '#FF6347', stroke: '#FF6347' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 50, endValue: 75, style: { fill: '#f6de54', stroke: '#f6de54' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 75, endValue: 100, style: { fill: '#00FF7F', stroke: '#00FF7F' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 }
                ],
                cap: { radius: 0.04 },
                caption: { offset: [0, -25], value: value1, position: 'bottom' },
                value: 5,
                style: { stroke: '#ffffff', 'stroke-width': '1px', fill: '#00CED1' },
                animationDuration: 1500,
                colorScheme: 'scheme04',
                //labels: labels,
                ticksMinor: { interval: 5, size: '10%' },
                ticksMajor: { interval: 1, size: '5%' },
            });

            $('#gauge1').jqxGauge('setValue', value);
        });


    // DAILY PLANT ABSENTIEESM
      $(document).ready(function () {
            var labels = { visible: true, position: 'inside'};
             var value = <?php echo $plant_absentpc; ?>;
            var value1 = value+"%<br> Daily Plant<br>Absentieesm";

            //Create jqxGauge
            $('#gauge2').jqxGauge({
                ranges: [{ startValue: 0, endValue: 25, style: { fill: '#00FF7F', stroke: '#00FF7F' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 25, endValue: 50, style: { fill: '#f6de54', stroke: '#f6de54' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 50, endValue: 75, style: { fill: '#FF6347', stroke: '#FF6347' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 75, endValue: 100, style: { fill: '#d02841', stroke: '#d02841' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 }
                ],
                cap: { radius: 0.04 },
                caption: { offset: [0, -25], value: value1, position: 'bottom' },
                value: 5,
                style: { stroke: '#ffffff', 'stroke-width': '1px', fill: '#00CED1' },
                animationDuration: 1500,
                colorScheme: 'scheme04',
                //labels: labels,
                ticksMinor: { interval: 5, size: '10%' },
                ticksMajor: { interval: 1, size: '5%' },
            });

            $('#gauge2').jqxGauge('setValue', value);
        });


        //PLANT TEAM LEADER AVAILABILITY
        $(document).ready(function () {
            var labels = { visible: true, position: 'inside'};
             var value = <?php echo $plantTLAvail; ?>;
            var value1 = value+"%<br>Daily Plant <br>T/L Availability";

            //Create jqxGauge
            $('#gauge3').jqxGauge({
                ranges: [{ startValue: 0, endValue: 25, style: { fill: '#d02841', stroke: '#d02841' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 25, endValue: 50, style: { fill: '#FF6347', stroke: '#FF6347' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 50, endValue: 75, style: { fill: '#f6de54', stroke: '#f6de54' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 },
                         { startValue: 75, endValue: 100, style: { fill: '#00FF7F', stroke: '#00FF7F' }, startDistance: '5%', endDistance: '5%', endWidth: 13, startWidth: 13 }
                ],
                cap: { radius: 0.04 },
                caption: { offset: [0, -25], value: value1, position: 'bottom' },
                value: 5,
                style: { stroke: '#ffffff', 'stroke-width': '1px', fill: '#00CED1' },
                animationDuration: 1500,
                colorScheme: 'scheme04',
                //labels: labels,
                ticksMinor: { interval: 5, size: '10%' },
                ticksMajor: { interval: 1, size: '5%' },
            });

            $('#gauge3').jqxGauge('setValue', value);
        });




//BAR CHARTS

//DAILY EFICIENCY PER SHOP
$(document).ready(function () {
var shopnames = <?php echo $shopnames; ?>;
  var shopcolor = <?php echo $shopcolor; ?>;
  var shop_eff = <?php echo $shop_eff; ?>;
  var tltarget = <?php echo $planteff; ?>;

const data = {
      labels: shopnames,//['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: '% DAILY EFICIENCY',
        data: shop_eff,//[70, 45, 0, 66, 40, 83, 69, 57, 70, 50, 75],
        backgroundColor: [
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(0, 0, 0, 0.2)',
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
        ],
        borderColor: [
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(0, 0, 0, 1)',
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
        ],
        borderWidth: 1
      }]
    };

    const horizontalDottedLine ={
        id: "horizontalDottedLine",
        beforeDatasetsDraw(chart, args, options){
            const {ctx, chartArea: {top, right, bottom, left, width, height},
                scales: {x, y}} = chart;
            ctx.save();
            ctx.strokeStyle = 'grey';
            ctx.strokeRect(left, y.getPixelForValue(tltarget), width, 0);
            ctx.restore();
        }
    }

    // config
    const config = {
      type: 'bar',
      data,
      options: {
        scales: {
                y: {
                beginAtZero: true
            }
            }

      },
      plugins: [horizontalDottedLine, ChartDataLabels],
      //plugins: [ChartDataLabels]
    };

    // render init block
    const myChart = new Chart(
      document.getElementById('bar-chart-horizontal'),
      config
    );
});

  /*new Chart(document.getElementById("bar-chart-horizontal"), {
		type: 'bar',
		data: {
		  labels: shopnames,
		  datasets: [
			{
			  label: "% Efficiency",
			  backgroundColor: shopcolor,
			  data: shop_eff//[88,67,54,84,33,58,67,54,94,63,74]
			}
		  ]
		},
		options: {
		  legend: { display: false },
		  title: {
			display: true,
			text: 'DAILY EFICIENCY PER SHOP'
		  },
          scales: {
                yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: '% EFFICIENCY'
                },
                ticks: {
                    suggestedMin: 0,
                    suggestedMax: 120
                }
                }],
                xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'SHOP'
                }
                }]
            },

    }
});*/

//MTD EFICIENCY PER SHOP
$(document).ready(function () {
var shopnames = <?php echo $shopnames; ?>;
  var shopcolor = <?php echo $shopcolor; ?>;
  var MTDshop_eff = <?php echo $MTDshop_eff; ?>;
  var tltarget = <?php echo $planteff; ?>;

const data = {
      labels: shopnames,//['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: '% MTD EFICIENCY',
        data: MTDshop_eff,//[70, 45, 0, 66, 40, 83, 69, 57, 70, 50, 75],
        backgroundColor: [
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(0, 0, 0, 0.2)',
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
        ],
        borderColor: [
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(0, 0, 0, 1)',
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
        ],
        borderWidth: 1
      }]
    };

    const horizontalDottedLine ={
        id: "horizontalDottedLine",
        beforeDatasetsDraw(chart, args, options){
            const {ctx, chartArea: {top, right, bottom, left, width, height},
                scales: {x, y}} = chart;
            ctx.save();
            ctx.strokeStyle = 'grey';
            ctx.strokeRect(left, y.getPixelForValue(tltarget), width, 0);
            ctx.restore();
        }
    }

    // config
    const config = {
      type: 'bar',
      data,
      options: {
        scales: {
                y: {
                beginAtZero: true
            }
            }

      },
      plugins: [horizontalDottedLine, ChartDataLabels],
      //plugins: [ChartDataLabels]
    };

    // render init block
    const myChart = new Chart(
      document.getElementById('bar-chart-horizontal1'),
      config
    );
});

  /*new Chart(document.getElementById("bar-chart-horizontal1"), {
		type: 'bar',
		data: {
		  labels: shopnames,
		  datasets: [
			{
			  label: "% Efficiency",
			  backgroundColor: shopcolor,
			  data: MTDshop_eff//[88,67,54,84,33,58,67,54,94,63,74]
			}
		  ]
		},
		options: {
		  legend: { display: false },
		  title: {
			display: true,
			text: 'MTD EFICIENCY PER SHOP'
		  },
          scales: {
                yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: '% EFFICIENCY'
                },
                ticks: {
                    suggestedMin: 0,
                    suggestedMax: 120
                }
                }],
                xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'SHOP'
                }
                }]
            }
    }
});*/



//MTD ABSENTIEESM PER SHOP
$(document).ready(function () {
    var shopnames = <?php echo $shopnames; ?>;
  var shopcolor = <?php echo $shopcolor; ?>;
  var absentiesm = <?php echo $absentiesm; ?>;
  var tltarget = <?php echo $plantabb; ?>;

const data = {
      labels: shopnames,//['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: '% MTD ABSENTIEESM',
        data: absentiesm,//[70, 45, 0, 66, 40, 83, 69, 57, 70, 50, 75],
        backgroundColor: [
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(0, 0, 0, 0.2)',
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
        ],
        borderColor: [
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(0, 0, 0, 1)',
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
        ],
        borderWidth: 1
      }]
    };

    const horizontalDottedLine ={
        id: "horizontalDottedLine",
        beforeDatasetsDraw(chart, args, options){
            const {ctx, chartArea: {top, right, bottom, left, width, height},
                scales: {x, y}} = chart;
            ctx.save();
            ctx.strokeStyle = 'grey';
            ctx.strokeRect(left, y.getPixelForValue(tltarget), width, 0);
            ctx.restore();
        }
    }

    // config
    const config = {
      type: 'bar',
      data,
      options: {
        scales: {
                y: {
                beginAtZero: true
            }
            }

      },
      plugins: [horizontalDottedLine, ChartDataLabels],
      //plugins: [ChartDataLabels]
    };

    // render init block
    const myChart = new Chart(
      document.getElementById('bar-chart-horizontal2'),
      config
    );
});
  /*new Chart(document.getElementById("bar-chart-horizontal2"), {
		type: 'bar',
		data: {
		  labels: shopnames,
		  datasets: [
			{
			  label: "% Absentieesm",
			  backgroundColor: shopcolor,
			  data: absentiesm//[88,67,54,84,33,58,67,54,94,63,74]
			}
		  ]
		},
		options: {
		  legend: { display: false },
		  title: {
			display: true,
			text: 'DAILY ABSENTIEESM PER SHOP'
		  },
          scales: {
                yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: '% ABSENTIEESM'
                },
                ticks: {
                    suggestedMin: 0,
                    suggestedMax: 120
                }
                }],
                xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'SHOP'
                }
                }]
            }
    }
});*/


//TEAM LEADER AVAILABILITY
$(document).ready(function () {
var shopnames = <?php echo $shopnames; ?>;
var shopcolor = <?php echo $shopcolor; ?>;
var shopTLavail = <?php echo $shopTLavail; ?>;
var tltarget = <?php echo $planttlav; ?>;

const data = {
      labels: shopnames,//['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: '% MTD T/L AVAILABILITY',
        data: shopTLavail,//[70, 45, 0, 66, 40, 83, 69, 57, 70, 50, 75],
        backgroundColor: [
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(0, 0, 0, 0.2)',
          'rgba(255, 26, 104, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
        ],
        borderColor: [
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(0, 0, 0, 1)',
          'rgba(255, 26, 104, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
        ],
        borderWidth: 1
      }]
    };

    const horizontalDottedLine ={
        id: "horizontalDottedLine",
        beforeDatasetsDraw(chart, args, options){
            const {ctx, chartArea: {top, right, bottom, left, width, height},
                scales: {x, y}} = chart;
            ctx.save();
            ctx.strokeStyle = 'grey';
            ctx.strokeRect(left, y.getPixelForValue(tltarget), width, 0);
            ctx.restore();
        }
    }

    // config
    const config = {
      type: 'bar',
      data,
      options: {
        scales: {
                y: {
                beginAtZero: true
            }
            }

      },
      plugins: [horizontalDottedLine, ChartDataLabels],
      //plugins: [ChartDataLabels]
    };

    // render init block
    const myChart = new Chart(
      document.getElementById('bar-chart-horizontal3'),
      config
    );
});
/*var shopnames = <?php echo $shopnames; ?>;
  var shopcolor = <?php echo $shopcolor; ?>;
  var shopTLavail = <?php echo $shopTLavail; ?>;

  new Chart(document.getElementById("bar-chart-horizontal3"), {
		type: 'bar',
		data: {
		  labels: shopnames,
		  datasets: [
			{
			  label: "% AVAILABILITY",
			  backgroundColor: shopcolor,
			  data: shopTLavail//[88,67,54,84,33,58,67,54,94,63,74]
			}
		  ]
		},
		options: {
		  legend: { display: false },
		  title: {
			display: true,
			text: 'TEAMLEADER AVAILABILITY PER SHOP'
		  },
          scales: {
                yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: '% AVAILABILITY'
                },
                ticks: {
                    suggestedMin: 0,
                    suggestedMax: 120
                }
                }],
                xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'SHOP'
                }
                }]
            }
    }
});*/

    </script>



