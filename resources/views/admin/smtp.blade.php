@extends('layouts.default')
@section('title', 'SMTP')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            SMTP </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                SMTP </a>
        </div>
    </div>
    
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
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
                    <i class="kt-font-brand flaticon-mail"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    SMTP
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions d-flex">
                        &nbsp;
                        @if ( !empty( $data ) && check_permission('smtp.test.mail') )
                            <form action="{{ route('smtp.test.mail') }}" method="get" id="sendMailForm">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="to_mail" class="form-control form-control-sm" placeholder="Send test mail" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-success  btn-icon-sm btn-sm " title="Send test mail" id="sendMailBtn">
                                            <i class="flaticon2-rocket-2"></i>
                                            Send test mail 
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('smtp.save') }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Mailer:</label>
                            <input type="text" name="ss_mailer" placeholder="Enter Mailer" value="{{ $data->ss_mailer ?? '' }}" class="form-control" >
                            <input type="hidden" name="edit_id" value="{{ $data->ss_id ?? '' }}">
                            @csrf
                        </div>
                        <div class="col-lg-4">
                            <label>Host:</label>
                            <input type="text" name="ss_host" placeholder="Enter Host" value="{{ $data->ss_host ?? '' }}" class="form-control" >
                        </div>
                        <div class="col-lg-4">
                            <label>Port:</label>
                            <input type="number" name="ss_port" placeholder="Enter Port" value="{{ $data->ss_port ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>User name:</label>
                            <input type="text" name="ss_uname" placeholder="Enter User name" value="{{ $data->ss_uname ?? '' }}" class="form-control" >
                        </div>
                        <div class="col-lg-4">
                            <label>Password:</label>
                            <input type="text" name="ss_pwd" placeholder="Enter Password" value="{{ $data->ss_pwd ?? '' }}" class="form-control" >
                        </div>
                        <div class="col-lg-4">
                            <label>Encryption:</label>
                            <input type="text" name="ss_encryption" placeholder="Enter Encryption" value="{{ $data->ss_encryption ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>From address:</label>
                            <input type="text" name="ss_from_address" placeholder="Enter From address" value="{{ $data->ss_from_address ?? '' }}" class="form-control" >
                        </div>
                        <div class="col-lg-6">
                            <label>From name:</label>
                            <input type="text" name="ss_from_name" placeholder="Enter From name" value="{{ $data->ss_from_name ?? '' }}" class="form-control" >
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
    <script src="{{ asset('js/smtp.js')}}"></script>
@endsection