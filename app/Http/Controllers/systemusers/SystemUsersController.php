<?php

namespace App\Http\Controllers\systemusers;

use App\Models\system_user\System_users;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\shop\Shop;
use App\Mail\RegisterMail;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Utils\MessageUtil;

use Illuminate\Support\Str;

class SystemUsersController extends Controller
{


      protected $messageUtil;

      /* public function __construct(MessageUtil $messageUtil)
    {
        $this->messageUtil = $messageUtil;

        $this->middleware('permission:sysuser-list|sysuser-create|sysuser-edit|sysuser-delete', ['only' => ['index','store']]);
         $this->middleware('permission:sysuser-create', ['only' => ['create','store']]);
         $this->middleware('permission:sysuser-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:sysuser-reset', ['only' => ['resetpasswordsu']]);
         $this->middleware('permission:sysuser-activate', ['only' => ['destroy']]);
         $this->middleware('permission:sysuser-delete', ['only' => ['deleteUser']]);
    }*/





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = array(
            'users' => User::All(),
        );

        return view('systemusers.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $roles = Role::pluck('name','name')->all();
        return view('systemusers.create')->with(compact('roles'));

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
            'email' => 'bail|required|email|unique:users,email',
            'name' => 'bail|required',
            'phone_no' => 'bail|required|unique:users,phone_no',

        ]);

    // Check validation failure
    if ($validator->fails()) {
            Toastr::error('Sorry! Email, Username and Phone No should be unique.');
            return redirect('/systemusers/create');
        }

        $data = $request->only(['email', 'username', 'name', 'phone_no']);
        // Check validation success
        $password=Str::random(4);
        $data['password'] = $password;
        $data['passw'] = $password;

        try{
            DB::beginTransaction();

            $data = User::create($data);
            //return "Yessww";
            $data->assignRole($request->input('role_id'));

            //$message ='Your have been successfully registed to ISUZU PQCS system your username is : '.$request->email.' and password is : '.$password .'';
           //$this->messageUtil->sendMessage($request->phone_no,$message);
           DB::commit();
            Toastr::success('User Created Successfully!', 'Success');
            return redirect('/systemusers');
        }
        catch(\Exception $e){
            DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            /*$output = ['success' => false,
            'msg' => $e->getMessage(),
            ];*/
            Toastr::error($e->getMessage());
            return redirect('/systemusers/create');
        }

        //return $output;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\System_users  $system_users
     * @return \Illuminate\Http\Response
     */
    public function show(System_users $system_users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\System_users  $system_users
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            $user = User::find($id);
            $userRole = $user->roles->pluck('name','name')->all();
            $roles = Role::pluck('name','name')->all();

        $data = array(
            'userRole' => $userRole,
            'roles' => $roles,
            'user' => $user,
        );

        return view('systemusers.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\System_users  $system_users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //return $request->all();
        $validator = Validator::make($request->all(), [
            //'username' => 'bail|required|unique:users,username',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone_no' => 'bail|required|unique:users,phone_no,'.$id,
            'role_id' => 'required'

        ]);
        $superadmin = $request->input('role_id');

        if ($validator->fails()) {
                Toastr::error('Sorry! Email, Username and Phone No should be unique.');
                return redirect('/systemusers');
            }

        try{
            DB::beginTransaction();
                $user = User::find($id);
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->phone_no = $request->input('phone_no');

                $user->save();

                if($superadmin != 'superadmin'){
                    DB::table('model_has_roles')->where('model_id',$id)->delete();
                    $user->assignRole($request->input('role_id'));
                }

            DB::commit();

            Toastr::success('Role Updated Successfully!', 'Success');
            return redirect('/systemusers');
        }
        catch(\Exception $e){
            DB::Rollback();

            Toastr::error('Oops! An error occured, User not created.');
            return redirect('/systemusers');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\System_users  $system_users
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
               $status = User::where('id', $id)->value('status');
                ($status=='Active') ? $newstatus = 'Inactive' : $newstatus = 'Active';

                if ($can_be_deleted) {
                    if (!empty($newstatus)) {
                        DB::beginTransaction();
                        //Delete Query  details
                        $s_user = User::Find($id);
                        $s_user->status = $newstatus;
                        $s_user->save();

                        DB::commit();

                        $output = ['success' => true,
                                'msg' => "User's Status Changed Successfully"
                            ];
                    }else{
                        $output = ['success' => false,
                                'msg' => "Could not be change User's status."
                            ];
                    }


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

    public function resetpasswordsu($id)
    {

        if (request()->ajax()) {
            try {
      DB::beginTransaction();
      $user = User::find($id);
         $password=Str::random(4);
         $passw = $password;

         $message ='Your have been successfully registed to ISUZU PQCS system your username is : '.$user->email.' and password is : '.$password .'';
           $result=$this->messageUtil->sendMessage($user->phone_no,$message);

		    if ($result->status) {

		 $user->password = $password;
           $user->passw = $passw;
           $user->save();

			  DB::commit();
            $output = ['success' => true,
                    'msg' => "Password reset Successfully"
                ];

		}else{

            $output = ['success' => false,
                                'msg' => "Somethin went Wrong",//"Something Went Wrong"
                            ];
        }





            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                                'msg' => $e->getMessage(),//"Something Went Wrong"
                            ];
            }

            return $output;
        }

    }


    public function deleteUser($id)
    {
        if (request()->ajax()) {
            try {
                DB::beginTransaction();
                $user = User::find($id);

                $user->delete();

                DB::commit();
            $output = ['success' => true,
                    'msg' => "User deleted Successfully"
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


    public function assignshop(){
        $data = array(
            'users' => User::All(),
            'shops' => Shop::where('overtime', '=', 1)->get(['id','report_name']),
        );
        return view('systemusers.assignshop')->with($data);
    }

    public function assignsection(Request $request){ //return $request->all();
         $validator = Validator::make($request->all(), [
            'shopid' => 'required',
        ]);

        if ($validator->fails()) {
                Toastr::error('Sorry! You have not choosen a section.');
                return back();
            }

        try{
            DB::beginTransaction();
                $id = $request->input('userid');
                $user = User::find($id);
                $user->section = $request->input('shopid');

                $user->save();

            DB::commit();

            Toastr::success('Section assigned Successfully!', 'Success');
            return back();
        }
        catch(\Exception $e){
            DB::Rollback();

            Toastr::error('Oops! An error occured, User not created.');
            return back();
        }
    }

}
