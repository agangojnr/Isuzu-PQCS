<div class="">
    @can('role-list','role-create')
    <div class="btn-group" role="group" aria-label="Basic example">
        @can('role-list')
            <a href="{{ route( 'roles.index' ) }}" class="btn btn-info  btn-lighten-2"><i
                    class="fa fa-list-alt"></i> List</a>
        @endcan
        @can('role-create')
            <a href="{{ route( 'roles.create' ) }}" class="btn btn-danger  btn-lighten-3">
                <i class="fa fa-plus-circle"></i> Create</a>
        @endcan
    </div>
    @endcan
</div>
<div class="clearfix"></div>
