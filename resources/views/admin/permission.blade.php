@extends('layouts.default')
@section('title','City management')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Add permission </h3>
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
            <form class="kt-form" action="{{ route('permissionSave') }}" id="admin_add_form" method="post" >
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <input type="hidden" name="user_id" value="{{ $id ?? '' }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        @csrf
                        <table class="table table-striped- table-hover" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>View</th>
                                    <th>Create</th>
                                    <th>Edit</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td>Admin</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                            <input type="checkbox" @if(in_array($item, $user)) checked  @endif name="privileges[]" value="{{ $item }}"> {{ $item }}
                                            <span></span>
                                            </label>
                                        </td>
                                    </tr>
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
