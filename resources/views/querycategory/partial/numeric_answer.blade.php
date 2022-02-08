  <hr class="mt-0 mb-2">
   <div class="card-body bg-light">
    <div class="row">

      @if($total_anwer==6)

       <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Lower Limit</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="lower_limit[]" id="lower_limit" 
                                            class="form-control" placeholder="Lower Limit">


                                                       </div>
                                                </div>
                                            </div>



                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Upper Limit</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="upper_limit[]" id="upper_limit" 
                                            class="form-control" placeholder="Upper Limit">


                                                       </div>
                                                </div>
                                            </div>


                                  
        @endif   


          @if($total_anwer==7)

       <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Lower Limit</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="lower_limit[]" id="lower_limit" 
                                            class="form-control" placeholder="Lower Limit">


                                                       </div>
                                                </div>
                                            </div>



                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Upper Limit</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="upper_limit[]" id="upper_limit" 
                                            class="form-control" placeholder="Upper Limit">


                                                       </div>
                                                </div>
                                            </div>


                                  
        @endif  

          <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Expected Value</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="correct_answers" id="correct_answers" 
                                            class="form-control" placeholder="Expected Value">


                                                       </div>
                                                </div>
                                            </div>


 
 
                                  </div>
 </div>