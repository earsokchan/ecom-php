<?php
header("Content-Type: application/json");

// Debugging: Log input data
$inputData = file_get_contents("php://input");
error_log("Input Data: " . $inputData);

$data = json_decode($inputData, true);

if (!isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(["error" => "Cart is empty"]);
    exit;
}

$ABA_PAYWAY_API_URL = 'https://link.payway.com.kh/aba?id=DDCDBF42E545&code=688814&acc=007265828&dynamic=true';
$ABA_PAYWAY_API_KEY = '096b56dee15c4ce58e56658f568a7b73551b3c77';
$ABA_PAYWAY_MERCHANT_ID = '2998822';

$req_time = time();
$transactionId = $req_time;
$amount = 0;
$itemsArray = [];

foreach ($data['cart'] as $item) {
    $amount += floatval($item['price']) * intval($item['quantity']);
    $itemsArray[] = [
        "name" => $item["name"],
        "quantity" => (string) $item["quantity"],
        "price" => number_format($item["price"], 2, '.', '')
    ];
}

$items = base64_encode(json_encode($itemsArray));
$shipping = "1.35";
$firstName = "Customer";
$lastName = "User";
$phone = "0123456789";
$email = "customer@example.com";
$return_params = "Order Completed";
$type = "purchase";
$currency = "USD";

$hash_string = $req_time . $ABA_PAYWAY_MERCHANT_ID . $transactionId . $amount . $items . $shipping . $firstName . $lastName . $email . $phone . $type . $currency . $return_params;
$hash = base64_encode(hash_hmac("sha512", $hash_string, $ABA_PAYWAY_API_KEY, true));

$redirect_url = $ABA_PAYWAY_API_URL . "?hash=" . urlencode($hash) .
    "&tran_id=" . urlencode($transactionId) .
    "&amount=" . urlencode($amount) .
    "&firstname=" . urlencode($firstName) .
    "&lastname=" . urlencode($lastName) .
    "&phone=" . urlencode($phone) .
    "&email=" . urlencode($email) .
    "&items=" . urlencode($items) .
    "&return_params=" . urlencode($return_params) .
    "&shipping=" . urlencode($shipping) .
    "&currency=" . urlencode($currency) .
    "&type=" . urlencode($type) .
    "&merchant_id=" . urlencode($ABA_PAYWAY_MERCHANT_ID) .
    "&req_time=" . urlencode($req_time);

// Debugging: Log the redirect URL
error_log("Generated Redirect URL: " . $redirect_url);

echo json_encode(["redirect_url" => $redirect_url]);
exit;
?>