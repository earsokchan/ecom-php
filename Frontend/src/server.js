const express = require('express');
const bodyParser = require('body-parser');
const crypto = require('crypto');
const app = express();
const port = 3000;

// Define constants
const ABA_PAYWAY_API_URL = 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase';
const ABA_PAYWAY_API_KEY = '096b56dee15c4ce58e56658f568a7b73551b3c77';
const ABA_PAYWAY_MERCHANT_ID = 'ec449300';

// Middleware to parse form data
app.use(bodyParser.urlencoded({ extended: true }));

// Function to generate HMAC hash
function getHash(str) {
    const hmac = crypto.createHmac('sha512', ABA_PAYWAY_API_KEY);
    hmac.update(str);
    return hmac.digest('base64');
}

// Serve static files (HTML)
app.use(express.static('public'));

// Handle form submission
app.post('/checkout', (req, res) => {
    const items = Buffer.from(JSON.stringify([
        { name: 'test1', quantity: '1', price: '10.00' },
        { name: 'test2', quantity: '1', price: '10.00' }
    ])).toString('base64');
    const req_time = Math.floor(Date.now() / 1000);
    const transactionId = req_time;
    const amount = '255.00';
    const firstName = 'Makara';
    const lastName = 'Prom';
    const phone = '093630466';
    const email = 'prom.makara@ababank.com';
    const return_params = 'Hello World!';
    const type = 'purchase';
    const currency = 'USD';
    // const payment_option = 'abapay';
    const shipping = '1.35';

    const hash = getHash(req_time + ABA_PAYWAY_MERCHANT_ID + transactionId + amount + items + shipping + firstName + lastName + email + phone + type + currency + return_params);

    res.send(`
        <form method="POST" action="${ABA_PAYWAY_API_URL}" id="aba_merchant_request">
            <input type="hidden" name="hash" value="${hash}" />
            <input type="hidden" name="tran_id" value="${transactionId}" />
            <input type="hidden" name="amount" value="${amount}" />
            <input type="hidden" name="firstname" value="${firstName}" />
            <input type="hidden" name="lastname" value="${lastName}" />
            <input type="hidden" name="phone" value="${phone}" />
            <input type="hidden" name="email" value="${email}" />
            <input type="hidden" name="items" value="${items}" />
            <input type="hidden" name="return_params" value="${return_params}" />
            <input type="hidden" name="shipping" value="${shipping}" />
            <input type="hidden" name="currency" value="${currency}" />
            <input type="hidden" name="type" value="${type}" />
            <input type="hidden" name="merchant_id" value="${ABA_PAYWAY_MERCHANT_ID}" />
            <input type="hidden" name="req_time" value="${req_time}" />
            <input type="hidden" name="return_param" value="${return_params}" />
        </form>
        <script>
            document.getElementById('aba_merchant_request').submit();
        </script>
    `);
});

// Start server
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
