<?php
require 'path-to-stripe.php';

//When an item is bought, then user will be redirected to this page. The card is not kepts on the sql server or on local files. Information is passed through tokens and sent to stripe.com 

//Test card number 4242424242424242  Visa
Stripe::setApiKey("sk_test_bRBLnLjGzNCX3jPfjoZPzATG");

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// Create a charge: this will charge the user's card
try {
  $charge = \Stripe\Charge::create(array(
    "amount" => 1000, // Amount in cents
    "currency" => "usd",
    "source" => $token,
    "description" => "Example charge",
        "metadata" => array("order_id" => "6735")

    ));
} catch(\Stripe\Error\Card $e) {
  // The card has been declined
}
}
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Stripe Getting Started Form</title>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <!-- jQuery is used only for this example; it isn't required to use Stripe -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('pk_test_9iOTMKp92oRJMewkOcx37vyQ');
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }

            $(document).ready(function() {
                $("#payment-form").submit(function(event) {
                    // disable the submit button to prevent repeated clicks
                    $('.submit-button').attr("disabled", "disabled");
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);

                    alert("Sucessul");
                    return false; // submit from callback
                });
            });
        </script>
    </head>
    <body>
        <h1>Charge $10 with Stripe</h1>
        <!-- to display errors returned by createToken -->
        <span class="payment-errors"><?= $error ?></span>
        <span class="payment-success"><?= $success ?></span>
        <form action="" method="POST" id="payment-form">
            <div class="form-row">
                <label>Card Number</label>
                <input type="text" size="20" autocomplete="off" class="card-number" />
            </div>
            <div class="form-row">
                <label>CVC</label>
                <input type="text" size="4" autocomplete="off" class="card-cvc" />
            </div>
            <div class="form-row">
                <label>Expiration (MM/YYYY)</label>
                <input type="text" size="2" class="card-expiry-month"/>
                <span> / </span>
                <input type="text" size="4" class="card-expiry-year"/>
            </div>
            <button type="submit" class="submit-button">Submit Payment</button>
        </form>
    </body>
</html>
