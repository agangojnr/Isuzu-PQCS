
@extends('layouts.app')
@section('title','Rerouting Create')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Rerouting Create </h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Rerouting Create</li>
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
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Filter</h4>
                {{ Form::open(['route' => 'filtererouting', 'class' => '', 'role' => 'form', 'method' => 'post', 'id' => 'filter-qrcode','files' => false])}}
                <div class="row">
                    <div class="col-md-3">
                        <label for="lot">By  Lot</label>
                        {!! Form::select('lot_no[]', $lot,  null, ['class' => 'select2 form-control custom-select','multiple'=>'multiple','style'=>'height: 25px;width: 100%;']); !!}
                        
                    </div>
                    <div class="col-md-3">
                        <label for="job">By Job</label>
                        {!! Form::select('job_no[]', $job,  null, ['class' => 'select2 form-control custom-select','multiple'=>'multiple','style'=>'height: 25px;width: 100%;']); !!}
                      
                    </div>
                    <div class="col-md-3 ">
                        <label for="date">By Model</label>
                        {!! Form::select('model_id[]', $model,  null, ['class' => 'select2 form-control custom-select','multiple'=>'multiple','style'=>'height: 25px;width: 100%;']); !!}
                       
                    </div>

                    <div class="col-md-3 p-4">
                        
                        {{ Form::submit('Filter Record', ['class' => 'btn btn-primary btn-md ']) }}
                    </div>
                  
                </div>
                {{ Form::close() }}
              
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
                     {{ Form::open(['route' => 'qrcode.store', 'class' => '', 'role' => 'form', 'method' => 'post', 'id' => 'create-query','files' => false,'target'=>"_blank"])}}




                
                <div class="card-body">

                        <div class="box-body">
        <div class="row">
            <div class="col-sm-12 col-sm-offset-2">
                 <label for="price_type">Unit Search:</label>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                        <input class="form-control" id="search_product_for_label" placeholder="Enter Lot Number,Engine Number,Chasis Number" autofocus name="search_product" type="text">
                    </div>
                </div>
            </div>

           
        </div>

        <div class="row">
            <div class="col-sm-12 col-sm-offset-2">
                <table class="table table-bordered table-striped table-condensed" id="product_table">
                    <thead>
                        <tr>
                            <th class="col-sm-3">Lot No</th>
                            <th class="col-sm-3">Job No</th>
                            <th class="col-sm-4">Chasis</th>
                            
                            <th class="col-sm-2">Select</th>
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
        {{$unit->lot_no}}

        <input type="hidden" name="units[{{$i}}][unit_id]" value="{{$unit->id}}">   
    </td>
    <td>
        {{$unit->job_no}}

         
    </td>
      <td>
        {{$unit->vin_no}}

         
    </td>
    
   <td><a class="btn btn-danger  m-1 align-content-end" href="{{ route("completererouting",[$unit->id]) }}" >  <i class="fa fa-check"></i></a>   </td>
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
                        {{ Form::submit('Print Label', ['class' => 'btn btn-info btn-md']) }}


                        {{ link_to_route('routingquery.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light','id'=>'submit-data']) }}
                    </div>
                </div>

                </div>
         {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>




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
    
</script>

  
    @endsection