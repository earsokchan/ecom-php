<?php
// process_checkout.php

// Get the payment data from the request
$paymentData = json_decode(file_get_contents('php://input'), true);

// Prepare the payload for the Payway API
$payload = [
    'merchant_id' => $paymentData['merchant_id'],
    'public_key' => $paymentData['public_key'],
    'amount' => $paymentData['amount'],
    'currency' => $paymentData['currency'],
    'items' => $paymentData['items']
];

// Send the request to the Payway API
$ch = curl_init('https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);

// Check if the payment was successful
if ($responseData && $responseData['success']) {
    // Redirect to the payment gateway
    echo json_encode([
        'success' => true,
        'redirect_url' => $responseData['redirect_url']
    ]);
} else {
    // Handle the error
    echo json_encode([
        'success' => false,
        'message' => $responseData['message'] ?? 'Payment failed'
    ]);
}
?>