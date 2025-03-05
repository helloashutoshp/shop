<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container col-md-4">
        <div class="card mt-5">
            <div class="card-header">
                Make Payment
            </div>
            <div class="card-body">
                @include('admin.message')
                <div class="d-flex justify-content-between small mb-2">
                    <span>Subtotal</span><span>$190.00</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span>Shipping</span><span>$20.00</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span>Coupon (Code:ygsdhg)</span><span class="text-danger">-$10.00</span>
                </div>
                <div class="card-footer">
                    <form action="{{ route('payment-catch') }}" method="post" id="stripe_form">
                        @csrf
                        <input type="hidden" name="price" id="price" value="200">
                        <input type="hidden" name="stripe_id" id="stripe_id">
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Total</span><strong>$200.00</strong>
                        </div>
                        <div class="form-control" id="card-element"></div>
                        <button class="btn btn-success w-100" type="button" onclick="createToken()">Payment
                            Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe("{{ env('STRIPE_KEY') }}");
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        function createToken() {
            stripe.createToken(cardElement).then(function(result) {
                jQuery('#stripe_id').val(result.token.id);
                jQuery('#stripe_form').submit();

            });
        }
    </script>
</body>

</html>
