@extends('layouts.default')
@section('title', 'Add Coupon')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Add Coupon </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="{{ route('coupon.list') }}" class="kt-subheader__breadcrumbs-link">
                Coupon management </a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Add Coupon </a>
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
                    Add Coupon
                </h3>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet add_new_admin_form">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('coupon.save') }}" enctype="multipart/form-data" id="admin_add_form" method="post" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Title:</label>
                            <input type="text" name="cm_title" placeholder="Enter title" value="{{ $data->cm_title ?? '' }}" class="form-control" required>
                            <input type="hidden" name="edit_id" value="{{ $data->cm_id ?? '' }}">
                            @csrf
                        </div>
                        <div class="col-lg-4">
                            <label>Code:</label>
                            <input type="text" name="cm_code" placeholder="Enter code" value="{{ $data->cm_code ?? '' }}" class="form-control" required>
                        </div>
                        <div class="col-lg-4">
                            <label>Discount type:</label>
                            <select class="form-control"  name="cm_discount_type" >
                                <option value="" >-- Select type --</option>
                                <option value="percentage" {{ (!empty($data->cm_discount_type) && $data->cm_discount_type == 'percentage') ? 'selected' : '' }} >Percentage discount </option>
                                <option value="amount" {{ (!empty($data->cm_discount_type) && $data->cm_discount_type == 'amount') ? 'selected' : '' }} >Amount discount</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Start date:</label>
                            <input type="date" name="cm_start_date" value="{{ $data->cm_start_date ?? null }}" class="form-control" >
                        </div>
                        <div class="col-lg-4">
                            <label>Expiry date:</label>
                            <input type="date" name="cm_expiry_date" value="{{ $data->cm_expiry_date ?? null }}" class="form-control" >
                        </div>
                        <div class="col-lg-4">
                            <label>Amount:</label>
                            <input type="number" name="cm_amount" value="{{ $data->cm_amount ?? null }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Usage limit:</label>
                            <input type="number" name="cm_usage_limit" value="{{ $data->cm_usage_limit ?? null }}" class="form-control" >
                        </div>
                        <div class="col-lg-4">
                            <label>Free shipping:</label>
                            <select class="form-control"  name="cm_free_shipping" >
                                <option value="yes" {{ (!empty($data->cm_free_shipping) && $data->cm_free_shipping == 'yes') ? 'selected' : '' }} >Yes</option>
                                <option value="no" {{ (!empty($data->cm_free_shipping) && $data->cm_free_shipping == 'no') ? 'selected' : '' }} >No</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Minimum amount:</label>
                            <input type="number" name="cm_minimum_amount" value="{{ $data->cm_minimum_amount ?? null }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label>Maximum amount:</label>
                            <input type="number" name="cm_maximum_amount" value="{{ $data->cm_maximum_amount ?? null }}" class="form-control" >
                        </div>
                        <div class="col-lg-4">
                            <label>Description:</label>
                            <textarea name="cm_description" class="form-control" cols="30" rows=""> {{ $data->cm_description ?? null }} </textarea>
                        </div>
                        <div class="col-lg-4">
                            <label>Status:</label>
                            <select class="form-control"  name="cm_status" >
                                <option value="active" {{ (!empty($data->cm_status) && $data->cm_status == 'active') ? 'selected' : '' }} >Active</option>
                                <option value="deactive" {{ (!empty($data->cm_status) && $data->cm_status == 'deactive') ? 'selected' : '' }} >De-active</option>
                            </select>
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
@section('pagespecificscripts')
    <script src="{{ asset('js/coupon/add.js')}}"></script>
@endsection