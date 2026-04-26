<?php
// stripe_checkout.php

// Step 1: Install Stripe PHP library first via composer
// composer require stripe/stripe-php

require 'vendor/autoload.php';

// Step 2: Set your Stripe Secret Key (Test mode)
\Stripe\Stripe::setApiKey('sk_test_xxxxxxxxxxxxxxxxxxxxx'); // Replace with your Test Secret Key

// Step 3: Handle AJAX request from frontend to create Checkout Session
if(isset($_POST['create_session'])) {
    header('Content-Type: application/json');
    try {
        $amount_rupees = 500; // ₹500, you can get this from POST if dynamic
        $amount_paise = $amount_rupees * 100; // Stripe expects smallest currency unit

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'unit_amount' => $amount_paise,
                    'product_data' => [
                        'name' => 'Zippy Food Order',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?success=1',
            'cancel_url' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?cancel=1',
        ]);

        echo json_encode(['id' => $checkout_session->id]);
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Zippy Food - Stripe Test Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Zippy Food - Test Payment</h1>

    <?php if(isset($_GET['success'])): ?>
        <h2 style="color:green;">Payment Successful!</h2>
    <?php elseif(isset($_GET['cancel'])): ?>
        <h2 style="color:red;">Payment Cancelled!</h2>
    <?php else: ?>
        <p>Pay ₹500 using test card</p>
        <button id="checkout-button">Pay Now</button>
    <?php endif; ?>

    <script>
        var stripe = Stripe('pk_test_xxxxxxxxxxxxxxxxxxxxx'); // Replace with your Test Publishable Key

        document.getElementById('checkout-button')?.addEventListener('click', function() {
            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'create_session=1'
            })
            .then(res => res.json())
            .then(session => {
                if(session.error){
                    alert('Error: ' + session.error);
                } else {
                    stripe.redirectToCheckout({ sessionId: session.id });
                }
            });
        });
    </script>
</body>
</html>
