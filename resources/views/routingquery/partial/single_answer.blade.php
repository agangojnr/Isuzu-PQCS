  <hr class="mt-0 mb-2">
   <div class="card-body bg-light">
    <div class="row">
  @php
for($i=1;$i<=$total_anwer;$i++){
  @endphp

    <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Option {{$i}}</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="answer[]" id="option-{{$i}}" 
                                            class="form-control" placeholder="Option {{$i}}">


                                                       </div>
                                                </div>
                                            </div>





                                   
        @php
}
        @endphp                            

     <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Correct Answer</label>
                                                    <div class="col-md-9">

                                                         <input type="number" name="correct_answers" id="correct_answer" 
                                            class="form-control" placeholder="Correct Answer">


                                                       </div>
                                                </div>
                                            </div>

 </div>
 </div>
                                  