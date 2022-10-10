@extends('layouts.default')
@section('title','Add permission')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Add permission </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a  class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  href="{{ route('group') }}" class="kt-subheader__breadcrumbs-link">
                User Group
            </a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Add role
            </a>
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
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Add permission
                </h3>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet add_new_admin_form" >
            
            <!--begin::Form-->
            <form class="kt-form validation_form" action="{{ route('savegroup') }}"  method="post" >
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>Role Name:</label>
                            @csrf
                            <input type="text" name="ug_name" class="form-control" value="{{ $data->ug_name ?? '' }}" placeholder="Enter role name">
                            <input type="hidden" name="ug_permissions" class="form-control" value="{{ $data->ug_permissions ?? '' }}" placeholder="Enter role name">
                            <input type="hidden" name="edit_id" value="{{ $data->ug_id ?? '' }}">
                        </div>
                        @csrf
                        <table class="table table-striped- table-hover" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>
                                        Enable/Disable <br> 
                                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                            <input type="checkbox"  id="checkAll">All Check
                                            <span></span>
                                        </label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($array as $key => $value)
                                    <tr>
                                        <td>{{$value}}</td>
                                        <td>
                                                <label class="kt-checkbox  kt-checkbox--bold kt-checkbox--success " style="margin-bottom: 1rem !important; ">
                                                    @if ( isset( $data['ug_permissions'] ) )
                                                        <input type="checkbox" class="permissionCheckbox" data-val={{ $value }} {{ ( !empty( in_array( $value, explode( ',', $data['ug_permissions'] ) ) ) ) ? 'checked' : ''  }} name="privileges[{{$value}}]" value="true">
                                                    @else
                                                        <input type="checkbox" class="permissionCheckbox" data-val={{ $value }} name="privileges[{{$value}}]" value="true">
                                                    @endif
                                                    <span></span>
                                                
                                                </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    </div>
</div>    
@endsection
@section('pagespecificscripts')
    <script src="{{ asset('js/add-role.js')}}"></script>
@endsection