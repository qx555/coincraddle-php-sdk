# Coincraddle API Documentation

## Overview

The Coincraddle API allows you to create your own cryptocurrency exchanger. To use the API, you need an API key, which can be obtained by registering in the [Partnership Program](https://coincraddle.com/partner/register).

- **Base URL**: `https://coincraddle.com/v1/api`
- **Response Format**: JSON
- **Copyright**: © 2020-2025 [coincraddle.com](https://coincraddle.com/)

## Authentication

All API requests require the `key` parameter, which is your API key.

## Endpoints

### 1. Validate Address

**Endpoint**: `/validate-address`

**Method**: GET

**Description**: Checks the validity of a cryptocurrency address.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `currency` (string): The currency code (e.g., `BTC`).
- `address` (string): The address to validate (e.g., `bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh`).

**Response**:
- `200 OK`: `{"result": true}` if valid, `{"result": false}` otherwise.

**Example**:
```bash
GET https://coincraddle.com/v1/api/validate-address?key=3r8wzm4c8uo9ep5tk&currency=BTC&address=bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh
```

**Response Example**:
```json
{"result":true}
```

### 2. Get Exchange Rate

**Endpoint**: `/rate`

**Method**: GET

**Description**: Gets the current exchange rate for a transaction amount (excludes fee).

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `from` (string): Source currency (e.g., `BTC`).
- `to` (string): Target currency (e.g., `USDT`).
- `amount` (float): Amount to exchange (e.g., `1`).
- `fix` (int): 0 for floating rate, 1 for fixed rate (e.g., `0`).

**Response**:
- `200 OK`: JSON object with rate details.
  - `fix`: boolean, whether the rate is fixed.
  - `minamount`: minimum exchange amount.
  - `maxamount`: maximum exchange amount.
  - `rate`: the exchange rate.
  - `withdrawalFee`: approximate withdrawal fee.
  - `result`: boolean, success indicator.

**Example**:
```bash
GET https://coincraddle.com/v1/api/rate?key=3r8wzm4c8uo9ep5tk&from=BTC&to=USDT&amount=1&fix=0
```

**Response Example**:
```json
{
  "fix": false,
  "minamount": 0.02,
  "maxamount": 120,
  "rate": 16776.052484164,
  "withdrawalFee": "3.96626 USDT",
  "result": true
}
```

**Notes**:
- `fix`: true - fixed rate, false - floating rate.
- `withdrawalFee`: approximate commission for withdrawal. Floating parameter.

### 3. Get Payment Rate

**Endpoint**: `/payment/rate`

**Method**: GET

**Description**: Gets the current exchange rate and calculates funds to send for payment.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `from` (string): Source currency (e.g., `BTC`).
- `to` (string): Target currency (e.g., `USDT`).
- `amountTo` (float): Desired amount to receive (e.g., `1000`).

**Response**:
- `200 OK`: JSON object with rate details.
  - `minamount`: minimum exchange amount.
  - `maxamount`: maximum exchange amount.
  - `rate`: amount of funds to be sent.
  - `result`: boolean, success indicator.

**Example**:
```bash
GET https://coincraddle.com/v1/api/payment/rate?key=3r8wzm4c8uo9ep5tk&from=BTC&to=USDT&amountTo=1000
```

**Response Example**:
```json
{
  "minamount": 0.02,
  "maxamount": 120,
  "rate": 0.04281856,
  "result": true
}
```

**Notes**:
- `rate`: amount of funds to be sent.

### 4. Get Currencies

**Endpoint**: `/currencies`

**Method**: GET

**Description**: Gets the list of all available currencies.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).

**Response**:
- `200 OK`: JSON object with currency codes as keys and details as values.
  - `coinName`: name of the currency.
  - `minamount`: minimum exchange amount.
  - `maxamount`: maximum exchange amount.
  - `tagname`: tag name if applicable.
  - `network`: network if applicable.
  - `available`: boolean, whether the currency is available.

**Example**:
```bash
GET https://coincraddle.com/v1/api/currencies?key=3r8wzm4c8uo9ep5tk
```

**Response Example**:
```json
{
  "BTC": {
    "coinName": "Bitcoin",
    "minamount": 0.1,
    "maxamount": 120,
    "tagname": "",
    "network": "",
    "available": true
  },
  "USDT": {
    "coinName": "Tether",
    "minamount": 1678.66,
    "maxamount": 2757528,
    "tagname": "",
    "network": "erc20",
    "available": true
  },
  "ETC": {
    "coinName": "Ethereum Classic",
    "minamount": 102.51,
    "maxamount": 100000,
    "tagname": "",
    "network": "",
    "available": true
  },
  "HBAR": {
    "coinName": "Hedera",
    "minamount": 100,
    "maxamount": 1000000,
    "tagname": "MEMO",
    "network": "",
    "available": true
  }
}
```

**Notes**:
- If `tagname` is not empty, `tag` is mandatory for this currency.

### 5. Get Pairs

**Endpoint**: `/pairs`

**Method**: GET

**Description**: Gets all pairs available for exchange.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).

**Response**:
- `200 OK`: JSON object with source currencies as keys and arrays of target currencies as values.

**Example**:
```bash
GET https://coincraddle.com/v1/api/pairs?key=3r8wzm4c8uo9ep5tk
```

**Response Example**:
```json
{
  "BTC": ["ZEC", "DAI", "USDT", "ETC", "BNB", "XRP"],
  "XMR": ["USDC", "BTC", "USDT", "TUSD"],
  "DASH": ["BTC", "USDT", "YEP"],
  "USDC": ["USDT", "ZEC"],
  "LOOM": ["ETH", "PERL"],
  "XRP": ["TRX"],
  "ADA": ["USDT"],
  "QTUM": ["BTC"]
}
```

### 6. Initiate Exchange

**Endpoint**: `/exchange-create`

**Method**: GET

**Description**: Initiates the exchange.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `from` (string): Source currency (e.g., `BTC`).
- `to` (string): Target currency (e.g., `ETH`).
- `amount` (float): Amount to exchange (e.g., `0.1`).
- `destinationAddress` (string): Address to send exchanged funds (e.g., `0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5`).
- `destinationTag` (string, optional): Tag for destination address.
- `refundAddress` (string): Address for refunds (e.g., `bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh`).
- `refundTag` (string, optional): Tag for refund address.
- `fix` (int): 0 for floating rate, 1 for fixed rate (e.g., `0`).

**Response**:
- `200 OK`: JSON object with exchange details.
  - `id`: exchange order ID.
  - `depositAddress`: address to send funds to.
  - `depositTag`: tag for deposit address.

**Example**:
```bash
GET https://coincraddle.com/v1/api/exchange-create?key=3r8wzm4c8uo9ep5tk&from=BTC&to=ETH&amount=0.1&destinationAddress=0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5&refundAddress=bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh&fix=0
```

**Response Example**:
```json
{
  "id": "2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a",
  "depositAddress": "bc1qgdjqv0av3q56jvd82tkdjpy7gdp9ut8tlqmgrpmv24sq90ecnvqqjwvw97",
  "depositTag": ""
}
```

### 7. Initiate Payment

**Endpoint**: `/payment/exchange-create`

**Method**: GET

**Description**: Initiates the payment.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `from` (string): Source currency (e.g., `BTC`).
- `to` (string): Target currency (e.g., `ETH`).
- `amountTo` (float): Desired amount to receive (e.g., `0.1`).
- `destinationAddress` (string): Address to send exchanged funds (e.g., `0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5`).
- `destinationTag` (string, optional): Tag for destination address.
- `refundAddress` (string): Address for refunds (e.g., `bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh`).
- `refundTag` (string, optional): Tag for refund address.

**Response**:
- `200 OK`: JSON object with payment details.
  - `id`: payment order ID.
  - `depositAddress`: address to send funds to.
  - `depositTag`: tag for deposit address.

**Example**:
```bash
GET https://coincraddle.com/v1/api/payment/exchange-create?key=3r8wzm4c8uo9ep5tk&from=BTC&to=ETH&amountTo=0.1&destinationAddress=0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5&refundAddress=bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh
```

**Response Example**:
```json
{
  "id": "2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a",
  "depositAddress": "bc1qgdjqv0av3q56jvd82tkdjpy7gdp9ut8tlqmgrpmv24sq90ecnvqqjwvw97",
  "depositTag": ""
}
```

### 8. Emergency Action

**Endpoint**: `/payment/emergency`

**Method**: GET

**Description**: Selects action for order in `payment_time_expired` status.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `id` (string): Order ID (e.g., `2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a`).
- `needExchange` (int): 1 to continue exchange at market rate, 0 to refund minus miner fee (e.g., `0`).
- `refundAddress` (string, optional): Required if `needExchange=0` (e.g., `bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh`).
- `refundTag` (string, optional): Tag for refund address.

**Response**:
- `200 OK`: `{"result": true}` on success.

**Example**:
```bash
GET https://coincraddle.com/v1/api/payment/emergency?key=3r8wzm4c8uo9ep5tk&id=2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a&needExchange=0&refundAddress=bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh
```

**Response Example**:
```json
{"result":true}
```

**Notes**:
- `needExchange`: 1 — Continue exchange at market rate, 0 — Refund minus miner fee, `refundAddress` required if `needExchange=0`.

### 9. Get Exchange Status

**Endpoint**: `/exchange-status`

**Method**: GET

**Description**: Gets the current exchange status.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `id` (string): Order ID (e.g., `2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a`).

**Response**:
- `200 OK`: JSON object with status details.
  - `status`: current status (e.g., `deposit_received`).
  - `from`: source currency.
  - `to`: target currency.
  - `depositAddress`: deposit address.
  - `depositTag`: deposit tag.
  - `destinationAddress`: destination address.
  - `destinationTag`: destination tag.
  - `refundAddress`: refund address.
  - `refundTag`: refund tag.
  - `expectedAmountFrom`: expected amount to send.
  - `expectedAmountTo`: expected amount to receive.
  - `amountFrom`: actual amount sent.
  - `date`: order creation date.
  - `txId`: transaction ID.
  - `amountTo`: actual amount received.
  - `emergency`: emergency status (e.g., `EXPIRED`).
  - `result`: boolean, success indicator.

**Example**:
```bash
GET https://coincraddle.com/v1/api/exchange-status?key=3r8wzm4c8uo9ep5tk&id=2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a
```

**Response Example**:
```json
{
  "status": "deposit_received",
  "from": "BTC",
  "to": "USDT",
  "depositAddress": "1F1tAaz5x1HUXrCNLbtMDqcw6o5GNn4xqX",
  "depositTag": "",
  "destinationAddress": "0xDAFEA492D9c6733ae3d56b7Ed1ADB60692c98Bc5",
  "destinationTag": "",
  "refundAddress": "bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh",
  "refundTag": "",
  "expectedAmountFrom": "0.1",
  "expectedAmountTo": "5443.75908463",
  "amountFrom": "0.1",
  "date": "01.05.2021 10:24:30",
  "txId": null,
  "amountTo": null,
  "emergency": {"status": "EXPIRED"},
  "result": true
}
```

### 10. Get Multiple Exchange Statuses

**Endpoint**: `/exchanges-status`

**Method**: GET

**Description**: Gets status for multiple exchanges.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `ids` (array): List of order IDs in headers (e.g., `['2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a', '3b5sa1n8-ev7c-4c43-b5ud-aa2a9347fa6k']`).

**Response**:
- `200 OK`: JSON object with status details for each order.

**Example**:
```bash
GET https://coincraddle.com/v1/api/exchanges-status?key=3r8wzm4c8uo9ep5tk
```
With header: `ids=['2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a', '3b5sa1n8-ev7c-4c43-b5ud-aa2a9347fa6k', '16257e7f-f9fc-4dbb-8ac7-63d0004b3064']`

**Response Example**:
```json
{
  "0": {
    "id": "2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a",
    "status": "time_expired",
    "from": "ICX",
    "to": "BAT"
  },
  "1": {
    "id": "3b5sa1n8-ev7c-4c43-b5ud-aa2a9347fa6k",
    "status": "time_expired",
    "from": "TRX",
    "to": "USDTTRC20"
  },
  "2": {
    "id": "16257e7f-f9fc-4dbb-8ac7-63d0004b3064",
    "status": "success",
    "from": "ICX",
    "to": "BAT"
  },
  "result": true
}
```

### 11. Get Exchange History

**Endpoint**: `/exchange-history`

**Method**: GET

**Description**: Gets a list of all exchanges.

**Parameters**:
- `key` (string): Your API key (e.g., `3r8wzm4c8uo9ep5tk`).
- `page` (int, optional): Page number.
- `limit` (int, optional): Number of records, max 100.

**Response**:
- `200 OK`: JSON object with exchange history.
  - `exchanges`: array of exchange details.
  - `limit`: number of records returned.
  - `page`: current page.
  - `result`: boolean, success indicator.

**Example**:
```bash
GET https://coincraddle.com/v1/api/exchange-history?key=3r8wzm4c8uo9ep5tk&page=0&limit=100
```

**Response Example**:
```json
{
  "exchanges": [
    {
      "id": "2a8ce4b6-ed5c-4c43-b4bd-ee2a9347fa7a",
      "status": "time_expired",
      "from": "ICX",
      "to": "BAT"
    },
    {
      "id": "3b5sa1n8-ev7c-4c43-b5ud-aa2a9347fa6k",
      "status": "time_expired",
      "from": "TRX",
      "to": "USDTTRC20"
    },
    {
      "id": "16257e7f-f9fc-4dbb-8ac7-63d0004b3064",
      "status": "success",
      "from": "ICX",
      "to": "BAT"
    }
  ],
  "limit": 100,
  "page": 0,
  "result": true
}
```

## Order Statuses

The following statuses are used to indicate the state of an exchange order:
- `new`: The order has been created.
- `waiting_deposit`: Waiting for the user to deposit funds.
- `deposit_received`: Deposit has been received.
- `exchanging`: The exchange is in progress.
- `sending`: Sending the exchanged funds to the destination address.
- `success`: The exchange was completed successfully.
- `time_expired`: The order expired due to timeout.
- `payment_time_expired`: The payment period expired.
- `failed`: The exchange failed.
- `sending_failed`: Failed to send the funds.
- `reverted`: The order was reverted.
- `payment_halted`: The payment was halted.
- `EXPIRED`: Transaction received after order expiration.
- `LESS`: Transaction amount is less than ordered.

## Error Handling

- **Error Statuses**:
  - `failed`: Exchange completed with an error.
  - `sending_failed`: Attempt to send failed.
- **Halted Order Statuses**:
  - `EXPIRED`: Transaction received after order expiration.
  - `LESS`: Transaction amount is less than ordered.
- **Refund Option**:
  - Available via `/payment/emergency` with `needExchange=0`.
  - Requires `refundAddress`.

## Security Features

- **API Key Requirement**: All requests must include the `key` parameter, available after registering in the [Partnership Program](https://coincraddle.com/partner/register).
- **Address Validation**: Use `/validate-address` to check address validity.
- **Refund Address**: Required for `/exchange-create` and `/payment/exchange-create` for fund recovery.
- **Emergency Actions**: Use `/payment/emergency` for handling expired payments, supports refund minus miner fee.
- **Exchange Status Tracking**: Detailed statuses (e.g., `new`, `time_expired`, `failed`) for order monitoring.
- **Tagname Requirement**: Mandatory for currencies with non-empty `tagname` (e.g., HBAR with MEMO).
- **Withdrawal Fee**: Floating and approximate, as shown in `/rate` response (e.g., 3.96626 USDT).

## Additional Notes

- No explicit rate limits are mentioned in the documentation.
- Example API key: `3r8wzm4c8uo9ep5tk` (for illustration only, not a real key).