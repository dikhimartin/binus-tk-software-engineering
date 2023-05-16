<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\PermissionRole;
use App\Traits\RespondsWithHttpStatus;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $controller = 'roles';

    private function title(){
        return __('main.user_role');
    }

    public function __construct() {
        $this->middleware('auth');
    }     

    public function index(){
        if (!Auth::user()->can($this->controller.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }

        return view('backend.user_access.'.$this->controller.'.list')->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function create(){
        if (!Auth::user()->can($this->controller.'-create')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }

        $permission = Permission::orderBy('sort','ASC')->get();
        $arrGroup = [];
        foreach ($permission as $row){
            $splices = explode('-', $row->name);
            if (count($splices) > 1){
                unset($splices[count($splices)-1]);    
            }
            $groupName = implode('-', $splices);
            $arrGroup[$groupName][] = $row;
        }

        return view('backend.user_access.'.$this->controller.'.create', compact('arrGroup'))->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function edit($id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }

        $role = Role::find($id);
        $permission = Permission::orderBy('sort','ASC')->get();
        $arrGroup = [];
        foreach ($permission as $row){
            $splices = explode('-', $row->name);
            if (count($splices) > 1){
                unset($splices[count($splices)-1]);    
            }
            $groupName = implode('-', $splices);
            $arrGroup[$groupName][] = $row;
        }
        $rolePermissions = PermissionRole::where("permission_role.role_id",$id)
            ->pluck('permission_role.permission_id','permission_role.permission_id')->all();

        return view('backend.user_access.'.$this->controller.'.edit', compact('role','permission','rolePermissions','arrGroup'))->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function get_data(Request $request){
        if (!Auth::user()->can($this->controller.'-list')){
            return $this->unauthorizedAccessModule();
        }        

        return DataTables::of(Role::query())
            ->filter(function ($query) use ($request) {
                if ($request->has('search.value')) {
                    $query->where(function ($query) use ($request) {
                        $value = $request->input('search.value');
                        $query->where('name', 'like', "%$value%")
                            ->orWhere('status', 'like', "%$value%")
                            ->orWhere('description', 'like', "%$value%");
                    });
                }
            })
            ->addColumn('action', function ($data) {
                // add your action column logic here
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function store(Request $request){
        if (!Auth::user()->can($this->controller.'-create')){
            return $this->unauthorizedAccessModule();
        }  

        $this->validate($request, [
            'name'          => 'required|unique:roles,name',
            'display_name'  => 'required',
            'status'        => 'required',
            'description'   => 'required',
            'permission'    => 'required',
        ]);

        $role = new Role();
        $role->name         = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->status       = $request->input('status');
        $role->description  = $request->input('description');
        $role->save();

        foreach ($request->input('permission') as $key => $value) {
            $role->attachPermission($value);
        }

        return redirect()->route('user_access.role.index')
                         ->with('success','Role created successfully');
    }

    public function update(Request $request, $id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }        

        $this->validate($request, [
            'name'          => 'required',
            'display_name'  => 'required',
            'description'   => 'required',
            'status'        => 'required',
            'permission'    => 'required',
        ]);

        $role               = Role::find($id);
        $role->name         = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->status       = $request->input('status');
        $role->description  = $request->input('description');
        $role->save();

        PermissionRole::where("permission_role.role_id",$id)
            ->delete();

        foreach ($request->input('permission') as $key => $value) {
            $role->attachPermission($value);
        }

        return redirect()->route('user_access.role.index')
                        ->with('success','Role updated successfully');
    }
    
    public function detail($id){
        if (!Auth::user()->can($this->controller.'-list')){
            return $this->unauthorizedAccessModule();
        }  

        $role = Role::with('permissions')->find($id);
        if($role == null){
            return $this->errorNotFound(null);
        }        
        $groupedPermissions = $role->permissions->groupBy(function ($permission) {
            $splices = explode('-', $permission->name);
            if (count($splices) > 1) {
                unset($splices[count($splices) - 1]);
            }
            return implode('-', $splices);
        });
        
        $permissions = $groupedPermissions->map(function ($permissions, $name) {
            $list = $permissions->map(function ($permission) {
                return [
                    'display_name' => $permission->display_name,
                    'name' => $permission->name,
                ];
            });
        
            return [
                'name' => ucfirst($name),
                'list' => $list->toArray(),
            ];
        })->values()->toArray();
        
        $res = [
            'role' => $role,
            'permissions' => $permissions,
        ];
        return $this->ok($res, null);
    }

    public function delete($id){
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Role::find($id);
        if (!$res) {
            return $this->errorNotFound(null);
        }
        $res->delete();
        
        return $this->deleted("Data deleted successfully");
    }

    public function delete_batch(Request $request) {
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $ids = $request->input('id');
        Role::whereIn('id', $ids)->delete();
        return $this->deleted("Data deleted successfully");
    }

}
