<?php

namespace App\Http\Controllers;

use App\Client;
use App\delivery_charge;
use App\home_slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\setting;

class settings extends Controller
{
    public function index()
    {
        $data = setting::where('id',1)->get()->toArray();
        $data = $data[0] ?? null;
        return view('admin.setting', compact('data'));
    }

    public function save(Request $request)
    {    
        $request->validate([
            'currency'         => 'required'
        ]);
        
        $post = array(
            'currency'          => request('currency'),
            'maintenance_mode'  => request('maintenance_mode'),
            'notification_day'  => request('notification_day'),
        );
        if (request('edit_id')) {
            setting::where('id', request('edit_id'))->update($post);
        }else{
            setting::insert($post);
        }

        Cache::forget('setting');

        return redirect()->back()->with('message' , 'Setting save Successfully..!!!');
    }
    public function notification()
    {
        return view('admin.notification');
    }
    public function notificationSend(Request $request)
    {
        $request->validate([
            'message'  => 'required',
            'title'   => 'required',
        ]);
        
        $title = $request->title;
        $messsage = $request->message;

        if( !empty( $request->user_list ) ){
           $ClientIds =  Client::whereIn('c_id',$request->user_list)->pluck('c_fcm_token')->toArray();
            if( $ClientIds ){
                foreach ($ClientIds as $key => $value) {
                    $data = array(
                        'to'    => $value,
                        'data'  => array(
                            'title'         => $request->title,
                            'message'       => $request->message,
                            'is_background' => true,
                            'is_login'      => true,
                        )
                    );
                    send_push_notification($data);
                }
            }
        }
        
        if(  !empty($request->all_user) ){
            Client::whereNotNull('c_fcm_token')->chunk(50, function ($client) use ($messsage,$request)  {
                foreach ($client as $key => $value) {
                    $data = array(
                        'to'    => $value->c_fcm_token,
                        'data'  => array(
                            'title'         => $request->title,
                            'message'       => $request->message,
                            'is_background' => true,
                            'is_login'      => true,
                        )
                    );
                    send_push_notification($data);
                }
            });
        }
      
        return redirect()->back()->with('message' , 'Notification sent');
    }
    public function homeSlider($id = null)
    {
        $data = null;
        if( !empty( $id ) ){
            $data = home_slider::where('hs_id',$id)->first();
        }
        $list = home_slider::orderBy('hs_order','asc')->get()->toArray();
        return view('setting.slider',compact( 'data', 'list' ));
    }

    public function homeSliderSave(Request $request)
    {
        $vali = request('edit_id') > 0  ? 'nullable' : 'required';
        $request->validate([
            'order' => 'nullable',
            'image' => ''.$vali.'|mimes:jpeg,png,jpg',
        ]);

        $post = array(
            'hs_order'      => $request->order,
        );

        $upload_path = public_path('upload/slider/');
        if ($request->hasfile('image')) {
            $avatar = time() . '_' . request('image')->getClientOriginalName();
            request('image')->move($upload_path, $avatar);
            $post['hs_image'] = $avatar;
        }

        if( request('edit_id') ){
            home_slider::where('hs_id',request('edit_id'))->update($post);
        }else{
            home_slider::create($post);
        }
        return redirect()->back()->with('message' , 'Image save Successfully');
    }

    public function deliveryCharge($id = null)
    {
        $data = null;
        if( !empty( $id ) ){
            $data = delivery_charge::where('dc_id',$id)->first();
        }
        $list = delivery_charge::latest()->get()->toArray();
        return view('setting.delivery',compact( 'data', 'list' ));
    }

    public function deliveryChargeSave(Request $request)
    {
        $request->validate([
            'dc_postcode' => 'required|numeric',
            'dc_price'    => 'required|numeric',
        ],[],[
            'dc_postcode' => 'Postcode',
            'dc_price'    => 'Price',
        ]);

        $post = array(
            'dc_postcode' => $request->dc_postcode,
            'dc_price'    => $request->dc_price,
        );

        if( request('edit_id') ){
            delivery_charge::where('dc_id',request('edit_id'))->update($post);
        }else{
            delivery_charge::create($post);
        }
        return redirect()->back()->with('message' , 'Data save Successfully');
    }

    public function deliveryChargeDelete($id)
    {
        $data = delivery_charge::findorFail($id);
        $data->delete();
        if( !empty($data) ){
            return redirect()->back()->with('message' , 'Data delete Successfully');
        }
        return redirect()->back()->with('error' , 'Something went wrong');

    }

    public function homeSliderDelete($id)
    {
        $data = home_slider::findorFail($id);
        $data->delete();
        if( !empty($data) ){
            return redirect()->back()->with('message' , 'Image delete Successfully');
        }
        return redirect()->back()->with('error' , 'Something went wrong');

    }


    
}
