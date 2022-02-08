@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 col-12 align-self-center">
        <h3 class="text-themecolor mb-0">PEOPLE ATTENDANCE SUMMARY REPORT</h3>
        <ol class="breadcrumb mb-0 p-0 bg-transparent">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active">SUMMARY REPORTS</li>
        </ol>
    </div>
    <div class="col-7">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-4">
                <a href="{{route('reportsummary')}}" class="btn waves-effect waves-light btn-lg btn-warning pt-1 pb-1 w-100">
                    <h6 class="text-white mt-2">TODAY'S SUMMARY</h6></a>
            </div>
            <div class="col-5">
                <a href="{{route('yestreportsummary')}}" class="btn waves-effect waves-light btn-lg btn-cyan pt-1 pb-1 w-100">
                    <h6 class="text-white mt-2">YESTERDAY'S SUMMARY</h6></a>
            </div>

        </div>

    </div>

</div>

     <!-- Individual column searching (select inputs) -->
     <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-block"><h4>PEOPLE ATTENDANCE REPORT SUMMARY ({{\Carbon\Carbon::today()->format('j M Y')}})</h4>

                        <hr>
                        <div class="table-responsive">
                                        <table class="table">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>#</th>
                                                    <th>SUMMARY DESCRIPTION</th>
                                                    <th>RESULT</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                    <tr>
                                                        <td>#</td>
                                                        <td>YTD CUM HOURS WORKED:</td>
                                                        <td>{{round($cumhrsyear, 2)}} Hrs</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>YTD CUM HOURS GENERATED:</td>
                                                        <td>{{round($cumstdhrsyear,2)}} Hrs</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>YTD EFFICIENCY:</td>
                                                        <td>{{$YTDcumeff}}%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>DAILY DIR. LBR ABSENT (%):</td>
                                                        <td>???</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>DAILY DIRECT OVERTIME HRS:</td>
                                                        <td>???</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>DAILY DIRECT HOURS ABSENT:</td>
                                                        <td>???</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>MTD DIRECT HOURS ABSENT (%):</td>
                                                        <td>40</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>MTD CUM DIRECT HOURS ABSENTIEESM (%):</td>
                                                        <td>40</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>DIRECT LABOUR UTILIZATION: (%)</td>
                                                        <td>40</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>YTD CUM INDIRECT HOURS WORKED:</td>
                                                        <td>{{round($cumindirecthrsyear,2)}} Hrs</td>
                                                    </tr>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>MTD CUM DIRECT OVERTIME HOURS:</td>
                                                        <td>???</td>
                                                    </tr>
                                            </tbody>
                                        </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
