
@extends('layouts.auth')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-12 col-12 align-self-center">
            <h3 class="text-center text-themecolor mb-0" style="color: #da251c;">PQCS-DASHBOARD</h3>
          
        </div>


           

    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">


         <div class="row">
                            <!-- Column -->

                            @foreach($shops as $shop)

                              <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-info">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h2>{{count($shop->unitmovement)}}</h2>
                                        <h3 class="text-info">{{$shop->shop_name}} </h3>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-info display-6"><i class="ti-truck"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                         <!--<div class="col-lg-3 col-md-6">
                                <div class="card bg-primary">
                                    <div class="card-body">
                                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                                <h3 class="text-white font-weight-medium">{{$shop->shop_name}} : {{count($shop->unitmovement)}}</h3>
                                                    
                                            <div class="carousel-inner">
                                                <div class="carousel-item active flex-column">
                                                   
                                                    <h3 class=" text-center text-white font-weight-medium">123</h3>
                                                    <div class="text-white mt-2">
                                                        <i>- Completed Today</i>
                                                    </div>
                                                </div>
                                                <div class="carousel-item flex-column">
                                                    
                                                    <h3 class="text-center text-white font-weight-medium">21</h3>
                                                    <div class="text-white mt-2">
                                                        <i>- Employees</i>
                                                    </div>
                                                </div>
                                                <div class="carousel-item flex-column">
                                                    
                                                    <h3 class="text-white font-weight-medium">80%</h3>
                                                    <div class="text-white mt-2">
                                                        <i>- Efficency</i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            @endforeach
                        
                        </div>






@endsection
@section('after-scripts')
<script>
     var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 60000) 
             window.location.reload(true);
         else 
             setTimeout(refresh, 10000);
     }

     setTimeout(refresh, 10000);
</script>

  @endsection