
                      <div class="card-body">
                              
                               <div class="row">
                             


                                <div class="form-group col-md-3">
                                        <label>Code <span class="help"> e.g. "BS-MF101"</span></label>

                                        {!! Form::text('query_code',  null, ['placeholder' => 'Query Code', 'class' => 'form-control form-control-line']); !!}


                                         </div>




                                    <div class="form-group col-md-3">
                                        <label>Shop <span class="help"> e.g. "Body Shop"</span></label>

                                         {!! Form::select('shop_id', $shops,  null, ['placeholder' => 'Select Shop', 'class' => 'form-control form-control-line']); !!}





                                         


                                         </div>
                                    <div class="form-group col-md-3">
                                        <label for="example-email">Models <span class="help"> e.g.
                                                "FRR90"</span></label>

                     {!! Form::select('model_id[]', $models,  null, ['class' => 'select2 form-control','multiple'=>'multiple','style'=>'height: 36px;width: 100%;']); !!}



                                       
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Query Category <span class="help"> e.g.
                                                "FRR90"</span></label>
                                         {!! Form::select('category_id', $querycategory,  null, ['placeholder' => 'Select Qury Category', 'class' => 'select2 form-control custom-select','style'=>'height: 36px;width: 100%;']); !!}
                                    </div>

                                </div>
                                      <div class="row">   
                                    <div class="form-group col-md-12">
                                        <label>Query Name</label>
                                       <textarea cols="70" name="query_name" id="description" name="testedit" rows="10" data-sample="1"
                                    data-sample-short>
                                   
                                </textarea>
                            </div>
                                    </div>

                                    <div class="row">   
                                       <div class="form-group col-md-4">
                                        <label>Attach Image</label>
                                         <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="icon" class="custom-file-input" id="icon" accept="image/png, image/gif, image/jpeg" >
                                            <label class="custom-file-label" for="coin">Choose file</label>
                                        </div>
                                    </div>
                                    </div>

                                       <div class="form-group col-md-2">
                                        <label>User To Sign</label><br>
                                <input type="checkbox" name="can_sign" class="form-control bt-switch"  data-on-color="primary" data-off-color="info" data-on-text="Yes" data-off-text="No">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Question Type</label>
                                        <select name="quiz_type" class="form-control" id="quiz_type">
                                            <option value="">Select Query Type</option>
                                            <option value="single">Single Answer</option>
                                            <option value="multiple">Multiple Answer</option>
                                            <option value="numeric">Numerical Answer</option>
                                             
                                            
                                        </select>
                                    </div>


                                   



                                       <div class="form-group col-md-3" id="quiz_form_part">

                                     </div>

                                  
                                </div>

<div class="row" id="quiz_option_form_part">  


</div>

<div class="row" id="total_answer_form_part">  


</div>



                          
                                       

                                     
                                
                              
                                 
                               </div>