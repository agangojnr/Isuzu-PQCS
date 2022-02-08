
@extends('layouts.app')
@section('title','Routing Query')

@section('content')


    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h3 class="text-themecolor mb-0">Routing Query </h3>
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
                    <h3 class="mb-0">Routing Query</h3>

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
        <div class="col-12">
            <div class="card">



                <div class="card-body">
                   
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover routingquery routingquery-category" id="routingqueryss">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Can Sign</th>
                                     <th>Use Different Answer</th>
                                    <th>Additional Field</th>
                                    <th>Position</th>
                                   
                                  

                                </tr>
                            </thead>
                            @foreach($querycategories as $category)
                            <tbody data-id="{{ $category->id }}" class="sortable">
                                <tr>
                                    <td colspan="5" style="background-color:#ddd;">{{ $category->query_code }} - {{ $category->category_name }} - {{ $category->shop->shop_name }} </td>
                                </tr>

                                @foreach($category->queries as $query)

                                <tr data-id="{{ $query->id }}" class="query">

                                    <td class="query-name">
                                        {{ $query->query_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $query->can_sign ?? '' }}
                                    </td>
                                    <td>
                                        {{ $query->use_defferent_routing ?? '' }}
                                    </td>
                                    
                                    <td>
                                        {{ $query->additional_field ?? '' }}
                                    </td>
                                    <td class="position">
                                        {{ $query->position ?? '' }}
                                    </td>
                                

                                </tr>




                                @endforeach

                                

                            </tbody>




                            @endforeach
                        
                        </table>
                    </div>
                </div>






            </div>
        </div>
    </div>




@endsection
@section('after-styles')
    {{ Html::style('assets/extra-libs/toastr/dist/build/toastr.min.css') }}
    {{ Html::style('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}
    
@endsection



@section('after-scripts')

{{ Html::script('assets/extra-libs/toastr/dist/build/toastr.min.js') }}
{{ Html::script('assets/extra-libs/toastr/toastr-init.js') }}
{{ Html::script('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>

<script type="text/javascript">



    window._token = $('meta[name="csrf-token"]').attr('content')

  //console.log(_token);
  
    


  $.extend(true, $.fn.dataTable.defaults, {
  
    columnDefs: [ {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 100,
  
 
  });

  $.fn.dataTable.ext.classes.sPageButton = '';


  function sendReorderMealsRequest($category) {
        var items = $category.sortable('toArray', {attribute: 'data-id'});
        var ids = $.grep(items, (item) => item !== "");

        if ($category.find('tr.query').length) {
            $category.find('.empty-message').hide();
        }
        $category.find('.query-name').text($category.find('tr:first td').text());

        

        $.post('{{ route('reorder') }}', {
                _token,
                ids,
                category_id: $category.data('id')
            })
            .done(function (response) {
                $category.children('tr.query').each(function (index, query) {
                    $(query).children('.position').text(response.positions[$(query).data('id')])
                });

              location.reload();
            })
            .fail(function (response) {
                alert('Error occured while sending reorder request');
                location.reload();
            });
    }

    $(document).ready(function () {
        var $categories = $('table');
        var $queries = $('.sortable');
        
        $categories.sortable({
            cancel: 'thead',
            stop: () => {
                var items = $categories.sortable('toArray', {attribute: 'data-id'});
                var ids = $.grep(items, (item) => item !== "");

              // console.log(ids);
                $.post('{{ route('categoryreorder') }}', {
                        _token,
                        ids
                    })
                    .fail(function (response) {
                        alert('Error occured while sending reorder request');
                        location.reload();
                    });
            }
        });

        $queries.sortable({
            connectWith: '.sortable',
            items: 'tr.query',
            stop: (event, ui) => {
                sendReorderMealsRequest($(ui.item).parent());

                if ($(event.target).data('id') != $(ui.item).parent().data('id')) {
                    if ($(event.target).find('tr.query').length) {
                        sendReorderMealsRequest($(event.target));
                    } else {
                        $(event.target).find('.empty-message').show();
                    }
                }
            }
        });
        $('table, .sortable').disableSelection();
    });



                      


</script>
    @endsection