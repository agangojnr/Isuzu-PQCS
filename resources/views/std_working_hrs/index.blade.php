<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Standard Working Hours</title>

    @include('layouts.header.header')
    @yield('after-styles')

</head>
<body>

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Standard Working Hours</h3>
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Standard Working Hours Table</li>
            </ol>


        </div>
        <div class="col-md-7">
            <div>
                <a href="/home" id="btn-add-contact" class="btn btn-danger float-right"
            style="background-color:#da251c; "><i class="mdi mdi-arrow-left font-16 mr-1"></i> Back to Home</a>
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

    <!-- Individual column searching (select inputs) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {!! Form::open(['action'=>'App\Http\Controllers\stdworkinghrs\StdWorkingHrController@store', 'method'=>'post', 'enctype' => 'multipart/form-data']); !!}
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Standard Working Hours</h4>
                        </div>
                        <div class="col-6">
                            <button id="savestdhr" disabled type="submit" class="btn btn-primary float-right mb-3">Save Changes</button>
                        </div>
                    </div>
                    <div class="table-responsive1" style="font-size:16px;">
                        <table id="scroll_ver" class="table table-striped table-bordered display"
                                        style="width:100%">
                            <thead class="red">
                                <tr>                                    
                                    <th>
                                        Model Name.
                                    </th>
                                    @foreach ($shopnames as $item)
                                    <th>{{$item->report_name}}</th>
                                     @endforeach
                                    <th>Plant</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(count($models) > 0)
                                @foreach($models as $model)
                                    <tr style="">
                                        
                                        <td>
                                            {{$model->model_name}}
                                        </td>
                                        @foreach ($shops as $shop)
                                            <td style="width:60px;" id="stdhr_{{$model->id}}_{{$shop->id}}" class="stdhr" data-editable>
                                                {{$stdhrs_arr[$model->id][$shop->id]}}</td>
                                        @endforeach
                                        <td><b>{{$plant[$model->id]}}</b></td>
                                    </tr>
                                @endforeach
                                @endif
                             </tbody>
                             <tfoot>
                             <tr>
                                <th>
                                    Model Name
                                </th>
                                @foreach ($shopnames as $item)
                                <th>{{$item->report_name}}</th>
                                 @endforeach
                                <th>Plant</th>
                            </tr>
                             </tfoot>
                        </table>
                    </div>
                    {!! Form::close() !!}
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

<script>
    $('body').on('click', '[data-editable]', function(){

    var $el = $(this);

    var $input = $('<input type="text" class="form-control" style="font-size: 14px;"/>').val( $el.text() );
    $el.html( $input );
    var $id_arr = $el.attr('id');
    var $id = $id_arr.split('_');

    var $mdlid = $id[1];
    var $shpd = $id[2];

    var save = function(){
        var $p =  $input.val();
        console.log($p);
    var $stdhr = $('<input type="hidden" name="stdhredited[]" value="'+$p+'"/>');
    var $modelid = $('<input type="hidden" name="modelid[]" value="'+$mdlid+'"/>');
    var $shopid = $('<input type="hidden" name="shopid[]" value="'+$shpd+'"/>');

    $el.html( $p );
    $el.append( $stdhr );
    $el.append( $modelid );
    $el.append( $shopid );

    $savestdhr = $('#savestdhr');
    $savestdhr.attr('disabled', false);
    };

    $input.one('blur', save).focus();

    });
</script>
</body>

</html>

