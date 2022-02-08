
                      <div class="card-body">
                              
                        {!! Form::hidden('unit_id',$id, ['id' => 'unit_id']); !!}     
                        
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="date">Choose Month</label>
                                    <input class="form-control from_custom_date" type="text" id="datepicker"
                                        required="" name="month_date" value="{{date('F Y')}}"  data-toggle="datepicker" autocomplete="off"  >
                                </div>
                                </div>
                      </div>




 <div class="row">
       <div class="form-group col-md-12">
                                        <label>File : csv, xls or xlsx</label>
                                         <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" required name="import_file" class="custom-file-input" id="icon"  >
                                            <label class="custom-file-label" for="coin">Choose file</label>
                                        </div>
                                    </div>
                                    </div>
 </div>


           
                               </div>