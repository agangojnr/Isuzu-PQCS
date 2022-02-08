
@extends('layouts.app')
@section('title','Add Roles')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Add Roles</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Add Roles</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>THIS MONTH</small></h6>
                        <h4 class="mt-0 text-info">40</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="monthchart"></div>
                    </div>
                </div>
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>LAST MONTH</small></h6>
                        <h4 class="mt-0 text-primary">50</h4>
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
  <div class="content-header row pb-1">
                <div class="content-header-left col-md-6 col-12 mb-2">


                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('role.partials.role-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">

        <div class="card-body">

                 {!! Form::open(['action'=>'App\Http\Controllers\role\RoleController@store',
                 'method'=>'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']); !!}
        <div class="card-header bg-primary mb-2">
            <h3 class="text-white">CREATE ROLE & PERMISSIONS </h3>
        </div>
        <div class="row">
        <div class="col-md-4">
          <div class="form-group" style="font-size: 18px;">
            {!! Form::label('name', 'Role Name:*') !!}
              {!! Form::text('name', null, ['class' => 'form-control ', 'required',
              'placeholder' => 'Role Name', 'autocomplete'=>'off' ]); !!}

          </div>
        </div>
        </div>




        <!--RESPONSIVENESS-->
        <h4>RESPONSIVENESS SECTION</h4>
        <hr>
        <div class="row">

        <div class="col-3">
            <h5 class="ml-3">Scheduling Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'schedule-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'schedule6']); !!}
                        <label for="schedule6">View Schedule</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'schedule-print', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'schedule5']); !!}
                        <label for="schedule5">Print Label</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'schedule-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'schedule1']); !!}
                        <label for="schedule1">Create</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'schedule-import', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'schedule2']); !!}
                        <label for="schedule2">Import</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'schedule-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'schedule3']); !!}
                        <label for="schedule3">Edit </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'schedule-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'schedule4']); !!}
                        <label for="schedule4">Delete</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Schedule Plan Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'prod-sche', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'prschedule']); !!}
                        <label for="prschedule">Prodn Schedule</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'fcw-sche', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'fcwsche']); !!}
                        <label for="fcwsche">FCW Schedule  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'hist-sche', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'schhist']); !!}
                        <label for="schhist">Schedule History</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Vehicle Models Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'model-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'model1']); !!}
                        <label for="model1">List </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'model-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'model2']); !!}
                        <label for="model2">Create  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'model-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'model3']); !!}
                        <label for="model3">Edit </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'model-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'model4']); !!}
                        <label for="model4">Delete</label>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Performance Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">
                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'Effny-dashboard', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'Effny1']); !!}
                        <label for="Effny1">Effny Dashboard</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'actual-production', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'actprod']); !!}
                        <label for="actprod">Actual Productn</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'buffer-status', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'bstatus']); !!}
                        <label for="bstatus">Buffer Status</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'performance-tack', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'performance']); !!}
                        <label for="performance">Performance Rpt</label>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Swap Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'swap-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'creswap']); !!}
                        <label for="creswap">Create Swap</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'swap-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'liswap']); !!}
                        <label for="liswap">Swap List </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'swap-reset', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'swares']); !!}
                        <label for="swares">Reset Swap </label>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Re-routing Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'reroute-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'crereroute']); !!}
                        <label for="crereroute">Create Reroute</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'reroute-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'lireroute']); !!}
                        <label for="lireroute">Reroute List </label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Vehicle Units:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'delayed-units', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'del-units']); !!}
                        <label for="del-units">Delayed Units</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

    </div>



    <!--QUALITY-->
    <h4>QUALITY SECTION</h4>
    <hr>
    <div class="row">

        <div class="col-3">
            <h5 class="ml-3">Routing Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'routing-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'routing1']); !!}
                        <label for="routing1">Create</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'routing-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'routing5']); !!}
                        <label for="routing5">List</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'routing-import', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'routing2']); !!}
                        <label for="routing2">Import</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'routing-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'routing3']); !!}
                        <label for="routing3">Edit </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'routing-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'routing4']); !!}
                        <label for="routing4">Delete</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'routing-bymodel', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'routingbm']); !!}
                        <label for="routingbm">Rt by Model</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">DRL Target Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drltgt-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drlli']); !!}
                        <label for="drlli">List Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drltgt-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drltgtcr']); !!}
                        <label for="drltgtcr">Create Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drltgt-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drltgted']); !!}
                        <label for="drltgted">Edit Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drltgt-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drltgtdel']); !!}
                        <label for="drltgtdel">Delete Target</label>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Quality Output Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'qualityrpt-position', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'qualityrpt1']); !!}
                        <label for="qualityrpt1">Curent Position</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'qualityrpt-marked', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'qualityrpt2']); !!}
                        <label for="qualityrpt2">Marked Routing</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drr-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drrlist']); !!}
                        <label for="drrlist">DRR List</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'defect-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'deflist']); !!}
                        <label for="deflist">Defect List</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">Quality Report Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drl-report', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drlrpt']); !!}
                        <label for="drlrpt">DRL Report</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drr-report', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drrrpt']); !!}
                        <label for="drrrpt">DRR Report </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'gca-score', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'gcascore']); !!}
                        <label for="gcascore">GCA Score </label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">DRR Target Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drrtgt-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drrli']); !!}
                        <label for="drrli">List Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drrtgt-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drrtgtcr']); !!}
                        <label for="drrtgtcr">Create Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drrtgt-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drrtgted']); !!}
                        <label for="drrtgted">Edit Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'drrtgt-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'drrtgtdel']); !!}
                        <label for="drrtgtdel">Delete Target</label>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">GCA Target Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'gcatgt-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'gcatarg']); !!}
                        <label for="gcatarg">Create Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'mangca-target', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'mangcatarg']); !!}
                        <label for="mangcatarg">Manage Target</label>
                    </div>

                </div>
            </div>
        </div>
        </div>

    </div>



<!--PEOPLE-->
<h4>PEOPLE SECTION</h4>
<hr>
<div class="row">
    <div class="col-3">
        <h5 class="ml-3">Attendance & OT Module:</h5>
    <div class="col-lg-12">
        <div class="card border-left border-right border-info">

            <div class="card-body">
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'attendance-mark', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'attendance1']); !!}
                    <label for="attendance1">Attdnce & OT</label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'attendance-preview', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'attendance2']); !!}
                    <label for="attendance2">Preview  Attdnce</label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'overtime-preview', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'overtime2']); !!}
                    <label for="overtime2">Preview OT </label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'overtime-report', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'overtime3']); !!}
                    <label for="overtime3">OT Reports </label>
                </div>

            </div>
        </div>
    </div>
    </div>

    <div class="col-3">
        <h5 class="ml-3">Headcount Module:</h5>
    <div class="col-lg-12">
        <div class="card border-left border-right border-info">

            <div class="card-body">
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'hc-list', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'hc1']); !!}
                    <label for="hc1">List </label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'hc-import', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'hc4']); !!}
                    <label for="hc4">Import</label>
                </div>
                <!--<div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'hc-create', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'hc2']); !!}
                    <label for="hc2">Create  </label>
                </div>-->
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'hc-edit', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'hc3']); !!}
                    <label for="hc3">Edit </label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'hc-activate', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'hc7']); !!}
                    <label for="hc7">Deactivate</label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'hc-delete', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'hc6']); !!}
                    <label for="hc6">Delete</label>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="col-3">
        <h5 class="ml-3">Attendance Report Module:</h5>
    <div class="col-lg-12">
        <div class="card border-left border-right border-info">
            <div class="card-body">
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'direct-manpower', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'direct']); !!}
                    <label for="direct">Drct ManPower</label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'stdhrs-generated', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'people1']); !!}
                    <label for="people1">Hrs Generated</label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'stdActual-hours', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'stdact']); !!}
                    <label for="stdact">Std & Actual Hrs</label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'target-report', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'targetR']); !!}
                    <label for="targetR">Target Report</label>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="col-3">
        <h5 class="ml-3">People Report Module:</h5>
    <div class="col-lg-12">
        <div class="card border-left border-right border-info">
            <div class="card-body">
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'people-summary', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'people2']); !!}
                    <label for="people2">Summary Rprt</label>
                </div>

                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'hc-summary', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'hc5']); !!}
                    <label for="hc5">HC Summary  </label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'plant-register', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'register1']); !!}
                    <label for="register1">Plant Register</label>
                </div>
                <div class="mb-2">
                    {!! Form::checkbox('permissions[]', 'shop-attendance', false,
                        [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'peopless']); !!}
                    <label for="peopless">Shop Attndnce</label>
                </div>
            </div>
        </div>
    </div>
    </div>

    </div>




    <!--CONFIGURATION-->
    <h4>CONFIGURATION SECTION</h4>
    <hr>
    <div class="row">
        <div class="col-3">
            <h5 class="ml-3">View Settings Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'shops-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'shop']); !!}
                        <label for="shop">Shops </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'route-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'route']); !!}
                        <label for="route">Routes  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'route-mapping', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'route1']); !!}
                        <label for="route1">Route Mapping </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'unit-category', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'unit']); !!}
                        <label for="unit">Unit Category</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'shop-section', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'shopsect']); !!}
                        <label for="shopsect">Sections</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">App Users Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'appuser-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'appuser1']); !!}
                        <label for="appuser1">List </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'appuser-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'appuser2']); !!}
                        <label for="appuser2">Create  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'appuser-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'appuser3']); !!}
                        <label for="appuser3">Edit </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'appuser-reset', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'appuser4']); !!}
                        <label for="appuser4">Reset  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'appuser-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'appuser5']); !!}
                        <label for="appuser5">Delete  </label>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">People Settings Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">
                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'set-default', false,
                            ['class' => 'material-inputs chk-col-cyan', 'id'=>'default']); !!}
                        <label for="default">Set Default Hrs</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'manage-target', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'target']); !!}
                        <label for="target">Manage Target</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'view-target', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'target2']); !!}
                        <label for="target2">View Targets</label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'set-stdhrs', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'target3']); !!}
                        <label for="target3">Set STD Hours</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">System Users Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'sysuser-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'sysuser1']); !!}
                        <label for="sysuser1">List </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'sysuser-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'sysuser2']); !!}
                        <label for="sysuser2">Create  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'sysuser-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'sysuser3']); !!}
                        <label for="sysuser3">Edit </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'sysuser-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'sysuser5']); !!}
                        <label for="sysuser5">Delete  </label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-3">
            <h5 class="ml-3">More on Sys. Users Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">

                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'sysuser-activate', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'sysuser6']); !!}
                        <label for="sysuser6">Deactivate  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'sysuser-reset', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'sysuser4']); !!}
                        <label for="sysuser4">Reset  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'assign-shop', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'assgnshop']); !!}
                        <label for="assgnshop">Assign Shop</label>
                    </div>


                </div>
            </div>
        </div>
        </div>


        <div class="col-3">
            <h5 class="ml-3">Role & Rights Module:</h5>
        <div class="col-lg-12">
            <div class="card border-left border-right border-info">

                <div class="card-body">
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'role-list', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'role1']); !!}
                        <label for="role1">List </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'role-create', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'role3']); !!}
                        <label for="role3">Create  </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'role-edit', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'role2']); !!}
                        <label for="role2">Edit </label>
                    </div>
                    <div class="mb-2">
                        {!! Form::checkbox('permissions[]', 'role-delete', false,
                            [ 'class' => 'material-inputs chk-col-cyan', 'id'=>'role4']); !!}
                        <label for="role4">Delete  </label>
                    </div>

                </div>
            </div>
        </div>
        </div>

        </div>


            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Save', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}


                        {{ link_to_route('roles.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light']) }}
                    </div>
                </div>


         {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    {!! Toastr::message() !!}

    @endsection

    @section('after-styles')
        {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
        {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}

    @endsection

    @section('after-scripts')
    {{ Html::script('assets/libs/jquery/dist/jquery.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}





<script type="text/javascript">

     /*$(document).on('submit', 'form#create-user', function(e){
            e.preventDefault();
            $("#submit-data").hide();
            var data = $(this).serialize();
            $.ajax({
                method: "post",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success:function(result){
                    if(result.success == true){
                        //$('div.account_model').modal('hide');
                        toastr.success(result.msg);
                       // capital_account_table.ajax.reload();
                        //other_account_table.ajax.reload();

                        location.href = '{{ route("appusers.index") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });*/

      $(document).on('ifChecked', '.check_all', function() {
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function() {
                $(this).iCheck('check');
            });
    });
    $(document).on('ifUnchecked', '.check_all', function() {
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function() {
                $(this).iCheck('uncheck');
            });
    });
       $('.check_all').each(function() {
        var length = 0;
        var checked_length = 0;
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function() {
                length += 1;
                if ($(this).iCheck('update')[0].checked) {
                    checked_length += 1;
                }
            });
        length = length - 1;
        if (checked_length != 0 && length == checked_length) {
            $(this).iCheck('check');
        }
    });
     //initialize iCheck
    $('input[type="checkbox"].input-icheck, input[type="radio"].input-icheck').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
    });


</script>

