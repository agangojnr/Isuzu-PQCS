@extends('layouts.app')

@section('content')
<div class="row page-titles">

</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">

<div class="card-columns widget-app-columns">
             <div class="card">
                <div class="card-body">
                    <div class="d-flex ">
                        <h4 class="card-title ml-3">PEOPLE</h4>

                    </div>

                    <div class="col-lg-12 mb-4">
                        <div class="card-header bg-primary mb-3">
                           <h4 class="mb-0 text-white">MTD ABSENTEEISM</h4>
                        </div>
                            <div class="gaugejs-box">
                                <canvas id="absentieesm" class="gaugejs1">guage</canvas>
                                <hr>
                                <div class="text-center aline">
                                    <h4 class="font-weight-light mb-3"><span style="margin-right: 12%;">Actual: {{$master['absentiesm']}}%</span>
                                        Target: {{$master['plantabb']}}%</h4>

                                 </div>
                            </div>
                    </div>

                    <div class="col-lg-12 mb-4">
                        <div class="card-header bg-primary mb-3">
                           <h4 class="mb-0 text-white">MTD T/L AVAILABILITY</h4>
                        </div>
                            <div class="gaugejs-box">
                                <canvas id="tlavail" class="gaugejs2">guage</canvas>
                                <hr>
                                <div class="text-center aline">
                                    <h4 class="font-weight-light mb-3"><span style="margin-right: 12%;">Actual: {{$master['TLavail']}}%</span>
                                        Target: {{$master['planttlav']}}%</h4>
                                 </div>
                            </div>
                    </div>
                    </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <h4 class="card-title  ml-3"><a href="{{route('quality_control_dashboard')}} ">QUALITY</a></h4>
                            </div>
							 <div class="col-lg-12">
                                <div class="card-header bg-orange">
                                    <h4 class="mb-0 text-white">MTD SCORE</h4>
                                </div>
                                <div class="row text-center mt-2">
                                    <div class="col-sm-4">
                                    </div>
                                    <div class="col-sm-4">
                                        <h5>Target</h5>
                                    </div>
                                    <div class="col-sm-4">
                                        <h5>Actual</h5>
                                    </div>
                                </div>


                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                            <h5>DRL</h5>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="border text-center pt-2">
                                            <h2 class="fs-30 font-w600 counter">{{month_to_date_drl()['drl_target_value']}}</h2>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="border text-center pt-2">
                                            <h2 class="fs-30 font-w600 counter">
                                                @if (month_to_date_drl()['drl'] <= month_to_date_drl()['drl_target_value'])
                                                    <span style="color:rgb(6, 236, 6);">{{month_to_date_drl()['drl']}}</span>
                                                @else
                                                    <span style="color:red;">{{month_to_date_drl()['drl']}}</span>
                                                @endif
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 ">
                                                <h5>DRR</h5>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="border text-center pt-2">
                                                <h2 class="fs-30 font-w600 counter">{{month_to_date_drr()['drr_target_value']}}</h2>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="border text-center pt-2">
                                                <h2 class="fs-30 font-w600 counter">
                                                    @if (month_to_date_drr()['plant_drr'] >= month_to_date_drr()['drr_target_value'])
                                                        <span style="color:rgb(6, 236, 6);">{{month_to_date_drr()['plant_drr']}}</span>
                                                    @else
                                                        <span style="color:red;">{{month_to_date_drr()['plant_drr']}}</span>
                                                    @endif
                                                </h2>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-4">
                                                <h5>CARE</h5>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="border text-center pt-2">
                                                <h2 class="fs-30 font-w600 counter">{{month_to_date_drr()['care_target_value']}}</h2>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="border text-center pt-2">
                                                <h2 class="fs-30 font-w600 counter">
                                                    @if (month_to_date_drr()['care'] >= month_to_date_drr()['care_target_value'])
                                                        <span style="color:rgb(6, 236, 6);">{{month_to_date_drr()['care']}}</span>
                                                    @else
                                                        <span style="color:red;">{{month_to_date_drr()['care']}}</span>
                                                    @endif
                                                </h2>
                                            </div>
                                        </div>
                                </div>
                        </div>
                    <hr>

                    <div class="col-lg-12">
                        <div class="card-header bg-orange">
                            <h4 class="mb-0 text-white">MTD GCA SCORE</h4>
                        </div>

                        <table width="100%" class="text-center mt-3">
                            <tr>
                                <th></th>
                                <th colspan="2">DPV</th>
                                <th colspan="2" class="ml-1">WDPV</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="pt-2"><i>Target</i></td>
                                <td class="pt-2"><i>Actual</i></td>
                                <td class="ml-1 pt-2"><i>Target</i></td>
                                <td  class="pt-2"><i>Actual</i></td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>CV</h4>
                                </td>
                                <td>
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600"><span>{{$master['cvdpvtarget']}}</span> </h4>
                                    </div>
                                </td>
                                <td class="pr-1">
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600">
                                            @if ($master['cvdpv'] <= $master['cvdpvtarget'])
                                                <span style="color:rgb(6, 236, 6);">{{$master['cvdpv']}}</span>
                                            @else
                                                <span class="text-danger">{{$master['cvdpv']}}</span>
                                            @endif
                                        </h4>
                                    </div>
                                </td>
                                <td class="pl-1">
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600"><span>{{$master['cvwdpvtarget']}}</span> </h4>
                                    </div>
                                </td>
                                <td>
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600">
                                            @if ($master['cvwdpv'] <= $master['cvwdpvtarget'])
                                                <span style="color:rgb(6, 236, 6);">{{$master['cvwdpv']}}</span>
                                            @else
                                                <span class="text-danger">{{$master['cvwdpv']}}</span>
                                            @endif
                                        </h4>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>LCV</h4>
                                </td>
                                <td>
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600"><span>{{$master['lcvdpvtarget']}}</span> </h4>
                                    </div>
                                </td>
                                <td class="pr-1">
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600">
                                            @if ($master['lcvdpv'] <= $master['lcvdpvtarget'])
                                                <span style="color:rgb(6, 236, 6);">{{$master['lcvdpv']}}</span>
                                            @else
                                                <span class="text-danger">{{$master['lcvdpv']}}</span>
                                            @endif
                                        </h4>
                                    </div>
                                </td>
                                <td class="pl-1">
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600"><span>{{$master['lcvwdpvtarget']}}</span> </h4>
                                    </div>
                                </td>
                                <td>
                                    <div class="border text-center pt-2">
                                        <h4 class="fs-26 font-w600">
                                            @if ($master['lcvwdpv'] <= $master['lcvwdpvtarget'])
                                                <span style="color:rgb(6, 236, 6);">{{$master['lcvwdpv']}}</span>
                                            @else
                                                <span class="text-danger">{{$master['lcvwdpv']}}</span>
                                            @endif
                                        </h4>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                   </div>
                </div>



                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <h4 class="card-title  ml-3">RESPONSIVENESS</h4>
                            </div>

                          <div class="col-lg-12">
                            <div class="card-header bg-info mb-3">
                                <h4 class="mb-0 text-white">MTD OFFLINE</h4>
                                </div>
                                <div class="table-responsive1">
                                    <table class="tablesaw table-bordered text-center">
                                        <thead>
                                            <th><h5>Actual</h5></th>
                                            <th><h5>Target</h5></th>
                                            <th><h5>Variance</h5></th>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                    <th><h4 class="fs-26 font-w600 text-center"><b>{{$master['offline']}}</b></h4></th>
                                                    <th><h4 class="fs-26 font-w600 text-center"><b>{{$master['offtarget']}}</b></h4></th>
                                                    <th><h4 class="fs-26 font-w600 text-center"><b>
                                                        @if ($master['offvar'] >= 0)
                                                            <span style="color:rgb(6, 236, 6);"><i class="mdi mdi-arrow-up"></i>
                                                                {{abs($master['offvar'])}}</span>
                                                        @else
                                                            <span class="text-danger"><i class="mdi mdi-arrow-down"></i>
                                                                {{abs($master['offvar'])}}</span>
                                                        @endif
                                                    </b></h4></th>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                        <hr>

                <div class="col-lg-12">
                    <div class="card-header bg-info mb-3">
                    <h4 class="mb-0 text-white">MTD FCW</h4>
                    </div>
                    <div class="table-responsive1">
                        <table class="tablesaw table-bordered text-center">
                            <thead>
                                <th><h5>Actual</h5></th>
                                <th><h5>Target</h5></th>
                                <th><h5>Variance</h5></th>
                            </thead>
                            <tbody>
                                    <tr>
                                        <th><h4 class="fs-26 font-w600 text-center"><b>{{$master['actual']}}</b></h4></th>
                                        <th><h4 class="fs-26 font-w600 text-center"><b>
                                            {{$master['fcwtarget']}}
                                        </b></h4 >
                                        </th>
                                        <th><h4 class="fs-26 font-w600 text-center"><b>
                                                @if ($master['fcwvarience'] >= 0)
                                                    <span style="color:rgb(6, 236, 6);"><i class="mdi mdi-arrow-up"></i>
                                                        {{abs($master['fcwvarience'])}}</span>
                                                @else
                                                    <span class="text-danger"><i class="mdi mdi-arrow-down"></i>
                                                        {{abs($master['fcwvarience'])}}</span>
                                                @endif

                                        </b></h4>
                                        </th>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>


                <div class="col-lg-12">
                    <div class="card-header bg-info mb-3">
                    <h4 class="mb-0 text-white">MTD EFFICIENCY</h4>
                    </div>
                    <div class="table-responsive1">
                        <table class="tablesaw table-bordered text-center">
                            <thead>
                                <th><h5>Actual</h5></th>
                                <th><h5>Target</h5></th>
                                <th><h5>Status</h5></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th><h4><b>{{$master['plant_eff']}}%</b></h4></th>
                                    <th><h4><b>
                                        {{$master['efftag']}}%
                                    </b></h4></th>
                                    <th class="text-center"><h4>
                                        @if ($master['plant_eff'] >= $master['efftag'])
                                        <span style="font-family: Arial Unicode MS, Lucida Grande; color:rgb(6, 236, 6);">
                                            &#10004;
                                        </span>
                                        @else
                                        <span style="font-family: Arial Unicode MS, Lucida Grande; color:red;">
                                            &#x2717;
                                        </span>
                                        @endif

                                        </h4></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<!-- Row -->
</div>
<div class="row">
    @if ($master['unlogged'] > 0)
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-2">
                    <h3 class="text-success mb-0">[{{$master['unlogged']}}]</h3>
                    <span class="text-muted">Unlogged Attendance</span>
                </div>
                <div class="p-2 bg-warning ml-auto ">
                    <h3 class="text-white p-2 mb-0 "><i class="ti-signal "></i></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($master['offscheduled'] == 0)
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-2">
                    <h3 class="text-success mb-0">[Missing]</h3>
                    <span class="text-muted">Offline Schedule</span>
                </div>
                <div class="p-2 bg-warning ml-auto ">
                    <h3 class="text-white p-2 mb-0 "><i class="ti-timer"></i></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($master['fcwscheduled'] == 0)
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-2">
                    <h3 class="text-success mb-0">[Missing]</h3>
                    <span class="text-muted">FCW Schedule</span>
                </div>
                <div class="p-2 bg-warning ml-auto ">
                    <h3 class="text-white p-2 mb-0 "><i class="ti-timer"></i></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="p-2">
                    <h3 class="text-success mb-0">[{{$master['delayed']}}]</h3>
                    <span class="text-muted">Delayed Units</span>
                </div>
                <div class="p-2 bg-warning ml-auto ">
                    <h3 class="text-white p-2 mb-0 "><i class="ti-server"></i></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
.gaugejs1 {
        margin-top: 0px;
        width: 52%;
        margin-left: 20%;
}
.gaugejs2 {
        margin-top: 0px;
        width: 52%;
        margin-left: 20%;
2

.aline{
    margin-bottom: 9%;
}

</style>
@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
@section('after-scripts')
{{ Html::script('assets/libs/gaugeJS/dist/gauge.min.js') }}
{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}

{!! Toastr::message() !!}

<script type="text/javascript">

$(function(){
    //EFFICIENCY
   /* var opts = {
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
    {strokeStyle: "#F03E3E", min: 0, max:'{{$master['efftag']}}' }, // Red from 100 to 130
   {strokeStyle: "#30B32D", min: '{{$master['efftag']}}', max: 100}  // Red

], // High resolution support
    };
    var value = '{{$master['plant_eff']}}';
    var effy = document.getElementById('efficiency'); // your canvas element
    var gauge = new Gauge(effy).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 100; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 45; // set animation speed (32 is default value)
    gauge.set(value); // set actual value
*/

//ABSENTIEESM
var opts = {
        angle: 0, // The span of the gauge arc
        lineWidth: 0.2, // The line thickness
        radiusScale: 1.1, // Relative radius
        pointer: {
            length: 0.60, // // Relative to gauge radius
            strokeWidth: 0.05, // The thickness
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
        {strokeStyle: "#30B32D", min: 0, max:'{{$master['plantabb']}}' }, // Red from 100 to 130
        {strokeStyle: "#F03E3E", min: '{{$master['plantabb']}}', max: 100}  // Red

], // High resolution support
    };
    var value = '{{$master['absentiesm']}}';
    var effy = document.getElementById('absentieesm'); // your canvas element
    var gauge = new Gauge(effy).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 100; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 45; // set animation speed (32 is default value)
    gauge.set(value); // set actual value


    //TL AVAIBAVILITY
    var opts = {
        angle: 0, // The span of the gauge arc
        lineWidth: 0.2, // The line thickness
        radiusScale: 1.1, // Relative radius
        pointer: {
            length: 0.60, // // Relative to gauge radius
            strokeWidth: 0.05, // The thickness
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
    {strokeStyle: "#F03E3E", min: 0, max:'{{$master['planttlav']}}' }, // Red from 100 to 130
    {strokeStyle: "#30B32D", min: '{{$master['planttlav']}}', max: 100}  // Red

], // High resolution support
    };
    var value = '{{$master['TLavail']}}';
    var effy = document.getElementById('tlavail'); // your canvas element
    var gauge = new Gauge(effy).setOptions(opts); // create sexy gauge!
    gauge.maxValue = 100; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 45; // set animation speed (32 is default value)
    gauge.set(value); // set actual value

});



$(function() {
    "use strict";

         $("#sparkline1").sparkline([0,75,80,56,96,90,56,85,33,60,60,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#e3c1f0',
            fillColor: '#e3c1f0',
            minSpotColor:'#e3c1f0',
            maxSpotColor: '#e3c1f0',
            highlightLineColor: 'rgba(227, 193, 240, 0.2)',
            highlightSpotColor: '#e3c1f0'
        });


   $("#sparkline2").sparkline([0,75,80,56,96,90,56,85,33,60,60,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#bae592',
            fillColor: '#bae592',
            minSpotColor:'#bae592',
            maxSpotColor: '#bae592',
            highlightLineColor: 'rgba(227, 193, 240, 0.2)',
            highlightSpotColor: '#bae592'
        });


       $("#sparkline3").sparkline([0,56,55,55,85,58,57,60,50,70,80,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#1e88e5',
            fillColor: '#1e88e5',
            minSpotColor:'#1e88e5',
            maxSpotColor: '#1e88e5',
            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
            highlightSpotColor: '#1e88e5'
        });

  $("#sparkline4").sparkline([0,56,55,55,85,58,57,60,50,70,80,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#ffa056',
            fillColor: '#ffa056',
            minSpotColor:'#ffa056',
            maxSpotColor: '#ffa056',
            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
            highlightSpotColor: '#ffa056'
        });

    $("#sparkline5").sparkline([0,56,55,55,85,58,57,60,50,70,80,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#8dddd0',
            fillColor: '#8dddd0',
            minSpotColor:'#8dddd0',
            maxSpotColor: '#8dddd0',
            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
            highlightSpotColor: '#8dddd0'
        });

        $("#sparklineGCA").sparkline([0,56,55,55,85,58,57,60,50,70,80,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#8dddd0',
            fillColor: '#8dddd0',
            minSpotColor:'#8dddd0',
            maxSpotColor: '#8dddd0',
            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
            highlightSpotColor: '#8dddd0'
        });

        $("#sparkline6").sparkline([0,56,55,55,85,58,57,60,50,70,80,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#ccc210',
            fillColor: '#ccc210',
            minSpotColor:'#ccc210',
            maxSpotColor: '#ccc210',
            highlightLineColor: 'rgba(204, 194, 16, 0.2)',
            highlightSpotColor: '#ccc210'
        });

        $("#sparkline7").sparkline([0,56,55,55,85,58,57,60,50,70,80,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#00ffff',
            fillColor: '#00ffff',
            minSpotColor:'#00ffff',
            maxSpotColor: '#00ffff',
            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
            highlightSpotColor: '#00ffff'
        });


         $("#sparkline8").sparkline([0,70,50,50,80,90,50,80,60,60,60,75 ], {
            type: 'line',
            width: '100%',
            height: '50',
            lineColor: '#fc4b6c',
            fillColor: '#fc4b6c',
            minSpotColor:'#fc4b6c',
            maxSpotColor: '#fc4b6c',
            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
            highlightSpotColor: '#fc4b6c'
        });

});

</script>

@endsection

