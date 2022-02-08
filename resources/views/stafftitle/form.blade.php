
                     <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Job Code</label>
                        <div class="col-sm-9">
                            {{Form::text('code', '', ['class'=>'form-control', 'id'=>'code', 'placeholder'=>'Job Code here'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-3 text-right control-label col-form-label">Job Description</label>
                        <div class="col-sm-9">
                            {{Form::text('description', '', ['class'=>'form-control', 'id'=>'description', 'placeholder'=>'Job description here'])}}
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
