
                      <div class="card-body">
                              
                               <div class="row">
                             
                                    <div class="form-group col-md-12">
                                        <label>Vehicle Type <span class="help"> e.g. "N-SERIES"</span></label>

                                         {!! Form::select('vehicle_type_id', $vehicletypes,  null, ['placeholder' => 'Select Type', 'class' => 'form-control form-control-line']); !!}
                                     </div> 
                                 
                                </div>


 <div class="row">
   <div class="form-group col-md-12">
                                        <label>Model Name <span class="help"> e.g. "NLR77U-EE1AYN"</span></label>

                                        {!! Form::text('model_name',  null, ['placeholder' => 'Model Name', 'class' => 'form-control form-control-line','autocomplete'=>'off']); !!}
                             </div>
 </div>

  <div class="row">
       <div class="form-group col-md-12">
                                        <label>Model Code <span class="help"> e.g. "BS-19278"</span></label>

                                        {!! Form::text('model_number',  null, ['placeholder' => 'Model Number', 'class' => 'form-control form-control-line','autocomplete'=>'off']); !!}
                             </div>
 </div>
           
                               </div>