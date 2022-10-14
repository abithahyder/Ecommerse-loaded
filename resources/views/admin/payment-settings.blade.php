@extends('layouts.default')
@section('title', 'Social Authentication')
@section('pagespecificcss')
    <link href="{{ asset('assets/css/demo1/pages/general/wizard/wizard-4.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Pyament Gateway Settings 
        </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Payment Gateway Settings 
            </a>
        </div>
    </div>
    
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    @if(session()->has('success'))
        <div class="alert alert-success fade show" role="alert">
            <div class="alert-text">{{ session()->get('success') }}</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-text">{{ session()->get('error') }}</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    @endif
    
    <div class="kt-wizard-v4" id="kt_wizard_v4" data-ktwizard-state="step-first">

        <!--begin: Form Wizard Nav -->
        <div class="kt-wizard-v4__nav">
            <div class="kt-wizard-v4__nav-items">
                <a class="kt-wizard-v4__nav-item payment-select" href="javascript:void(0);" data-ktwizard-type="step" data-ktwizard-state="current" data-id="stripe-settings">
                    <div class="kt-wizard-v4__nav-body">
                        <div class="kt-wizard-v4__nav-number">
                            <i class="fab fa-stripe-s"></i>
                        </div>
                        <div class="kt-wizard-v4__nav-label">
                            <div class="kt-wizard-v4__nav-label-title">
                                Stripe
                            </div>
                            <div class="kt-wizard-v4__nav-label-desc">
                                Stripe payment gateway settings
                            </div>
                        </div>
                    </div>
                </a>
                <a class="kt-wizard-v4__nav-item payment-select" href="javascript:void(0);" data-ktwizard-type="step" data-id="paypal-settings">
                    <div class="kt-wizard-v4__nav-body">
                        <div class="kt-wizard-v4__nav-number">
                            <i class="socicon-paypal"></i>
                        </div>
                        <div class="kt-wizard-v4__nav-label">
                            <div class="kt-wizard-v4__nav-label-title">
                                Paypal
                            </div>
                            <div class="kt-wizard-v4__nav-label-desc">
                                Paypal payment gateway settings
                            </div>
                        </div>
                    </div>
                </a>
                <a class="kt-wizard-v4__nav-item payment-select" href="javascript:void(0);" data-ktwizard-type="step" data-id="razorpay-settings">
                    <div class="kt-wizard-v4__nav-body">
                        <div class="kt-wizard-v4__nav-number">
                            R
                        </div>
                        <div class="kt-wizard-v4__nav-label">
                            <div class="kt-wizard-v4__nav-label-title">
                                Razorpay
                            </div>
                            <div class="kt-wizard-v4__nav-label-desc">
                                Razorpay payment gateway settings
                            </div>
                        </div>
                    </div>
                </a>
                <a class="kt-wizard-v4__nav-item payment-select" href="javascript:void(0);" data-ktwizard-type="step" data-id="paytm-settings">
                    <div class="kt-wizard-v4__nav-body">
                        <div class="kt-wizard-v4__nav-number">
                            P
                        </div>
                        <div class="kt-wizard-v4__nav-label">
                            <div class="kt-wizard-v4__nav-label-title">
                                Paytm
                            </div>
                            <div class="kt-wizard-v4__nav-label-desc">
                                Paytm payment gateway settings
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
    <input type="hidden" name="active_gateway" placeholder="Active gateway Type" value="{{ $data['active_gateway_type'] ?? "" }}" class="form-control active_gateway" >
    
    <div class="kt-portlet kt-portlet--mobile stripe-settings">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fab fa-stripe"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Stripe Settings
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                       @if($data)
                        @php
                        if($data){
                            if( $data['active_gateway_type'] &&  $data['active_gateway_type'] == 'stripe' ) {
                                $status = "Active";
                                $class  = "success";
                            } else {
                                $status = "Inactive";
                                $class  = "danger";
                            }
                        }
                       @endphp
                        <button type="button" class="btn btn-sm btn-{{ $class }} btn-bold">{{ $status }}</button>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route( 'payment-settings.save' ) }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Stripe Key :</label>
                            <input type="hidden" name="key[active_gateway_type]" placeholder="Active gateway Type" value="stripe" class="form-control" >
                            <input type="text" name="key[stripe_key]" placeholder="Enter Stripe Key" value="{{ $data['stripe_key'] ?? '' }}" class="form-control" >
                            @csrf
                        </div>
                        <div class="col-lg-6">
                            <label>Stripe Secret :</label>
                            <input type="text" name="key[stripe_secret]" placeholder="Enter Stripe Secret" value="{{ $data['stripe_secret'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
    
    <div class="kt-portlet kt-portlet--mobile razorpay-settings">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand la la-registered"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Razorpay Settings
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        @if($data)
                        <?php
                        
                            if( $data['active_gateway_type'] &&  $data['active_gateway_type'] == 'razorpay' ) {
                                $status = "Active";
                                $class  = "success";
                            } else {
                                $status = "Inactive";
                                $class  = "danger";
                            }
                       
                        ?>
                        <button type="button" class="btn btn-sm btn-{{ $class }} btn-bold">{{ $status }}</button>
                     @endif
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route( 'payment-settings.save' ) }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Razorpay Key :</label>
                            <input type="hidden" name="key[active_gateway_type]" placeholder="Active gateway Type" value="razorpay" class="form-control" >
                            <input type="text" name="key[razorpay_key]" placeholder="Enter Razorpay Key" value="{{ $data['razorpay_key'] ?? '' }}" class="form-control" >
                            @csrf
                        </div>
                        <div class="col-lg-6">
                            <label>Razorpay Secret :</label>
                            <input type="text" name="key[razorpay_secret]" placeholder="Enter Razorpay Secret" value="{{ $data['razorpay_secret'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
    
    <div class="kt-portlet kt-portlet--mobile paypal-settings">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fab fa-paypal"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Paypal Settings
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        @if($data)
                        <?php
                        
                            if( $data['active_gateway_type'] &&  $data['active_gateway_type'] == 'paypal' ) {
                                $status = "Active";
                                $class  = "success";
                            } else {
                                $status = "Inactive";
                                $class  = "danger";
                            }
                        
                        ?>
                        <button type="button" class="btn btn-sm btn-{{ $class }} btn-bold">{{ $status }}</button>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route( 'payment-settings.save' ) }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Paypal Client Id :</label>
                            <input type="hidden" name="key[active_gateway_type]" placeholder="Active gateway Type" value="paypal" class="form-control" >
                            <input type="text" name="key[paypal_client_id]" placeholder="Enter Paypal Client Id" value="{{ $data['paypal_client_id'] ?? '' }}" class="form-control" >
                            @csrf
                        </div>
                        <div class="col-lg-6">
                            <label>Paypal Secret :</label>
                            <input type="text" name="key[paypal_secret]" placeholder="Enter Paypal Secret" value="{{ $data['paypal_secret'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Paypal Mode :</label>
                            <input type="text" name="key[paypal_mode]" placeholder="Enter Paypal Mode" value="{{ $data['paypal_mode'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
    
    <div class="kt-portlet kt-portlet--mobile paytm-settings">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand la la-rouble"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Paytm Settings
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        
                        @if($data)
                        <?php
                            if( $data['active_gateway_type'] &&  $data['active_gateway_type'] == 'paytm' ) {
                                $status = "Active";
                                $class  = "success";
                            } else {
                                $status = "Inactive";
                                $class  = "danger";
                            }
                        
                        ?>
                        
                        <button type="button" class="btn btn-sm btn-{{ $class }} btn-bold">{{ $status }}</button>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route( 'payment-settings.save' ) }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Paytm Enviornment :</label>
                            <input type="hidden" name="key[active_gateway_type]" placeholder="Active gateway Type" value="paytm" class="form-control" >
                            <select class="form-control" name="key[paytm_env]" >
                                <option value="local" {{ ( ! empty( $data['paytm_env'] ) && $data['paytm_env'] == 'local' ) ? 'selected' : '' }} >Local</option>
                                <option value="production" {{ ( ! empty( $data['paytm_env'] ) && $data['paytm_env'] == 'production' ) ? 'selected' : '' }}>Production</option>
                            </select>
                            @csrf
                        </div>
                        <div class="col-lg-6">
                            <label>Paytm Merchant Id :</label>
                            <input type="text" name="key[paytm_merchant_id]" placeholder="Enter Paypal Merchant Id" value="{{ $data['paytm_merchant_id'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Paytm Merchant Key :</label>
                            <input type="text" name="key[paytm_merchant_key]" placeholder="Enter Paytm Merchant key" value="{{ $data['paytm_merchant_key'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
    
</div>    
@endsection

@section('pagespecificscripts')
    <script src="{{ asset('js/payment-gateway-settings.js')}}"></script>
@endsection