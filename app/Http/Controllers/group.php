<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user_groups;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GroupTableExport;

class group extends Controller
{   
    public function __construct()
    {   
        $this->middleware(function ($request, $next) {
            if (Auth::user()->user_type != 'administrator') {
                return abort(401,'Unauthorized ');
            }
            return $next($request);
        });
    }

    public function index($id = null)
    {   
        return view('admin.group');
    }

    public function getdata(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }
        
        $data = user_groups::select('ug_id','ug_name', 'created_at');
        return Datatables::of($data)
        ->addColumn('action', function ($data) {
            return '<a href="'.route( 'groupEdit' , $data->ug_id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                        <i class="la la-edit"></i>
                    </a>
                    <a href="javascript:;"  data-id="'.$data->ug_id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
						<i class="la la-trash"></i>
					</a>';
        })
        ->rawColumns(['action' => 'action'])
        ->make(true);
    }

    public function save(Request $request)
    {    
        $permissions = [];

        foreach( request('privileges') as $key => $value )
        {
            if( !empty( $value ) )
            {
                $permissions[] = $key;
            }
        }

        $niceNames = [
            'ug_name' => 'Group Name'
        ];

        $request->validate([
            'ug_name' => 'required|max:20',
        ],[],$niceNames);
        
        $insert_id = null;
        if (request('edit_id')) {
            $post = array( 
                'ug_permissions'  => request('ug_permissions')??null,
            );
        $insert_id = request('edit_id');
        user_groups::where('ug_id', request('edit_id'))->update($post);

        }else{
            $user = new user_groups();
            $user->ug_name = request('ug_name');
            $user->ug_permissions = request('ug_permissions')??null;
            if($user->save()){
                $insert_id = $user->ug_id;
            }

        }

        return redirect()->route('group')->with('message' , 'Group save Successfully..!!!');
    }

    public function deleteGroup(Request $request)
    {   
        if(!$request->ajax()){
            return abort(404);
        }
        $delete =  user_groups::where('ug_id', request('id'))->delete();
        
        echo ($delete > 0) ? true :  false;
    }

    public function addRole($id = null)
    {
        $data = null;
        $permission = null;
        if ($id) {
            $data = user_groups::find($id);
            $permission = explode( ',', $data['ug_permissions'] );
        }
        $array = array( 
            'dashboard'         => 'Dashboard',
            'user_manegement'   => 'User Managements',
            
        );
        $array = get_routes();
        return view('admin.addRole',compact('array','data','permission'));
    }

    public function export(Request $request)
    {
        try {
            if ( $request->export == 'xlsx' ) {
                return Excel::download(new GroupTableExport(), 'Group-'.date('d-M-Y').'.xlsx');
            }else if( $request->export == 'csv' ){
                return Excel::download(new GroupTableExport(), 'Group-'.date('d-M-Y').'.csv');
            }else{

            }
        } catch (\Throwable $th) {
            return  $th->getMessage();
        }

        return redirect()->back();
    }
}
