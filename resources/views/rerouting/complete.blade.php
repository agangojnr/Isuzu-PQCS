
@extends('layouts.app')
@section('title','Rerouting Complete')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Complete Re-Routing </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">>Complete Re-Routing</li>
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
                            
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    {{ Form::open(['route' => 'saverouting', 'class' => '', 'role' => 'form', 'method' => 'post', 'id' => 'complete-rerouting','files' => false])}}
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$vehicle->model->model_name}}-{{$vehicle->vin_no}} <span class="ml-5">Lot:{{$vehicle->lot_no}} Job:{{$vehicle->job_no}} Route:{{$vehicle->route}}</span></h4>
        
                <div class="row">
                    <div class="col-md-4">
                        <label for="lot">Change To</label>
                        {!! Form::select('to_route_id', $route,  null, ['class' => 'select2 form-control custom-select','style'=>'height: 40px;width: 100%;']); !!}
                        
                    </div>
                    <div class="col-md-6">
                        <label for="job">Reason </label>
                        {!! Form::text('description',  null, ['class' => 'form-control']); !!}
                      
                    </div>
                    <input type="hidden" name="vehicle_id" value="{{ $id }}">  
                    <input type="hidden" name="from_route_id" value="{{ $vehicle->route }}">  

                  
                  
                </div>
             
              
            </div>
        </div>
    </div>

  
    <div class="row">
        <div class="col-12">
            <div class="card">

@php
    //print_r($data);
  
@endphp

                <div class="card-body">
                   




                
                <div class="card-body">

                        <div class="box-body">
  

        <div class="row">
            <div class="col-sm-12 col-sm-offset-2">
                <table class="table table-bordered table-striped table-condensed" id="product_table">
                    <thead>
                        <tr>
                            <th class="col-sm-4">Current Shop</th>
                            <th class="col-sm-4">Change With</th>
                            <th class="col-sm-4">Remove</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>

                        @if(!empty($data))

                        @php
                        $i=0;
                        
                    @endphp
                        @foreach ($data as $unit)

                        @php
                        $i++;
                      
                    @endphp

<tr>
    <td>
    {{$unit->shop->shop_name}}

        <input type="hidden" name="unit_movement_id[]" value="{{ $unit->id }}">   
        <input type="hidden" name="from_shop_id[]" value="{{ $unit->shop_id }}">   
    </td>
    <td>
        <select name="to_shop_id[]"  class="form-control">
            <option value="">Select Shop<option>
            @foreach ( $shops as $shop )
            <option value="{{ $shop->id }}">{{ $shop->shop_name }}<option>
                
            @endforeach
            
        </select>
      

         
    </td>
      <td>
        <div class="col-md-3">
            <input type="checkbox" id="md_checkbox_{{  $unit->shop_id }}" value="1" name="is_deleted[]" class="material-inputs filled-in chk-col-red"  />
            <label for="md_checkbox_{{  $unit->shop_id }}"></label>
        </div>

         
    </td>
    

</tr>


                        @endforeach
                      

            
@endif

                 </tbody>
                </table>
            </div>
        </div>





    </div>


        

          
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Complete Re-Routing', ['class' => 'btn btn-info btn-md']) }}


                        {{ link_to_route('routingquery.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light','id'=>'submit-data']) }}
                    </div>
                </div>

                </div>
       
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}



@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
       {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    {{ Html::style('assets/libs/dist/jquery-ui.css') }}
    {{ Html::style('assets/libs/select2/dist/css/select2.min.css') }}

     
    
@endsection



@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
   
{{ Html::script('assets/libs/jquery/dist/jquery-ui.min.js') }}
{{ Html::script('assets/libs/jquery/dist/jquery-migrate-3.0.0.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.full.min.js') }}
{{ Html::script('assets/libs/select2/dist/js/select2.min.js') }}



<script type="text/javascript">
$(".select2").select2();



    $(document).ready(function() {
    //Add products
    if ($('#search_product_for_label').length > 0) {
        $('#search_product_for_label')
            .autocomplete({
                source: 'get_units',
                minLength: 2,
                response: function(event, ui) {
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];
                      
                        $(this)
                            .data('ui-autocomplete')
                            ._trigger('select', 'autocompleteselect', ui);
                        $(this).autocomplete('close');
                    } else if (ui.content.length == 0) {
                        Swal.fire("Unit Not Found!");


                        //swal('LANG.no_products_found');
                    }
                },
                select: function(event, ui) {
                    $(this).val(null);
                    
                    get_label_product_row(ui.item.id);
                },
            })
            .autocomplete('instance')._renderItem = function(ul, item) {
                //console.log(item);
            return $('<li>')
                .append('<div>Lot No:' + item.lot_no + ' Job No:' + item.job_no + ' Chasis:' + item.vin_no + '</div>')
                .appendTo(ul);
        };
    }

    $('input#is_show_price').change(function() {
        if ($(this).is(':checked')) {
            $('div#price_type_div').show();
        } else {
            $('div#price_type_div').hide();
        }
    });

    $('button#labels_preview').click(function() {
        if ($('form#preview_setting_form table#product_table tbody tr').length > 0) {
            var url = base_path + '/labels/preview?' + $('form#preview_setting_form').serialize();

            window.open(url, 'newwindow');

            // $.ajax({
            //     method: 'get',
            //     url: '/labels/preview',
            //     dataType: 'json',
            //     data: $('form#preview_setting_form').serialize(),
            //     success: function(result) {
            //         if (result.success) {
            //             $('div.display_label_div').removeClass('hide');
            //             $('div#preview_box').html(result.html);
            //             __currency_convert_recursively($('div#preview_box'));
            //         } else {
            //             toastr.error(result.msg);
            //         }
            //     },
            // });
        } else {
            swal(LANG.label_no_product_error).then(value => {
                $('#search_product_for_label').focus();
            });
        }
    });

    $(document).on('click', 'button#print_label', function() {
        window.print();
    });
});




   
function get_label_product_row(unit_id) {
    if (unit_id) {
        var row_count = $('table#product_table tbody tr').length;
        $.ajax({
            method: 'GET',
            url: '{{ route("add_unit_row") }}',
            dataType: 'html',
            data: { unit_id: unit_id, row_count: row_count},
            success: function(result) {
                $('table#product_table tbody').append(result);
            },
        });
    }
}


$(document).on('click', ".v_delete_temp", function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });



        $(document).on('submit', 'form#complete-rerouting', function(e){
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

                        location.href = '{{ route("rerouting.create") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });
    
</script>

  
    @endsection