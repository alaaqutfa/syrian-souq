@extends('seller.layouts.app')

@section('panel_content')
    <div class="page-content mx-0">
        <div class="aiz-titlebar mt-2 mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3">{{ translate('Add Your Product') }}</h1>
                </div>
                <div class="col text-right">
                    <a class="btn btn-xs btn-soft-primary" href="javascript:void(0);" onclick="clearTempdata()">
                        {{ translate('Clear Tempdata') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Meassages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Data type -->
        <input type="hidden" id="data_type" value="physical">

        <form class="" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data"
            id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-12">
                    @csrf
                    <input type="hidden" name="added_by" value="seller">
                    @foreach ($categories as $category)
                        @php($tax_category = $category->id)
                        <input type="hidden" name="category_id" value="{{ $category->id }}" />
                    @endforeach
                    <!-- General -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('General') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>
                                </div>
                            </div>
                            <!-- Categories -->
                            <div class="Categories">
                                @php
                                    $hasSubCategories = false;
                                @endphp
                                @foreach ($categories as $category)
                                    @if ($category->childrenCategories->isNotEmpty())
                                        @php
                                            $hasSubCategories = true;
                                        @endphp
                                    @break
                                @endif
                            @endforeach
                            @if ($hasSubCategories)
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">
                                        {{ translate('Product Category') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        @foreach ($categories as $category)
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('backend.product.products.child_category', [
                                                    'child_category' => $childCategory,
                                                ])
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Short Description -->
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('short description') }}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="aiz-text-editor"></textarea>
                            </div>
                        </div>
                        <!-- Brand -->
                        @if (\App\Models\Brand::count() > 0)
                            <div class="form-group row" id="brand">
                                <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <!-- Minimum Purchase Qty -->
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Minimum Purchase Qty') }}</label>
                            <div class="col-md-8">
                                <input type="number" lang="en" class="form-control" name="min_qty" value="1"
                                    min="1" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Images -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Images') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Gallery Images') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image"
                                    data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="photos" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('These images are visible in product details page gallery. Minimum dimensions required: 900px width X 900px height.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Thumbnail Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="thumbnail_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('This image is visible in all product box. Minimum dimensions required: 195px width X 195px height. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="meta_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product price + stock -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product price + stock') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Unit price') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('Unit price') }}" name="unit_price"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Cost price') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('Cost price') }}" name="wholesale_price"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 control-label"
                                for="start_date">{{ translate('Discount Date Range') }} </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control aiz-date-range" name="date_range"
                                    placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                    data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Discount') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="number" lang="en" min="0" value="0" step="0.01"
                                    placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control aiz-selectpicker" name="discount_type">
                                    <option value="amount">{{ translate('Flat') }}</option>
                                    <option value="percent">{{ translate('Percent') }}</option>
                                </select>
                            </div>
                        </div>

                        <div id="show-hide-div">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0"
                                        step="1" placeholder="{{ translate('Quantity') }}"
                                        name="current_stock" class="form-control" required>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{ translate('SKU') }}
                                </label>
                                <div class="col-md-6">
                                    <input type="text" placeholder="{{ translate('SKU') }}" name="sku"
                                        class="form-control">
                                </div>
                            </div> --}}
                        </div>
                        {{-- <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{ translate('External link') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('External link') }}" name="external_link"
                                    class="form-control">
                                <small class="text-muted">{{ translate('Leave it blank if you do not use external site
                                    link') }}</small>
                            </div>
                        </div> --}}
                        {{-- <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{ translate('External link button text') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('External link button text') }}"
                                    name="external_link_btn" class="form-control">
                                <small class="text-muted">{{ translate('Leave it blank if you do not use external site
                                    link') }}</small>
                            </div>
                        </div> --}}
                        <div class="sku_combination" id="sku_combination">

                        </div>
                    </div>
                </div>
                <!-- Product Description -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea class="aiz-text-editor w-100" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- VAT & Tax -->
                @foreach (\App\Models\Tax::where('tax_status', 1)->where('type', 'physical')->where('tax_category', $tax_category)->get() as $tax)
                    <div class="tax_{{ $tax->id }}">
                        <input type="hidden" name="tax_name" value="{{ $tax->name }}">
                        <input type="hidden" name="tax_id[]" value="{{ $tax->id }}">
                        <input type="hidden" name="tax_value[]" value="{{ $tax->tax_value }}">
                        <input type="hidden" name="tax_types[]" value="{{ $tax->tax_type }}">
                        {{-- amount , percent --}}
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4 d-none">
                <!-- SEO Meta Tags -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title"
                                    placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <script>
                            document.getElementById('product_name').addEventListener('input', function() {
                                document.getElementById('meta_title').value = this.value;
                            });
                        </script>
                    </div>
                </div>

                <!-- Shipping Configuration -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{ translate('Shipping Configuration') }}
                        </h5>
                    </div>

                    <div class="card-body">
                        @if (get_setting('shipping_type') == 'product_wise_shipping')
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Free Shipping') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="shipping_type" value="free"
                                            id="freeShippingRadio" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Flat Rate') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="shipping_type" value="flat_rate">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="flat_rate_shipping_div" style="display: none">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Shipping cost') }}</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0"
                                            step="0.01" placeholder="{{ translate('Shipping cost') }}"
                                            name="flat_shipping_cost" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    class="col-md-6 col-from-label">{{ translate('Is Product Quantity Multiply') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="is_quantity_multiplied" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <p>
                                {{ translate('Shipping configuration is maintained by Admin.') }}
                            </p>
                        @endif
                    </div>
                </div>

                <script>
                    // تعيين الخيار الافتراضي إلى "Free Shipping"
                    document.addEventListener("DOMContentLoaded", function() {
                        var freeShippingRadio = document.getElementById("freeShippingRadio");
                        if (freeShippingRadio) {
                            freeShippingRadio.checked = true; // تعيين الخيار الافتراضي
                        }
                    });
                </script>

                <!-- Low Stock Quantity Warning -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Low Stock Quantity Warning') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Quantity') }}
                            </label>
                            <input type="number" name="low_stock_quantity" value="1" min="0"
                                step="1" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Stock Visibility State -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{ translate('Stock Visibility State') }}
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="quantity"
                                        id="stockQuantity" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                class="col-md-6 col-from-label">{{ translate('Show Stock With Text Only') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="text">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{ translate('Hide Stock') }}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="hide">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // ضبط الخيار الافتراضي إلى "Show Stock Quantity"
                    document.addEventListener("DOMContentLoaded", function() {
                        var stockQuantityRadio = document.getElementById("stockQuantity");
                        if (stockQuantityRadio) {
                            stockQuantityRadio.checked = true; // تفعيل الخيار الافتراضي
                        }
                    });
                </script>

                <!-- Cash On Delivery -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Cash On Delivery') }}</h5>
                    </div>
                    <div class="card-body">
                        @if (get_setting('cash_payment') == '1')
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="cash_on_delivery" value="1"
                                            id="cashOnDeliveryCheckbox" checked="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <p>
                                {{ translate('Cash On Delivery activation is maintained by Admin.') }}
                            </p>
                        @endif
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var cashCheckbox = document.getElementById("cashOnDeliveryCheckbox");
                        if (cashCheckbox) {
                            cashCheckbox.checked = true;
                        }
                    });
                </script>

                <!-- Estimate Shipping Time -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Estimate Shipping Time') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{ translate('Shipping Days') }}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="est_shipping_days" min="1"
                                    step="1" placeholder="{{ translate('Shipping Days') }}"
                                    id="shippingDaysInput">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"
                                        id="inputGroupPrepend">{{ translate('Days') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // ضبط قيمة وقت الشحن افتراضيًا إلى 1
                    //هذا السكريبت لكي اعالج الحالة في الفرونت بدلاً من الباك
                    document.addEventListener("DOMContentLoaded", function() {
                        var shippingInput = document.getElementById("shippingDaysInput");
                        if (shippingInput) {
                            shippingInput.value = 1;
                        }
                    });
                </script>

            </div>
            <div class="col-12">
                <div class="mar-all text-right mb-2">
                    <button type="submit" name="button" value="publish"
                        class="btn btn-primary">{{ translate('Upload Product') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('modal')
<!-- Frequently Bought Product Select Modal -->
@include('modals.product_select_modal')
@endsection

@section('script')
<!-- Treeview js -->
<script src="{{ static_asset('assets/js/hummingbird-treeview.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#treeview").hummingbird();

        $('#treeview input:checkbox').on("click", function() {
            let $this = $(this);
            if ($this.prop('checked') && ($('#treeview input:radio:checked').length == 0)) {
                let val = $this.val();
                $('#treeview input:radio[value=' + val + ']').prop('checked', true);
            }
        });
    });

    $("[name=shipping_type]").on("change", function() {
        $(".product_wise_shipping_div").hide();
        $(".flat_rate_shipping_div").hide();
        if ($(this).val() == 'product_wise') {
            $(".product_wise_shipping_div").show();
        }
        if ($(this).val() == 'flat_rate') {
            $(".flat_rate_shipping_div").show();
        }

    });

    function add_more_customer_choice_option(i, name) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{ route('seller.products.add-more-choice-option') }}',
            data: {
                attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                                                        <div class="form-group row">\
                                                            <div class="col-md-3">\
                                                                <input type="hidden" name="choice_no[]" value="' + i +
                    '">\
                                                                <input type="text" class="form-control" name="choice[]" value="' +
                    name +
                    '" placeholder="{{ translate('Choice Title') }}" readonly>\
                                                            </div>\
                                                            <div class="col-md-8">\
                                                                <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' +
                    i + '[]" multiple>\
                                                                    ' + obj + '\
                                                                </select>\
                                                            </div>\
                                                        </div>');
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });


    }

    $('input[name="colors_active"]').on('change', function() {
        if (!$('input[name="colors_active"]').is(':checked')) {
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        } else {
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice", function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });

    // $('input[name="name"]').on('keyup', function() {
    //     update_sku();
    // });

    function delete_row(em) {
        $(em).closest('.form-group row').remove();
        update_sku();
    }

    function delete_variant(em) {
        $(em).closest('.variant').remove();
    }

    function update_sku() {
        $.ajax({
            type: "POST",
            url: '{{ route('seller.products.sku_combination') }}',
            data: $('#choice_form').serialize(),
            success: function(data) {
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.sectionFooTable('#sku_combination');
                if (data.trim().length > 1) {
                    $('#show-hide-div').hide();
                } else {
                    $('#show-hide-div').show();
                }
            }
        });
    }

    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
        $.each($("#choice_attributes option:selected"), function() {
            add_more_customer_choice_option($(this).val(), $(this).text());
        });
        update_sku();
    });

    function fq_bought_product_selection_type() {
        var productSelectionType = $("input[name='frequently_bought_selection_type']:checked").val();
        if (productSelectionType == 'product') {
            $('.fq_bought_select_product_div').removeClass('d-none');
            $('.fq_bought_select_category_div').addClass('d-none');
        } else if (productSelectionType == 'category') {
            $('.fq_bought_select_category_div').removeClass('d-none');
            $('.fq_bought_select_product_div').addClass('d-none');
        }
    }

    function showFqBoughtProductModal() {
        $('#fq-bought-product-select-modal').modal('show', {
            backdrop: 'static'
        });
    }

    function filterFqBoughtProduct() {
        var searchKey = $('input[name=search_keyword]').val();
        var fqBroughCategory = $('select[name=fq_brough_category]').val();
        $.post('{{ route('seller.product.search') }}', {
            _token: AIZ.data.csrf,
            product_id: null,
            search_key: searchKey,
            category: fqBroughCategory,
            product_type: "physical"
        }, function(data) {
            $('#product-list').html(data);
            AIZ.plugins.sectionFooTable('#product-list');
        });
    }

    function addFqBoughtProduct() {
        var selectedProducts = [];
        $("input:checkbox[name=fq_bought_product_id]:checked").each(function() {
            selectedProducts.push($(this).val());
        });

        var fqBoughtProductIds = [];
        $("input[name='fq_bought_product_ids[]']").each(function() {
            fqBoughtProductIds.push($(this).val());
        });

        var productIds = selectedProducts.concat(fqBoughtProductIds.filter((item) => selectedProducts.indexOf(item) <
            0))

        $.post('{{ route('seller.get-selected-products') }}', {
            _token: AIZ.data.csrf,
            product_ids: productIds
        }, function(data) {
            $('#fq-bought-product-select-modal').modal('hide');
            $('#selected-fq-bought-products').html(data);
            AIZ.plugins.sectionFooTable('#selected-fq-bought-products');
        });
    }
</script>

@include('partials.product.product_temp_data')
@endsection
