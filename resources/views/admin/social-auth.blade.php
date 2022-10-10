@extends('layouts.default')
@section('title', 'Social Authentication')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Social Authentication </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Social Authentication </a>
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
                    <i class="kt-font-brand flaticon-letter-g"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Google Authentication
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('social.save') }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Google Client Id:</label>
                            <input type="text" name="key[google_client_id]" placeholder="Enter Google Client Id" value="{{ $data['google_client_id'] ?? '' }}" class="form-control" >
                            <input type="hidden" name="edit_id" placeholder="Enter Id" value="{{ $data->id ?? '' }}" class="form-control" >
                            @csrf
                        </div>
                        <div class="col-lg-6">
                            <label>Google Client Secret Key:</label>
                            <input type="text" name="key[google_client_secret]" placeholder="Enter Google Client Secret Key" value="{{ $data['google_client_secret'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Google Redirect Url:</label>
                            <input type="text" name="key[google_redirect_url]" placeholder="Enter Google Redirect Url" value="{{ $data['google_redirect_url'] ?? '' }}" class="form-control" >
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
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-facebook-letter-logo"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Facebook Authentication
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('social.save') }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Facebook Client Id:</label>
                            <input type="text" name="key[fb_client_id]" placeholder="Enter Facebook Client Id" value="{{ $data['fb_client_id'] ?? '' }}" class="form-control" >
                            @csrf
                        </div>
                        <div class="col-lg-6">
                            <label>Facebook Client Secret Key:</label>
                            <input type="text" name="key[fb_client_secret]" placeholder="Enter Facebook Client Secret Key" value="{{ $data['fb_client_secret'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Facebook Redirect Url:</label>
                            <input type="text" name="key[fb_redirect_url]" placeholder="Enter Facebook Redirect Url" value="{{ $data['fb_redirect_url'] ?? '' }}" class="form-control" >
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
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-linkedin-logo"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    LinkedIn Authentication
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('social.save') }}" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>LinkedIn Client Id:</label>
                            <input type="text" name="key[linkedin_client_id]" placeholder="Enter LinkedIn Client Id" value="{{ $data['linkedin_client_id'] ?? '' }}" class="form-control" >
                            @csrf
                        </div>
                        <div class="col-lg-6">
                            <label>LinkedIn Client Secret Key:</label>
                            <input type="text" name="key[linkedin_client_secret]" placeholder="Enter LinkedIn Client Secret Key" value="{{ $data['linkedin_client_secret'] ?? '' }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>LinkedIn Redirect Url:</label>
                            <input type="text" name="key[linkedin_redirect_url]" placeholder="Enter LinkedIn Redirect Url" value="{{ $data['linkedin_redirect_url'] ?? '' }}" class="form-control" >
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