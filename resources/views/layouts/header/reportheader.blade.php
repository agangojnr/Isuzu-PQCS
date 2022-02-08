<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Standard Working Hours</title>

    @include('layouts.header.header')
    @yield('after-styles')

<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles" style="background-color:#da251c; ">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-white mb-0">DAILY ATTENDANCE AND EFFICIENCY REPORT</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-white">REPORTS</li>
            </ol>

        </div>
        <div class="col-md-7">
            <div class="row float-left w-100">
                <div class="col-lg-7">
                    <span  class="btn waves-effect waves-light btn-lg"
                    style="background-color: #DAF7A6; opacity: 0.9; font-familiy:Times New Roman;">

                    <h5 class="float-right mt-2">{{\Carbon\Carbon::today()->format('j M Y')}}</h5></span>
                </div>
                <div class="col-5">
                    <a href="/home" id="btn-add-contact" class="btn btn-primary float-right"
                ><i class="mdi mdi-arrow-left font-16"></i> Back to Home</a>
                </div>
            </div>

        </div>
    </div>
    <div class="row ">

    </div>
      <!-- Row -->
      <div class="row ml-1 mr-1 mb-2">
        <div class="col-lg-3">
        <a href="/headcount" class="btn waves-effect waves-light btn-lg btn-primary pt-3 pb-3 w-100">
            <i class="display-8 cc DASH-alt text-white" title="LTC"></i>
            <h6 class="text-white float-right mt-2 mr-2">MANPOWER (HOURS WORKED)</h6></a>
        </div>
        <div class="col-lg-3">
        <a href="/prodnoutput" class="btn waves-effect waves-light btn-lg btn-warning pt-3 pb-3 w-100">
            <i class="display-8 cc DASH-alt text-white" title="LTC"></i>
            <h6 class="text-white float-right mt-2">STANDARD HOURS GENERATED</h6></a>
        </div>
        <!--<div class="col-lg-3">
        <a href="{{route('weeklystdhrs')}}" class="btn waves-effect waves-light btn-lg btn-danger pt-3 pb-3 w-100">
            <i class="display-8 cc DASH-alt text-white" title="LTC"></i>
            <h6 class="text-white float-right mt-2">WEEKLY STANDARD & ACTUAL HRS</h6></a>
        </div>-->
        <!--<div class="col-lg-2">
            <a href="{{route('weeklyactualhrs')}}" class="btn waves-effect waves-light btn-lg btn-success pt-3 pb-3 w-100">
            <i class="display-8 cc DASH-alt text-white" title="LTC"></i>
            <h6 class="text-white float-right mt-2">WEEKLY ACTUAL HRS</h6></a>
        </div>-->
        <div class="col-lg-3">
            <a href="/attendancereport" class="btn waves-effect waves-light btn-lg pt-3 pb-3 w-100" style="background-color: #AEAEAE;">
            <i class="display-8 cc DASH-alt text-white" title="LTC"></i>
            <h6 class="text-white float-right mt-2">INDIV. REGISTER (HRS PER SHOP)</h6></a>
        </div>
        <div class="col-lg-3">
            <a href="{{route('peopleAttreport')}}" class="btn waves-effect waves-light btn-lg btn-dark pt-3 pb-3 w-100">
            <i class="display-8 cc DASH-alt text-white" title="LTC"></i>
            <h6 class="text-white float-right mt-2">TARGET REPORTS</h6></a>
        </div>


    </div>
