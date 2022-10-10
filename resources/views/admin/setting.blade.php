@extends('layouts.default')
@section('title', 'Setting')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Setting </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Setting </a>
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
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-user-add"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Setting
                </h3>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet add_new_admin_form">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('setting.save') }}" id="admin_add_form" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Currency:</label>
                            <input type="text" name="currency" placeholder="Enter Currency" value="{{ $data['currency'] ?? '' }}" class="form-control" >
                            <input type="hidden" name="edit_id" value="{{ $data['id'] ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label>Maintenance mode:</label>
                            <br>
                            @csrf
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                <input type="checkbox" {{ (!empty($data['maintenance_mode']) && $data['maintenance_mode'] == 1) ? 'checked' : ''  }}  name="maintenance_mode" value="1">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Notification day:</label>
                            <input type="text" name="notification_day" placeholder="Enter notification days" value="{{ $data['notification_day'] ?? '' }}" class="form-control" >
                        </div>
                        
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary add_admin">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>    
@endsection