
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <meta http-equiv="refresh" content="30">

    @include('layouts.header.header')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h2 class="text-themecolor mb-0 text-white">SCREENBOARD</h2>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-white">REALTIME PRODUCTION PROGRESS (<span>{{$shift}}Hr SHIFT</span>)</li>
            </ol>

        </div>
        <div class="col-md-5 align-self-right text-right pt-3">
            <h4>{{\Carbon\Carbon::today()->format('j M Y')}}
                &nbsp;&nbsp;&nbsp;&nbsp;
            <span id="time"></span>
        </div>
        <div class="col-lg-1 text-right" style="font-size: 30px;">
            <span id="full" onclick="activate(document.documentElement);" ><i class="mdi mdi-fullscreen"></i></span>
            <span id="exitfull" onclick="deactivate();"><i class="mdi mdi-fullscreen-exit"></i></span>
        </div>
        <div class="col-lg-1 text-right" style="font-size: 30px;">
            <a href="{{route('screenboard')}}" class="btn btn-secondary float-right"><i class="mdi mdi-arrow-left font-18 mr-1"></i> </a></h4>
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
            <div class="card" style="background: #000000;">
                <div class="card-body">
                    <h2 class="card-title">PRODUCTION WIP SCREEN</h2>
                    <div class="row">
                        @if (count($shops) > 0)
                        @foreach ($shops as $item)
                        <div class="col-lg-3 col-md-12">
                            <!-- card -->
                            <div class="card bg-primary">
                               <div class="card-body">
                                   <div class="row">
                                    <div class="col-7">
                                        <h2 class="card-title text-white">{{$item->shop_name}}</h2>
                                    </div>
                                       <div class="col-5">
                                         <table class="text-center">
                                             <tr style="font-size: 30px;">
                                                @if ($finishedunits[$item->id] < $targetunits[$item->id])
                                                    <td  style="color: rgb(204, 31, 31);">{{$finishedunits[$item->id]}}</td>
                                                @else
                                                    <td style="color: rgb(18, 253, 38);">{{$finishedunits[$item->id]}}</td>
                                                @endif
                                                 <td>|</td>
                                                 <td class="text-white">{{$targetunits[$item->id]}}</td>
                                             </tr>
                                             <tr>
                                                <td class="text-white">Actual</td>
                                                <td>|</td>
                                                <td class="text-white">Target</td>
                                            </tr>
                                         </table>
                                       </div>
                                   </div>
                               </div>
                           </div>
                        </div>

                        @endforeach

                        @endif


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
    //Time
    $(document).ready(function() {
        $('#exitfull').hide();
    setInterval(runningTime, 1000);
    });

    function runningTime() {
        $.ajax({
                url: '{{route('screenboardindexReload')}}',
                method: "GET",
                dataType: 'json',
                data:{'section':"plant",'shift':9},
                success: function(response) {
                    $('#time').html(response.data.time);
            }
        });
    }


     // Function for full screen activation
         function activate(ele) {
            if (ele.requestFullscreen) {
                ele.requestFullscreen();
                $('#exitfull').show();
                $('#full').hide();
            }
        }

        // Function for full screen activation
        function deactivate() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                $('#exitfull').hide();
                $('#full').show();
            }
        }
    </script>

