@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Update Your Product') }}</h1>
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

    <form class="" action="{{ route('seller.digitalproducts.update', $product->id) }}" method="POST"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-12">
                @csrf
                <input name="_method" type="hidden" value="Post">
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="lang" value="{{ $lang }}">
                <input type="hidden" name="category_id" value="{{ $product->category_id }}" />
                <!-- General -->
                <div class="card">
                    <ul class="nav nav-tabs nav-fill language-bar">
                        @foreach (get_all_active_language() as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
                                    href="{{ route('seller.digitalproducts.edit', ['id' => $product->id, 'lang' => $language->code]) }}">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                        height="11" class="mr-1">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="card-body">
                        <!-- Name -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{ translate('Product Name') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ translate('Product Name') }}"
                                    value="{{ $product->getTranslation('name', $lang) }}" required>
                            </div>
                        </div>
                        <!-- Categories -->
                        <div class="Categories">
                            @php
                                $hasSubCategories = false;
                                $old_categories = $product->categories()->pluck('category_id')->toArray();
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
                            @php
                                $old_category_id = $product->categories()->pluck('category_id')->first();
                            @endphp
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
                            <textarea name="meta_description" rows="8" class="aiz-text-editor">{{ $product->meta_description }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Product Images -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Images') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{ translate('Product File') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group" data-toggle="aizuploader" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="file_name" class="selected-files"
                                    value="{{ $product->file_name }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="signinSrEmail">{{ translate('Gallery Images') }}
                            <small>(600x600)</small></label>
                        <div class="col-lg-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="photos" value="{{ $product->photos }}"
                                    class="selected-files" required>
                            </div>
                            <div class="file-preview box sm">
                            </div>
                            <small
                                class="text-muted">{{ translate('These images are visible in product details page gallery. Use 600x600 sizes images.') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label" for="signinSrEmail">{{ translate('Thumbnail Image') }}
                            <small>(300x300)</small></label>
                        <div class="col-lg-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}"
                                    class="selected-files" required>
                            </div>
                            <div class="file-preview box sm">
                            </div>
                            <small
                                class="text-muted">{{ translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label"
                            for="signinSrEmail">{{ translate('Meta Image') }}</label>
                        <div class="col-lg-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image"
                                data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="meta_img" value="{{ $product->meta_img }}"
                                    class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Price -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Price') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{ translate('Unit price') }}</label>
                        <div class="col-lg-9">
                            <input type="text" placeholder="{{ translate('Unit price') }}" name="unit_price"
                                class="form-control" value="{{ $product->unit_price }}" required>
                        </div>
                    </div>

                    @php
                        $date_range = '';
                        if ($product->discount_start_date) {
                            $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                            $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                            $date_range = $start_date . ' to ' . $end_date;
                        }
                    @endphp

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label"
                            for="start_date">{{ translate('Discount Date Range') }}</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control aiz-date-range" value="{{ $date_range }}"
                                name="date_range" placeholder="{{ translate('Select Date') }}"
                                data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to "
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{ translate('Discount') }}</label>
                        <div class="col-lg-6">
                            <input type="number" lang="en" min="0" step="0.01"
                                placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                value="{{ $product->discount }}" required>
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control aiz-selectpicker" name="discount_type" required>
                                <option value="amount" <?php if ($product->discount_type == 'amount') {
                                    echo 'selected';
                                } ?>>{{ translate('Flat') }}</option>
                                <option value="percent" <?php if ($product->discount_type == 'percent') {
                                    echo 'selected';
                                } ?>>{{ translate('Percent') }}</option>
                            </select>
                        </div>
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
                        <div class="col-lg-12">
                            <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- VAT & Tax -->
            @foreach (\App\Models\Tax::where('tax_status', 1)->where('type', 'digital')->where('tax_category', $product->category_id)->get() as $tax)
                <div class="tax_{{ $tax->id }}">
                    <input type="hidden" name="tax_name" value="{{ $tax->name }}">
                    <input type="hidden" name="type" value="{{ $tax->type }}">
                    <input type="hidden" name="tax_id[]" value="{{ $tax->id }}">
                    <input type="hidden" name="tax_value[]" value="{{ $tax->tax_value }}">
                    <input type="hidden" name="tax_types[]" value="{{ $tax->tax_type }}">
                    {{-- amount , percent --}}
                </div>
            @endforeach
        </div>
        <div class="col-lg-4 d-none">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Meta Tags') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{ translate('Meta Title') }}</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="meta_title"
                                value="{{ $product->meta_title }}" placeholder="{{ translate('Meta Title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{ translate('Slug') }}</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="slug" value="{{ $product->slug }}"
                                placeholder="{{ translate('Slug') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group mb-0 text-right mb-2">
        <button type="submit" class="btn btn-primary">{{ translate('Update Product') }}</button>
    </div>
</form>

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
        var main_id = '{{ $product->category_id != null ? $product->category_id : 0 }}';
        var selected_ids = '{{ implode(',', $old_categories) }}';
        if (selected_ids != '') {
            const myArray = selected_ids.split(",");
            for (let i = 0; i < myArray.length; i++) {
                const element = myArray[i];
                $('#treeview input:checkbox#' + element).prop('checked', true);
                $('#treeview input:checkbox#' + element).parents("ul").css("display", "block");
                $('#treeview input:checkbox#' + element).parents("li").children('.las').removeClass("la-plus")
                    .addClass('la-minus');
            }
        }
        $('#treeview input:radio[value=' + main_id + ']').prop('checked', true);
        fq_bought_product_selection_type();
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
        var productID = $('input[name=id]').val();
        var searchKey = $('input[name=search_keyword]').val();
        var fqBroughCategory = $('select[name=fq_brough_category]').val();
        $.post('{{ route('seller.product.search') }}', {
            _token: AIZ.data.csrf,
            product_id: productID,
            search_key: searchKey,
            category: fqBroughCategory,
            product_type: "digital"
        }, function(data) {
            $('#product-list').html(data);
            AIZ.plugins.fooTable();
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
            AIZ.plugins.fooTable();
        });
    }
</script>
@endsection
