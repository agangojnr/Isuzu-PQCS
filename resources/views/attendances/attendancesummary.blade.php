@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 col-12 align-self-center">
        <h3 class="text-themecolor mb-0">Attendance</h3>
        <ol class="breadcrumb mb-0 p-0 bg-transparent">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active">Attendance Summary</li>
        </ol>
    </div>
</div>

    <div class="container-fluid note-has-grid">


     <!-- Individual column searching (select inputs) -->
     <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Attendance Summary Per Shop</h4>

        {!! Form::open(['action'=>'App\Http\Controllers\attendance\AttendanceController@searchsummaryreport', 'method'=>'post', 'enctype' => 'multipart/form-data']); !!}

            <ul class="nav nav-pills p-3 bg-white mb-3 rounded-pill align-items-center">

                     <li class="nav-item" style="width: 40%;">
                        <div class="form-group ml-4">
                        <label>Choose Shop</label>
                        <div class='input-group'>
                            {{Form::select('shop', $shops, $shops->pluck('id'), array('class'=>'form-control select2', 'placeholder'=>'Please select ...', 'required'=>'required',
                            'style'=>'width: 90%;'))}}
                        </div>
                        </div>
                     </li>

                     <li class="nav-item">
                         <button type="submit" class="nav-link btn-primary d-flex align-items-center px-3" id="add-notes">
                       View &nbsp;&nbsp;<i class="mdi mdi-view-headline"></i><span class="d-none d-md-block font-14"></span></button>
                     </li>
                </ul>
                {{Form::hidden('_method', 'GET')}}
           {!! Form::close() !!}
           @if (!isset($shop))
           <div class="table-responsive">
            <table class="table table-striped table-bordered datatable-select-inputs">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Day</th>
                        <th>Date</th>
                        <th>Shop</th>
                        <th>Marked</th>
                        <th>Direct Hrs</th>
                        <th>Indirect Hrs</th>
                        <th>Loaned Hrs</th>
                        <th>Total Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i=0; $i<count($dates); $i++)
                    <tr>
                        <td>{{$n=$i+1}}</td>
                        <td>{{$days[$i]}}</td>
                        <td>{{$dates[$i]}}</td>
                        <td>{{$shopnames[$i]}}</td>
                        <td><span class="badge badge-{{$color[$i]}}">{{$marked[$i]}}</span></td>
                        <td>{{$directSum[$i]}} Hrs</td>
                        <td>{{$indirectSum[$i]}}Hrs</td>
                        <td>{{$loanedSum[$i]}} Hrs</td>
                        <td>{{$directSum[$i] + $indirectSum[$i]+ $loanedSum[$i]}} Hrs</td>
                    </tr>
                    @endfor

                </tbody>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Day</th>
                        <th>Date</th>
                        <th>Shop</th>
                        <th>Marked</th>
                        <th>Direct Hrs</th>
                        <th>Indirect Hrs</th>
                        <th>Loaned Hrs</th>
                        <th>Total Hrs</th>
                    </tr>
                </tfoot>
            </table>
        </div>

            @else
                <h1 >{{$shop}}</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered datatable-select-inputs">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Day</th>
                                <th>Date</th>
                                <th>Marked</th>
                                <th>Direct Hrs</th>
                                <th>Indirect Hrs</th>
                                <th>Loaned Hrs</th>
                                <th>Total Hrs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i=0; $i<count($dates); $i++)
                            <tr>
                                <td>{{$n=$i+1}}</td>
                                <td>{{$days[$i]}}</td>
                                <td>{{$dates[$i]}}</td>
                                <td><span class="badge badge-{{$color[$i]}}">{{$marked[$i]}}</span></td>
                                <td>{{$directSum[$i]}} Hrs</td>
                                <td>{{$indirectSum[$i]}}Hrs</td>
                                <td>{{$loanedSum[$i]}} Hrs</td>
                                <td>{{$directSum[$i] + $indirectSum[$i]+ $loanedSum[$i]}} Hrs</td>
                            </tr>
                            @endfor

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Day</th>
                                <th>Date</th>
                                <th>Marked</th>
                                <th>Direct Hrs</th>
                                <th>Indirect Hrs</th>
                                <th>Loaned Hrs</th>
                                <th>Total Hrs</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif

                </div>
            </div>
        </div>
    </div>
@endsection

