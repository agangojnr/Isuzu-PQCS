 <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            {{ Form::open(['route' => 'saveunitmovement', 'class' => 'form-horizontal','files' => true, 'role' => 'form', 'method' => 'post', 'id' => 'change_answer_form'])}}



                                     {{ Form::hidden('movement_id', $id) }}
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="fullWidthModalLabel">Move Unit</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">

    <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Select Shop</label>
                                                    <div class="col-md-9">

                                                         {!! Form::select('shop_id', $shops, null, ['placeholder' => 'Select Shop', 'class' => 'form-control custom-select']); !!}



                                          
                                                      
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
                                                <button type="submit" class="btn btn-primary">Complete Action</button>
                                            </div>

                                             {!! Form::close() !!}
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->


