@include('layouts.header.reportheader')
    <!-- End Row -->

    <div class="container-fluid">

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <ol class="breadcrumb mb-2  bg-grey">
                                <li class="breadcrumb-item">
                                    <h3 class="card-title"><u>WEEKLY ACTUAL HOURS GENERATED FOR
                                        ({{\Carbon\Carbon::createFromFormat('Y-m-d', $todate)->format('M j Y')}})</u></h3>
                                </li>
                            </ol>

                        </div>
                        <div class="col-6">
                            {!! Form::open(['action'=>'App\Http\Controllers\attendance\AttendanceController@prodnoutput',
                            'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                            <div class="row">
                                <div class="col-7">
                                    <h4 class="card-title">Filter HC by Date</h4>
                                <div class='input-group'>
                                    <input type='text' name="mdate" class="form-control singledate" />

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="ti-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <button type="submit" class="btn btn-success mt-4">Filter Hours</button>
                            </div>

                            </div>
                            {{Form::hidden('_method', 'GET')}}
                            {!! Form::close() !!}

                        </div>
                    </div>



                    <table class="tablesaw table-bordered table-hover table no-wrap" data-tablesaw-mode="swipe"
                                    data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap
                                    data-tablesaw-mode-switch>
                            <thead>
                                <tr>
                                    <th rowspan="2">SECTION</th>
                                    <th rowspan="2"></th>
                                    <th>MON</th>
                                    <th>TUE</th>
                                    <th>WED</th>
                                    <th>THUR</th>
                                    <th>FRI</th>
                                    <th>SAT</th>
                                    <th>SUN</th>
                                    <th rowspan="2">WEEKLY TOTAL</th>
                                    <th rowspan="2">MTD TOTAL</th>
                                    <th rowspan="2">PREV. MTD TOTAL</th>
                                </tr>
                                <tr>
                                    <th>26</th>
                                    <th>27</th>
                                    <th>28</th>
                                    <th>29</th>
                                    <th>30</th>
                                    <th>31</th>
                                    <th>1</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shops as $shop)
                                    <tr>
                                        <td rowspan="3">{{$shop->report_name}}</td>
                                        @foreach ($filday as $fil)
                                            <td>{{$fil}}</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                        </tr>
                                        @endforeach


                                @endforeach


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SECTION</th>
                                    <th></th>
                                    <th>MON</th>
                                    <th>TUE</th>
                                    <th>WED</th>
                                    <th>THUR</th>
                                    <th>FRI</th>
                                    <th>SAT</th>
                                    <th>SUN</th>
                                    <th>WEEKLY TOTAL</th>
                                    <th>MTD TOTAL</th>
                                    <th>PREV. MTD TOTAL</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer.script')
    @yield('after-scripts')
    @yield('extra-scripts')
    {{ Html::script('dist/js/pages/datatable/datatable-basic.init.js') }}

     {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}

    {{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
    {{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
    {!! Toastr::message() !!}

