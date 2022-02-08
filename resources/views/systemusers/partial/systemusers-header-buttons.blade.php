<div class="">
    @can('sysuser-list','sysuser-create')
    <div class="btn-group" role="group" aria-label="Basic example">
        @can('sysuser-list')
            <a href="{{ route( 'systemusers.index' ) }}" class="btn btn-info  btn-lighten-2"><i
                        class="fa fa-list-alt"></i> List</a>
        @endcan
        @can('sysuser-create')
         <a href="{{ route( 'systemusers.create' ) }}" class="btn btn-danger  btn-lighten-3">
            <i class="fa fa-plus-circle"></i> Create</a>
        @endcan
    </div>
    @endcan
</div>
<div class="clearfix"></div>
