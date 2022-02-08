




                     <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label">Employee Name</label>
                        <div class="col-sm-9">
                             {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Employee Name','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pname" class="col-sm-3 text-right control-label col-form-label">Phone Number</label>
                        <div class="col-sm-9">
                            {{ Form::text('phone_no', null, ['class' => 'form-control', 'placeholder' => 'Phone Number','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date" class="col-sm-3 text-right control-label col-form-label">Email</label>
                        <div class="col-sm-9">
                            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email Address','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>

                           {{ Form::hidden('username', 'username', ['class' => 'form-control', 'placeholder' => 'Username','required'=>'required','autocomplete'=>'off']) }}

                    <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label">User Role:</label>
                        <div class="col-sm-9">
                            {!! Form::select('role_id', $roles,  null, ['placeholder' => 'Select Role',
                            'class' => 'form-control custom-select select2']); !!}

                        </div>
                    </div>
                   <!-- <div class="form-group row">
                        <label for="stime" class="col-sm-3 text-right control-label col-form-label">Password</label>
                        <div class="col-sm-9">

                            {{ Form::password('password', ['class' => 'form-control','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>-->








