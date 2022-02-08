<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\routingquery\Routingquery;
use App\Models\querycategory\Querycategory;
use App\Models\defaultanswer\Defaultanswer;
use App\Models\queryanswer\Queryanswer;
use App\Models\querydefect\Querydefect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppHelper;

use DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

          public function quiz($id,$vid,$shop_id)
    {
       
         $master = array();    
        
         $master['category'] = Querycategory::find($id);
        /* $master['query']  = Routingquery::where('category_id',$id)->with(['getanswer'=>function ($q)  use ($vid)  {
        $q->where('vehicle_id', $vid);
        }])->with(['getanswercount'=>function ($q)  use ($vid)  {
        $q->where('vehicle_id', $vid);
        }])->get();*/


        $queryresults=Routingquery::where('category_id',$id)->orderBy('position', 'asc')->get();

       /* ->with(['getanswer'=>function ($q)  use ($vid)  {
        $q->where('vehicle_id', $vid);
        }])->with(['getanswercount'=>function ($q)  use ($vid)  {
        $q->where('vehicle_id', $vid);
        }])->get();*/

         $data=[];
         //check if value exist in array
         if(!empty($queryresults)){

           
           
        foreach($queryresults as $queryresult){

     $checkanswered=$exist=$this->checkanswerbyshop($queryresult->id,$vid,$shop_id);
      
    
     $haserror=$exist=$this->checkhaserrorbyshop($queryresult->id,$vid,$shop_id,'Yes');


      $answer='';
      if($checkanswered){
$answers_given=Queryanswer::where('query_id', $queryresult->id)->where('vehicle_id',$vid)->where('shop_id',$shop_id)->first();


$answer=$answers_given->answer;
     }



    if($queryresult->use_defferent_routing=='No'){

           $options= json_decode($queryresult->category->answers ,true);
           $quiz_type=$queryresult->category->quiz_type;
           $total_options=$queryresult->category->total_options;
           //$answers_given=json_decode($queryresult->category->answers,true);
           $correct_answers=$queryresult->category->correct_answers;
      
      }else{
        //use query category
        $quiz_type=$queryresult->quiz_type;
        $options= json_decode($queryresult->answers ,true);
        $total_options=$queryresult->total_options;
       // $answers_given=json_decode($queryresult->answers ,true) ;
        $correct_answers=$queryresult->correct_answers;

      }








             $data[] = [
            'id' => $queryresult->id,
            'query_name' => $queryresult->query_name,
            'quiz_type' => $quiz_type,
            'options' => $options,
            'total_options' => $total_options,
            'correct_answers' => $correct_answers,
            'checkanswered' => $checkanswered,
            'haserror' => $haserror,
            'can_sign' => $queryresult->can_sign,
            'answer_given' => $answer,
          
            

        ];


        }
}
     
        $master['query'] = $data;

        return response()->json(['msg' => null, 'data' => $master, 'success' => true], 200);
    }



       public function loadquery($cat_id,$quiz_id)
    {
       
            $master = array();
        
       
        $data = Querycategory::find($cat_id);
       $master['category']=$data;
       $master['options']= json_decode($data->answers ,true);
       $master['dafaultoptions']= Defaultanswer::get(['name']);
        

       $query= Routingquery::find($quiz_id);
       $master['routings'] = Routingquery::find($quiz_id);


       if($query->use_defferent_routing=='No'){
        //use master
          $master['options']= json_decode($data->answers ,true);
         
           $master['quiz_type']=$data->quiz_type;
           $master['total_options']=$data->total_options;
           $master['answers']=$data->answers;
           $master['correct_answers']=$data->correct_answers;

         
      }else{
        //use query category
        $master['quiz_type']=$query->quiz_type;
        $master['options']= json_decode($query->answers ,true);
        $master['total_options']=$query->total_options;
        $master['answers']=json_decode($query->answers ,true) ;
        $master['correct_answers']=$query->correct_answers;

      }


        
     


    }

     public function answerquery(Request $request)
    {



try {
    $datas = $request->all();
    $can_save=true;
    $error_message='success';


$save= 0;

if(!empty($datas)){


     $save_aarary=[];
     $answer_check=[];


     foreach($datas as $data){

        $answer_check[]=$data['answer'];

if($data['quiz_type']=='multiple' && $data['answer']=='NOK'){
    //check if defect is captured
    if(empty($data['defects'] )){

       $can_save=false;
    $error_message='Some queries marked NOK but no defect  captured!!!'; 

    }

  
    }//end defect check

if($data['quiz_type']=='others' && !empty($data['answer'])   ){
    //check if defect is captured
    if(empty($data['defects'] )){

       $can_save=false;
    $error_message='Defect Category Not  Selected!!!'; 

    }

  
    }//end others  check

   


    if($data['can_sign']=='Yes' && !empty($data['answer']) ){
    //check if defect is captured
    if(empty($data['signature'] )){

       $can_save=false;
    $error_message='Make sure you capture all signatures!!!'; 

    }

  
    }//end can sign check


    



    }// end foreach


 if(!array_filter($answer_check)){// check if all items are empty

    $can_save=false;
    $error_message='Please Mark atleast One Query!!!'; 

    }

 DB::beginTransaction();
  
if($can_save){
//save data
    foreach($datas as $datatosave){

          $exist=$this->checkanswer($datatosave['item_id'],$datatosave['vehicle_id']);

if((!empty($datatosave['answer']) || $datatosave['answer']=='undefined' || $datatosave['answer']=='null') &&  $exist==false ){


$master=array();//holdarraytosave

if($datatosave['quiz_type']=='numeric'){  //numerics
//$has_error=$datatosave['answer']; //answer given by user from input


if($datatosave['total_options']==6){  //if option 6 between lower and uper limit

$answ=$datatosave['options'];  // fetch lower and uper limit

$min=$answ[0]['min'][0];
$max=$answ[1]['max'][0];


if ($datatosave['answer'] > $min && $datatosave['answer'] < $max ){

$has_error='No';


}else{

$has_error='Yes';

}

}else if($datatosave['total_options']==1){ //answer equals to


if ($datatosave['correct_answers'] == $datatosave['answer']  ){

$has_error='No';

}else{

$has_error='Yes';

}


}else if($datatosave['total_options']==2){  // answer greater than

    if ($datatosave['answer'] > $datatosave['correct_answers']  ){

$has_error='No';

}else{

$has_error='Yes';

}


}else if($datatosave['total_options']==3){ // answer less than

     if ($datatosave['answer'] < $datatosave['correct_answers']  ){

$has_error='No';

}else{

$has_error='Yes';

}

}else if($datatosave['total_options']==4){ // answer greater than or equals to

      if ($datatosave['answer'] >= $datatosave['correct_answers'] ){

$has_error='No';

}else{

$has_error='Yes';

}

}else if($datatosave['total_options']==5){  /// answer less than or equals to
 
      if ($datatosave['answer'] <= $datatosave['correct_answers']  ){

$has_error='No';

}else{

$has_error='Yes';

}


}

 $additional_query=0;

 $answer=$datatosave['answer'];

}else if($datatosave['quiz_type']=='others'){

    $has_error='Yes';

    $additional_query=1;
    $answer='NOK';

    

}else{

      //$master['answer']=$datatosave['answer'];
      $has_error='No';
     if($datatosave['answer']=='NOK'){
      $has_error='Yes';
     }

     $additional_query=0;

     $answer=$datatosave['answer'];

}


//Common logic

$signature=$datatosave['signature'];

if($datatosave['can_sign']=='Yes'){

        if ($datatosave['signature']  && $datatosave['signature'] != "undefined") {
            $signature = (new AppHelper)->saveBase64Png($datatosave['signature']);
            
        }
     }




    $master ['done_by'] = Auth::id();
    $master ['query_id']= $datatosave['item_id'];
    $master ['job_id'] = substr(uniqid('job-'), 0, 10);
    $master ['category_id'] = $datatosave['quiz_id'];
    $master ['shop_id'] = $datatosave['shop_id'];
    $master ['vehicle_id'] = $datatosave['vehicle_id'];
    $master ['signature'] = $signature;
    $master ['has_error'] = $has_error;
    $master ['answer'] =$answer;
    $master ['additional_query'] = $additional_query;

    $record = Queryanswer::create($master);

     if($record->id){
        $save=1;

     if($datatosave['quiz_type']=='multiple' &&  $datatosave['answer']=='NOK'){

        
         $arr = $datatosave['defects'];
         $defectsave=array();
        foreach ($arr as $value) {
             $defectsave['query_anwer_id'] = $record['id'];
             $defectsave['category_id'] = $datatosave['quiz_id'];
             $defectsave['shop_id'] = $datatosave['shop_id'];
             $defectsave['vehicle_id'] = $datatosave['vehicle_id'];
             $defectsave['defect_name'] = $value['option_value'];
            $defectsave['routingquery_id'] = $datatosave['item_id'];
             $defectsave['is_defect'] = 'Yes';
             $defectsave['is_addition'] = 'No';

             Querydefect::create($defectsave);

                        
        }


    }


       if($datatosave['quiz_type']=='others'){

        
        



            $defectsave=array();
             $arr = $datatosave['defects']['option_value'];
             $defectsave['query_anwer_id'] = $record['id'];
             $defectsave['category_id'] = $datatosave['quiz_id'];
             $defectsave['shop_id'] = $datatosave['shop_id'];
             $defectsave['vehicle_id'] = $datatosave['vehicle_id'];
             $defectsave['defect_name'] =  $datatosave['answer'];
             $defectsave['routingquery_id'] = $datatosave['item_id'];
             $defectsave['is_defect'] = 'Yes';
             $defectsave['is_addition'] = 'No';
             $defectsave['defect_category'] = $arr;
            Querydefect::create($defectsave);



    

    }






        if(($record->has_error=='Yes' && $datatosave['quiz_type']=='single') || ($record->has_error=='Yes' && $datatosave['quiz_type']=='numeric')  ){

            $defectsave['query_anwer_id'] = $record['id'];
            $defectsave['category_id'] = $datatosave['quiz_id'];
            $defectsave['routingquery_id'] = $datatosave['item_id'];

            
            $defectsave['shop_id'] = $datatosave['shop_id'];
            $defectsave['vehicle_id'] = $datatosave['vehicle_id'];
            $defectsave['defect_name'] = $datatosave['answer'];
            $defectsave['is_defect'] = 'Yes';
            $defectsave['is_addition'] = 'No';

             Querydefect::create($defectsave);              

    }

}





         }//end empty check


      }//end  foreach


//commit and success message

DB::commit();
return response()->json(['msg' =>   'Record Saved Successfully', 'data' => null, 'success' => true], 200);


    

   }else{  //end can save

       return response()->json(['msg' => $error_message, 'data' => null, 'success' => false], 200);

}//end can save check  







}//end empty check
   

  

 } catch (\Exception $e) {
    DB::rollBack(); // roll back transaction if not completely saved
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

     return response()->json(['msg' => 'Something Went Wrong', 'data' => null , 'success' => false], 200);
                    
            
            }




    }




  public function checkanswer($value,$value1)
    {
        return  Queryanswer::where('query_id', $value)->where('vehicle_id',$value1)->exists();
    }

 public function checkanswerbyshop($value,$value1,$value2)
    {
        return  Queryanswer::where('query_id', $value)->where('vehicle_id',$value1)->where('shop_id',$value2)->exists();
    }

     public function checkhaserrorbyshop($value,$value1,$value2,$value3)
    {
        return  Queryanswer::where('query_id', $value)->where('vehicle_id',$value1)->where('shop_id',$value2)->where('has_error',$value3)->exists();
    }

    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
