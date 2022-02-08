     <label class="control-label text-right col-md-3">Total Options</label>
                                                    <div class="col-md-9">
                                    <select name="total_options" class="form-control custom-select" id="load_options_numeric">
                                              <option value="">Select Options</option>
                                            <option value="">Select Option</option>
                                            <option value="1">Equals To (=)</option>
                                            <option value="2">Greater Than (>) </option>
                                            <option value="3">Less Than (<)</option>
                                            <option value="4">Greater Than or Equals To (>=)</option>
                                            <option value="5">Less Than or Equals To (<=)</option>
                                            <option value="6">Between </option>
                                            <!--<option value="7">Between (Difference) </option>-->

                                             
                                            
                                        </select>
                                                      
                                                    </div>


                                    

 
<script type="text/javascript">

$('#load_options_numeric').change(function() {

   
       show_quiz_option_numeric_form();
       $('#total_answer_form_part').html("");
    });




function show_quiz_option_numeric_form() {

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
        data: { load_options: $('#load_options_numeric').val(), quiz_type: $('#quiz_type').val(), action: action },
        success: function(result) {
            if (result) {
                $('#quiz_option_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}

        </script>