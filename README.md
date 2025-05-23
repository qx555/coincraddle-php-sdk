# Coincraddle PHP SDK

Официальная PHP-библиотека для работы с API криптовалютного обменника Coincraddle. SDK предоставляет простой и интуитивно понятный способ интеграции всех функций Coincraddle API в ваше приложение.

[English version below](#english)

## Быстрый старт

### 1. Установка

```bash
composer require coincraddle/php-sdk
```

### 2. Базовое использование

```php
// Подключаем автозагрузчик Composer
require_once 'vendor/autoload.php';

// Импортируем необходимые классы
use Coincraddle\CoincraddleClient;
use Coincraddle\Constants\OrderStatus;

// Инициализируем клиент с вашим API ключом
$client = new CoincraddleClient('ваш_api_ключ');

// Пример создания обмена
try {
    $exchange = $client->createExchange(
        'BTC',     // Исходная валюта
        'ETH',     // Целевая валюта
        0.1,       // Сумма для обмена
        '0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5',  // Адрес получателя
        'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh'   // Адрес для возврата
    );
    
    echo "Создан обмен с ID: " . $exchange['id'];
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
```

## Требования

- PHP 7.4 или выше
- Guzzle HTTP Client
- Расширение JSON

## Основные возможности

- Валидация криптовалютных адресов
- Получение актуальных курсов обмена
- Создание ордеров на обмен
- Создание платежей
- Проверка статуса обменов
- Получение истории обменов
- Обработка чрезвычайных ситуаций
- Поддержка тегов для адресов (MEMO, DestinationTag и др.)

## Подробная документация

### Доступные методы

#### Валидация адреса
```php
$isValid = $client->validateAddress('BTC', 'адрес_для_проверки');
```

#### Получение курса обмена
```php
$rate = $client->getRate('BTC', 'USDT', 1.0);
```

#### Создание обмена
```php
$exchange = $client->createExchange(
    'BTC',
    'ETH',
    0.1,
    'адрес_получателя',
    'адрес_возврата',
    'тег_получателя',  // опционально
    'тег_возврата',    // опционально
    0                  // 0 - плавающий курс, 1 - фиксированный
);
```

### Статусы ордеров

Для удобства работы со статусами ордеров используйте константы из класса `OrderStatus`:

```php
use Coincraddle\Constants\OrderStatus;

if ($status['status'] === OrderStatus::SUCCESS) {
    // Обмен успешно завершен
} elseif ($status['status'] === OrderStatus::WAITING_DEPOSIT) {
    // Ожидание депозита
}
```

### Обработка ошибок

```php
try {
    $result = $client->validateAddress('BTC', $address);
} catch (GuzzleException $e) {
    // Ошибка API
    echo "Ошибка API: " . $e->getMessage();
} catch (Exception $e) {
    // Другие ошибки
    echo "Ошибка: " . $e->getMessage();
}
```

---

<a name="english"></a>
# Coincraddle PHP SDK (English)

Official PHP library for working with the Coincraddle cryptocurrency exchange API. The SDK provides a simple and intuitive way to integrate all Coincraddle API functions into your application.

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

[See full documentation in Russian above](#coincraddle-php-sdk)

## License

MIT

## Support

For support, please contact support@coincraddle.com or visit https://coincraddle.com/ 