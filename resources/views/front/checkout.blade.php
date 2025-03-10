@extends('front.layout.layout')

@section('title')
Checkout
@endsection

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form action="" id="checkoutForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            placeholder="First Name">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            placeholder="Last Name">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Email">
                                        <p></p>
                                    </div>
                                </div>
                                @if ($country->isNotEmpty())
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select a Country</option>
                                            @foreach ($country as $cnt)
                                            <option value="{{ $cnt->id }}">{{ $cnt->name }}</option>
                                            @endforeach
                                        </select>
                                        <p></p>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control"></textarea>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="appartment" id="appartment" class="form-control"
                                            placeholder="Apartment, suite, unit, etc. (optional)">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="city" id="city" class="form-control"
                                            placeholder="City">
                                        <p></p>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="state" id="state" class="form-control"
                                            placeholder="State">
                                        <p></p>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="zip" id="zip" class="form-control"
                                            placeholder="Zip">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="mobile" id="mobile" class="form-control"
                                            placeholder="Mobile No.">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                            class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sub-title">
                        <h2>Order Summery</h3>
                    </div>
                    <div class="card cart-summery">
                        <div class="card-body">
                            @foreach (Cart::content() as $cart)
                            <div class="d-flex justify-content-between pb-2">
                                <div class="h6">{{ $cart->name }}</div>
                                <div class="h6">{{ $cart->price }} X {{ $cart->qty }}</div>
                            </div>
                            @endforeach
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                <div class="h6"><strong>{{ Cart::subtotal() }}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong>0</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong>{{ Cart::subtotal() }}</strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="card payment-form ">
                        <h3 class="card-title h5 mb-3">Payment Details</h3>
                        <div class="card-body p-2">
                            <div class="payment-option">
                                <div class="payment-first">
                                    <input checked type="radio" name="payment" id="paymentOne" value="p-one">
                                    <label for="paymentOne">COD</label>
                                    <br>
                                    <input type="radio" name="payment" id="paymentTwo" value="p-two">
                                    <label for="paymentTwo">Stripe</label>
                                </div>
                            </div>
                            <div class="stripPayment d-none">
                                <div class="mb-2 mt-2">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number"
                                        placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row ">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- CREDIT CARD FORM ENDS HERE -->
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('custom-js')
<script>
    $('#paymentOne').click(function() {
        if ($(this).is(':checked')) {
            $('.stripPayment').addClass('d-none');
        }
    })
    $('#paymentTwo').click(function() {
        if ($(this).is(':checked')) {
            $('.stripPayment').removeClass('d-none');
        }
    })

    $('#checkoutForm').submit(function(e) {
        e.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: "{{ route('user-checkout-store') }}",
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response['status'] == true) {
                    $("input[type='text'], input[type='number'], select").removeClass('is-invalid');
                    $('.error').removeClass('invalid-feedback').html('');
                } else {
                    var errors = response['errors'];
                    console.log(errors);
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