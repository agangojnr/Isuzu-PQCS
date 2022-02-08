@extends('layouts.app')

@section('content')
<div class="card m-5 p-3">
    <h2 class="card-title">Create Vehicle</h2>
    <hr>
            <form class="form-horizontal">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label">Employee Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ename" placeholder="Employee Name Here">
                        </div>
                    </div>

            @include('app_users.form')

                </div>
            </form>

</div>
@endsection

