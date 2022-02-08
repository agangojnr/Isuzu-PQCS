<div class="">
    @can('gca-score')
    <div class="btn-group" role="group" aria-label="Basic example">
        <a href="{{ route( 'gcascore.index' ) }}" class="btn btn-lighten-2 text-white" style="background:teal;"><i
            class="fa fa-list-alt"></i> Report</a>

            <a href="{{ route( 'gcalist' ) }}" class="btn btn-info  btn-lighten-2"><i
                        class="fa fa-list-alt"></i> List</a>

         <a href="{{ route( 'gcascore.create' ) }}" class="btn btn-danger  btn-lighten-3">
            <i class="fa fa-plus-circle"></i> Build Score</a>

    </div>
    @endcan
</div>
<div class="clearfix"></div>
