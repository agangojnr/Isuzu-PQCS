<div class="">
@if(Auth()->User()->can('swap-create') || Auth()->User()->can('swap-list'))
    <div class="btn-group" role="group" aria-label="Basic example">
		@can('swap-list')
        <a href="{{ route( 'swapunit.index' ) }}" class="btn btn-info  btn-lighten-2"><i
                    class="fa fa-list-alt"></i> List</a>
		@endcan
		@can('swap-create')
         <a href="{{ route( 'swapunit.create' ) }}"
                                         class="btn btn-danger  btn-lighten-3"><i
                    class="fa fa-plus-circle"></i> Create</a> 
		@endcan
    </div>
@endif
</div>
<div class="clearfix"></div>
