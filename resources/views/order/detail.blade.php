@extends('layouts.default')
@section('title', 'Order details')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Order details </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="{{ route('order.list') }}" class="kt-subheader__breadcrumbs-link">
                Order management </a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Order details </a>
        </div>
    </div>
    
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
{{-- <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content"> --}}
    @if ($errors->any())
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-text">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    @endif
    @if(session()->has('message'))
        <div class="alert alert-success fade show" role="alert">
            <div class="alert-text">{{ session()->get('message') }}</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    @endif
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

        <!--Begin::App-->
        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

            <!--Begin:: App Aside Mobile Toggle-->
            <button class="kt-app__aside-close" id="kt_user_profile_aside_close">
                <i class="la la-close"></i>
            </button>

            <!--End:: App Aside Mobile Toggle-->

            <!--Begin:: App Aside-->
            <div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside">

                <!--begin:: Widgets/Applications/User/Profile1-->
                <div class="kt-portlet kt-portlet--height-fluid-">
                    <div class="kt-portlet__head  kt-portlet__head--noborder">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit-y">

                        <!--begin::Widget -->
                        <div class="kt-widget kt-widget--user-profile-1">
                            <div class="kt-widget__head">
                                <div class="kt-widget__media">
                                    <img src="{{ $data->usr->hasMedia('client') ? $data->usr->getMedia('client')->first()->getUrl() : null }}" alt="image">
                                </div>
                                <div class="kt-widget__content">
                                    <div class="kt-widget__section">
                                        <a href="{{ route('client.edit',$data->usr->c_id) }}" class="kt-widget__username">
                                            {{ $data->usr->c_name  }}
                                            {{-- <i class="flaticon2-correct kt-font-success"></i> --}}
                                        </a>
                                        {{-- <span class="kt-widget__subtitle">
                                            Head of Development
                                        </span> --}}
                                    </div>
                                    <div class="kt-widget__action">
                                        {{-- <button type="button" class="btn btn-info btn-sm">chat</button>&nbsp;
                                        <button type="button" class="btn btn-success btn-sm">follow</button> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__body">
                                <div class="kt-widget__content">
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">Email:</span>
                                        <a href="{{ route('client.edit',$data->usr->c_id) }}" class="kt-widget__data">{{ $data->usr->c_email }}</a>
                                    </div>
                                    {{-- <div class="kt-widget__info">
                                        <span class="kt-widget__label">Phone:</span>
                                        <a href="#" class="kt-widget__data">{{ $data->usr->c_email }}</a>
                                    </div>
                                    <div class="kt-widget__info">
                                        <span class="kt-widget__label">Location:</span>
                                        <span class="kt-widget__data">Melbourne</span>
                                    </div> --}}
                                </div>
                                <div class="kt-widget__items">
                                    
                                </div>
                            </div>
                        </div>

                        <!--end::Widget -->
                    </div>


                    
                </div>

                <!--end:: Widgets/Applications/User/Profile1-->
            </div>

            <!--End:: App Aside-->

            <!--Begin:: App Content-->
            <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
                <div class="row">
                    <div class="col-xl-6">
                        

                        <!--begin:: Widgets/Order Statistics-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        Order Statistics
                                    </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                   
                                </div>
                            </div>
                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                <div class="kt-widget12">
                                    <div class="kt-widget12__content">
                                        <div class="kt-widget12__item">
                                            <div class="kt-widget12__info">
                                                <span class="kt-widget12__desc">Order number</span>
                                                <span class="kt-widget12__value">{{ $data->om_or_id_no }}</span>
                                            </div>
                                            <div class="kt-widget12__info">
                                                <span class="kt-widget12__desc">Order date</span>
                                                <span class="kt-widget12__value">{{ $data->om_date }}</span>
                                            </div>
                                            <div class="kt-widget12__info">
                                                <span class="kt-widget12__desc">Payment type</span>
                                                <span class="kt-widget12__value">{{ $data->payment }}</span>
                                            </div>
                                        </div>
                                        <div class="kt-widget12__item">
                                            <div class="kt-widget12__info">
                                                <span class="kt-widget12__desc">Order status</span>
                                                <input type="hidden" value="{{$data->orm_id}}" id="orderId">
                                                <select class="form-control col-4" id="change-status">
                                                    @foreach (orderStatusList() as $key => $value )
                                                        <option value="{{ $key }}" {{ ( $key ==  $data->om_status ) ? 'selected' : '' }} >{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                           
                                            
                                            <div class="kt-widget12__info">
                                                <span class="kt-widget12__desc">Grand total</span>
                                                <span class="kt-widget12__value">{{ $data->om_grand_total }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        Billing Address
                                    </h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="row">
                                <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Name:</span>
                                            <span class="kt-widget__data text-dark">{{$data->usr->c_name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Address line 1:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->usraddrss->ca_address_line_1}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Address line 2:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->usraddrss->ca_address_line_2 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Mobile:</span>
                                            <span class="kt-widget__data text-dark">{{$data->usraddrss->ca_mobile,$data->usraddrss->altr_num }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">City:</span>
                                            <span class="kt-widget__data text-dark">{{$data->usraddrss->ca_city }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">State:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->usraddrss->ca_state }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Country:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->usraddrss->ca_country }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Pincode:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->usraddrss->ca_pincode }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Order Statistics-->
                    </div>
                    <div class="col-xl-6">
                        <!--begin:: Widgets/Tasks -->
                        <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        Shipping Address
                                    </h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="row">
                                <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Name:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_sname }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Address line 1:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_addresline_1 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Address line 2:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_addresline_2 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Mobile:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_mobile }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">City:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_city }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">State:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_state }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Country:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_country }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="kt-widget__info">
                                            <span class="kt-widget__label ">Pincode:</span>
                                            <span class="kt-widget__data text-dark">{{ $data->om_pincode }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Tasks -->
                    </div>
                </div>
                <div class="row">
                    
                </div>
            </div>
            
            <!--End:: App Content-->
        </div>
        <div class="row">
            <div class="col-xl-12">

                <!--begin:: Widgets/Notifications-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Order item
                            </h3>
                        </div>
                       
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget6_tab1_content" aria-expanded="true">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="itemstatus[]" id="itemvalues" value="0"/></th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                     
                                            
                                            <th>Qty</th>
                                            <th>Discount</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Delivery</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php

use Illuminate\Support\Facades\DB;

                                     $payflag=0;
                                     $deliflag=0;
                                     ?>
                                     
                                      @foreach($data->items as $item)
                                      <?php
                                      if($item->delivery_status!="Delivered"){
                                        $deliflag=1;
                                      }
                                      if($item->pay_status!="Paid"){
                                        $payflag=1;
                                      }
                                      ?>

                                      <tr>
                                       <td><input type="checkbox" name="itemstatus[]" id="itemvalue" value="{{$item->or_id}}"/></td>
                                        <td><img src="../uploads/{{$item->oim_image}}" width="40" height="40"/></td>
                                         <?php 
                                         if(!empty($item->sku)){
                                            $skus = App\skus::where('sku_id','=',$item->sku)->first();
                     
                                           $pname=$skus->sku_name;
                                            $pprice=$skus->sku_price;
                                       
                                            
                                         }
                                         else{
                                           
                                           $pname= $item->product->p_name;
                                          $pprice= $item->product->p_price;
                                         }
                                        ?>
                                        
                                        <td>{{$item->name}}</td>
                                           <td>{{$item->price}}</td>
                                           <td>{{$item->qty}}</td>
                                            <td>{{$item->discount}}</td>
                                           <td>{{$item->total_price}}</td> 
                                           <td>{{$item->pay_status}}</td>
                                           <td>{{$item->delivery_status}}</td>
                                        </tr>
                                      @endforeach
                                        
                                        
                                            
                                       
                                     
                                    
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                       <td colspan="2" >
                                        @if($payflag==1) 
                                        <button type="button" class="btn btn-primary" id="change-payment">Paid</button>
                                        @endif
                                        @if($deliflag==1)
                                       <button type="button" class="btn btn-primary" id="change-delivery">Delivered</button></td>
                                       @endif
                                        </tr>
                                    
                                        <tr>
                                            <td colspan="4" class="text-right">Total</td>
                                            <td >{{ $data->om_total }}</td>
                                        </tr>
                                         <tr>
                                            <td colspan="4" class="text-right">Delivery charge</td>
                                            <td >{{ $data->delivery_charge }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right">Discount price</td>
                                            <td >{{ $data->discount }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right">Grand total</td>
                                            <td >{{ $data->om_grand_total  }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Notifications-->
            </div>
        </div>

        <!--End::App-->
    </div>

{{-- </div>     --}}
@endsection
@section('pagespecificscripts')
    <script src="{{ asset('js/order/detail.js')}}"></script>
@endsection