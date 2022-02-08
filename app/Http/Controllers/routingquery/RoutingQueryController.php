<?php

namespace App\Http\Controllers\routingquery;

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

class RoutingQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$exampleSlug = Str::slug('Example of str slug');

        //dd($exampleSlug);
        if (request()->ajax()) {

             $routingquery = Routingquery::get();

        return DataTables::of($routingquery)
            ->addColumn(
                    'action',
                    '
                    <button data-href="#" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</button>
                        &nbsp;
                   
                   
                        <button data-href="#" class="btn btn-xs btn-danger delete_brand_button"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                 '
                )
               ->addColumn('shop', function ($routingquery) {
                return $routingquery->shop->shop_name;
            })
       ->addColumn('category', function ($routingquery) {
                return $routingquery->category->category_name;
           })


     ->make(true);
       

    }


     


        return view('routingquery.index');
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

   
        return view('routingquery.create')->with(compact('shops','models','querycategory'));
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

  $data['model_id'] = $this->prepareModels($request, $data); 
}



 
 if(empty($request->input('query_name'))){

    $itemcheck=false;                          
}




   //$data['can_sign'] = $request->has('can_sign') ? 1 : 0;

  if ($request->icon && $request->icon != "undefined") {
            $data['icon'] = (new AppHelper)->saveImage($request);
        }




 

    if($request->quiz_type == 'single' || $request->quiz_type == 'traceable' ) {

          $data['answers'] = ''; //$this->prepareOptions($request, $data);
          $data['total_options']      = ''; //$request->input('total_options');
          $data['correct_answers'] =  ''; // $request->input('correct_answers');
          $data['total_correct_answer'] = 0;
          

           /*$validator = Validator::make($request->all(), [
            'answer' => 'required',
            'correct_answers' => 'bail|required|max:5',
            'total_options' => 'bail|required|max:5',

        ]);*/
            
        }

        if($request->quiz_type == 'multiple' || $request->quiz_type == 'others') {

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
          $data['correct_answers']      = $request->input('correct_answers');

          

           $validator = Validator::make($request->all(), [
            'correct_answers' => 'required',
            'total_options' => 'bail|required|max:5',
            
        ]);


           if($request->input('total_options')==6){
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



    if ($exist) {

       $output = ['success' => false,
                            'msg' => "Query Code is already used",
                            ];
                            
    }


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
    if ($validator->passes() && $exist==false && $modelcheck==true && $itemcheck==true ) {

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
     * @param  \App\Models\Routing_query  $routing_query
     * @return \Illuminate\Http\Response
     */
    public function show(Routing_query $routing_query)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Routing_query  $routing_query
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


      return view('routingquery.edit')->with(compact('routing_query','shops','models','querycategory','modelid'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Routing_query  $routing_query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Routing_query $routing_query)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Routing_query  $routing_query
     * @return \Illuminate\Http\Response
     */
    public function destroy(Routing_query $routing_query)
    {
        //
    }

    public function load_options(Request $request)
    {

       // if(!empty($request->input('quiz_type'))) {
       $quiz_type= $request->input('quiz_type');

       /*if($quiz_type=='single'){
         return view('routingquery.partial.single_answer_option');

       }else */

       if($quiz_type=='multiple'){

          return view('querycategory.partial.multiple_answer_option');
       }else if($quiz_type=='numeric'){

          return view('querycategory.partial.numeric_answer_option');
       }else if($quiz_type=='others'){

       return view('querycategory.partial.others_answer_option');

       }


          

//}
         
    }


     public function load_quiz_options(Request $request)
    {

        $quiz_type= $request->input('quiz_type');
       $total_anwer= $request->input('load_options');



       /*if($quiz_type=='single'){
         return view('routingquery.partial.single_answer')->with(compact('total_anwer'));

       }else */

       if($quiz_type=='multiple'){


 return view('querycategory.partial.multiple_answer')->with(compact('total_anwer'));

       }else if($quiz_type=='numeric'){


 return view('querycategory.partial.numeric_answer')->with(compact('total_anwer'));

       }else if($quiz_type=='others'){


       return view('querycategory.partial.others_answer')->with(compact('total_anwer'));

       }


          

//}
         
    }

     public function load_total_answer(Request $request)
    {

       $quiz_type= $request->input('quiz_type');
       $total_correct_answer= $request->input('total_correct_answer');



      
         return view('routingquery.partial.total_correct_answer')->with(compact('total_correct_answer'));
         
    }



    
public function prepareOptions($request, $record)
    {

      $options    = $request->answer;
       $list       = array();
       for($index = 0; $index < $request->total_options; $index++)
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

    
}
