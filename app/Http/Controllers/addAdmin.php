<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\user_groups;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserTableExport;

class addAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = null)
    {
      $user = null;
        if ($id) {
            $user = user::find($id);
        }
        $group = user_groups::select('ug_id','ug_name')->get()->toArray();
        return view('admin.add',compact('user','group'));
    }

    public function getdata(Request $request)
    {
        if(!$request->ajax())
        return abort(404);
        $user = user::select('id','name', 'email','status','ug_name')
                ->leftJoin('user_groups', 'users.user_group', '=', 'user_groups.ug_id');
        return Datatables::of($user)
       ->addColumn('status', function ($user) {
            return '<span class="kt-switch kt-switch--sm kt-switch--icon kt-switch--success kt-switch--outline">
                        <label>
                            <input type="checkbox" '.(check_permission('adminEdit') ? '' : 'disabled').' class="chkbox_active"  '. ($user->status == 'active' ? 'checked' : '') .' value="'.(check_permission('adminEdit') ? $user->id : '').'">
                            <span></span>
                        </label>
                    </span>';
        })
        ->addColumn('action', function ($user) {
            $data = null;
                    if(check_permission('adminEdit')){
                        $data .= '<a href="'.route( 'adminEdit' , $user->id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                                <i class="la la-edit"></i>
                            </a>';
                    }
                    if (check_permission('adminDelete')) {
                        $data .= '<a href="javascript:;" data-id="'.$user->id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                                <i class="la la-trash"></i>
                            </a>';
                    }
            return $data;
        })
        ->rawColumns(['action' => 'action','status' => 'status'])
        ->make(true);
    }

    public function saveAdmin(Request $request)
    {
        if (request('edit_id')) {

            $request->validate([
                'name'   => 'required|max:20',
                'email'  => 'required|unique:users,email,'.request('edit_id').'',
                'status' => 'required',
                'group'  => 'required',
            ]);

            $post = array(
                'name'      => request('name'),
                'email'     => request('email'),
                'status'    => request('status'),
                'user_group'=> request('group'),
            );

            if (request('password')) {
                $request->validate([
                    'password' => 'min:8|confirmed',
                ]);
                $post['password'] = Hash::make(request('password'));
            }
            user::where('id', request('edit_id'))->update($post);

        }else{

            $request->validate([
                'name'      => 'required|max:20',
                'email'     => 'required|unique:users',
                'password'  => 'required|min:8|confirmed',
                'group'     => 'required',
            ]);
            $user = new user();
            $user->name         = request('name');
            $user->email        = request('email');
            $user->status       = request('status');
            $user->user_group   = request('group');
            $user->password     = Hash::make(request('password'));
            $user->save();

        }
        
        return redirect()->back()->with('message' , 'User save Successfully..!!!');
    }

    public function deleteAdmin(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }

       $delete =  user::where('id', request('id'))->delete();
       echo ($delete > 0) ? true :  false;
    }

    public function statusChange(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }
        $affected = user::where('id', request('id'))->update(array('status' => request('status')));
        echo ($affected > 0) ? true :  false;
    }

    

    public function permissionSave(Request $request)
    {
       $request->validate([
        'privileges' => 'required',
        'type' => 'required',
        'user_id' => 'required',
       ]);

       foreach (request('privileges') as $key) {
            $post[] = array(
                'up_name'         => $key,
                'up_related_id'   => request('user_id'),
               'up_related_type' => request('type'),
            );
       }
       
       return redirect()->back()->with('message' , 'Data Submitted Successfully..!!!');
    }

    public function notification_status()
    {
        $update = notification_model::where('noti_is_read',0)->update(array('noti_is_read' => 1));
        return response()->json(['status' => true]);
    }

    public function export(Request $request)
    {   
        try {
            if ( $request->export == 'xlsx' ) {
                return Excel::download(new UserTableExport(), 'User-'.date('d-M-Y').'.xlsx');
            }else if( $request->export == 'csv' ){
                return Excel::download(new UserTableExport(), 'User-'.date('d-M-Y').'.csv');
            }else{

            }
        } catch (\Throwable $th) {
            return  $th->getMessage();
        }

        return redirect()->back();
    }
}
