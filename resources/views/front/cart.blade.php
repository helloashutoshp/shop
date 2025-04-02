@extends('front.layout.layout')
@section('title')
    Home
@endsection
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            {{-- @include('admin.message') --}}
            <div class="row">
                @if ($cart->isNotEmpty())
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table" id="cart">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($cart as $car)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($car->options->productImage && $car->options->productImage->image)
                                                        <img src="{{ asset('uploads/product/large/' . $car->options->productImage->image) }}"
                                                            width="" height="">
                                                    @else
                                                        <img src="{{ asset('uploads/product/large/404.jpg') }}"
                                                            alt="">
                                                    @endif
                                                    <h2>{{ $car->name }}</h2>
                                                </div>
                                            </td>
                                            <td>{{ $car->price }}</td>
                                            <td>
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub"
                                                            data-id="{{ $car->rowId }}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control form-control-sm  border-0 text-center qty-val"
                                                        value="{{ $car->qty }}">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add"
                                                            data-id="{{ $car->rowId }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $car->price * $car->qty }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteCart('{{ $car->rowId }}')"><i
                                                        class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card cart-summery">
                            <div class="sub-title">
                                <h2 class="bg-white">Cart Summery</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>Subtotal</div>
                                    <div class="subtotal">{{ Cart::subtotal() }}</div>
                                </div>
                                {{-- <div class="d-flex justify-content-between pb-2">
                                <div>Shipping</div>
                                <div>$20</div>
                            </div> --}}
                                <div class="d-flex justify-content-between summery-end">
                                    <div>Total</div>
                                    <div class="total">{{ Cart::subtotal() }}</div>
                                </div>
                                <div class="pt-5">
                                    <a href="{{ route('checkOut') }}" class="btn-dark btn btn-block w-100">Proceed to
                                        Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <h5>Cart is empty</h5>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
    <script>
        // $('#cart tbody tr').each(function() {
        //     var qtyInput = $(this).find('.qty-val'); // Find the quantity input
        //     var count = parseInt(qtyInput.val());

        //     // Find the add and sub buttons
        //     var addButton = $(this).find('.btn-plus');
        //     var subButton = $(this).find('.btn-minus');

        //     // Apply 'faded' class based on quantity
        //     if (count >= 10) {
        //         addButton.addClass('faded');
        //     }
        //     if (count <= 1) {
        //         subButton.addClass('faded');
        //     }
        // });

        $('#cart tbody tr').each(function() {
            var quantity = $(this).find('.qty-val');
            var qty = parseInt(quantity.val());
            var addButton = $(this).find('.add');
            var subButton = $(this).find('.sub');
            if (qty == 1) {
                subButton.addClass('faded');
            }
            if (qty == 10) {
                addButton.addClass('faded');
            }
        })

        $('.add').click(function() {
            var item = $(this).parent().prev();
            var count = parseInt(item.val());
            var id = $(this).data('id');
            var subButton = $(this).closest('.quantity').find('.sub');

            // If the count is less than the max (10)
            if (count < 10) {
                count = count + 1;
                item.val(count);
                var quantity = item.val();
                updateCart(id, quantity);
                subButton.removeClass('faded');
            }
            if (count == 10) {
                $(this).addClass('faded');
            }
        });

        $('.sub').click(function() {
            var item = $(this).parent().next();
            var id = $(this).data('id');
            var count = parseInt(item.val());
            var addButton = $(this).closest('.quantity').find('.add');

            // If the count is greater than the min (1)
            if (count > 1) {
                count = count - 1;
                item.val(count);
                var quantity = item.val();
                updateCart(id, quantity);

                // Remove faded class from add button
                addButton.removeClass('faded');
            }

            // Add faded class if min count is reached
            if (count == 1) {
                $(this).addClass('faded');
            }
        });

        function updateCart(id, quantity) {
            $.ajax({
                url: "{{ route('cartUpdate') }}",
                method: "post",
                type: "json",
                data: {
                    id: id,
                    quantity: quantity,
                },
                success: function(response) {
                    if (response.status == false) {
                        alert(response.message);
                        window.location.href = "{{ route('product.cart') }}"
                    } else {
                        var row = $(`[data-id="${response.rowId}"]`).closest('tr');
                        row.find('td:nth-child(4)').text(response.rowTotal); // Update row total
                        $('.subtotal').html(response.subtotal);
                        $('.total').html(response.subtotal);
                    }

                }
            });
        }

        function deleteCart(id) {
            if (confirm("Are you sure to remove this item")) {
                $.ajax({
                    url: "{{ route('cartDelete') }}",
                    method: "post",
                    type: "json",
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.href = "{{ route('product.cart') }}";
                    }
                });
            }
        }
    </script>
@endsection
