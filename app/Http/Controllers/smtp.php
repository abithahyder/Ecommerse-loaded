<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\smtp_setting;

use Mail;
use App\Mail\Test;

class smtp extends Controller
{
    public function index()
    {
        $data = smtp_setting::get()->first();
        return view('admin.smtp', compact('data'));
    }

    public function save(Request $request)
    {    
        $post = array(
            'ss_mailer'         => $request->ss_mailer,
            'ss_host'           => $request->ss_host,
            'ss_port'           => $request->ss_port,
            'ss_uname'          => $request->ss_uname,
            'ss_pwd'            => $request->ss_pwd,
            'ss_encryption'     => $request->ss_encryption,
            'ss_from_address'   => $request->ss_from_address,
            'ss_from_name'      => $request->ss_from_name,
        );
        
        if ( !empty( $request->edit_id ) ) {
            smtp_setting::where('ss_id', $request->edit_id )->update( $post );
        }else{
            smtp_setting::insert($post);
        }

        Cache::forget('smtp');
        return redirect()->back()->with('message' , 'SMTP save Successfully!');
    }

    public function test_mail( Request $request )
    {   
        $to = $request->to_mail ?? 'email@email.com';
        try {
            Mail::to($to)->send(new Test);
            return redirect()->back()->with('message' , 'Mail send Successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error' , $th->getMessage() );
        }
    }
}
