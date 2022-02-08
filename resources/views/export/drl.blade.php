<table>
                            <thead>

                                   <tr>
                                    <th colspan = "{{($shopcount*2)+1}}" >{{$heading}}</th>
                                  

                                </tr>


                                <tr>
                                    <th rowspan = "2" >Model</th>
                                      @foreach ($shops as $item)
                                    <th colspan = "2">{{$item->shop_name}}</th>
                                    @endforeach
                                     
                              

                                </tr>

                                 <tr>
                                    @foreach ($shops as $item)
                                    <th style="background-color: {{$item->color_code}}" >Units</th>
                                    <th style="background-color: {{$item->color_code}}" >Defects</th>
                                    @endforeach
                                    
                                    
                                 
                                </tr>

                            </thead>

                            <tbody>
                                 @if(count($vehicles) > 0)
                                @foreach($vehicles as $vehicle)
                                    <tr >
                                        <td>
                                             {{$vehicle->model->model_name}} LOT {{$vehicle->lot_no}}
                                        </td>
                                      
                                        @foreach ($shops as $shop)
                                            <td style="background-color: {{$shop->color_code}}">{{$drr_arr[$vehicle->model_id][$vehicle->lot_no][$shop->id]['units']}}</td>
                                               <td style="background-color: {{$shop->color_code}}">{{$drr_arr[$vehicle->model_id][$vehicle->lot_no][$shop->id]['defects']}}</td>
                                        @endforeach
                                       
                                        
                                    </tr>
                                @endforeach
                                @endif
                             </tbody>
                           <tfoot>
                                            <tr  >
                                                <th style="background-color: #e3dffc;">TOTAL</th>
                                                 @foreach ($shops as $item)
                                    <th style="background-color: #e3dffc;" >{{$unit_count[$item->id]['total_units']}}</th>
                                    <th style="background-color: #e3dffc;" >{{$unit_count[$item->id]['total_defects']}}</th>
                                    @endforeach
                                            </tr>


                                              <tr >
                                                <th style="background-color: #fff0d5">ACTUAL MTD SCORE</th>
                                                 @foreach ($shops as $item)
                                    <th style="background-color: #fff0d5"  colspan="2" >{{$unit_count[$item->id]['mdiscore']}}</th>
                                   
                                    @endforeach
                                            </tr>

                                             <tr>
                                                <th style="background-color: #8cdfea">{{$target_name}}</th>
                                                 @foreach ($shops as $item)
                                    <th style="background-color: #8cdfea" colspan="2"  >{{$unit_count[$item->id]['targetscore']}}</th>
                                   
                                    @endforeach
                                            </tr>

                                            <tr>

                                             <th colspan = "{{($shopcount)+1}}" >PLANT DRL : {{$pant_drl}}</th>

                                             <th colspan = "{{$shopcount}}" >PLANT TARGET : {{$plant_target}}</th>
                                  
                                                  </tr>
                              
                                        </tfoot>



                        
                        </table>