<?php

namespace App\Http\Controllers;


use App\Ordermaster;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderControllers extends Controller
{
   public function index(){
    return view('order.list');
   }

   public function details( $id = null )
   {    
       $data = null;
       $data = Ordermaster::findorFail($id); 
       return view('order.detail',compact('data'));
   }
   public function statusUpdate(Request $request)
   {    
       $validate = $request->validate([
           'status'   => 'required',
           'order_id'   => 'required|numeric',
       ],[],[
           'status'   => 'status',
           'order_id'   => 'order',
       ]);
       
       if (request('order_id')) {
           $order = Ordermaster::find( $request->order_id );
           $order->om_status = $request->status;
           $order->save();

           if(!empty($order->user->c_fcm_token)){
               $data = array(
                   'to'    => $order->user->c_fcm_token,
                   'data'  => array(
                       'title'         => 'Order status update',
                       'message'       => 'Your order has been '.orderStatusList($request->status),
                       'is_background' => true,
                       'is_login'      => true,
                   )
               );
               send_push_notification( $data );
           }
           echo ($order ) ? true :  false;
           return;
       }

       echo false;
       return;
   }
   public function getdata(Request $request)
   {
       if(!$request->ajax())
       return abort(404);

       $data = Ordermaster::select('orm_id','om_u_id', 'om_or_id_no','om_date','om_status','om_grand_total','payment','clients.c_id','clients.c_name')
                           ->leftJoin('clients','ordermasters.om_u_id','clients.c_id');
       if( request('status') ){
           $data = $data->where('om_status',request('status'));
       }

       if( request('date') ){
           $data = $data->whereDate('om_date',request('date'));
       }

       return DataTables::of($data)
       ->addColumn('action', function ($data) {
           $action = null;
               $action .= '<a href="'.route( 'order.edit' , $data->orm_id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                       <i class="la la-edit"></i>
                   </a>
                   <a href="javascript:;" data-id="'.$data->orm_id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                       <i class="la la-trash"></i>
                   </a>';
           return $action;
       })
       ->addColumn('om_status', function ($data) {
           return orderStatusList($data->om_status);
       })
       ->addColumn('number', function ($data) {
           return '<a href="'.route( 'order.edit' , $data->orm_id).'" target="_blank"  title="Order details">
                       '.$data->om_or_id_no.'
                   </a>';
       })
       ->addColumn('cname', function ($data) {
           return '<a href="'.route( 'client.edit' , $data->c_id).'" target="_blank"  title="User details">
                       '.$data->c_name.'
                   </a>';
       })
       ->rawColumns(['action' => 'action','number' => 'number','cname' => 'cname','om_status' => 'om_status'])
       ->make(true);
   }

}
