<?php

namespace App\Http\Controllers;

use App\Client as ClientModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Cache;
use Plank\Mediable\Facades\MediaUploader;
use Yajra\DataTables\DataTables;

class Client extends Controller
{
    public function index()
    {
        return view('client.list');
    }
    public function add( $id = null )
    {    
        $data = null;
        if( !empty($id) ){
            $data = ClientModel::findorFail($id);
        }
        return view('client.add',compact('data' ));
    }
    public function save(Request $request)
    {    
        $id = $request->edit_id;
        $request->validate([
            'c_name'   => 'required',
            'c_email'  => 'required|email|unique:clients'. ($id ? ",c_email,$id,c_id" : ''),
        ],[],[
            'c_name' => 'Name',
            'c_email' => 'Email',
        ]);
        
        if (request('edit_id')) {
            $client = ClientModel::find( $request->edit_id );
            
        }else{
            $client = New ClientModel;
            

        }
        $client->c_name   = $request->c_name;
        $client->c_email  = $request->c_email;
        $client->c_status = $request->c_status;
        $client->c_pwd    = Hash::make($request->pwd);
        $client->save();
        if( $request->file('user_image') ){
            $media = MediaUploader::fromSource($request->file('user_image'))->toDirectory('client')->useHashForFilename()->upload();
            
            $client->syncMedia($media, ['client']);
        }
        
        return redirect()->route('client.list')->with('message' , 'Client info save Successfully..!!!');
    }
    public function getdata(Request $request)
    {
        if(!$request->ajax())
        return abort(404);

        $data = ClientModel::select('c_id','c_name','c_email', 'c_status','created_at')->orderBy('c_id','desc');

        return DataTables::of($data)
        ->addColumn('action', function ($data) {
            $action = null;
                $action .= '<a href="'.route( 'client.edit' , $data->c_id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                        <i class="la la-edit"></i>
                    </a>';
                $action .= '<a href="javascript:;" data-id="'.$data->c_id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                        <i class="la la-trash"></i>
                    </a>';
            return $action;
        })
        ->addColumn('image', function ($data) {
            if($data->hasMedia('client')){
                return '<img src="uploads/'.$data->getMedia('client')->first()->getDiskPath().'" class="img-thumbnail h-auto" style="width: 55px !important; " alt="No image found">';
            }
            return null;
        })
        ->rawColumns(['action' => 'action','image' => 'image'])
        ->make(true);
    }

}
