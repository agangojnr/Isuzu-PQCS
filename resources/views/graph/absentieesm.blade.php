
@extends('layouts.app')
@section('title','Absentieesm Report')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Absetieesm Report</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Absetieesm</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>THIS MONTH</small></h6>
                        <h4 class="mt-0 text-info">$58,356</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="monthchart"></div>
                    </div>
                </div>
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>LAST MONTH</small></h6>
                        <h4 class="mt-0 text-primary">$48,356</h4>
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

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <div class="row">

                            <div class="col-lg-10">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">ABSENTIEESM</h4>
                                        <div>
                                            <div id="canvas-holder" style="width:100%">
                                                <canvas id="gauge-chart"></canvas>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-10">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">ABSENTIEESM</h4>
                                        <div>
                                            <canvas id="bar-chart-horizontal" height="200"> </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--<div id='myChart'><a class="zc-ref" href="https://www.zingchart.com/">Charts by ZingChart</a></div>-->

                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/chart.js@2.8.0/dist/Chart.bundle.js"></script>
  <script src="https://unpkg.com/chartjs-gauge@0.3.0/dist/chartjs-gauge.js"></script>
    <script src="{{ asset('dist/js/pages/chartjs/chartjs.init.js') }}"></script>

<script>

var data = [ 10, 10,10,0 ];
var value = <?php echo $plant_absentpc; ?>;

var config = {
  type: 'gauge',
  data: {
    datasets: [{
      value: value,
      data: [10, 50, 75, 100],
      backgroundColor: ['green', 'yellow', 'orange', 'red'],
      borderWidth: 2
    }],
    },

  options: {
    responsive: true,
    title: {
      display: true,
      text: 'Plant Absentieesm'
    },


    layout: {
      padding: {
        bottom: 30
      }
    },
    needle: {
      radiusPercentage: 2,
      widthPercentage: 3.2,
      lengthPercentage: 100,
      rangeLabel: ['0', '100'],
      color: 'blue'
    },

    valueLabel: {
      formatter: Math.round
    }
  }
};

window.onload = function() {
  var ctx1 = document.getElementById('gauge-chart').getContext('2d');
  window.myGauge = new Chart(ctx1, config);
};



        // Horizental Bar Chart
        var shopnames = <?php echo $shopnames; ?>;
        var shopcolor = <?php echo $shopcolor; ?>;
        var absentiesm = <?php echo $absentiesm; ?>;
	new Chart(document.getElementById("bar-chart-horizontal"), {
		type: 'horizontalBar',
		data: {
		  labels: shopnames,
		  datasets: [
			{
			  label: "Absentieesm Percentage",
			  backgroundColor: shopcolor,
			  data: absentiesm
			}
		  ]
		},
		options: {
		  legend: { display: false },
		  title: {
			display: true,
			text: 'Percentage Absentieesm per Day'
		  },
          scales: {
            xAxes: [{
                ticks: {
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            }]
        }
		}
	});




    /*
var myConfig = {
  type: "gauge",
  globals: {
    fontSize: 25
  },
  plotarea: {
    marginTop: 80
  },
  plot: {
    size: '100%',
    valueBox: {
      placement: 'center',
      text: '%v', //default
      fontSize: 30,
      rules: [{
          rule: '%v >= 70',
          text: '%v<br>EXCELLENT'
        },
        {
          rule: '%v < 70 && %v > 60',
          text: '%v<br>Good'
        },
        {
          rule: '%v < 60 && %v > 50',
          text: '%v<br>Fair'
        },
        {
          rule: '%v <  50',
          text: '%v<br>Bad'
        }
      ]
    }
  },
  tooltip: {
    borderRadius: 5
  },
  scaleR: {
    aperture: 180,
    minValue: 0,
    maxValue: 100,
    step: 20,
    center: {
      visible: false
    },
    tick: {
      visible: false
    },
    item: {
      offsetR: 0,
      rules: [{
        rule: '%i == 9',
        offsetX: 15
      }]
    },
    labels: ['0',  '20',  '40','60',  '80',  '100'],
    ring: {
      size: 50,
      rules: [{
          rule: '%v <= 10',
          backgroundColor: '#E53935'
        },
        {
          rule: '%v > 10 && %v < 60',
          backgroundColor: '#EF5350'
        },
        {
          rule: '%v >= 60 && %v < 70',
          backgroundColor: '#FFA726'
        },
        {
          rule: '%v >= 70',
          backgroundColor: '#29B6F6'
        }
      ]
    }
  },

  series: [{
    values: [value], // starting value
    backgroundColor: 'black',
    indicator: [10, 10, 10, 10, 0.75],

  }]
};

zingchart.render({
  id: 'myChart',
  data: myConfig,
  height: 500,
  width: '100%'
});
*/

    </script>

@endsection

