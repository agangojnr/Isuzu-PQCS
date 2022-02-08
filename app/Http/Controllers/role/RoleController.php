<?php

namespace App\Http\Controllers\role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use Brian2694\Toastr\Facades\Toastr;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }


    public function index()
    { //return User::with('permissions')->get();
        $superadmin = User::where('id','=',Auth()->User()->id)->value('superAdmin');
        $data = array(
            'roles' => Role::All(),
            'i' => 0, 'superadmin'=>$superadmin,
        );

        return view('role.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Permission::create(['name' => 'attendance-mark']);
        //Permission::create(['name' => 'attendance-preview']);

        //Permission::create(['name' => 'role-list']);
        //Permission::create(['name' => 'role-create']);
        //Permission::create(['name' => 'role-edit']);
        //Permission::create(['name' => 'role-delete']);

        //Permission::create(['name' => 'appuser-list']);
        //Permission::create(['name' => 'appuser-create']);
        //Permission::create(['name' => 'appuser-edit']);
        //Permission::create(['name' => 'appuser-reset']);
        //Permission::create(['name' => 'appuser-delete']);

        //Permission::create(['name' => 'sysuser-list']);
        //Permission::create(['name' => 'sysuser-create']);
        //Permission::create(['name' => 'sysuser-edit']);
        //Permission::create(['name' => 'sysuser-reset']);
        //Permission::create(['name' => 'sysuser-activate']);
        //Permission::create(['name' => 'sysuser-delete']);

        //Permission::create(['name' => 'schedule-list']);
        //Permission::create(['name' => 'schedule-create']);
        //Permission::create(['name' => 'schedule-import']);
        //Permission::create(['name' => 'schedule-edit']);
        //Permission::create(['name' => 'schedule-delete']);
        //Permission::create(['name' => 'schedule-print']);

        //Permission::create(['name' => 'routing-create']);
        //Permission::create(['name' => 'routing-list']);
        //Permission::create(['name' => 'routing-edit']);
        //Permission::create(['name' => 'routing-import']);
        //Permission::create(['name' => 'routing-delete']);
        //Permission::create(['name' => 'routing-bymodel']);

        //Permission::create(['name' => 'qualityrpt-position']);
        //Permission::create(['name' => 'qualityrpt-marked']);
        //Permission::create(['name' => 'defect-list']);
        //Permission::create(['name' => 'drr-list']);

        //Permission::create(['name' => 'drltgt-list']);
        //Permission::create(['name' => 'drltgt-create']);
        //Permission::create(['name' => 'drltgt-edit']);
        //Permission::create(['name' => 'drltgt-delete']);
		//Permission::create(['name' => 'gcatgt-create']);

        //Permission::create(['name' => 'drrtgt-list']);
        //Permission::create(['name' => 'drrtgt-create']);
        //Permission::create(['name' => 'drrtgt-edit']);
        //Permission::create(['name' => 'drrtgt-delete']);

        //Permission::create(['name' => 'overtime-mark']);
        //Permission::create(['name' => 'overtime-preview']);
        //Permission::create(['name' => 'overtime-report']);
        //Permission::create(['name' => 'auth-hrs']);

        //Permission::create(['name' => 'Effny-dashboard']);
        //Permission::create(['name' => 'people-report']);
        //Permission::create(['name' => 'people-summary']);
		//Permission::create(['name' => 'attend-register']); Replaced
        //Permission::create(['name' => 'shop-attendance']);
		
        //Permission::create(['name' => 'performance-tack']);
		//Permission::create(['name' => 'buffer-status']);
		//Permission::create(['name' => 'actual-production']);
		//Permission::create(['name' => 'assign-shop']);

        //Permission::create(['name' => 'set-default']);
        //Permission::create(['name' => 'manage-target']);
        //Permission::create(['name' => 'view-target']);
        //Permission::create(['name' => 'set-stdhrs']);

        //Permission::create(['name' => 'hc-list']);
        //Permission::create(['name' => 'hc-create']);
        //Permission::create(['name' => 'hc-edit']);
        //Permission::create(['name' => 'hc-activate']);
        //Permission::create(['name' => 'hc-delete']);
        //Permission::create(['name' => 'hc-import']);
        //Permission::create(['name' => 'hc-summary']);

        //Permission::create(['name' => 'drl-report']);
        //Permission::create(['name' => 'drr-report']);
		//Permission::create(['name' => 'gca-score']);
		//Permission::create(['name' => 'mangca-target']);
		
        //Permission::create(['name' => 'prod-sche']);
		//Permission::create(['name' => 'fcw-sche']);
        //Permission::create(['name' => 'hist-sche']);
		
		//Permission::create(['name' => 'reroute-create']);
        //Permission::create(['name' => 'reroute-list']);

        //Permission::create(['name' => 'model-create']);
        //Permission::create(['name' => 'model-list']);
        //Permission::create(['name' => 'model-edit']);
        //Permission::create(['name' => 'model-delete']);

        //Permission::create(['name' => 'shops-list']);
        //Permission::create(['name' => 'route-list']);
        //Permission::create(['name' => 'route-mapping']);
        //Permission::create(['name' => 'unit-category']);
		//Permission::create(['name' => 'shop-section']);

        //Permission::create(['name' => 'direct-manpower']);
        //Permission::create(['name' => 'stdhrs-generated']);
        //Permission::create(['name' => 'stdActual-hours']);
        //Permission::create(['name' => 'plant-register']);
        //Permission::create(['name' => 'target-report']);
		
		//Permission::create(['name' => 'swap-create']);
		//Permission::create(['name' => 'swap-list']);
		//Permission::create(['name' => 'swap-reset']);
		
		//Permission::create(['name' => 'delayed-units']);

         return view('role.create');
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
        //return $request->input('name');

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',

        ]);
       //return $request->input('permissions');

        if ($validator->fails()) {
                Toastr::error('Sorry! Role name should be unique and permissions are required.');
                return redirect('/roles/create');
            }
        try{
            DB::beginTransaction();
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permissions'));
            DB::commit();

            Toastr::success('Role Created Successfully!', 'Success');
            return redirect('/roles');
        }
        catch(\Exception $e){
            DB::Rollback();

            Toastr::error('Oops! An error occured, User not created.');
            return redirect('/roles/create');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $rolename = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
            if(count($rolePermissions)){
                foreach($rolePermissions as $perm){
                    $perms[] = $perm ->name;
                }
            }else{
                $perms = [];
            }

        $data = array(
            'perms' => $perms,
            'role' => $rolename,
        );
        return view('role.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rolename = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        if(count($rolePermissions)){
            foreach($rolePermissions as $perm){
                $perms[] = $perm ->name;
            }
        }else{
            $perms = [];
        }


        $data = array(
            'perms' => $perms,
            'role' => $rolename,
        );
        return view('role.edit')->with($data);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required',

        ]);

        if ($validator->fails()) {
                Toastr::error('Sorry! Role name and permissions are required.');
                return back();
            }

        try{
            DB::beginTransaction();
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();

            $role->syncPermissions($request->input('permissions'));
            DB::commit();

            Toastr::success('Role Updated Successfully!', 'Success');
            return redirect('/roles');
        }
        catch(\Exception $e){
            DB::Rollback();

            Toastr::error('Oops! An error occured, role not Updated.');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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
               $role = true; //Role::where('id', $id)->first();

                if ($can_be_deleted) {
                    if ($role) {
                        DB::beginTransaction();
                        //Delete Query  details
                        Role::where('id', $id)->delete();
                        DB::table("role_has_permissions")->where('role_id',$id)->delete();
                        //$role->delete();

                        DB::commit();

                        $output = ['success' => true,
                                'msg' => "Role Deleted Successfully"
                            ];
                    }else{
                        $output = ['success' => false,
                                'msg' => "Could not be deleted, Child record exist."
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
}
