<table  >
                            <thead>

                                   <tr>
                                    <th colspan = "{{($shopcount*3)+1}}" >{{$heading}}</th>
                                  

                                </tr>


                                <tr>
                                    <th  rowspan = "2" >Model&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
                                      @foreach ($shops as $item)
                                    <th colspan = "3">{{$item->shop_name}}</th>
                                    @endforeach
                                     
                              

                                </tr>

                                 <tr>
                                    @foreach ($shops as $item)
                                    <th style="background-color: {{$item->color_code}}" >No. of units produced</th>
                                    <th style="background-color: {{$item->color_code}}" >OK UNITS</th>
                                      <th style="background-color: {{$item->color_code}}" >SCORE %</th>
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
                                               <td style="background-color: {{$shop->color_code}}">{{$drr_arr[$vehicle->model_id][$vehicle->lot_no][$shop->id]['drr']}}</td>
                                                <td style="background-color: {{$shop->color_code}}">{{$drr_arr[$vehicle->model_id][$vehicle->lot_no][$shop->id]['score']}}</td>
                                        @endforeach
                                       
                                        
                                    </tr>
                                @endforeach
                                @endif
                             </tbody>
                           <tfoot>
                                            <tr>
                                                <th style="background-color: #8cdfea" ><strong>TOTAL</strong></th>
                                                 @foreach ($shops as $item)
                                    <th style="background-color: #8cdfea" >{{$unit_count[$item->id]['total_units']}}</th>
                                    <th style="background-color: #8cdfea" >{{$unit_count[$item->id]['total_drr']}}</th>
                                    <th  style="background-color: #8cdfea" >{{$unit_count[$item->id]['mdiscore']}}</th>
                                    @endforeach
                                            </tr>


                                              <tr >
                                                <th style="background-color: #ffd791"><strong>ACTUAL MTD SCORE</strong></th>
                                                 @foreach ($shops as $item)
                                    <th style="background-color: #ffd791" colspan="3"  >{{$unit_count[$item->id]['mdiscore']}}</th>
                                   
                                    @endforeach
                                            </tr>

                                             <tr>
                                                <th style="background-color: #b7acf6" ><strong>{{$target_name}}</strong></th>
                                                 @foreach ($shops as $item)
                                    <th style="background-color: #b7acf6" colspan="3" >{{$unit_count[$item->id]['targetscore']}}</th>
                                   
                                    @endforeach
                                            </tr>

                                            <tr >

                                             <th colspan = "{{($shopcount)+2}}" >PLANT DRR : <strong>{{$pant_drr}}</strong></th>

                                             <th colspan = "{{($shopcount)+2}}" >PLANT TARGET : <strong>{{$plant_target}}</strong></th>
                                  

                                </tr>

                                        </tfoot>



                        
                        </table>