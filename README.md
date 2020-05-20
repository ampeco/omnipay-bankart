# omnipay-bankart

[Omnipay](https://omnipay.thephpleague.com) plugin for [bankart](https://gateway.bankart.si/documentation/gateway)

## Installation
```bash
composer require ampeco/omnipay-bankart
```

## Getting started

Create the gateway
```php
$gateway = Omnipay::create('\Ampeco\OmnipayBakart\Gateway');
$gateway->initialize([
    'username'           => 'Your API username',
    'password'           => 'Your API password',
    'apiKey'             => 'Your API key',
    'sharedSecret'       => 'Your API shared secret',
]);
```

Add a new credit card
```php

$response = $gateway->createCard([
        'transaction_id' => uniqid('', true),
        'description' => 'Description',
        'return_url' => 'https://your-return-url',
        'notify_url' => 'https://your-notify-url',
        'customer' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'identification' => 1,
            'email' => 'john@example.com',
            'billingAddress1' => 'None',
            'billingCity' => 'Unknown',
            'billingCountry' => 'NA',
            'billingPostcode' => '0000',
        ]
])->send();

if (!$response->isSuccessful()) {
    abort(422, $response->getMessage());
}

// You must redirect the client to:
echo $response->getRedirectUrl();
echo $response->getTransactionReference(); // The transaction ID assigned by the bank
```

Check if the client completed the card registration
```php
$transactionReference = '1234567890'; // Fetched from above - $response->getTransactionReference()

$result = $gateway->fetchTransaction([
    'transactionReference' => $transactionReference,
])->send();

if (!$result->isSuccessful()){
    abort(422, $result->getMessage());
}
```

Charge the saved credit card reference
```php
$transactionReference = '1234567890'; // saved from above - $transactionReference;
$response = $gateway->purchase([
    'cardReference'     => $transactionReference,
    'amount'            => 3,
    'currency'          => 'EUR',
    'description'       => 'Purchase #1234',
])->send();

if ($response->isSuccessful()) {
    echo $response->getTransactionReference();
    
} else {
    abort(422, $response->getMessage());
}
```
