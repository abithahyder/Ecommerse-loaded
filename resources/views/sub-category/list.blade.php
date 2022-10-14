@extends('layouts.default')
@section('title' ,'Category management')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            SubCategory management </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                SubCategory management </a>
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
                    <i class="kt-font-brand flaticon2-user"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                   Sub Category management
                </h3>
            </div>
            @if(check_permission('up_create','user_manegement'))
            <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <a href="{{ route('subcategory.add') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            {{-- <button class="btn btn-brand btn-elevate btn-icon-sm add_admin"> --}}
                                <i class="la la-plus"></i>
                                New Sub category
                            {{-- </button> --}}
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!--begin::Portlet-->
            <!--end::Portlet-->
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover" id="admin_list_table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Images</th>
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
    <script src="{{ asset('js/subcategory-list.js')}}"></script>
@endsection