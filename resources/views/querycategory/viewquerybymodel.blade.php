
@extends('layouts.app')
@section('title','View Routing')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">View Routing </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">View Routing</li>
            </ol>
        </div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <div class="d-flex mt-2 justify-content-end">
                <div class="d-flex mr-3 ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>THIS MONTH</small></h6>
                        <h4 class="mt-0 text-info">$58,356</h4>
                    </div>
                    <div class="spark-chart">
                        <div id="monthchart"></div>
                    </div>
                </div>
                <div class="d-flex ml-2">
                    <div class="chart-text mr-2">
                        <h6 class="mb-0"><small>LAST MONTH</small></h6>
                        <h4 class="mt-0 text-primary">$48,356</h4>
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
                    <h3 class="mb-0">Model <strong>{{$model->model_name}}</strong></h3>

                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="media width-250 float-right">

                        <div class="media-body media-right text-right">
                            @include('markedunit.partial.markedunit-header-buttons')
                           
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



            @foreach($shops as $shop)
             @if(count($shop->querycategory)>0)
                 <div class="card-header bg-info">
                                <h4 class="mb-0 text-white">{{$shop->shop_name}}</h4>
                            </div>
                           

                          
                           @php
                           $i=0;
                           @endphp
                           
                            @foreach($shop->querycategory as $row)

                           
                             @php
                           $i++;
                           @endphp
                             <div class="card-body">
                                        <h4 class="card-title"> {{$i}}. {{$row->query_code}} : {{$row->category_name}}</h4>
                                    </div>

                            

                           <div class="card-body">
                  
                   
                       <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-warning text-white">
                                        <tr>
                                           
                                            <th scope="col">Query Name</th>
                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($row->query_items as $data)
                                        <tr>
                                            @php
                                           

                                             
                                          

                                               @endphp

                          
                                         
                                            <td>{{$data->query_name}}</td>
                                        
                                          
                                        </tr>
                                      @endforeach
                                       
                                    </tbody>
                                </table>
                            </div>
                   
                </div>
               
                @endforeach
                 @endif
                
                @endforeach
            </div>
        </div>
    </div>


<div class="modal fade unit_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    
@endsection



@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}




    @endsection