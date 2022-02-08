  <hr class="mt-0 mb-2">
   <div class="card-body bg-light">
    <div class="row">

        @php
for($i=1;$i<=$total_anwer;$i++){
  @endphp

  <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Options {{$i}}</label>
                                                    <div class="col-md-9">

                                                         <input type="text" name="answer[]" id="option-{{$i}}" 
                                            class="form-control" autocomplete="off" placeholder="Options {{$i}}">


                                                       </div>
                                                </div>
                                            </div>

                                   
        @php
}
        @endphp                            
 <!-- <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Correct Answers</label>
                                                    <div class="col-md-9">

                                                        <select class="form-control" name="total_correct_answers" id="total_correct_answer">
                                           <option value="">Select Options</option>
                                            <option value="1">1 </option>
                                            <option value="2">2 </option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5 </option>
                                            <option value="6">6 </option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9 </option>
                                            <option value="10">10 </option>
                                           
                                            
                                        </select>




                                                        


                                                       </div>
                                                </div>
                                            </div>-->



 
 </div>
 </div>


 
<script type="text/javascript">

$('#total_correct_answer').change(function() {

   
       show_quiz_total_answer_form();
    });




function show_quiz_total_answer_form() {

    
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
    var action = 2;
    var product_id = 1;
    $.ajax({
        method: 'POST',
        url: '{{ route("load_total_answer") }}',
        dataType: 'html',
        data: { total_correct_answer: $('#total_correct_answer').val(), quiz_type: $('#quiz_type').val(), action: action },
        success: function(result) {
            if (result) {
                $('#total_answer_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}

        </script>
 
                                  