
                     <div class="form-group row">
                        <label for="pname" class="col-sm-3 text-right control-label col-form-label">Float Type</label>
                        <div class="col-sm-9">
                            

                            {!! Form::select('float_type', ['repair'=>'Repair','system'=>'System'],  null, ['placeholder' => 'Float Type Shop','required'=>'required', 'class' => 'form-control custom-select ']); !!}


                        </div>
                    </div>                      
                     <div class="form-group row">
                        <label for="ename" class="col-sm-3 text-right control-label col-form-label"> Float Name</label>
                        <div class="col-sm-9">
                             {{ Form::text('float_name', null, ['class' => 'form-control', 'placeholder' => 'Float  Name','required'=>'required','autocomplete'=>'off']) }}    
                        </div>
                    </div>
                  
                  
                 

               
                 
                 

         
            



