@extends('layouts.default')
@section('title' ,'Delivery Charge')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Delivery Charge </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Delivery Charge </a>
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
                    Delivery Charge
                </h3>
            </div>
            @if(check_permission('up_create','user_manegement'))
            <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <button class="btn btn-brand btn-elevate btn-icon-sm add_admin">
                            <i class="la la-plus"></i>
                            Add new
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!--begin::Portlet-->
        <div class="kt-portlet add_new_admin_form" style="display:{{  ($errors->any() || !empty($data)) ? 'block' : 'none'  }}">
            
            <!--begin::Form-->
            <form class="kt-form" action="{{ route('setting.delivery.charge.save') }}" id="admin_add_form" method="post" enctype="multipart/form-data" >
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="form-group">
                            <label>Postcode:</label>
                            @csrf
                            <input type="number" name="dc_postcode" class="form-control" value="{{ $data->dc_postcode ?? '' }}" placeholder="Enter postcode">
                            <input type="hidden" name="edit_id" value="{{ $data->dc_id ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Price:</label>
                            <input type="number" name="dc_price" class="form-control" value="{{ $data->dc_price ?? '' }}" placeholder="Enter price">
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
                        <th>#ID</th>
                        <th>Postcode</th>
                        <th>Price</th>
                        {{-- <th>Default</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)
                        <tr>
                            <td> {{ $item['dc_id'] }}</td>
                            <td> {{ $item['dc_postcode'] }}</td>
                            <td> {{ $item['dc_price'] }}</td>
                            {{-- <td> {{ $item['dc_is_default'] }}</td> --}}
                            <td>  
                                <a href="{{ route('setting.delivery.charge.edit',$item['dc_id']) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                                    <i class="la la-edit"></i>
                                </a>
                                <a href="{{ route('setting.delivery.charge.delete',$item['dc_id']) }}" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                                    <i class="la la-trash"></i>
                                </a>
                             </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>    
@endsection
@section('pagespecificscripts')
    <script src="{{ asset('js/setting/delivery.js')}}"></script>
@endsection