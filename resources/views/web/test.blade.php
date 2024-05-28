<!DOCTYPE html>
<html>
  <head>
    {{-- <link rel="stylesheet" href="https://stripe-samples.github.io/developer-office-hours/demo.css" type="text/css" /> --}}
  </head>
  <body>
    <div id="main">
        <div id="checkout">
          <div id="payment-form">
            {{-- <img src="https://stripe-samples.github.io/developer-office-hours/logo.svg" alt="logo" /> --}}
            <h1>Step 3: Authenticate</h1>
            <div id="card-error"></div>
            <button id="card-button">Authenticate</button>
            <div id="card-message"></div>
          </div>
        </div>
      </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
            var stripe = Stripe("{{ config('app.stripe_key') }}");
            var cardError = document.getElementById('card-error');
            var cardButton = document.getElementById('card-button');
            var params = new URLSearchParams(window.location.search);
            var paymentIntentId = params.get('pi');
            var paymentMethodId = params.get('pm');
            var paymentIntent;

            // Fetch payment intent and show error
            debug('Fetching payment intent');
            fetch('/setup-intents?id=' + paymentIntentId).then(function(r) {
                return r.json();
            }).then(function(response) {
                console.log(response);
                paymentIntent = response;
                if(response.last_payment_error) {
                cardError.innerText = response.last_payment_error.message;
                }
            });

            // Add click handler to start handleCardPayment process.
            cardButton.addEventListener('click', function(event) {
                event.preventDefault();
                debug('Handling card payment...');
                stripe.handleCardPayment(
                paymentIntent.client_secret, {
                    payment_method: paymentMethodId
                }
                ).then(function(response) {
                console.log(response);
                if(response.error) {
                    debug(response.error.message);
                } else {
                    debug('Payment successful!');
                }
                });
            });

            function debug(message) {
                var debugMessage = document.getElementById('card-message');
                console.log('DEBUG: ', message);
                debugMessage.innerText += "\n" + message;
            }
    </script>
  </body>
</html>
