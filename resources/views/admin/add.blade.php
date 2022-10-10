@extends('layouts.default')
@section('title' ,'User management')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            User management </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                User management </a>
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
                    User management
                </h3>
            </div>
            @if(check_permission('adminList'))
            <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <button class="btn btn-brand btn-elevate btn-icon-sm add_admin">
                            <i class="la la-plus"></i>
                            New Admin
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet add_new_admin_form" style="display:{{  ($errors->any() || !empty($user)) ? 'block' : 'none'  }}">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('saveAdmin') }}" id="admin_add_form" method="post" >
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>Full Name:</label>
                            @csrf
                            <input type="text" name="name" class="form-control" value="{{ $user->name ?? '' }}" placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label>Email address:</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}" placeholder="Enter email">
                            <input type="hidden" name="edit_id" value="{{ $user->id ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password:</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Enter password">
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1">Status:</label>
                            <select class="form-control"  name="status" >
                                <option value="active" {{ (!empty($user->status) && $user->status == 'active') ? 'selected' : '' }} >Active</option>
                                <option value="inactive" {{ (!empty($user->status) &&$user->status == 'inactive') ? 'selected' : '' }}>InActive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1">Select Group:</label>
                            <select class="form-control"  name="group" >
                                @foreach ($group as $key )
                                    <option value="{{ $key['ug_id'] }}" {{ (!empty($user->user_group) && $user->user_group == $key['ug_id']) ? 'selected' : '' }} >{{ $key['ug_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary add_admin">Cancel</button>
                    </div>
                </div>
            </form>

            <!--end::Form-->
        </div>

            <!--end::Portlet-->
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover" id="admin_list_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Group</th>
                        <th>status</th>
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
    <script src="{{ asset('js/admin-list.js')}}"></script>
@endsection