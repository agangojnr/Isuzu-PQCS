<?php

namespace App\Http\Controllers\appusers;

use App\Models\appuser\Appuser;
use App\Models\shop\Shop;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Utils\MessageUtil;
use Illuminate\Support\Str;

use DB;

class AppUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     protected $messageUtil;

       public function __construct(MessageUtil $messageUtil)
    {
   
        $this->messageUtil = $messageUtil;
    }




    public function index()
    {

        if (request()->ajax()) {

             $appusers = Appuser::get();

        return DataTables::of($appusers)

           ->addColumn('action', function ($appusers) {
                return '
                    <a href="' . route('resetpassword', [encrypt_data($appusers->id)]) . '" title="Reset"  class="btn btn-xs btn-success reset-password"><i class="mdi mdi-tooltip-edit"></i> Reset </a>
                        &nbsp;
                     <a href="' . route('appusers.edit', [encrypt_data($appusers->id)]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                        &nbsp;
                       <a href="' . route('appusers.destroy', [encrypt_data($appusers->id)]) . '" class="btn btn-xs btn-danger delete_brand_button delete-user"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                 ';
            })



    

     ->make(true);
       

    }


        return view('appusers.index');
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
        return view('appusers.create')->with(compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
         $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|unique:appusers,email',
            'username' => 'bail|required|unique:appusers,username',
            'name' => 'bail|required',
            'phone_no' => 'bail|required|unique:appusers,phone_no',
            'device_token' => 'bail|required|unique:appusers,device_token',
            
            
        ]);



         // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }
   $data = $request->only(['email','device_token', 'username', 'name', 'phone_no','shop_id']);
   $data['status'] = 1;
     // Check validation success
   $password=Str::random(4);
     $data['password'] = $password;
  
    if ($validator->passes()) {

         if (request()->ajax()) {
            try {
                      
             $data = Appuser::create($data);    

             if($data){

            $token = $data->createToken('user')->accessToken;
            $data['token'] = $token;
            
            

             $message ='Your have been successfully registed to ISUZU PQCS system your username is : '.$request->username.' and password is : '.$password .'';
           $this->messageUtil->sendMessage($request->phone_no,$message);
             }       
              
                $output = ['success' => true,
                            'msg' => "User Created Successfully"
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

    public function resetpassword($id)
    {



        if (request()->ajax()) {
            try {

                $id=decrypt_data($id);

      DB::beginTransaction();

      $user = Appuser::find($id); 

         $password=Str::random(4);
        

        
        


         $message ='Your Password has been successfully reset, your username is : '.$user->username.' and password is : '.$password .'';
          $this->messageUtil->sendMessage($user->phone_no,$message);

           $user->password=$password;
           $user->save();
          DB::commit();
        $output = ['success' => true,
                                'msg' => "Password reset Successfully".$password
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


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\App_users  $app_users
     * @return \Illuminate\Http\Response
     */
    public function show(App_users $app_users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\App_users  $app_users
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=decrypt_data($id);

         $user = Appuser::find($id); 
        $shops = Shop::where('check_point', 1)
                            ->pluck('shop_name', 'id');
        return view('appusers.edit')->with(compact('shops','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\App_users  $app_users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required',
            'username' => 'bail|required',
            'name' => 'bail|required',
            'phone_no' => 'bail|required',
            'device_token' => 'bail|required',
            
        ]);



         // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }
   $data = $request->only(['email','device_token', 'username', 'name', 'phone_no','shop_id']);
   
  
    if ($validator->passes()) {

         if (request()->ajax()) {
            try {
                      


            $result = Appuser::find($id); 
            $result->update($data);
            $result->touch();
             
           $output = ['success' => true,
                            'msg' => "User Updated Successfully"
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\App_users  $app_users
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

                $id=decrypt_data($id);
  
                $items = Appuser::where('id', $id)
                           ->first();
        
            

                if ($can_be_deleted) {
                    if (!empty($items)) {
                        DB::beginTransaction();
                        $items->delete();

                        DB::commit();
                    }

                    $output = ['success' => true,
                                'msg' => "User Deleted Successfully"
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

      public function login (Request $request) {

   
          $request->validate([
            'username' => 'bail|required',
            'password' => 'bail|required|min:4',
        ]);
        $user = AppUser::where('username', $request->username)->first();
 
        if ($user && Hash::check($request->password, $user->password)) {
          
            if ($user['status'] == 0) {
                return response()->json(['msg' => 'You account is inactive contact admin for activation', 'data' => null, 'success' => false], 200);
            }
            $token = $user->createToken('user')->accessToken;
            $user['device_token'] = $request->device_token;
            $user->save();
            $user['token'] = $token;
            return response()->json(['msg' => 'Welcome back to PQCS System ', 'data' => $user, 'success' => true], 200);
        } else {
            return response()->json(['msg' => 'Username and Password not match with our record', 'data' => null, 'success' => false], 200);
        }
}



  public function password(PasswordRequest $request)
    {

        auth()->user()->update(['password' => $request->get('password')]);
        $data['token'] = auth()->user()->createToken('user')->accessToken;
        return response()->json(['msg' => "Password Change", 'data' => $data['token'], 'success' => true], 200);
    }


}
