
@extends('layouts.app')
@section('title','Add Query')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Add Query </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Routing Query</li>
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
                            @include('querycategory.partial.querycategory-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
  



              <div class="row">
                    <div class="col-lg-12">
                        <div class="card ">
                            <div class="card-header bg-danger">
                                <h4 class="card-title text-white">Add Query  </h4>
                            </div>
                           
                             {{ Form::open(['route' => 'querysave', 'class' => 'form-horizontal','files' => true, 'role' => 'form', 'method' => 'post', 'id' => 'create-query'])}}


               <input type="hidden" name="query_id" id="query_id" value="{{ $routing_query->id }}">
                           
                                <div class="form-body">
                                    <div class="card-body">
                                        
                                        <h4 class="card-title">Query Category </h4>
                                    </div>
                                    <hr class="mt-0 mb-5">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Code</label>
                                                    <div class="col-md-9">
                                                         {!! Form::text('query_code',  $routing_query->query_code, ['placeholder' => 'BS-MF101', 'disabled', 'class' => 'form-control']); !!}

                                                        
                                                       </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Category Name</label>
                                                    <div class="col-md-9">
                                                         {!! Form::text('category_name',   $routing_query->category_name, ['placeholder' => 'Category Name', 'class' => 'form-control','disabled']); !!}
                                                        </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                         <div class="row">

                                             <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Shop</label>
                                                    <div class="col-md-9">

                                                         {!! Form::select('shop_id', $shops,  $routing_query->shop_id, ['placeholder' => 'Select Shop', 'disabled', 'class' => 'form-control custom-select ']); !!}
                                                        </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Status</label>
                                                    <div class="col-md-9">

                                                         {!! Form::select('status', ['Active'=>'Active','Inactive'=>'Inactive'],  $routing_query->status, ['placeholder' => 'Select Shop', 'disabled', 'class' => 'form-control custom-select ']); !!}
                                                        
                                                       </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                           
                                            <!--/span-->
                                        </div>


                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Model</label>
                                                    <div class="col-md-9">


                                                        <select name="model_id[]" class="select2 form-control custom-select" multiple="multiple" disabled="disabled" style="height: 25px;width: 100%;">
        @foreach($models as $model)
       <option value="{{$model->id}}" {{in_array($model->id, $modelid) ? 'selected':''}}> 
          {{$model->model_name}}
       </option>
        @endforeach
</select> 


                                                       </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Attach Image</label>
                                                    <div class="col-md-9">
                                                           <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="icon" disabled="disabled" class="custom-file-input" id="icon" accept="image/png, image/gif, image/jpeg" >
                                            <label class="custom-file-label" for="coin">Choose file</label>
                                        </div>
                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Question Type</label>
                                                    <div class="col-md-9">

                                                         {!! Form::select('quiz_type', ['single'=>'Single Answer','multiple'=>'Multiple Answer','numeric'=>'Numerical Answer','traceable'=>'Traceable Answer'],  $routing_query->quiz_type, ['placeholder' => 'Quiz Type','disabled', 'class' => 'form-control custom-select ','id'=>'quiz_type']); !!}


                                                     
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                   <div class="col-md-6">

                                    <div class="form-group row" id="quiz_form_part">

                                        @if($routing_query->quiz_type=='single')

                                       
                                        @elseif($routing_query->quiz_type=='multiple')
                                  <label class="control-label text-right col-md-3">Total Defects</label>
                                    <div class="col-md-9">
                                         {!! Form::select('total_options', ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'11','12'=>'13','14'=>'15','16'=>'17','18'=>'19','20'=>'20'],  $routing_query->total_options, ['placeholder' => 'Select Options','disabled', 'class' => 'form-control custom-select ','id'=>'load_options_multiple']); !!}

                                            </div>


                                    @elseif($routing_query->quiz_type=='numeric')

                                     <label class="control-label text-right col-md-3">Equation</label>
                                                    <div class="col-md-9">

                            {!! Form::select('total_options', ['1'=>'Equals To (=)','2'=>'Greater Than (>)','3'=>'Less Than (<)','4'=>'Greater Than or Equals To (>=)','5'=>'Less Than or Equals To (<=)','6'=>'Between'],  $routing_query->total_options, ['placeholder' => 'Select Options', 'class' => 'form-control custom-select ', 'disabled', 'id'=>'load_options_numeric']); !!}

                                                      
                                                    </div>

                                        @endif

                                     </div>



                                                
                                            </div>
                                            <!--/span-->
                                        </div>


                                        <div class="row" id="quiz_option_form_part">  

                                            @if($routing_query->quiz_type=='single')


   
            

                                           @elseif($routing_query->quiz_type=='multiple')


  <hr class="mt-0 mb-2">
   <div class="card-body bg-light">
    <div class="row">
@php
$i=1;
@endphp
       @foreach($quizanswers as $quizanswer)

  <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Defect {{$i}}</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="answer[]" id="option-{{$i}}" 
                                            class="form-control" disabled="disabled" value="{{$quizanswer['option_value']}}" placeholder="Defect {{$i}}">


                                                       </div>
                                                </div>
                                            </div>
@php
$i++;
@endphp
                                   @endforeach
                          
  



 
 </div>
 </div>



                                            @endif




                                         </div>


                    <div class="row" id="total_answer_form_part">  
 @if($routing_query->quiz_type=='multiple')


                                             
 @elseif($routing_query->quiz_type=='numeric')

 <hr class="mt-0 mb-2">
   <div class="card-body bg-light">
    <div class="row">

      @if($routing_query->total_options==6)


@php 
$mindata=($quizanswers[0]['min']);
$maxdata=($quizanswers[1]['max']);
@endphp
    

       

       <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Lower Limit</label>
                                                    <div class="col-md-9">

                                                         <input type="number" name="lower_limit[]" id="lower_limit" 
                                            class="form-control" disabled="disabled" value="{{$mindata[0]}}" placeholder="Lower Limit">


                                                       </div>
                                                </div>
                                            </div>



                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Upper Limit</label>
                                                    <div class="col-md-9">

                                                         <input type="number" value="{{$maxdata[0]}}" name="upper_limit[]" disabled="disabled" id="upper_limit" 
                                            class="form-control" placeholder="Upper Limit">


                                                       </div>
                                                </div>
                                            </div>


                                  
        @endif   

          <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Expected Value</label>
                                                    <div class="col-md-9">

                                                         <input type="number" value="{{$routing_query->correct_answers}}" name="correct_answers" disabled="disabled" id="correct_answers" 
                                            class="form-control" placeholder="Expected Value">


                                                       </div>
                                                </div>
                                            </div>


 
 
                                  </div>
 </div>
@endif





</div>




                                     <h4 class="card-title">Load Queries</h4>    
                                    </div>


       

@foreach($routing_query->query_items as $items)

       


                                    <hr class="mt-0 mb-5">
                                    <div class="card-body">
                                        <!--/row-->
                                        <div class="row">
                                                <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">User To Sign</label>
                                                    <div class="col-md-9">
                                                       

                                                         {!! Form::select('can_sign[]', ['No'=>'No','Yes'=>'Yes'],  $items->can_sign, ['placeholder' => 'User To Sign', 'disabled', 'class' => 'form-control custom-select ']); !!}


                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Additional Field</label>
                                                    <div class="col-md-9">
                                                       

                                                        {!! Form::select('additional_field[]', ['No'=>'No','Yes'=>'Yes'],  $items->additional_field, ['placeholder' => 'Additional Field', 'disabled','class' => 'form-control custom-select ']); !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Query Name</label>
                                                    <div class="col-md-9">
                                        

                                                    {!! Form::textarea('query_name[]', $items->query_name, ['placeholder' => 'Query Name','disabled', 'rows' => 3, 'class' => 'form-control']); !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            
                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                        <!--/row-->
                                    </div>
                                    
                                

@endforeach




<div id="main_product">
    <div class="product">
                                    <hr class="mt-0 mb-5">
                                    <div class="card-body">
                                        <!--/row-->
                                        <div class="row">
                                                <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">User To Sign</label>
                                                    <div class="col-md-9">
                                                        <select name="can_sign[]" class="form-control custom-select">
                                                            <option value="No" >No</option>
                                                            <option value="Yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Additional Field</label>
                                                    <div class="col-md-9">
                                                        <select name="additional_field[]" class="form-control custom-select">
                                                            <option value="No" >No</option>
                                                            <option value="Yes">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Query Name</label>
                                                    <div class="col-md-9">
                                <textarea name="query_name[]" required="required" class="form-control" id="exampleTextarea" rows="3"
                                                    placeholder="Message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                       



 <span class="col-6 del_b"></span>

                                        <!--/row-->
                                    </div>

                                </div>

                            </div>



                                     <div id="added_product"></div>




                            <div class="card-body">

                            <button class="btn waves-effect waves-light btn-warning add_more btn-sm m-1">Add Row</button>
                        </div>




                                    <hr>
                                    <div class="form-actions">
                                        <div class="card-body">
                                            <div class="text-right">
                                                {{ Form::submit('Add Query', ['class' => 'btn btn-info btn-md']) }}


                        {{ link_to_route('querycategory.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light','id'=>'submit-data']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>




@endsection
@section('after-styles')
 <style>
        #added_product div:nth-child(even) .product {
            background: #FFF
        }

        #added_product div:nth-child(odd) .product {
            background: #eeeeee
        }

        #product_sub div:nth-child(odd) .v_product_t {
            background: #FFF
        }

        #product_sub div:nth-child(even) .v_product_t {
            background: #eeeeee
        }
    </style>


    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    {{ Html::style('assets/libs/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}
    {{ Html::style('assets/libs/ckeditor/samples/css/samples.css') }}
    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}

 {{ Html::style('assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}

     
    
@endsection



@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/ckeditor/ckeditor.js') }}
{{ Html::script('assets/libs/ckeditor/samples/js/sample.js') }}
  <!-- This Page JS -->
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}
{{ Html::script('assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}
    

<script type="text/javascript">
     
 $(document).on('submit', 'form#create-query', function(e){
            e.preventDefault();
            //var data = $(this).serialize();
            $("#submit-data").hide();
            $.ajax({
                method: "POST",
                url: $(this).attr("action"),
                data:  new FormData(this),
               contentType: false,
               cache: false,
               processData:false,
                success:function(result){
                    if(result.success == true){
                        //$('div.account_model').modal('hide');
                        toastr.success(result.msg);
                       // capital_account_table.ajax.reload();
                        //other_account_table.ajax.reload();

                        location.href = '{{ route("querycategory.index") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });
$('#quiz_type').change(function() {
        show_product_type_form();
        $('#quiz_option_form_part').html("");
         $('#total_answer_form_part').html("");
    });





function show_product_type_form() {

  
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
    var action = 1;
    var product_id = 1;
    $.ajax({
        method: 'POST',
        url: '{{ route("load_options") }}',
        dataType: 'html',
        data: { quiz_type: $('#quiz_type').val(), product_id: product_id, action: action },
        success: function(result) {
            if (result) {
                $('#quiz_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}
$(".select2").select2();
$(".bt-switch").bootstrapSwitch();

    $(document).on('click', ".add_more", function (e) {
            e.preventDefault();
            var product_details = $('#main_product').clone().find(".old_id input:hidden").val(0).end();
            product_details.find(".del_b").append('<button class="btn btn-danger v_delete_temp m-1 align-content-end"><i class="fa fa-trash"></i> </button>').end();
            $('#added_product').append(product_details);
            
        });  


        $(document).on('click', ".v_delete_temp", function (e) {

          
            e.preventDefault();
            $(this).closest('div .product').remove();
        });

$('#load_options_multiple').change(function() {

   
       show_quiz_option_multiple_form();
       $('#total_answer_form_part').html("");
    });



    function show_quiz_option_multiple_form() {

    //Disable Stock management & Woocommmerce sync if type combo
    /*if($('#type').val() == 'combo'){
        $('#enable_stock').iCheck('uncheck');
        $('input[name="woocommerce_disable_sync"]').iCheck('check');
    }*/
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
    var action = 2;
    var product_id = 1;
    $.ajax({
        method: 'POST',
        url: '{{ route("load_quiz_options") }}',
        dataType: 'html',
        data: { load_options: $('#load_options_multiple').val(), quiz_type: $('#quiz_type').val(), action: action },
        success: function(result) {
            if (result) {
                $('#quiz_option_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}



$('#load_options_numeric').change(function() {

   
       show_quiz_option_numeric_form();
       $('#total_answer_form_part').html("");
    });




function show_quiz_option_numeric_form() {

    //Disable Stock management & Woocommmerce sync if type combo
    /*if($('#type').val() == 'combo'){
        $('#enable_stock').iCheck('uncheck');
        $('input[name="woocommerce_disable_sync"]').iCheck('check');
    }*/
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
    var action = 2;
    var product_id = 1;
    $.ajax({
        method: 'POST',
        url: '{{ route("load_quiz_options") }}',
        dataType: 'html',
        data: { load_options: $('#load_options_numeric').val(), quiz_type: $('#quiz_type').val(), action: action },
        success: function(result) {
            if (result) {
                $('#quiz_option_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}

$('#load_options_multiple').change(function() {

   
       show_quiz_option_multiple_form();
       $('#total_answer_form_part').html("");
    });




function show_quiz_option_multiple_form() {

    //Disable Stock management & Woocommmerce sync if type combo
    /*if($('#type').val() == 'combo'){
        $('#enable_stock').iCheck('uncheck');
        $('input[name="woocommerce_disable_sync"]').iCheck('check');
    }*/
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
    var action = 2;
    var product_id = 1;
    $.ajax({
        method: 'POST',
        url: '{{ route("load_quiz_options") }}',
        dataType: 'html',
        data: { load_options: $('#load_options_multiple').val(), quiz_type: $('#quiz_type').val(), action: action },
        success: function(result) {
            if (result) {
                $('#quiz_option_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}
</script>


    @endsection