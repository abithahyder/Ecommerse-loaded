<?php

namespace App\Http\Controllers;

use App\Coupons;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('coupon.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        $data = null;
        if( !empty($id) ){
            $data = Coupons::findorFail($id); 
        }
        return view('coupon.add',compact('data' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->edit_id;
        $validate = $request->validate([
            'cm_title'   => 'required',
            'cm_code'  => 'required|unique:coupons'. ($id ? ",cm_code,$id,cm_id" : ''),
            'cm_discount_type'   => 'required',
            'cm_start_date'   => 'required',
            'cm_expiry_date'   => 'required',
            'cm_amount'   => 'required|numeric',
            'cm_usage_limit'   => 'required|numeric',
            'cm_free_shipping'   => 'required',
            'cm_minimum_amount'   => 'required|numeric',
            'cm_maximum_amount'   => 'required|numeric',
            'cm_description'   => 'required',
            'cm_status'   => 'required',
        ],[],[
            'cm_title'   => 'Title',
            'cm_code'  => 'Code',
            'cm_discount_type'   => 'Discount type',
            'cm_start_date'   => 'start date',
            'cm_expiry_date'   => 'expiry date',
            'cm_amount'   => 'Amount',
            'cm_usage_limit'   => 'usage limit',
            'cm_free_shipping'   => 'Free shipping',
            'cm_minimum_amount'   => 'Minimum amount',
            'cm_maximum_amount'   => 'Maximum amount',
            'cm_description'   => 'Desctiption',
            'cm_status'   => 'Status',
        ]);
        
        if (request('edit_id')) {
            $coupon = Coupons::find( $request->edit_id );
        }else{
            $coupon = New Coupons;
            $validate['cm_usage_count'] = 0;
        }
        $coupon->fill($validate);
        $coupon->save();
        return redirect()->route('coupon.list')->with('message' , 'Coupon info save Successfully..!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }

       $delete =  Coupons::where('cm_id', request('id'))->delete();
       echo ($delete > 0) ? true :  false;
    }

    public function getdata(Request $request)
    {
        if(!$request->ajax())
        return abort(404);

        $data = Coupons::query();

        return Datatables::of($data)
        ->addColumn('action', function ($data) {
            $action = null;
                $action .= '<a href="'.route( 'coupon.edit' , $data->cm_id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                        <i class="la la-edit"></i>
                    </a>';
                $action .= '<a href="javascript:;" data-id="'.$data->cm_id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                        <i class="la la-trash"></i>
                    </a>';
            return $action;
        })
       
        ->rawColumns(['action' => 'action'])
        ->make(true);
    }

}
