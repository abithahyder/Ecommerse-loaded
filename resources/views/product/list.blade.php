@extends('layouts.default')
@section('title' ,'Product management')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Product management </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            
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
    <div class="kt-portlet kt-portlet--collapsed" data-ktportlet="true" id="kt_portlet_tools_1">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Filter
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-group">
                    <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md" aria-describedby="tooltip_ufkbe8nwzo"><i class="la la-angle-down"></i></a>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body"  style="">
            <div class="kt-portlet__content">
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Category:</label>
                        <select class="form-control category-filter"  name="category" id="category">
                            <option value="" >-- Select category --</option>
                            @foreach ($categoryList as $item)
                                <option value="{{ $item->id }}" >{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Sub Category:</label>
                        <select class="form-control category-filter"  name="sub_category" id="subcategory" >
                            <option value="" >-- Select sub category --</option>
                            
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-user"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Product management
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <a href="{{ route('product.add') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New product
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
            <!--end::Portlet-->
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover" id="admin_list_table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Main category</th>
                        <th>Sub category</th>
                        <th>Price</th>
                        <th>Sale price</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>    
@endsection
@section('pagespecificscripts')
    <script src="{{ asset('js/product/product-list.js')}}"></script>
@endsection