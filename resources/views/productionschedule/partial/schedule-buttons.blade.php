<div class="">
    @can('prod-sche')
    <div class="btn-group" role="group" aria-label="Basic example">
    <a href="{{route('fcwschedule')}}" class="btn btn-lighten-2 text-white" style="background:teal;"><i
            class="fa fa-list-alt"></i> FCW Schedule</a>

        <a href="{{route('productionschedule')}}" class="btn btn-lighten-2 text-white btn-primary"><i
            class="fa fa-list-alt"></i> Offline Schedule</a>

        <a href="{{ route('comments') }}" class="btn btn-danger  btn-lighten-3">
            <i class="mdi mdi-comment-text"></i> Revision Comments</a>

    </div>
    @endcan
</div>
<div class="clearfix"></div>
