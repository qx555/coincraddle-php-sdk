# Coincraddle PHP SDK

Официальная PHP-библиотека для работы с API криптовалютного обменника Coincraddle. SDK предоставляет простой и интуитивно понятный способ интеграции всех функций Coincraddle API в ваше приложение.

[English version](README.md)

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

## Документация

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

#### Создание платежа
```php
$payment = $client->createPayment(
    'BTC',
    'USDT',
    1000.0,            // сумма к получению
    'адрес_получателя',
    'адрес_возврата',
    'тег_получателя',  // опционально
    'тег_возврата'     // опционально
);
```

#### Проверка статуса обмена
```php
$status = $client->getExchangeStatus('id_обмена');
```

#### Получение истории обменов
```php
$history = $client->getExchangeHistory(0, 10); // страница 0, 10 записей на странице
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

Доступные статусы:
- `OrderStatus::NEW` - Ордер создан
- `OrderStatus::WAITING_DEPOSIT` - Ожидание депозита
- `OrderStatus::DEPOSIT_RECEIVED` - Депозит получен
- `OrderStatus::EXCHANGING` - Выполняется обмен
- `OrderStatus::SENDING` - Отправка обмененных средств
- `OrderStatus::SUCCESS` - Обмен завершен
- `OrderStatus::TIME_EXPIRED` - Ордер просрочен
- `OrderStatus::PAYMENT_TIME_EXPIRED` - Время на оплату истекло
- `OrderStatus::FAILED` - Обмен не удался
- `OrderStatus::SENDING_FAILED` - Ошибка отправки средств
- `OrderStatus::REVERTED` - Ордер отменен
- `OrderStatus::PAYMENT_HALTED` - Платеж приостановлен
- `OrderStatus::EXPIRED` - Транзакция получена после истечения срока
- `OrderStatus::LESS` - Сумма транзакции меньше заказанной

### Обработка ошибок

SDK использует исключения для обработки ошибок. Всегда оборачивайте код в блоки try-catch:

```php
try {
    $result = $client->validateAddress('BTC', $address);
} catch (GuzzleException $e) {
    // Обработка ошибок API
    echo "Ошибка API: " . $e->getMessage();
} catch (Exception $e) {
    // Обработка других ошибок
    echo "Ошибка: " . $e->getMessage();
}
```

## Участие в разработке

1. Сделайте форк репозитория
2. Создайте ветку для вашей функции (`git checkout -b feature/amazing-feature`)
3. Зафиксируйте изменения (`git commit -m 'Добавлена новая функция'`)
4. Отправьте изменения в ваш форк (`git push origin feature/amazing-feature`)
5. Создайте Pull Request

## Часто задаваемые вопросы

### Как получить API ключ?
Зарегистрируйтесь в [Партнерской программе](https://coincraddle.com/partner/register) для получения API ключа.

### Какие валюты поддерживаются?
Используйте метод `getCurrencies()` для получения списка всех доступных валют:
```php
$currencies = $client->getCurrencies();
```

### Как работать с тегами адресов (MEMO, DestinationTag)?
Для валют, требующих теги (например, XRP, XLM), укажите их в параметрах `destinationTag` и `refundTag`:
```php
$exchange = $client->createExchange(
    'XRP',
    'BTC',
    100,
    'адрес_получателя',
    'адрес_возврата',
    '12345',  // тег получателя
    '67890'   // тег возврата
);
```

## Лицензия

MIT

## Поддержка

Для получения поддержки обращайтесь по адресу support@coincraddle.com или посетите https://coincraddle.com/ 