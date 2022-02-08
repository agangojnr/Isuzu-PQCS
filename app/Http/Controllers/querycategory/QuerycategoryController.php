<?php

namespace App\Http\Controllers\querycategory;

use App\Models\Routing_query;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\unit_model\Unit_model;
use App\Models\shop\Shop;
use App\Models\querycategory\Querycategory;
use App\Models\routingquery\Routingquery;
use App\Http\Controllers\AppHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\querymodels\QueryModels;
use Symfony\Component\HttpFoundation\Response;



class QuerycategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (request()->ajax()) {

             $categories = Querycategory::get();

        return DataTables::of($categories)

           ->addColumn('action', function ($categories) {
                return '
                  <a href="' . route('querylisting', [$categories->id]) . '" class="btn btn-xs btn-warning edit_brand_button"><i class="glyphicon glyphicon-edit"></i>List</a>
                        &nbsp;
                   <a href="' . route('addrouting', [$categories->id]) . '" class="btn btn-xs btn-success edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Copy</a>
                        &nbsp;
                    <a href="' . route('querycategory.edit', [$categories->id]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                        &nbsp;
                       <a href="' . route('querycategory.destroy', [$categories->id]) . '" class="btn btn-xs btn-danger delete_brand_button delete-query"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                 ';
            })

        ->addColumn('image', function ($categories) {
           $url= asset('upload/'.$categories->icon);
            return '<img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" />';
               })
              ->addColumn('shop', function ($categories) {
                return $categories->shop->shop_name;
            })->rawColumns(['image', 'action'])

     ->make(true);
       

    }


     return view('querycategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        
         $shops = Shop::where('check_point', 1)
                            ->pluck('shop_name', 'id');
   $models = Unit_model::pluck('model_name', 'id');
    $querycategory = Querycategory::pluck('category_name', 'id');

   
        return view('querycategory.create')->with(compact('shops','models','querycategory'));
    }


 
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

   $modelcheck=true;
   $itemcheck=true;

  $data = $request->only(['shop_id', 'model_id', 'category_name', 'quiz_type','query_code','status']);
  $item_data['query']  = $request->only(['can_sign', 'query_name', 'additional_field']);


  $validator = Validator::make($request->all(), [
            'shop_id' => 'bail|required|max:20',
            'model_id' => 'bail|required|max:20',
            'category_name' => 'bail|required|max:20',
            'query_name' => 'required',
            'quiz_type' => 'required',
            'query_code' => 'required',
            
        ]);

  if(empty($request->input('model_id'))){

    $modelcheck=false;                          
}else{

  $model_id=$request->input('model_id');


  if (is_array($model_id)) {

  
       $models = array();
       foreach ($model_id as $row) {


           $models[] = array('querycategory_id' => $id, 'unit_model_id' => $row);
         
       }
   }

  $data['model_id'] = $this->prepareModels($request, $data); 
}



 
 if(empty($request->input('query_name'))){

    $itemcheck=false;                          
}




   //$data['can_sign'] = $request->has('can_sign') ? 1 : 0;

  if ($request->icon && $request->icon != "undefined") {
            $data['icon'] = (new AppHelper)->saveImage($request);
        }




 

    if($request->quiz_type == 'single') {

        $data['answers'] = $this->prepareOptions($request, $data);
          $data['total_options']      = $request->input('total_options');
         $data['correct_answers'] = $request->input('correct_answers');
         $data['total_correct_answer'] = 0;
          

           $validator = Validator::make($request->all(), [
            'answer' => 'required',
            'correct_answers' => 'bail|required|max:5',
            'total_options' => 'bail|required|max:5',

        ]);
            
        }

        if($request->quiz_type == 'multiple') {

        $data['answers'] = $this->prepareOptions($request, $data);
          $data['total_options']      = $request->input('total_options');
         $data['total_correct_answers'] = $request->input('total_correct_answers');
          $data['correct_answers']      = $this->prepareMultiAnswers($request);

           $validator = Validator::make($request->all(), [
            'answer' => 'required',
            'correct_answers' => 'bail|required|max:5',
            'total_options' => 'bail|required|max:5',
            'total_correct_answers' => 'bail|required|max:5',
        ]);
            
        }

           if($request->quiz_type == 'numeric') {
         $data['answers'] = 0;
          $data['answers'] = 0;
          $data['total_correct_answers'] = 0;
          $data['total_options']      = $request->input('total_options');
          $data['correct_answers']    = $request->input('correct_answers');

          

           $validator = Validator::make($request->all(), [
            'correct_answers' => 'required',
            'total_options' => 'bail|required|max:5',
            
        ]);


           if($request->input('total_options')==6 || $request->input('total_options')==7){
             $validator = Validator::make($request->all(), [
            'lower_limit' => 'required',
            'upper_limit' => 'required',
        ]);
       $data['answers'] = $this->prepareNumericAnswers($request, $data);
           
           }
            
        }



// Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }

//check if code  exist
    $exist=$this->passes($request->input('query_code'));



   /* if ($exist) {

       $output = ['success' => false,
                            'msg' => "Query Code is already used",
                            ];
                            
    }*/


         if (!$modelcheck) {
     $output = ['success' => false,
                            'msg' => "Model Code connot be empty",
                            ];
    }  


      if (!$itemcheck) {
     $output = ['success' => false,
                            'msg' => "You must add atleast one Query",
                            ];
    }               


    // Check validation success
    if ($validator->passes()  && $modelcheck==true && $itemcheck==true ) {

         if (request()->ajax()) {

     
            try {

              
   
              $result = Querycategory::create($data);

              if($result->id){

                   $queries = array();
                    $i = 0;

                  foreach ($item_data['query']['query_name'] as $key => $value) {

                    

                     $queries[] = array(
                      'category_id' => $result->id,
                      'can_sign' => strip_tags($item_data['query']['can_sign'][$key]),
                      'query_name' => strip_tags($item_data['query']['query_name'][$key]),
                      'additional_field' => strip_tags($item_data['query']['additional_field'][$key]));

                     $i++;

                  }




              }



              Routingquery::insert($queries);

              QueryModels::insert($models);
             
              
                $output = ['success' => true,
                            'msg' => "Query Created Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

           // return $output;
        }



      


    }

    return $output;


        

         
    }

      public function additionalquery(Request $request)
    {



      $modelcheck=true;
   $itemcheck=true;

  $data = $request->only(['shop_id', 'model_id', 'category_name', 'quiz_type','query_code','status']);
  $item_data['query']  = $request->only(['can_sign', 'query_name', 'additional_field']);


  $validator = Validator::make($request->all(), [
            'shop_id' => 'bail|required|max:20',
            'model_id' => 'bail|required|max:20',
            'category_name' => 'bail|required|max:255',
            'query_name' => 'required',
            'quiz_type' => 'required',
            'query_code' => 'required',
            
        ]);

  if(empty($request->input('model_id'))){

    $modelcheck=false;                          
}else{

  $model_id=$request->input('model_id');


  

  $data['model_id'] = $this->prepareModels($request, $data); 
}



 
 if(empty($request->input('query_name'))){

    $itemcheck=false;                          
}




   //$data['can_sign'] = $request->has('can_sign') ? 1 : 0;

  if ($request->icon && $request->icon != "undefined") {
            $data['icon'] = (new AppHelper)->saveImage($request);
        }




 

    if($request->quiz_type == 'single') {

        $data['answers'] = ''; //$this->prepareOptions($request, $data);
          $data['total_options']      = '';  //$request->input('total_options');
         $data['correct_answers'] ='';    //$request->input('correct_answers');
         $data['total_correct_answer'] = 0;
          

          /* $validator = Validator::make($request->all(), [
            'answer' => 'required',
            'correct_answers' => 'bail|required|max:5',
            'total_options' => 'bail|required|max:5',

        ]);*/
            
        }

        if($request->quiz_type == 'multiple' || $request->quiz_type == 'others' ) {

          $data['answers'] = $this->prepareOptions($request, $data);
          $data['total_options']      = $request->input('total_options');
          $data['total_correct_answers'] ='';  //$request->input('total_correct_answers');
          $data['correct_answers']      = ''; //$this->prepareMultiAnswers($request);

           $validator = Validator::make($request->all(), [
            'answer' => 'required',
           // 'correct_answers' => 'bail|required|max:5',
            //'total_options' => 'bail|required|max:20',
            //'total_correct_answers' => 'bail|required|max:5',
        ]);
            
        }

           if($request->quiz_type == 'numeric') {
         $data['answers'] = 0;
          $data['answers'] = 0;
          $data['total_correct_answers'] = 0;
          $data['total_options']      = $request->input('total_options');
          $data['correct_answers']    = $request->input('correct_answers');

          

           $validator = Validator::make($request->all(), [
            'correct_answers' => 'required',
            'total_options' => 'bail|required|max:5',
            
        ]);


           if($request->input('total_options')==6 || $request->input('total_options')==7){
             $validator = Validator::make($request->all(), [
            'lower_limit' => 'required',
            'upper_limit' => 'required',
        ]);
       $data['answers'] = $this->prepareNumericAnswers($request, $data);
           
           }
            
        }



// Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }

//check if code  exist
    $exist=$this->passes($request->input('query_code'));



   // if ($exist) {

      // $output = ['success' => false,
                         //   'msg' => "Query Code is already used",
                           // ];
                            
    //}


         if (!$modelcheck) {
     $output = ['success' => false,
                            'msg' => "Model Code connot be empty",
                            ];
    }  


      if (!$itemcheck) {
     $output = ['success' => false,
                            'msg' => "You must add atleast one Query",
                            ];
    }               


    // Check validation success
    if ($validator->passes()  && $modelcheck==true && $itemcheck==true ) {

         if (request()->ajax()) {

     
            try {

              
   
              $result = Querycategory::create($data);

              if($result->id){

                   $queries = array();
                    $i = 0;

                  foreach ($item_data['query']['query_name'] as $key => $value) {

                    

                     $queries[] = array(
                      'category_id' => $result->id,
                      'can_sign' => strip_tags($item_data['query']['can_sign'][$key]),
                      'query_name' => strip_tags($item_data['query']['query_name'][$key]),
                      'additional_field' => strip_tags($item_data['query']['additional_field'][$key]));

                     $i++;

                  }




              }

              if (is_array($model_id)) {

  
                $models = array();
                foreach ($model_id as $row) {
         
         
                    $models[] = array('querycategory_id' => $result->id, 'unit_model_id' => $row);
                  
                }
            }



              Routingquery::insert($queries);

              QueryModels::insert($models);
             
              
                $output = ['success' => true,
                            'msg' => "Query Created Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

           // return $output;
        }



      


    }

    return $output;

    }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Querycategory  $querycategory
     * @return \Illuminate\Http\Response
     */
    public function show(Querycategory $querycategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Querycategory  $querycategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     

       $shops = Shop::where('check_point', 1)->pluck('shop_name', 'id');
   $models = Unit_model::all();
    $querycategory = Querycategory::pluck('category_name', 'id');
      $routing_query = Querycategory::find($id);

            $modelid = [];

        $modelrecords= json_decode($routing_query->model_id ,true);
        
        foreach($modelrecords as $modelrecord)
        {
            $modelid[] = $modelrecord['model_value'];
        } 


$quizanswers=[];
if($routing_query->quiz_type=='single'|| $routing_query->quiz_type=='multiple' || $routing_query->quiz_type=='numeric' ){
$quizanswers= json_decode($routing_query->answers ,true);
}



$totalcorrectanswers=[];
if($routing_query->quiz_type=='multiple' ){
$totalcorrectanswers= json_decode($routing_query->correct_answers ,true);
}
        


      return view('querycategory.edit')->with(compact('routing_query','shops','models','querycategory','modelid','quizanswers','totalcorrectanswers'));
        //
    }


     public function addrouting($id)
    {

        
        
       $shops = Shop::where('check_point', 1)->pluck('shop_name', 'id');
   $models = Unit_model::all();
    $querycategory = Querycategory::pluck('category_name', 'id');
      $routing_query = Querycategory::find($id);

            $modelid = [];

        $modelrecords= json_decode($routing_query->model_id ,true);
        
        foreach($modelrecords as $modelrecord)
        {
            $modelid[] = $modelrecord['model_value'];
        } 


$quizanswers=[];
if($routing_query->quiz_type=='single'|| $routing_query->quiz_type=='multiple' || $routing_query->quiz_type=='numeric'|| $routing_query->quiz_type=='others'  ){
$quizanswers= json_decode($routing_query->answers ,true);
}



$totalcorrectanswers=[];
if($routing_query->quiz_type=='multiple' || $routing_query->quiz_type=='others' ){
$totalcorrectanswers= json_decode($routing_query->correct_answers ,true);
}
        


      return view('querycategory.add')->with(compact('routing_query','shops','models','querycategory','modelid','quizanswers','totalcorrectanswers'));
    }

     public function addquery($id)
    {

        
        
       $shops = Shop::where('check_point', 1)->pluck('shop_name', 'id');
   $models = Unit_model::all();
    $querycategory = Querycategory::pluck('category_name', 'id');
      $routing_query = Querycategory::find($id);

            $modelid = [];

        $modelrecords= json_decode($routing_query->model_id ,true);
        
        foreach($modelrecords as $modelrecord)
        {
            $modelid[] = $modelrecord['model_value'];
        } 


$quizanswers=[];
if($routing_query->quiz_type=='single'|| $routing_query->quiz_type=='multiple' || $routing_query->quiz_type=='numeric' ){
$quizanswers= json_decode($routing_query->answers ,true);
}



$totalcorrectanswers=[];
if($routing_query->quiz_type=='multiple' ){
$totalcorrectanswers= json_decode($routing_query->correct_answers ,true);
}
        


      return view('querycategory.addquery')->with(compact('routing_query','shops','models','querycategory','modelid','quizanswers','totalcorrectanswers'));
    }




        public function querysave(Request $request)
    {


  //$data = $request->only(['shop_id', 'model_id', 'category_name', 'quiz_type','query_code','status']);
  $item_data['query']  = $request->only(['can_sign', 'query_name', 'additional_field']);

  $validator = Validator::make($request->all(), [
            'query_name' => 'required',
            
        ]);





// Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }


    // Check validation success
    if ($validator->passes()) {

         if (request()->ajax()) {

     
            try {

             
                   $queries = array();
                    $i = 0;

                  foreach ($item_data['query']['query_name'] as $key => $value) {

                    

                     $queries[] = array(
                      'category_id' => $request->query_id,
                      'can_sign' => strip_tags($item_data['query']['can_sign'][$key]),
                      'query_name' => strip_tags($item_data['query']['query_name'][$key]),
                      'additional_field' => strip_tags($item_data['query']['additional_field'][$key]));

                     $i++;

                  }




             



              Routingquery::insert($queries);

            
             
              
                $output = ['success' => true,
                            'msg' => "Query Created Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

           // return $output;
        }



      


    }

    return $output;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Routing_query  $routing_query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       


  $modelcheck=true;


  $data = $request->only(['shop_id','model_id','category_name','query_code','quiz_type','status']);


  //$item_data['query']  = $request->only(['can_sign', 'query_name', 'additional_field']);


  $validator = Validator::make($request->all(), [
            'shop_id' => 'bail|required|max:250',
            'model_id' => 'bail|required|max:250',
            'category_name' => 'bail|required|max:250',
            'query_code' => 'required',
             'quiz_type' => 'required',
            
        ]);


   if(($request->quiz_type == 'multiple' &&  empty($request->input('model_id'))) || ($request->quiz_type == 'others' &&  empty($request->input('model_id')))    ){

    $modelcheck=false;                          
}else{

         $model_id=$request->input('model_id');


           if (is_array($model_id)) {

           
                $models = array();
                foreach ($model_id as $row) {


                    $models[] = array('querycategory_id' => $id, 'unit_model_id' => $row);
                  
                }
            }

  $data['model_id'] = $this->prepareModels($request, $data); 
}









  if ($request->icon && $request->icon != "undefined") {
            $data['icon'] = (new AppHelper)->saveImage($request);
        }



    if($request->quiz_type == 'single') {

        $data['answers'] = ''; //$this->prepareOptions($request, $data);
          $data['total_options']      = '';  //$request->input('total_options');
         $data['correct_answers'] ='';    //$request->input('correct_answers');
         $data['total_correct_answer'] = 0;
          

            
        }

        if($request->quiz_type == 'multiple'  ) {
 

          $data['answers'] = $this->prepareOptions($request, $data);
          $data['total_options']      = $request->input('total_options');
          $data['total_correct_answers'] ='';  //$request->input('total_correct_answers');
          $data['correct_answers']      = ''; //$this->prepareMultiAnswers($request);

           $validator = Validator::make($request->all(), [
            'answer' => 'required',
          'total_options' => 'bail|required|max:5',
        
        ]);
            
        }


 

           if($request->quiz_type == 'numeric') {
         $data['answers'] = 0;
          $data['answers'] = 0;
          $data['total_correct_answers'] = 0;
          $data['total_options']      = $request->input('total_options');
          $data['correct_answers']    = $request->input('correct_answers');

          

           $validator = Validator::make($request->all(), [
            'correct_answers' => 'required',
            'total_options' => 'bail|required|max:5',
            
        ]);


           if($request->input('total_options')==6 || $request->input('total_options')==7){
             $validator = Validator::make($request->all(), [
            'lower_limit' => 'required',
            'upper_limit' => 'required',
        ]);
       $data['answers'] = $this->prepareNumericAnswers($request, $data);
           
           }
            
        }



// Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }




         if (!$modelcheck) {
     $output = ['success' => false,
                            'msg' => "Model Code connot be empty",
                            ];
    }  


             


    // Check validation success
    if ($validator->passes()  && $modelcheck==true ) {

         if (request()->ajax()) {

     
            try {


              

             $result = Querycategory::find($id); 
             $result->update($data);
            $result->touch();

            QueryModels::where('querycategory_id', $id)->delete();

          QueryModels::insert($models);

            
           $output = ['success' => true,
                            'msg' => "Query Created Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

           // return $output;
        }



      


    }

    return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Routing_query  $routing_query
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       

        if (request()->ajax()) {
            try {
               

                $can_be_deleted = true;
                $error_msg = '';



                //Check if any routing has been done
               //do logic here

                $items = Querycategory::where('id', $id)
                           ->first();
        
            

                if ($can_be_deleted) {
                    if (!empty($items)) {
                        DB::beginTransaction();
                        //Delete Query  details
                        Routingquery::where('category_id', $id)
                                                ->delete();
                        $items->delete();

                        DB::commit();
                    }

                    $output = ['success' => true,
                                'msg' => "Query Deleted Successfully"
                            ];
                } else {
                    $output = ['success' => false,
                                'msg' => $error_msg
                            ];
                }
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => false,
                                'msg' => "Something Went Wrong"
                            ];
            }

            return $output;
        }
    }

    public function load_options(Request $request)
    {

       // if(!empty($request->input('quiz_type'))) {
       $quiz_type= $request->input('quiz_type');

       if($quiz_type=='single'){
         return view('querycategory.partial.single_answer_option');

       }else if($quiz_type=='multiple'){

          return view('querycategory.partial.multiple_answer_option');
       }else if($quiz_type=='numeric'){

          return view('querycategory.partial.numeric_answer_option');
       }


          

//}
         
    }


     public function load_quiz_options(Request $request)
    {

        $quiz_type= $request->input('quiz_type');
       $total_anwer= $request->input('load_options');



       if($quiz_type=='single'){
         return view('querycategory.partial.single_answer')->with(compact('total_anwer'));

       }else if($quiz_type=='multiple'){


 return view('querycategory.partial.multiple_answer')->with(compact('total_anwer'));

       }else if($quiz_type=='numeric'){


 return view('querycategory.partial.numeric_answer')->with(compact('total_anwer'));

       }


          

//}
         
    }

     public function load_total_answer(Request $request)
    {

       $quiz_type= $request->input('quiz_type');
       $total_correct_answer= $request->input('total_correct_answer');



      
         return view('querycategory.partial.total_correct_answer')->with(compact('total_correct_answer'));
         
    }



    
public function prepareOptions($request, $record)
    {

      $options    = $request->answer;
       $list       = array();
       for($index = 0; $index < count($request->answer); $index++)
        {
         $spl_char   = ['\t','\n','\b','\c','\r','\'','\\','\$','\"',"'"];
        $list[$index]['option_value']   = str_replace($spl_char,'',$options[$index]);
    

    
            
        }

        return json_encode($list);
    }


    public function prepareNumericAnswers($request, $record)
    {  

       
      $lower_limit    = $request->lower_limit;
      $upper_limit  = $request->upper_limit;
       $list       = array();
      

        $spl_char   = ['\t','\n','\b','\c','\r','\'','\\','\$','\"',"'"];
       $list[]['min']   = str_replace($spl_char,'',$lower_limit);
        $list[]['max']   = str_replace($spl_char,'',$upper_limit);
            

        return json_encode($list);
    }


    public function prepareModels($request, $record)
    {

      $options    = $request->model_id;
       $list       = array();
       foreach ($options as $key => $value) {
         $spl_char   = ['\t','\n','\b','\c','\r','\'','\\','\$','\"',"'"];
        $list[$key]['model_value']   = str_replace($spl_char,'',$options[$key]);
    

    
            
        }

        return json_encode($list);
    }


        public function prepareMultiAnswers($request)
    {   
        $correct_answers = $request->correct_answers;
        
        $list = array();

        for($index = 0; $index < $request->total_correct_answers; $index++)
        {
            $list[$index]['answer']   = $correct_answers[$index];
        }

        return json_encode($list);
    }



    public function passes($value)
    {
        return  Querycategory::where('query_code', $value)->exists();
    }


      public function checkquery($value)
    {
        return  Routingquery::where('category_id', $value)->where('use_defferent_routing','Yes')->exists();
    }


    public function querylisting($id)
    {
        $data=  Routingquery::where('category_id', $id)->get();

        return view('querycategory.list')->with(compact('data','id'));
    }

     public function loadlisting(Request $request)
    {
         if (request()->ajax()) {

          $id = $request->quiz_id;

             $categories = Routingquery::where('category_id', $id)->get();

        return DataTables::of($categories)

           ->addColumn('action', function ($categories) {
                return '
                <button data-href="' . route('changeanswer', [$categories->id]) . '" title="Change"  class="btn btn-xs btn-primary edit_unit_button"><i class="mdi mdi-tooltip-edit"></i> Change </button>
                   &nbsp;
                    <button data-href="' . route('editrouting', [$categories->id]) . '" title="Edit"  class="btn btn-xs btn-success edit_unit_button"><i class="mdi mdi-tooltip-edit"></i> Edit </button>
                   &nbsp;
                   <a href="' . route('deletequeryoption', [$categories->id]) . '" title="Delete"  class="btn btn-xs btn-danger delete-query "><i class="mdi mdi-delete"></i> Delete </a>';
            })

           

        ->addColumn('image', function ($categories) {
           $url= asset('upload/'.$categories->icon);
            return '<img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" />';
               })->rawColumns(['action'])

     ->make(true);
       

    }

        //return view('querycategory.list')->with(compact('data','id'));
    }


      public function changeanswer($id)
    {
        

        if (request()->ajax()) {
          

            return view('querycategory.changeanswer')->with(compact('id'));
        }
    }

       public function editrouting($id)
    {
        

        if (request()->ajax()) {


          $data=  Routingquery::find($id);

            return view('querycategory.editrouting')->with(compact('id','data'));
        }
    }


    


      public function saveanswer(Request $request)
    {
        

       
   $modelcheck=true;
   $itemcheck=true;

  $data = $request->only(['quiz_type']);
    $validator = Validator::make($request->all(), [
          'quiz_type' => 'required',
         
            
        ]);



    if($request->quiz_type == 'single') {

        $data['answers'] = ''; //$this->prepareOptions($request, $data);
          $data['total_options']      =''; //$request->input('total_options');
         $data['correct_answers'] = '';//$request->input('correct_answers');
         $data['total_correct_answer'] = 0;
          

           /*$validator = Validator::make($request->all(), [
            'answer' => 'required',
            'correct_answers' => 'bail|required|max:5',
            'total_options' => 'bail|required|max:5',

        ]);*/
            
        }
 
        if($request->quiz_type == 'multiple' || $request->quiz_type == 'others' ) {

        $data['answers'] = $this->prepareOptions($request, $data);
          $data['total_options']      = $request->input('total_options');
         $data['total_correct_answers'] = ''; //$request->input('total_correct_answers');
          $data['correct_answers']      = ''; //$this->prepareMultiAnswers($request);

           $validator = Validator::make($request->all(), [
            'answer' => 'required',
            //'correct_answers' => 'bail|required|max:5',
            'total_options' => 'bail|required|max:5',
            //'total_correct_answers' => 'bail|required|max:5',
        ]);
            
        }

           if($request->quiz_type == 'numeric') {
         $data['answers'] = 0;
          $data['answers'] = 0;
          $data['total_correct_answers'] = 0;
          $data['total_options']      = $request->input('total_options');
          $data['correct_answers']    = $request->input('correct_answers');

          

           $validator = Validator::make($request->all(), [
            'correct_answers' => 'required',
            'total_options' => 'bail|required|max:5',
            
        ]);


           if($request->input('total_options')==6 || $request->input('total_options')==7){
             $validator = Validator::make($request->all(), [
            'lower_limit' => 'required',
            'upper_limit' => 'required',
        ]);
       $data['answers'] = $this->prepareNumericAnswers($request, $data);
           
           }
            
        }



// Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }

               


    // Check validation success
    if ($validator->passes()  ) {

         if (request()->ajax()) {

     
            try {

             $id= $request->input('quiz_id');
              $data['use_defferent_routing']="Yes";
             

             $result = Routingquery::find($id); 
             $result->update($data);
             $result->touch();
   
              
                $output = ['success' => true,
                            'msg' => "Routing Query Answer Updated Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

           
        }



      


    }

    return $output;
    }


      public function updatequeryoption(Request $request)
    {

         if (request()->ajax()) {

     
            try {

              $id= $request->input('quiz_id');
              $data = $request->only(['query_name','can_sign','additional_field']);
             
             

             $result = Routingquery::find($id); 
             $result->update($data);
             $result->touch();
   
              
                $output = ['success' => true,
                            'msg' => "Routing Query Answer Updated Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

           
        }

            return $output;

    }


        public function deletequeryoption($id)
    {
       

        if (request()->ajax()) {
            try {
               

                   
                        DB::beginTransaction();
                        //Delete Query  details
                        Routingquery::find($id)
                                     ->delete();
                         DB::commit();
                    

                    $output = ['success' => true,
                                'msg' => "Query Deleted Successfully"
                            ];
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => false,
                                'msg' => "Something Went Wrong"
                            ];
            }

            return $output;
        }
    }



public function querybymodel()
    {
         if (request()->ajax()) {

             $model = Unit_model::get();

        return DataTables::of($model)

           ->addColumn('action', function ($model) {
                return '
                  <a href="' . route('viewquerybymodel', [encrypt_data($model->id)]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="mdi mdi-tooltip-edit"></i></i>View</a>
                  
                 ';
            })

      
            

     ->make(true);
       

    }


     return view('querycategory.querybymodel');
    }

    public function viewquerybymodel($id)
    {



      $id=decrypt_data($id);
          $model = Unit_model::find($id);
        

         

          $shops = Shop::with(['querycategory.query_items','querycategory'=> function ($query) use( $id) {
     $query->whereJsonContains('model_id',  ['model_value' => ''.$id.'']);
 }])->get();

  
         

      return view('querycategory.viewquerybymodel')->with(compact('shops','model'));

     
    }

    public function sortroutingquery(){

      $querycategories = Querycategory::with('queries')->orderBy('position', 'asc')->get();

      return view('querycategory.sortroutings')->with(compact('querycategories'));;


  }

  public function reorder(Request $request){

    $request->validate([
      'ids'         => 'required|array',
      'ids.*'       => 'integer',
      'category_id' => 'required|integer|exists:querycategories,id',
  ]);

  foreach ($request->ids as $index => $id) {
      DB::table('routingqueries')
          ->where('id', $id)
          ->update([
              'position' => $index + 1
          ]);
  }

  $positions = Querycategory::find($request->category_id)
      ->queries()
      ->pluck('position', 'id');

  return response(compact('positions'), Response::HTTP_OK);


}


public function categoryreorder(Request $request){

  $request->validate([
    'ids'   => 'required|array',
    'ids.*' => 'integer',
]);

foreach ($request->ids as $index => $id) {
    DB::table('querycategories')
        ->where('id', $id)
        ->update([
            'position' => $index + 1
        ]);
}

return response(null, Response::HTTP_NO_CONTENT);


}







  

  
  


    
    

    

    
}
