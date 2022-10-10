@extends('layouts.default')
@section('title', 'Social Authentication')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            SMS Authentication </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                SMS Authentication </a>
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
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-multimedia"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    SMS Settings
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <form action="{{ route('sendSms') }}" method="post" id="sendSmsForm" style="margin-bottom: 17px">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name="mobile_number" class="form-control form-control-sm" placeholder="Enter Mobile Number" required>
                                    @csrf
                                </div>
                                <div class="col-sm-6">
                                    <button class="btn btn-success  btn-icon-sm btn-sm " title="Send test mail" id="sendMailBtn">
                                        <i class="flaticon2-rocket-2"></i>
                                        Send Test SMS 
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route( 'sms-settings.save' ) }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>SMS Authentication Key : </label>
                            <input type="text" name="key[sms_authentication_key]" placeholder="Enter SMS Authentication Key" value="{{ $data['sms_authentication_key'] ?? '' }}" class="form-control" >
                            <span class="form-text text-muted">SMS Authentication key for MSG91.</span>
                            @csrf
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