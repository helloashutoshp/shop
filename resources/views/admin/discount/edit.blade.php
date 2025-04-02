@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Discount Coupon</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('discount-index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" id="editDiscountForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" name="id" value="{{$discount->id}}">
                                    <label for="name">Name*</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{$discount->name}}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code">Code*</label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Disocunt Code" value="{{$discount->code}}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea class="summernote" name="description" id="description" cols="30" rows="10">value="{{$discount->description}}"</textarea>
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="maxuses">Max uses*</label>
                                    <input type="number" name="maxuses" id="maxuses" class="form-control"
                                        placeholder="Coupon Max Uses" value="{{$discount->max_uses}}">
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="maxuser">Max user uses*</label>
                                    <input type="number" name="maxuser" id="maxuser" class="form-control"
                                        placeholder="Max User Uses" value="{{$discount->max_uses_user}}">
                                    <p class="error"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="dtype">Discount Type</label>
                                    <select name="dtype" id="dtype" class="form-control">
                                        <option value="fixed" {{ $discount->type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percent" {{ $discount->type == 'percent' ? 'selected' : '' }}>Percent</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="dvalue">Discount Value*</label>
                                    <input type="number" name="dvalue" id="dvalue" class="form-control"
                                        placeholder="Disocunt Value" value="{{$discount->dicount_amount}}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimum_amount">Minimum Amount*</label>
                                    <input type="number" name="minimum_amount" id="minimum_amount" class="form-control"
                                        placeholder="Minimum Amount" value="{{$discount->minimum_amount}}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $discount->status == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ $discount->status == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="starts_at">Disocunt Starts At*</label>
                                    <input type="text" name="starts_at" id="starts_at" class="form-control"
                                        placeholder="Disocunt Starts At" autocomplete="off" value="{{$discount->starts_at}}">
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="starts_at">Disocunt Ends At*</label>
                                    <input type="text" name="ends_at" id="ends_at" class="form-control"
                                        placeholder="Disocunt Ends At" autocomplete="off" value="{{$discount->ends_at}}">
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('custom')
    <script>
        $(document).ready(function() {
            $('#starts_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
            $('#ends_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });
        $('#editDiscountForm').submit(function(e) {
            e.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('discount-update') }}",
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status'] == true) {
                        $("input[type='text'], input[type='number'], select").removeClass('is-invalid');
                        $('.error').removeClass('invalid-feedback').html('');
                        window.location.href = "{{ route('discount-index') }}"
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
