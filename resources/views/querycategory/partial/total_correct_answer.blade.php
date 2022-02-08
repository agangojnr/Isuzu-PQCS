 <hr class="mt-0 mb-2">
   <div class="card-body">
    <div class="row">
      @php
for($i=1;$i<=$total_correct_answer;$i++){
  @endphp
  <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Answer  </label>
                                                    <div class="col-md-9">

                                                         <input type="number" name="correct_answers[]" id="correct_answer" 
                                            class="form-control" placeholder="Answer {{$i}}">


                                                       </div>
                                                </div>
                                            </div>









                                   
        @php
}
        @endphp                            

 
  </div>
 </div>
                                  