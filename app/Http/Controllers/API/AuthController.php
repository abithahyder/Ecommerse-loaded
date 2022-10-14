<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Plank\Mediable\Facades\MediaUploader;

class AuthController extends Controller
{
    public function signup(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'c_name'  => 'required',
            'c_email' => 'email|required|unique:clients',
            'c_pwd'   => 'required',
            'confirm_password' => 'required',
            'c_pwd'    => 'required|same:confirm_password',
        ],[],[
            'c_name' => 'Username',
            'c_email' => 'Email',
            'c_pwd' => 'Password',
            'confirm_password' => 'Confirm password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false ,
                'message'   => 'The following fields are required' ,
                'error'     => $validator->errors()
            ], 200);
        }
        $validatedData = $request->all();
        $validatedData['c_pwd'] = Hash::make($request->c_pwd);
        if( !empty($request->c_fcm_token) ){
            $validatedData['c_fcm_token'] = $request->c_fcm_token;
        }
        $ClientModel = Client::create($validatedData);

        $accessToken = $ClientModel->createToken('authToken')->plainTextToken;

        return response([ 
            'status' => true,
            'message' => 'Congratulations ! Your registration has been successfully',
            'client' => $ClientModel,
            'access_token' => $accessToken
        ]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $client  = Client::where('c_email', request('email'))->first();
        
        if ($client && Hash::check(request('password'), $client->c_pwd)) {
            if( !empty( $request->c_fcm_token ) ){
                $client->c_fcm_token = $request->c_fcm_token;
                $client->update();
            }
            return response()->json([
                'status'    => true,
                'message'   => 'Congratulations ! You are successfully logged in',
                'token'     => $client->createToken('authToken')->plainTextToken,
            ], 200);
        }else {
            return response()->json([
                'status'    => false,
                'message'   => 'These credentials do not match our records.'
            ], 200);
        }

    }

    public function details()
    {   
        $client = Auth::user();
        if ($client) {
            if( $client->hasMedia('client') ){
                $client->image = $client->firstMedia('client')->getUrl();
            }
            return response()->json([ 
                'status' => true,
                'data'   => $client
            ], 200);
        }
        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 200);
    }

    public function updateProfile(Request $request)
    {   
        $client = Auth::user();

        $validator = Validator::make($request->all(), [
            'c_name'  => 'required',
            'c_email'  => 'required|email|unique:clients,c_email,'.$client->c_id.',c_id',
            'image'   => 'required',
        ],[],[
            'c_name'  => 'Username',
            'c_email' => 'Email',
            'image'   => 'Profile photo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false ,
                'message'   => 'The following fields are required' ,
                'error'     => $validator->errors()
            ], 200);
        }

        if ($client) {
            $client->c_name  = $request->c_name;
            $client->c_email = $request->c_email;
            $client->save();
            if( $request->file('image') ){
                $media = MediaUploader::fromSource($request->file('image'))->toDirectory('client')->useHashForFilename()->upload();
                $client->syncMedia($media, ['client']);
            }
            return response()->json([ 
                'status' => true,
                'data'   => $client
            ], 200);
        }
        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 200);
    }
    public function forgot_pwd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'  => 'required|email',
        ], []);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors()
            ], 200);
        }

        $user =  Client::where('c_email',request('email'))->first();
        if (!empty($user)) {
            try {
                $code = mt_rand(100000, 999999);
                $data = ['code' => $code];

                Mail::to($user->c_email)->send(new ForgotPwdEmail($data));

                return response()->json([
                    'status'    => true,
                    'message'   => 'We have e-mailed your password reset OTP!',
                    'code'      =>  $code,
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Sorry,Something went wrong. Please try again later'
                ], 200);
            }
        }

        return response()->json([
            'status'    => false,
            'message'   => "We can't find a user with that e-mail address",
        ], 200);

        
    }

    public function getAddress(Request $request)
    {
        $client = Auth::user();
        $address = \App\clientAddress::where('ca_c_id',$client->c_id)->get();
        
        if ($address) {
            return response()->json([ 
                'status' => true,
                'data'   => $address
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 200);
    }

    public function removeAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ca_id' => 'required|numeric',
        ],[],[
            'ca_id' => 'Address',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false ,
                'message'   => 'The following fields are required' ,
                'error'     => $validator->errors()
            ], 200);
        }

        $client = Auth::user();
        $address = \App\clientAddress::where('ca_id',$request->ca_id)->where('ca_c_id',$client->c_id)->delete();

        if ($address) {
            return response()->json([ 
                'status' => true,
                'data'   => 'Address removed successfully'
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No address found'
        ], 200);
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ca_address_line_1' => 'required',
            'ca_address_line_2' => 'nullable',
            'ca_mobile'         => 'required|numeric|min:10',
            'ca_altr_num'       => 'nullable',
            'ca_city'           => 'required',
            'ca_state'          => 'required',
            'ca_country'        => 'required',
            'ca_pincode'        => 'required|numeric',
        ],[],[
            'ca_address_line_1' => 'Address line 1',
            'ca_address_line_2' => 'Address line 2',
            'ca_mobile'         => 'Mobile',
            'ca_city'           => 'City',
            'ca_state'          => 'State',
            'ca_country'        => 'Country',
            'ca_pincode'        => 'Pincode',
            'ca_altr_num'       =>'Alternate Number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false ,
                'message'   => 'The following fields are required' ,
                'error'     => $validator->errors()
            ], 200);
        }

        $client = Auth::user();
        $params = $validator->validate();
        $params['ca_c_id'] = $client->c_id;

        if( $request->ca_id ){
            $newAddress = \App\clientAddress::where('ca_id',$request->ca_id)->where('ca_c_id',$client->c_id)->update( $params );
        }else{
            $newAddress = \App\clientAddress::create( $params );
        }

        if( $newAddress ){
            return response()->json([
                'status'    => true ,
                'message'   => 'Address successfully saved',
            ], 200);
        }

        return response()->json([
            'status'    => false ,
            'message'   => 'Something went wrong, Please try again!' ,
        ], 200);

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status'    => true,
            'message'   => 'Successfully logged out'
        ]);
    }
    
    public function resetPwd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }
        try {
            Client::where('c_email',request('email'))->update(['c_pwd' => Hash::make(request('password'))]);
            return response()->json([
                'status'    => true,
                'message'   => 'Congratulations! Your password has been reset successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Sorry,Something went wrong. Please try again later',
            ], 200);
        }
    }

    public function change_password(Request $request)
    {
        $client = Auth::user();
        $niceNames = [
            'current_pwd'   => 'Current password',
            'new_pwd'       => 'New password',
            'confirm_pwd'   => 'Confirm password'
        ];

        $validator = Validator::make($request->all(), [
            'current_pwd'  => 'required',
            'new_pwd'      => 'required',
            'confirm_pwd'  => 'required|same:new_pwd',
        ], [], $niceNames);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors()
            ], 200);
        }

        if (!Hash::check(request('current_pwd'), Auth::user()->c_pwd)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Current password is incorrect!',
            ], 200);
        }else{
            try {
                $update = array(
                    'c_pwd' => Hash::make(request('new_pwd')),
                );
                Client::where('c_id', $client->c_id)->update($update);
                return response()->json([
                    'status'    => true,
                    'message'   => 'Your password has been changed successfully!'
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Sorry,Something went wrong. Please try again later'
                ], 200);
            }
        }
    }
}
