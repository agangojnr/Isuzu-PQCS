
                     <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Staff No.:</label>
                        <div class="col-sm-9">
                            {{Form::text('staffno', '', ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Job Code here',
                            'autocomplete'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Staff Name.:</label>
                        <div class="col-sm-9">
                            {{Form::text('staffname', '', ['class'=>'form-control', 'id'=>'description',
                            'placeholder'=>'Staff Name here','autocomplete'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>

                   <div class="form-group row">
                        <label for="department" class="col-sm-3 text-right control-label col-form-label">Department Name.:</label>
                        <div class="col-sm-9">
                            {{Form::text('department', '', ['class'=>'form-control', 'id'=>'department',
                            'placeholder'=>'Department Name here','autocomplete'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="category" class="col-sm-3 text-right control-label col-form-label">Category Name.:</label>
                        <div class="col-sm-9">
                            {{Form::text('category', '', ['class'=>'form-control', 'id'=>'category',
                            'placeholder'=>'Category Name here','autocomplete'=>'off', 'required'=>'required'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Shop.:</label>
                        <div class="col-sm-9">
                            {{Form::select('shop', $shops, $shops->pluck('id'), array('class'=>'form-control select2',
                            'style'=>'width:100%;', 'placeholder'=>'Please select ...', 'required'=>'required'))}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Is Staff Team Leader.:</label>
                        <div class="col-sm-9">
                            <input name="teamleader" value="yes" type="radio" class="with-gap material-inputs radio-col-blue" id="radio_3" required='required' />
                            <label for="radio_3">Yes</label>

                            <input name="teamleader" value="no" type="radio" checked id="radio_4" class="with-gap material-inputs radio-col-pink" required='required' />
                            <label for="radio_4">No</label>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Staff Status.:</label>
                        <div class="col-sm-9">
                            <input name="status" value="Active" type="radio" checked class="with-gap material-inputs radio-col-indigo" id="radio1" />
                            <label for="radio1">Active</label>

                            <input name="status" value="Inactive" type="radio" id="radio2" class="with-gap material-inputs radio-col-red" />
                            <label for="radio2">Inactive</label>
                        </div>

                    </div>

                <hr>
                <div class="card-body">
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-info waves-effect waves-light" id="close-button1">Save</button>
                        <button type="reset" class="btn btn-dark waves-effect waves-light">Cancel</button>
                    </div>
                </div>


<!-- End Row -->
