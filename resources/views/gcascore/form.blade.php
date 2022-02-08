



                    <div class="form-group row">
                        <label for="date" class="col-sm-6 text-right control-label col-form-label">Section CV/LCV:</label>
                        <div class="col-sm-6">
                            <select name="lcvcv" class="form-control select2" id="cvlcv" style="width: 100%;">
                                <option value="">Choose Section...</option>
                                <option value="lcv">LCV Section</option>
                                <option value="cv">CV Section</option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="ename" class="col-sm-6 text-right control-label col-form-label">Entry Date</label>
                        <div class="col-sm-6">
                            <div class='input-group'>
                                <input type='text' name="mdate" class="form-control singledate" />

                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ti-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="date" class="col-sm-6 text-right control-label col-form-label">Sample Size</label>
                        <div class="col-sm-6">
                            <select name="samplesize" class="form-control select2" id="samplesize" style="width: 100%;">
                                <option value="">Choose sample size...</option>
                                <option value="1">One (1) Unit audited</option>
                                <option value="2">Two (2) Units audited</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pname" class="col-sm-6 text-right control-label col-form-label">Total No. of Defects</label>
                        <div class="col-sm-3">
                            {{ Form::text('ttdefectsc1', null, ['class' => 'form-control', 'placeholder' => 'Defects in Car 1...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                        <div class="col-sm-3">
                            {{ Form::text('ttdefectsc2', null, ['class' => 'form-control car2', 'placeholder' => 'Defects in Car 2...','required'=>'required','autocomplete'=>'off']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pname" class="col-sm-6 text-right control-label col-form-label">Cummulative WDPV </label>
                        <div class="col-sm-3">
                            {{ Form::text('mtdwdpv', null, ['class' => 'form-control', 'placeholder' => 'MTD WDPV score...','required'=>'required','autocomplete'=>'off']) }}
                        </div>

                    </div>










