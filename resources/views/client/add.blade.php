@extends('layouts.default')
@section('title', 'Edit Client')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Edit user </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="{{ route('client.list') }}" class="kt-subheader__breadcrumbs-link">
                User management </a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Edit User </a>
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
                    Edit User
                </h3>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet add_new_admin_form">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('client.save') }}" enctype="multipart/form-data" id="admin_add_form" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Name:</label>
                            <input type="text" name="c_name" placeholder="Enter name" value="{{ $data->c_name ?? '' }}" class="form-control" required>
                            <input type="hidden" name="edit_id" value="{{ $data->c_id ?? '' }}">
                            @csrf
                        </div>
                        <div class="col-lg-4">
                            <label>Email:</label>
                            <input type="text" name="c_email" placeholder="Enter name" value="{{ $data->c_email ?? '' }}" class="form-control" required>
                        </div>
                        <div class="col-lg-4">
                            <label>status:</label>
                            <select class="form-control"  name="c_status" >
                                <option value="" >-- Select status --</option>
                                <option value="active" {{ (!empty($data->c_status) && $data->c_status == 'active') ? 'selected' : '' }} >Active</option>
                                <option value="deactive" {{ (!empty($data->c_status) && $data->c_status == 'deactive') ? 'selected' : '' }} >De-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Image:</label>
                            <input type="file" name="user_image"  class="form-control" >
                            @if ( $data && $data->hasMedia('client'))
                                <img src="../uploads/{{ $data->firstMedia('client')->getDiskPath()}}" class="h-auto img-thumbnail"  style="width: 100px !important; " alt="">
                          @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Password:</label>
                            <input type="password" name="pwd"  class="form-control" >
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