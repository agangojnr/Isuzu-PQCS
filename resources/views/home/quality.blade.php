@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 col-12 align-self-center">
        <h3 class="text-themecolor mb-0">Dashboard</h3>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
    <!--<div class="col-md-7 col-12 align-self-center d-none d-md-block">
        <div class="d-flex mt-2 justify-content-end">
            <div class="d-flex mr-3 ml-2">
                <div class="chart-text mr-2">
                    <h6 class="mb-0"><small>THIS MONTH</small></h6>
                    <h4 class="mt-0 text-info">12 Units</h4>
                </div>
                <div class="spark-chart">
                    <div id="monthchart"></div>
                </div>
            </div>
            <div class="d-flex ml-2">
                <div class="chart-text mr-2">
                    <h6 class="mb-0"><small>Last Month</small></h6>
                    <h4 class="mt-0 text-primary">48 Units</h4>
                </div>
                <div class="spark-chart">
                    <div id="lastmonthchart"></div>
                </div>
            </div>
        </div>
    </div>-->
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">




 <div class="card border-warning">
    <div class="card-header bg-warning">
        <h4 class="mb-0 text-white">Quality Control Section</h4></div>
    <div class="card-body">
        <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-md-6  ">
                        <div class="card  border-success">

                            <div class="card-header bg-success">
                               <h4 class="mb-0 text-white">DRR [PLANT MONTH TO DATE]</h4></div> 
                            <div class="card-body">
                               
                                <div class="gaugejs-box">
                                    <canvas id="foo" class="gaugejs">guage</canvas>
                                    <div class="  text-center">
                                        <h4 class="font-weight-medium mb-0"> {{$master['plant_drr']}}%</h4>
                                     </div>
                                </div>
                            </div>
                            <div class="box p-2 border-top text-center">
                                <div class="row">
                                     <div class="col-lg-6 col-md-6">
                                        <h4 class="font-weight-medium mb-0">ACTUAL : {{$master['plant_drr']}}%</h4>
                                     </div>


                                      <div class="col-lg-6 col-md-6">
                                        <h4 class="font-weight-medium mb-0">TARGET : 70%</h4>
                                     </div>
                                    
                                </div>
                                </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->

                     <div class="col-lg-4 col-md-6  ">
                        <div class="card  border-success">

                            <div class="card-header bg-success">
                               <h4 class="mb-0 text-white">DRL [PLANT MONTH TO DATE]</h4></div> 
                            <div class="card-body">
                               
                                <div class="gaugejs-box">
                                    <canvas id="foo2" class="gaugejs">guage</canvas>
                                    <div class="  text-center">
                                        <h4 class="font-weight-medium mb-0"> {{$master['plant_drl']}} PPH</h4>
                                     </div>
                                </div>
                            </div>
                            <div class="box p-2 border-top text-center">
                                <div class="row">
                                     <div class="col-lg-6 col-md-6">
                                        <h4 class="font-weight-medium mb-0">ACTUAL :  {{$master['plant_drl']}}</h4>
                                     </div>


                                      <div class="col-lg-6 col-md-6">
                                        <h4 class="font-weight-medium mb-0">TARGET : {{$master['drl_plant_target']}}</h4>
                                     </div>
                                    
                                </div>
                                </div>
                        </div>
                    </div>


                    <!-- Column -->

                     <div class="col-lg-4 col-md-6  ">
                        <div class="card  border-success">

                            <div class="card-header bg-success">
                               <h4 class="mb-0 text-white">CARE [PLANT MONTH TO DATE]</h4></div> 
                            <div class="card-body">
                               
                                <div class="gaugejs-box">
                                    <canvas id="foo3" class="gaugejs">guage</canvas>
                                     <div class="  text-center">
                                        <h4 class="font-weight-medium mb-0"> {{$master['care_midscore']}}%</h4>
                                     </div>

                                </div>
                            </div>
                            <div class="box p-2 border-top text-center">
                                <div class="row">
                                     <div class="col-lg-6 col-md-6">
                                        <h4 class="font-weight-medium mb-0">ACTUAL : {{$master['care_midscore']}}</h4>
                                     </div>


                                      <div class="col-lg-6 col-md-6">
                                        <h4 class="font-weight-medium mb-0">TARGET : {{$master['care_target_details']}}</h4>
                                     </div>
                                    
                                </div>
                                </div>
                        </div>
                    </div>

               <!-- Column -->
                 
                </div>
                <!-- Row -->

    </div>
</div>

   


   

                  <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">DRR</h4>
                                <div class="d-flex flex-row">
                                    <div class="p-2 pl-0 border-right">
                                        <h6 class="font-weight-light">Today</h6><b>{{$master['daily_plant_drr']}} %</b></div>
                                    <div class="p-2 border-right">
                                        <h6 class="font-weight-light">MTD</h6><b>{{$master['plant_drr']}} %</b>
                                    </div>
                                    <div class="p-2">
                                        <h6 class="font-weight-light">YTD</h6><b>{{$master['year_plant_drr']}} %</b>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">DRL</h4>
                                <div class="d-flex flex-row">
                                    <div class="p-2 pl-0 border-right">
                                        <h6 class="font-weight-light">Today</h6><b>{{$master['daily_plant_drl']}} PPH</b></div>
                                    <div class="p-2 border-right">
                                        <h6 class="font-weight-light">MTD</h6><b> {{$master['plant_drl']}} PPH</b>
                                    </div>
                                    <div class="p-2">
                                        <h6 class="font-weight-light">YTD</h6><b>{{$master['year_plant_drl']}} PPH</b>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">CARE</h4>
                                <div class="d-flex flex-row">
                                    <div class="p-2 pl-0 border-right">
                                        <h6 class="font-weight-light">Daily</h6><b>{{$master['daily_care_midscore']}}%</b></div>
                                    <div class="p-2 border-right">
                                        <h6 class="font-weight-light">MTD</h6><b>{{$master['care_midscore']}} %</b>
                                    </div>
                                    <div class="p-2">
                                        <h6 class="font-weight-light">YTD</h6><b>{{$master['year_care_midscore']}} %</b>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <!-- Column -->

                          <!-- Column -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">REPAIR FLOAT</h4>
                                <div class="d-flex flex-row">
                                    <div class="p-2 pl-0 border-right">
                                        <h6 class="font-weight-light">Today</h6><b>{{$master['today_repair_float']}}</b></div>
                                    <div class="p-2 border-right">
                                        <h6 class="font-weight-light">MTD</h6><b>{{$master['mtd_repair_float']}}</b>
                                    </div>
                                    <div class="p-2">
                                        <h6 class="font-weight-light">YTD</h6><b>{{$master['ytd_repair_float']}}</b>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->


    <!-- Row -->
</div>
<style type="text/css">
    .label {

        margin-left:215px;
    margin-top:-80px;
}
</style>
@endsection

@section('after-scripts')

    


      {{ Html::script('assets/libs/gaugeJS/dist/gauge.min.js') }}

      <script type="text/javascript">
$(function() {
    "use strict";
    // ============================================================== 
    // Foo1 total visit
    // ============================================================== 
    var opts = {
        angle: 0, // The span of the gauge arc
        lineWidth: 0.2, // The line thickness
        radiusScale: 0.7, // Relative radius
        pointer: {
            length: 0.64, // // Relative to gauge radius
            strokeWidth: 0.04, // The thickness
            color: '#000000' // Fill color
        },
        limitMax: false, // If false, the max value of the gauge will be updated if value surpass max
        limitMin: false, // If true, the min value of the gauge will be fixed unless you set it manually
       // colorStart: '#009efb', // Colors
        //colorStop: '#009efb', // just experiment with them
        //strokeColor: '#E0E0E0', // to see which ones work best for you
        generateGradient: true,
        highDpiSupport: true,
        staticZones: [
    {strokeStyle: "#F03E3E", min: 0, max:'{{$master['plant_drr_target']}}' }, // Red from 100 to 130
   {strokeStyle: "#30B32D", min: '{{$master['plant_drr_target']}}', max: 130}  // Red

], // High resolution support
    };

    var target = document.getElementById('foo'); // your canvas element
    var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 130; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 45; // set animation speed (32 is default value)
    gauge.set('{{$master['plant_drr']}}'); // set actual value 
    // ============================================================== 
    // Foo1 total visit

     

       var opts = {
        angle: 0, // The span of the gauge arc
        lineWidth: 0.2, // The line thickness
        radiusScale: 0.7, // Relative radius
        pointer: {
            length: 0.64, // // Relative to gauge radius
            strokeWidth: 0.04, // The thickness
            color: '#000000' // Fill color
        },
        limitMax: false, // If false, the max value of the gauge will be updated if value surpass max
        limitMin: false, // If true, the min value of the gauge will be fixed unless you set it manually
        //colorStart: '#7460ee', // Colors
       // colorStop: '#7460ee', // just experiment with them
        //strokeColor: '#E0E0E0', // to see which ones work best for you
        generateGradient: true,
        highDpiSupport: true, // High resolution support
         staticZones: [
    {strokeStyle: "#30B32D", min: 0, max: '{{$master['drl_plant_target']}}' }, // Red 
   {strokeStyle: "#F03E3E", min: '{{$master['drl_plant_target']}}', max: 200}  // Green

],
    };
    var target = document.getElementById('foo2'); // your canvas element
    var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 200; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 45; // set animation speed (32 is default value)
    gauge.set('{{$master['plant_drl']}}'); // set actual value 
    // ============================================================== 
    // Foo1 total visit
    // ============================================================== 

        var opts = {
        angle: 0, // The span of the gauge arc
        lineWidth: 0.2, // The line thickness
        radiusScale: 0.7, // Relative radius
        pointer: {
            length: 0.64, // // Relative to gauge radius
            strokeWidth: 0.04, // The thickness
            color: '#000000' // Fill color
        },
        limitMax: false, // If false, the max value of the gauge will be updated if value surpass max
        limitMin: false, // If true, the min value of the gauge will be fixed unless you set it manually
       // colorStart: '#f62d51', // Colors
        //colorStop: '#f62d51', // just experiment with them
        //strokeColor: '#E0E0E0', // to see which ones work best for you
        generateGradient: true,
               highDpiSupport: true, // High resolution support
         staticZones: [
    {strokeStyle: "#F03E3E", min: 0, max:'{{$master['plant_drr_target']}}' }, // Red from 100 to 130
   {strokeStyle: "#30B32D", min: '{{$master['plant_drr_target']}}', max: 130}  // Red

],
    };
    var target = document.getElementById('foo3'); // your canvas element
    var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 130; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 45; // set animation speed (32 is default value)
    gauge.set('{{$master['care_midscore']}}'); // set actual value 

    // ============================================================== 
    // Foo1 total visit
    // ============================================================== 
});
    </script>

        @endsection
