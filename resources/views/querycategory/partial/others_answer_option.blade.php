   <label class="control-label text-right col-md-3">Total Options</label>
                                                    <div class="col-md-9">
                                    <select name="total_options" class="form-control custom-select" id="load_options_others">
                                              <option value="">Select Number Of Options</option>
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
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                             <option value="17">17</option>
                                             <option value="18">18</option>
                                            <option value="19">19</option>
                                             <option value="20">20</option>

                                             
                                            
                                        </select>
                                                      
                                                    </div>



                                        
                                    

 
<script type="text/javascript">

$('#load_options_others').change(function() {

   
       show_quiz_option_multiple_form();
       $('#total_answer_form_part').html("");
    });




function show_quiz_option_multiple_form() {

    //Disable Stock management & Woocommmerce sync if type combo
    /*if($('#type').val() == 'combo'){
        $('#enable_stock').iCheck('uncheck');
        $('input[name="woocommerce_disable_sync"]').iCheck('check');
    }*/
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
    var action = 2;
    var product_id = 1;
    $.ajax({
        method: 'POST',
        url: '{{ route("load_quiz_options") }}',
        dataType: 'html',
        data: { load_options: $('#load_options_others').val(), quiz_type: $('#quiz_type').val(), action: action },
        success: function(result) {
            if (result) {
                $('#quiz_option_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}

        </script>