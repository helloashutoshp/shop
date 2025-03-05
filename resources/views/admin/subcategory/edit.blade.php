@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('sub-category-list')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" id="editSubCategory">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    @if ($maincategory->isNotEmpty())
                                        <select name="category" id="category" class="form-control">
                                            {{-- <option value="{{$category->categoryId}}">{{$category->categoryName}}</option> --}}
                                            @foreach ($maincategory as $maincategory)
                                                <option value="{{ $maincategory->id }}" {{ $category->cate_id == $maincategory->id ? 'selected' : '' }}>{{ $maincategory->name }}</option>
                                            @endforeach
                                        </select>
                                        <p class="errors"></p>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" name="cat_id" id="cat_id" value="{{$category->id}}">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{$category->name}}">
                                        <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input  type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug" value="{{$category->slug}}">
                                        <p class="errors"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="show">Show In Home Page</label>
                                    <select name="show" id="show" class="form-control">
                                        <option value="Yes" {{ $category->showHome == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $category->showHome == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Accept
                                        </option>
                                        <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('sub-category-list')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection

@section('custom')
    <script>
        $('#name').on('change', function() {
            var title = $('#name').val();
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

        $('#editSubCategory').submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('admin-sub-category-update') }}",
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status'] == true) {
                        $("input[type='text'], input[type='number'], select").removeClass('is-invalid');
                        $('.error').removeClass('invalid-feedback').html('');
                        window.location.href = "{{ route('sub-category-list') }}"
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
    </script>
@endsection
