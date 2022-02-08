
@extends('layouts.app')
@section('title','Swap Unit')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Swap Unit </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Swap Unit</li>
            </ol>
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
                            @include('swapunit.partial.swapunit-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
  



              <div class="row">
                    <div class="col-lg-12">
                        <div class="card ">
                            <div class="card-header bg-danger">
                                <h4 class="card-title text-white">Swap Unit</h4>
                            </div>
                             {{ Form::open(['route' => 'swapunit', 'class' => 'form-horizontal','files' => true, 'role' => 'form', 'method' => 'post', 'id' => 'swap-unit'])}}


                           
                                <div class="form-body">
                               
                                

                       

                  <div id="main_product">
                        <div class="product">
                                  
                                    <div class="card-body">
                                        <!--/row-->

                          
                  

 <span class="col-6 del_b"></span>



 <div class="table-responsive">  
    <table class="table table-bordered" id="dynamic_field">  
        <tr>
            <td>Unit To Swap</td>
     
         
           
           
            
            <td class="table-warning">Swap With</td>
        
            <td >Action</td>
        </tr>
        <tr>  
            <td><input type="text" class="form-control" name="unit_to_swap[]" placeholder="Search By Vin NUmber Or Lot Number" id='unit_to_swap-0'>
                <input type="hidden" class="swapId" required="required"  name="unit_to_id[]"  id='unit_to_id-0'></td>  

            <td class="table-warning"><input type="text" class="form-control swapToid" name="unit_with_swap[]" placeholder="Search By Vin NUmber Or Lot Number" id='unit_with_swap-0'>
                <input type="hidden" class="swapToid" required="required"  name="unit_with_swap_id[]"  id='unit_with_swap_id-0'></td>  
          
            <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
        </tr>  
    </table>   
  
</div>

                                        <!--/row-->
                                    </div>

                                </div>

                            </div>




                                    <hr>




                                    <div class="card-body">
                                       
                                    
                                     
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Give Reason</label>
                                                    <div class="col-md-9">
                                        <textarea name="reason" required="required"  class="form-control" id="exampleTextarea" rows="3"
                                                    placeholder="Message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            
                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                       </div>

                                    <div class="form-actions">
                                        <div class="card-body">
                                            <div class="text-right">
                                                {{ Form::submit('Save', ['class' => 'btn btn-info btn-md']) }}


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
 {{ Html::style('assets/libs/jquery-ui-1.13.0/jquery-ui.min.css') }}

     
    
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
{{ Html::script('assets/libs/jquery-ui-1.13.0/jquery-ui.min.js') }}
    

<script type="text/javascript">
     
 $(document).on('submit', 'form#swap-unit', function(e){
            e.preventDefault();
            //var data = $(this).serialize();
            $("#submit-data").hide();
            $.ajax({
                method: "post",
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



  





//$(".select2").select2();



$(".bt-switch").bootstrapSwitch();


    


            $(document).ready(function(){      
    //  var postURL = "<?php echo url('addmore'); ?>";
      var i=1;  


      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" class="form-control swapToid" name="unit_to_swap[]" placeholder="Search By Vin NUmber Or Lot Number" id="unit_to_swap-'+i+'"><input type="hidden"  required="required" class="swapId"  name="unit_to_id[]"  id="unit_to_id-'+i+'"></td> <td class="table-warning"><input type="text" class="form-control swapToid" name="unit_with_swap[]" placeholder="Search By Vin NUmber Or Lot Number" id="unit_with_swap-'+i+'"><input type="hidden" class="swapToid" required="required" name="unit_with_swap_id[]"  id="unit_with_swap_id-'+i+'"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>'); 


           $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      }); 

  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
 

 $( "#unit_to_swap-"+i).autocomplete({

    
     source: function( request, response ) {
       // Fetch data
       $.ajax({
         url: baseurl + 'swapunit/seach',
         type: 'post',
         dataType: "json",
         data: {
            _token: CSRF_TOKEN,
            search: request.term
         },
         success: function( data ) {
            response( data );
         }
       });
     },
     select: function (event, ui) {
         var dupswapid;
         var dupswaptoid;
         var label=ui.item.label;
         var value=ui.item.value;

         $('.swapId').each(function () {
             if ($(this).val() == ui.item.value) dupswapid = true;
         });

         $('.swapToid').each(function () {
             if ($(this).val() == ui.item.value) dupswaptoid = true;
         });


         if (dupswapid) {
             alert('Already Exists!!');
             label='';
             value='';
           
         }

         if (dupswaptoid) {
            

             alert('Already Exists!!');
             label='';
             value='';
           
         }


        $('#unit_to_swap-'+i).val(label);
       $('#unit_to_id-'+i).val(value); 
        return false;
     }
   });



     
 $( "#unit_with_swap-"+i ).autocomplete({
     source: function( request, response ) {
       // Fetch data
       $.ajax({
         url: baseurl + 'swapunit/seach',
         type: 'post',
         dataType: "json",
         data: {
            _token: CSRF_TOKEN,
            search: request.term
         },
         success: function( data ) {
            response( data );
         }
       });
     },
     select: function (event, ui) {
         var dupswapid;
         var dupswaptoid;
         var label=ui.item.label;
         var value=ui.item.value;

         $('.swapId').each(function () {
             if ($(this).val() == ui.item.value) dupswapid = true;
         });

         $('.swapToid').each(function () {
             if ($(this).val() == ui.item.value) dupswaptoid = true;
         });


         if (dupswapid) {
             alert('Already Exists!!');
             label='';
             value='';
            
             //return;
         }

         if (dupswaptoid) {
            
             label='';
             value='';
             alert('Already Exists!!');
             //return;
         }


        $('#unit_with_swap-'+i).val(label);
       $('#unit_with_swap_id-'+i).val(value); 
        return false;
     }
   });

           
            
      });  


    
    }); 

   


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
 

 $( "#unit_to_swap-0" ).autocomplete({

    
     source: function( request, response ) {
       // Fetch data
       $.ajax({
         url: baseurl + 'swapunit/seach',
         type: 'post',
         dataType: "json",
         data: {
            _token: CSRF_TOKEN,
            search: request.term
         },
         success: function( data ) {
            response( data );
         }
       });
     },
     select: function (event, ui) {
         var dupswapid;
         var dupswaptoid;

         var label=ui.item.label;
         var value=ui.item.value;

         $('.swapId').each(function () {
             if ($(this).val() == ui.item.value) dupswapid = true;
         });

         $('.swapToid').each(function () {
             if ($(this).val() == ui.item.value) dupswaptoid = true;
         });


         if (dupswapid) {
             alert('Already Exists!!');
             label='';
             value='';
            
         }

         if (dupswaptoid) {
            

             alert('Already Exists!!');
             label='';
             value='';
        
         }


        $('#unit_to_swap-0').val(label);
       $('#unit_to_id-0').val(value); 
        return false;
     }
   });




   
 $( "#unit_with_swap-0" ).autocomplete({
     source: function( request, response ) {
       // Fetch data
       $.ajax({
         url: baseurl + 'swapunit/seach',
         type: 'post',
         dataType: "json",
         data: {
            _token: CSRF_TOKEN,
            search: request.term
         },
         success: function( data ) {
            response( data );
         }
       });
     },
     select: function (event, ui) {
         var dupswapid;
         var dupswaptoid;
         var label=ui.item.label;
         var value=ui.item.value;

         $('.swapId').each(function () {
             if ($(this).val() == ui.item.value) dupswapid = true;
         });

         $('.swapToid').each(function () {
             if ($(this).val() == ui.item.value) dupswaptoid = true;
         });


         if (dupswapid) {
             alert('Already Exists!!');
             label='';
             value='';
            
             //return;
         }

         if (dupswaptoid) {
            
             label='';
             value='';
             alert('Already Exists!!');
             //return;
         }


        $('#unit_with_swap-0').val(label);
       $('#unit_with_swap_id-0').val(value); 
        return false;
     }
   });



</script>


    @endsection