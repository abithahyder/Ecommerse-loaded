@extends('layouts.default')
@section('title', 'Add product')
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Add product </h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="{{ route('product.list') }}" class="kt-subheader__breadcrumbs-link">
                Product management </a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a  class="kt-subheader__breadcrumbs-link">
                Add product </a>
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
    <form class="" action="{{ route('product.save') }}" enctype="multipart/form-data" id="admin_add_form" method="post" >
      
    <div class="kt-portlet form">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand flaticon-user-add"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Add product
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Name:</label>
                        <input type="text" name="p_name" placeholder="Enter name" value="{{ $data->p_name ?? '' }}" class="form-control" required>
                        <input type="hidden" name="edit_id" value="{{ $data->p_id ?? '' }}">
                        @csrf
                    </div>
                    <div class="col-lg-4">
                        <label>Category:</label>
                        <select class="form-control"  name="p_cat_parent_id" id="category">
                            <option value="" >-- Select category --</option>
                            @foreach ($categoryList as $item)
                                <option value="{{ $item->id }}" {{ (!empty($data->p_cat_parent_id) && $data->p_cat_parent_id == $item->id) ? 'selected' : ''}}>{{$item->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Sub Category:</label>
                        <select class="form-control" data-category="{{ $data->p_cat_id ?? '' }}" name="p_cat_id" id="subCategory">
                            <option value="" >-- Select sub category --</option>
                            <!-- @foreach ($subcategoryList as $itemm)
                                <option value="{{ $itemm->id }}" >{{$itemm->Product_category_name}}</option>
                            @endforeach -->
                                   </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Price:</label>
                        <input type="number" name="p_price" value="{{ $data->p_price ?? '' }}" placeholder="Enter price" class="form-control" >
                    </div>
                    <div class="col-lg-4">
                        <label>Sale Price:</label>
                        <input type="number" name="p_sale_price" value="{{ $data->p_sale_price ?? '' }}"  placeholder="Enter sale price" class="form-control" >
                    </div>
                    <div class="col-lg-4">
                        <label>Available Stock:</label>
                        
                        <input type="number" name="p_stock" value="{{ $data->p_stock ?? '' }}"  placeholder="Enter Available Stock" class="form-control" >
                    </div>
    
                    <div class="col-lg-4">
                        <label>Status:</label>
                        <select class="form-control"  name="p_status" >
                            <option value="" >-- Select status --</option>
                            <option value="active" {{ (!empty($data->p_status) && $data->p_status == 'active') ? 'selected' : '' }} >Active</option>
                            <option value="deactive" {{ (!empty($data->p_status) && $data->p_status == 'deactive') ? 'selected' : '' }} >De-Active</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label>Short Description:</label>
                        <textarea name="p_short_desc" class="form-control" cols="30" rows="3" placeholder="Enter description">{{ $data->p_short_desc ?? '' }}</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label>Description:</label>
                        <textarea name="p_desc" class="form-control" cols="30" rows="4" placeholder="Enter description">{{ $data->p_desc ?? '' }}</textarea>
                    </div>
                    <div class="col-lg-4">
                        <label>Available Pincodes:</label>
                        <div class="multipleSelection" id="selpinval">
            <div class="selectBox " 
                onclick="showCheckboxes()">
                <select class="form-control">
                    <option>Select options</option>
                </select >
                <div class="overSelect"></div>
            </div>
   
            <div id="checkBoxes">
            <label for="first">
            @php $pincodes =explode(',' ,$data->p_availability); @endphp
                    <input type="checkbox" name="pinselect[]" id="checkpinAll" value="0" {{ (!empty($data->p_availability) && (in_array("0",$pincodes))) ? 'checked' : '' }}/>
                    select All
                </label>
                @foreach($pin as $pin)
                
                 <label for="first">
                    <input type="checkbox" name="pinselect[]" id="itemvalues" value="{{$pin->dc_id}}" {{ (!empty($data->p_availability) && (in_array($pin->dc_id,$pincodes))) ? 'checked' : '' }}/>
                    
                    {{$pin->dc_postcode}}
                </label>
                @endforeach
           </div>
        </div>
       <input type="hidden" value="" id="pinvalues" name="p_availability"/>
                       
                    </div>
                    <div class="col-lg-4">
                        <label>Images:</label>
                        <input type="file" name="images[]" multiple class="form-control" >
                    </div>
                    
                </div>
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label class="kt-checkbox kt-checkbox--brand">
                            <input type="checkbox" id="multi-option" value="1" {{ (!empty($data->p_multi_option) && $data->p_multi_option == '1') ? 'checked' : '' }} name="p_multi_option">This product has multiple options, like diffrent sizes or colors
                            <span></span>
                        </label>
                    </div>
                </div>
                @if ( $data && $data->hasMedia('product') )
                    <h3 for="">Images</h3><br>
                    <div class="form-group row ">
                        @foreach ($data->getMedia('product') as $item)
                            <div class="col-md-2 p-2 image-relative image-delete" data-product="{{ $data->p_id }}" data-id="{{ $item->getKey() }}">
                                <div class="overlay-images">
                                    <span class="btn btn-danger btn-xs pull-right">
                                        <i class="fa fa-trash fa-sm"></i>
                                    </span>
                                </div>
                                <img src="../uploads/{{ $item->getDiskPath()}}" class="img-thumbnail h-auto" style="" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @if ( !empty($data->p_multi_option) && $data->p_multi_option == '1' )
        <div class="kt-portlet kt-portlet--collapsed" data-ktportlet="true" id="kt_portlet_tools_1">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Edit Variants
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
                    <div class="">
                        @if(!empty($data->p_multi_option) && $data->p_multi_option == '1' )
                            <?php
                                $skuList = $data->sku;                                                                 
                            ?>
                            @foreach($data->skuOption as $key => $item)
                              
                            <?php
                                                      
                             $value = App\sku_value::leftJoin('variant_options','sku-values.skuv_vo_id','variant_options.vo_id')->where(['skuv_sku_id'=> $item->skuv_sku_id, 'skuv_p_id' => $item->skuv_p_id ])->pluck('variant_options.vo_name')->toArray();
                            ?>                  
                                <div class="form-group row remove-option">
                                    <div class="col-lg-2 col-form-label-lg">
                                        <label class="ml-5"> {{ implode( ',', $value ) }}  </label>
                                    </div>
                                    <div class="col-lg-3">
                                        <label > Price </label>
                                        <input name="price{{$item->skuv_sku_id}}" type="number" value="{{ $skuList[$key]->sku_price }}" class="form-control" placeholder="Enter price" >
                                    </div>
                                    <div class="col-lg-2">
                                        <label > Qty </label>
                                        <input name="qty{{$item->skuv_sku_id}}" type="number" value="{{ $skuList[$key]->sku_qty }}" class="form-control" placeholder="Enter qty" >
                                    </div>
                                    <div class="col-lg-3">
                                        <label > SKU </label>
                                        <input name="sku{{$item->skuv_sku_id}}" type="text" value="{{ $skuList[$key]->sku_name }}" class="form-control" placeholder="Enter sku" >
                                        <input type="hidden" name="sku_id" value="{{ $item->skuv_sku_id }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label>  </label>
                                        <button type="button" title="Save" class="btn-sm btn btn-label-brand  btn-bold update-sku" data-id="{{ $item->skuv_sku_id }}"><i class="la la-save"></i></button>
                                        <button type="button" title="Remove" class="btn btn-label-danger btn-sm delete-sku" data-id="{{ $item->skuv_sku_id }}">  <i class="la la-trash"></i> </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <button type="button" class="btn btn-outline-secondary btn-md more-option">Add more option</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="kt-portlet variants-part d-none">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Variants
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <label>Options:</label>
                <div class="option-list">
                </div>
                <div class="form-group row">
                    <div class="col-lg-4">
                        <button type="button" id="add-more-option" class="btn-sm btn btn-label-brand  btn-bold">
                            <i class="la la-plus"></i>Add
                        </button>
                    </div>
                </div>
                <div class="kt-separator kt-separator--border-dashed kt-separator--space-sm kt-separator--portlet-fit"></div>
                <label>Previews:</label>
                <div class="preview-list">
                    
                </div>
            </div>
        </div>
        <div class="kt-portlet footer-action">
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>    
@endsection
@section('pagespecificscripts')
    <script src="{{ asset('js/product/add-product.js')}}"></script>
@endsection