@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin-product-list') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" id="editproductForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title" value="{{ $product->title }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" name="slug" id="slug" class="form-control"
                                                placeholder="Slug" value="{{ $product->slug }}" readonly>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote"
                                                placeholder="Short Description">{{ $product->short_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">{{ $product->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="shipping_return">Shipping Return</label>
                                            <textarea name="shipping_return" id="shipping_return" cols="30" rows="10" class="summernote"
                                                placeholder="Shipping Return">{{ $product->shipping_return }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row image-gallery">
                            @if ($productImage->isNotEmpty())
                                @foreach ($productImage as $prodImg)
                                    <div class="col-md-3" id="single-image{{ $prodImg->id }}">
                                        <div class="card">
                                            <input type="hidden" value="{{ $prodImg->id }}" name="productImg[]" />
                                            <img class="card-img-top"
                                                src={{ asset('uploads/product/large/' . $prodImg->image) }}
                                                alt="Card image cap">
                                            <div class="card-body">
                                                <a href="javascript:void(0)" onclick="deleteEditImage({{ $prodImg->id }})"
                                                    class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price" value="{{ $product->price }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Compare Price"
                                                value="{{ $product->compare_price }}">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku" value="{{ $product->sku }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode" value="{{ $product->barcode }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty"
                                                    name="track_qty" value="Yes"
                                                    {{ $product->trackqty == 'Yes' ? 'checked' : '' }}>
                                                <p class="error"></p>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty" value="{{ $product->qty }}"
                                                disabled>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Related product</h2>
                                <div class="mb-3">
                                    <select multiple name="related_product[]" id="related_product"
                                        class="related_product form-control w-100">
                                        @if ($items->isNotEmpty())
                                            @foreach ($items as $item)
                                                <option selected value="{{ $item->id }}">{{ $item->title }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Block
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        {{-- <option value="">{{$product->categoryName}}</option> --}}
                                        @if ($category->isNotEmpty())
                                            @foreach ($category as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ $product->cate_id == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        @if ($subcategory->isNotEmpty())
                                            @foreach ($subcategory as $subcat)
                                                <option value="{{ $subcat->id }}"
                                                    {{ $product->sub_cate_id == $subcat->id ? 'selected' : '' }}>
                                                    {{ $product->subCategoryName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        {{-- <option value="">{{$product->brandName}}</option> --}}
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No"{{ $product->isfeature == 'No' ? 'selected' : '' }}>No
                                        </option>
                                        <option value="Yes" {{ $product->isfeature == 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('admin-product-list') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
@endsection

@section('custom')
    <script>
        $('.related_product').select2({
            ajax: {
                url: '{{ route('product.related') }}',
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function(data) {
                    return {
                        results: data.tags
                    };
                }
            }
        });
        qtyChecked();
        $('#track_qty').change(function() {
            qtyChecked();
        });

        function qtyChecked() {
            var tQty = $('#track_qty');
            $('#qty').prop('disabled', !tQty.prop('checked'));
        }
        $('#editproductForm').submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('admin-product-update') }}",
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status'] == true) {
                        $("input[type='text'], input[type='number'], select").removeClass('is-invalid');
                        $('.error').removeClass('invalid-feedback').html('');
                        window.location.href = "{{ route('admin-product-list') }}"
                    } else {
                        var errors = response['errors'];
                        $("input[type='text'], input[type='number'], select").removeClass('is-invalid');
                        $('.error').removeClass('invalid-feedback').html('');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        })
                    }
                }
            })
        });

        $('#title').on('change', function() {
            var title = $('#title').val();
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('get-slug') }}",
                type: 'get',
                data: {
                    title: title
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    $('#slug').val(response.slug);
                }
            });
        });

        function categoryAssign() {
            var category = $('#category').val();
            $.ajax({
                url: "{{ route('product-subCategory') }}",
                type: 'get',
                data: {
                    category: category
                },
                dataType: 'json',
                success: function(response) {
                    $('#sub_category').find('option').remove();
                    $.each(response['subcategory'], function(key, item) {
                        $('#sub_category').append(
                            `<option value='${item.id}'>${item.name}</option>`)
                    })
                }
            });
        }

        function categoryOldAssign() {
            var category = $('#category').val();
            $.ajax({
                url: "{{ route('product-subCategory') }}",
                type: 'get',
                data: {
                    category: category
                },
                dataType: 'json',
                success: function(response) {
                    $('#sub_category').find('option').not(':first').remove();
                    $.each(response['subcategory'], function(key, item) {
                        $('#sub_category').append(
                            `<option value='${item.id}'>${item.name}</option>`)
                    })
                }
            });
        }
        $('#category').on('change', function() {
            categoryAssign();
        });

        categoryOldAssign();
        Dropzone.autoDiscover = false;
        const dropzone = $('#image').dropzone({
            url: "{{ route('update-productImage') }}",
            maxFiles: 10,
            paramName: 'image',
            params: {
                'product_id': '{{ $product->id }}'
            },
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/jpg,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, res) {
                console.log(res.imagePath);
                console.log(res.imageId);
                var gallery = `
                <div class="col-md-3" id="single-image${res.imageId}">
                 <div class="card" >
                    <input type="hidden" value="${res.imageId}" name="productImg[]" />
                    <img class="card-img-top" src="${res.imagePath}" alt="Card image cap">  
                    <div class="card-body">
                        <a href="javascript:void(0)" onclick="deleteEditImage(${res.imageId})" class="btn btn-danger">Delete</a>    
                    </div>
                  </div>
                </div>`
                $('.image-gallery').append(gallery);

            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        function deleteEditImage(id) {
            if (confirm("Are you sure to delete this image ?")) {
                $('#single-image' + id).remove();
                $.ajax({
                    url: "{{ route('deleteProductImage') }}",
                    type: "get",
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res.status == "true") {
                            alert('Image deleted successfully');
                        }
                    }
                })
            }
        }
    </script>
@endsection
