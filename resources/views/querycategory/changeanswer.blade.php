 <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            {{ Form::open(['route' => 'saveanswer', 'class' => 'form-horizontal','files' => true, 'role' => 'form', 'method' => 'post', 'id' => 'change_answer_form'])}}



                                     {{ Form::hidden('quiz_id', $id) }}
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="fullWidthModalLabel">Change Query Answer</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">

    <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Question Type</label>
                                                    <div class="col-md-9">
                                                         <select name="quiz_type" class="form-control custom-select" id="quiz_type">
                                            <option value="">Select Query Type</option>
                                            <option value="single">Single Answer</option>
                                            <option value="multiple">Multiple Answer</option>
                                            <option value="numeric">Numerical Answer</option>
                                            <option value="traceable">Traceable Answer</option>
                                            <option value="others">Others</option>
                                             
                                            
                                        </select>
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">

                                    <div class="form-group row" id="quiz_form_part">

                                     </div>



                                                
                                            </div>
                                            <!--/span-->
                                        </div>


                                    <div class="row" id="quiz_option_form_part">  


                                         </div>


                                         <div class="row" id="total_answer_form_part">  


</div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>

                                             {!! Form::close() !!}
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->



                                    <script type="text/javascript">
   $('#quiz_type').change(function() {
        show_product_type_form();
        $('#quiz_option_form_part').html("");
         $('#total_answer_form_part').html("");
    });

   function show_product_type_form() {

  
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
    var action = 1;
    var product_id = 1;
    $.ajax({
        method: 'POST',
        url: '{{ route("load_options") }}',
        dataType: 'html',
        data: { quiz_type: $('#quiz_type').val(), product_id: product_id, action: action },
        success: function(result) {
            if (result) {
                $('#quiz_form_part').html(result);
                //toggle_dsp_input();
            }
        },
    });
}

                                        </script>