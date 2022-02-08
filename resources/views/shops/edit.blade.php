
@extends('layouts.app')
@section('title','Edit Shop')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Edit Shop</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit Shop</li>
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
                            @include('shops.partial.shops-header-buttons')
                        </div>
                    </div>
                </div>
            </div>

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">



                <div class="card-body">
                     {{ Form::open(['route' => ['shops.update',$shops->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PUT', 'id' => 'update-shop'])}}


                
                <div class="card-body">
        
                     <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label">Shop Name</label>
                        <div class="col-sm-9">
                             {{ Form::text('shop_name', $shops->shop_name, ['class' => 'form-control', 'placeholder' => 'Shop Name','required'=>'required','autocomplete'=>'off']) }}    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pname" class="col-sm-3 text-right control-label col-form-label">Short Form</label>
                        <div class="col-sm-9">
                            {{ Form::text('report_name', $shops->report_name, ['class' => 'form-control', 'placeholder' => 'Short Form','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="check_point" class="col-sm-3 text-right control-label col-form-label">Checkpoint</label>
                        <div class="col-sm-9">

                            {!! Form::select('check_point', ['1'=>'Yes','0'=>'No'],  $shops->check_point, ['placeholder' => 'Select If Checkpont', 'class' => 'form-control custom-select ']); !!}


                           
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="color_code" class="col-sm-3 text-right control-label col-form-label">Color Code</label>
                        <div class="col-sm-9">
                            {{ Form::text('color_code', $shops->color_code, ['class' => 'form-control demo', 'placeholder' => 'Color Code', 'data-control'=>'hue', 'required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    
                 
               
                 

            <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-info btn-md','id'=>'submit-data']) }}


                        {{ link_to_route('shops.index', 'Cancel', [], ['class' => 'btn btn-dark waves-effect waves-light']) }}
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
    {{ Html::style('assets/libs/@claviska/jquery-minicolors/jquery.minicolors.css') }}
  

    
    
@endsection



@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}

{{ Html::script('assets/libs/jquery-asColor/dist/jquery-asColor.min.js') }}
{{ Html::script('assets/libs/jquery-asGradient/dist/jquery-asGradient.js') }}
{{ Html::script('assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js') }}
{{ Html::script('assets/libs/@claviska/jquery-minicolors/jquery.minicolors.min.js') }}



    

<script type="text/javascript">
     $(document).on('submit', 'form#update-shop', function(e){
            e.preventDefault();
            $("#submit-data").hide();
            var data = $(this).serialize();
            $.ajax({
                method: "POST",
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success:function(result){
                    if(result.success == true){
                        //$('div.account_model').modal('hide');
                        toastr.success(result.msg);
                       // capital_account_table.ajax.reload();
                        //other_account_table.ajax.reload();

                        location.href = '{{ route("shops.index") }}';
                    }else{
                         $("#submit-data").show();
                        toastr.error(result.msg);
                    }
                }
            });
        });


         $('.demo').each(function() {
      
        $(this).minicolors({
            control: $(this).attr('data-control') || 'hue',
            defaultValue: $(this).attr('data-defaultValue') || '',
            format: $(this).attr('data-format') || 'hex',
            keywords: $(this).attr('data-keywords') || '',
            inline: $(this).attr('data-inline') === 'true',
            letterCase: $(this).attr('data-letterCase') || 'lowercase',
            opacity: $(this).attr('data-opacity'),
            position: $(this).attr('data-position') || 'bottom left',
            swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            change: function(value, opacity) {
                if (!value) return;
                if (opacity) value += ', ' + opacity;
                if (typeof console === 'object') {
                    console.log(value);
                }
            },
            theme: 'bootstrap'
        });

    });
</script>
    @endsection