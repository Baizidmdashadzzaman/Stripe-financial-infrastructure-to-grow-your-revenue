<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title text-primary">Stripe Payment</h1>
                        <p class="card-text">Hey there, I'm a Stripe Payment Page.</p>
                        <p class="card-text">
                            Click the button below and you'll be taken to a
                            <a href="https://stripe.com/en-gb-us" target="_blank" class="text-decoration-underline">Stripe</a>
                            checkout form where you can enter real credit/debit card details and send me money.
                        </p>
                        <p class="card-text">
                            My purpose is to demonstrate building a
                            <a href="https://laravel.com/docs/9.x/" target="_blank" class="text-decoration-underline">Laravel</a> /
                            <a href="https://stripe.com/en-gb-us" target="_blank" class="text-decoration-underline">Stripe</a> app in 5 minutes.
                        </p>
                        <p class="text-danger fw-bold">
                            WARNING!!!<br/>
                            This is set to LIVE mode, so real money is used.<br/>
                            No refunds, use at your own risk.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="d-flex justify-content-center gap-3">
                    <form action="{{route('stripe.card.payment.process')}}" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <button type="submit" id="checkout-test-button" class="btn btn-primary btn-lg">Checkout (Test)</button>
                    </form>
                    <form action="/live" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <button type="submit" id="checkout-live-button" class="btn btn-success btn-lg">Checkout (LIVE)</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
