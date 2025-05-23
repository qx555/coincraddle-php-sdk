<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Coincraddle\CoincraddleClient;
use Coincraddle\Constants\OrderStatus;

// Initialize the client with your API key
$client = new CoincraddleClient('your_api_key_here');

try {
    // Example 1: Validate a Bitcoin address
    $isValid = $client->validateAddress('BTC', 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh');
    echo "Address validation result: " . ($isValid ? 'valid' : 'invalid') . "\n";

    // Example 2: Get exchange rate
    $rate = $client->getRate('BTC', 'USDT', 1.0);
    echo "Exchange rate: " . $rate['rate'] . "\n";

    // Example 3: Get available currencies
    $currencies = $client->getCurrencies();
    echo "Available currencies:\n";
    print_r($currencies);

    // Example 4: Create an exchange order
    $exchange = $client->createExchange(
        'BTC',
        'ETH',
        0.1,
        '0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5',
        'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh'
    );
    echo "Exchange created with ID: " . $exchange['id'] . "\n";

    // Example 5: Check exchange status
    $status = $client->getExchangeStatus($exchange['id']);
    echo "Exchange status: " . $status['status'] . "\n";

    // Example 6: Get exchange history
    $history = $client->getExchangeHistory(0, 10);
    echo "Recent exchanges:\n";
    print_r($history);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 