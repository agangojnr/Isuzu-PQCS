
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>

    @include('layouts.header.header')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h2 class="text-themecolor mb-0 text-white">SCREENBOARD</h2>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-white">SCREENBOARD MENU</li>
            </ol>
        </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid pt-3"  style="background: #5e5d5d;">


    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">MAIN SCREEN</h2>
                    <div class="row">
                        <div class="col-md-3 text-center text-md-left">
                            {{ Form::open(['route' => 'screenboardindex', 'method' => 'GET'])}}
                            {{ csrf_field(); }}
                            <input type="hidden" class="shiftname" name="shift" value="9">
                                <input type="hidden" name="section" value="plant">
                                <button type="submit" style="width: 80%; " class="btn btn-primary btn-lg">Plant Screen</button>
                            {!! Form::close(); !!}
                        </div>
                        <div class="col-md-3 text-center text-md-primary">
                            {{ Form::open(['route' => 'screenboardindex', 'method' => 'GET'])}}
                            {{ csrf_field(); }}
                                <input type="hidden" class="shiftname" name="shift" value="9">
                                <input type="hidden" name="section" value="cv">
                                <button type="submit" style="width: 80%;" class="btn btn-primary btn-lg">CV Screen</button>
                            {!! Form::close(); !!}
                        </div>
                        <div class="col-md-3 text-center text-md-primary">
                            {{ Form::open(['route' => 'screenboardindex', 'method' => 'GET'])}}
                            {{ csrf_field(); }}
                                <input type="hidden" class="shiftname" name="shift" value="9">
                                <input type="hidden" name="section" value="lcv">
                                <button type="submit" style="width: 80%;" class="btn btn-primary btn-lg">LCV Screen</button>
                            {!! Form::close(); !!}
                        </div>
                        <div class="col-md-3 text-center text-md-primary">
                            {{ Form::open(['route' => 'screenboardall', 'method' => 'GET'])}}
                            {{ csrf_field(); }}
                                <input type="hidden" class="shiftname" name="shift" value="9">
                                <button type="submit" style="width: 80%;" class="btn btn-primary btn-lg">All Sections</button>
                            {!! Form::close(); !!}
                        </div>

                    </div>


                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">SECTION SCREEN</h2>
                    <div class="row">
                        @if (count($sections) > 0)
                            @foreach ($sections as $item)
                                <div class="col-md-3 text-md-info">
                                    {{ Form::open(['route' => 'screenboardpershop', 'method' => 'GET'])}}
                                    {{ csrf_field(); }}
                                        <input type="hidden" class="shiftname" name="shift" value="9">
                                        <input type="hidden" name="section" value="{{$item->id}}">
                                        <button type="submit" style="width: 80%;" class="btn btn-lg btn-info mb-4">{{$item->shop_name}}</button>
                                    {!! Form::close(); !!}
                                </div>
                            @endforeach
                        @endif


                    </div>
                    <div class="row">
                        <div class="col-6"></div>
                        <div class="col-3 text-right mt-2">
                                <h2 style="color:rgb(201, 202, 250);" id="shiftshow">8 Hr Shift</h2>

                        </div>
                        <div class="col-md-3 text-md-primary">
                            <button id="shift" type="button" style="width: 80%;" class="btn btn-cyan btn-lg  text-center">Change to 10Hr Shift</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer.script')
    <script src="../../dist/js/app.init.dark.js"></script>
    @yield('after-scripts')
    @yield('extra-scripts')
    <script>
$(document).on('click', '#shift', function () {
    var shift =  $('.shiftname').val();
    if(shift == 11){
        $('.shiftname').val(9);
        $('#shift').html('Change to 10Hr Shift');
        $('#shiftshow').html("8 Hr Shift");
    }else{
        $('.shiftname').val(11);
        $('#shift').html('Change to 8Hr Shift');
        $('#shiftshow').html("10 Hr Shift");
    }
});
    </script>

