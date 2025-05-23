# Coincraddle PHP SDK

Official PHP library for working with the Coincraddle cryptocurrency exchange API. The SDK provides a simple and intuitive way to integrate all Coincraddle API functions into your application.

[Русская версия](README_RU.md)

## Quick Start

### 1. Installation

```bash
composer require coincraddle/php-sdk
```

### 2. Basic Usage

```php
// Include Composer autoloader
require_once 'vendor/autoload.php';

// Import required classes
use Coincraddle\CoincraddleClient;
use Coincraddle\Constants\OrderStatus;

// Initialize client with your API key
$client = new CoincraddleClient('your_api_key');

// Exchange example
try {
    $exchange = $client->createExchange(
        'BTC',     // Source currency
        'ETH',     // Target currency
        0.1,       // Amount to exchange
        '0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5',  // Destination address
        'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh'   // Refund address
    );
    
    echo "Exchange created with ID: " . $exchange['id'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP Client
- JSON extension

## Features

- Cryptocurrency address validation
- Real-time exchange rates
- Exchange order creation
- Payment processing
- Exchange status tracking
- Exchange history
- Emergency handling
- Address tag support (MEMO, DestinationTag, etc.)

## Documentation

### Available Methods

#### Address Validation
```php
$isValid = $client->validateAddress('BTC', 'address_to_validate');
```

#### Get Exchange Rate
```php
$rate = $client->getRate('BTC', 'USDT', 1.0);
```

#### Create Exchange Order
```php
$exchange = $client->createExchange(
    'BTC',
    'ETH',
    0.1,
    'destination_address',
    'refund_address',
    'destination_tag',  // optional
    'refund_tag',      // optional
    0                  // 0 - floating rate, 1 - fixed rate
);
```

#### Create Payment
```php
$payment = $client->createPayment(
    'BTC',
    'USDT',
    1000.0,            // amount to receive
    'destination_address',
    'refund_address',
    'destination_tag',  // optional
    'refund_tag'       // optional
);
```

#### Check Exchange Status
```php
$status = $client->getExchangeStatus('exchange_id');
```

#### Get Exchange History
```php
$history = $client->getExchangeHistory(0, 10); // page 0, 10 items per page
```

### Order Statuses

Use the `OrderStatus` class constants for working with order statuses:

```php
use Coincraddle\Constants\OrderStatus;

if ($status['status'] === OrderStatus::SUCCESS) {
    // Exchange completed successfully
} elseif ($status['status'] === OrderStatus::WAITING_DEPOSIT) {
    // Waiting for deposit
}
```

Available statuses:
- `OrderStatus::NEW` - Order created
- `OrderStatus::WAITING_DEPOSIT` - Waiting for deposit
- `OrderStatus::DEPOSIT_RECEIVED` - Deposit received
- `OrderStatus::EXCHANGING` - Exchange in progress
- `OrderStatus::SENDING` - Sending exchanged funds
- `OrderStatus::SUCCESS` - Exchange completed
- `OrderStatus::TIME_EXPIRED` - Order expired
- `OrderStatus::PAYMENT_TIME_EXPIRED` - Payment period expired
- `OrderStatus::FAILED` - Exchange failed
- `OrderStatus::SENDING_FAILED` - Failed to send funds
- `OrderStatus::REVERTED` - Order reverted
- `OrderStatus::PAYMENT_HALTED` - Payment halted
- `OrderStatus::EXPIRED` - Transaction received after expiration
- `OrderStatus::LESS` - Transaction amount is less than ordered

### Error Handling

The SDK uses exceptions for error handling. Always wrap your code in try-catch blocks:

```php
try {
    $result = $client->validateAddress('BTC', $address);
} catch (GuzzleException $e) {
    // Handle API error
    echo "API Error: " . $e->getMessage();
} catch (Exception $e) {
    // Handle other errors
    echo "Error: " . $e->getMessage();
}
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## FAQ

### How do I get an API key?
Register in the [Partnership Program](https://coincraddle.com/partner/register) to obtain your API key.

### What currencies are supported?
Use the `getCurrencies()` method to get the list of all available currencies:
```php
$currencies = $client->getCurrencies();
```

### How do I handle address tags (MEMO, DestinationTag)?
For currencies that require tags (like XRP, XLM), provide them in the `destinationTag` and `refundTag` parameters:
```php
$exchange = $client->createExchange(
    'XRP',
    'BTC',
    100,
    'destination_address',
    'refund_address',
    '12345',  // destination tag
    '67890'   // refund tag
);
```

## License

MIT

## Support

For support, please contact support@coincraddle.com or visit https://coincraddle.com/ 