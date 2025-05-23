<?php

namespace Coincraddle;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CoincraddleClient
{
    private const BASE_URL = 'https://coincraddle.com/v1/api';
    private string $apiKey;
    private Client $httpClient;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => 30,
        ]);
    }

    /**
     * Validate cryptocurrency address
     *
     * @param string $currency Currency code (e.g., BTC)
     * @param string $address Address to validate
     * @return bool
     * @throws GuzzleException
     */
    public function validateAddress(string $currency, string $address): bool
    {
        $response = $this->get('/validate-address', [
            'currency' => $currency,
            'address' => $address,
        ]);

        return $response['result'] ?? false;
    }

    /**
     * Get exchange rate
     *
     * @param string $from Source currency
     * @param string $to Target currency
     * @param float $amount Amount to exchange
     * @param int $fix 0 for floating rate, 1 for fixed rate
     * @return array
     * @throws GuzzleException
     */
    public function getRate(string $from, string $to, float $amount, int $fix = 0): array
    {
        return $this->get('/rate', [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'fix' => $fix,
        ]);
    }

    /**
     * Get payment rate
     *
     * @param string $from Source currency
     * @param string $to Target currency
     * @param float $amountTo Desired amount to receive
     * @return array
     * @throws GuzzleException
     */
    public function getPaymentRate(string $from, string $to, float $amountTo): array
    {
        return $this->get('/payment/rate', [
            'from' => $from,
            'to' => $to,
            'amountTo' => $amountTo,
        ]);
    }

    /**
     * Get available currencies
     *
     * @return array
     * @throws GuzzleException
     */
    public function getCurrencies(): array
    {
        return $this->get('/currencies');
    }

    /**
     * Get available exchange pairs
     *
     * @return array
     * @throws GuzzleException
     */
    public function getPairs(): array
    {
        return $this->get('/pairs');
    }

    /**
     * Create exchange order
     *
     * @param string $from Source currency
     * @param string $to Target currency
     * @param float $amount Amount to exchange
     * @param string $destinationAddress Address to send exchanged funds
     * @param string $refundAddress Address for refunds
     * @param string|null $destinationTag Tag for destination address
     * @param string|null $refundTag Tag for refund address
     * @param int $fix 0 for floating rate, 1 for fixed rate
     * @return array
     * @throws GuzzleException
     */
    public function createExchange(
        string $from,
        string $to,
        float $amount,
        string $destinationAddress,
        string $refundAddress,
        ?string $destinationTag = null,
        ?string $refundTag = null,
        int $fix = 0
    ): array {
        $params = [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'destinationAddress' => $destinationAddress,
            'refundAddress' => $refundAddress,
            'fix' => $fix,
        ];

        if ($destinationTag !== null) {
            $params['destinationTag'] = $destinationTag;
        }

        if ($refundTag !== null) {
            $params['refundTag'] = $refundTag;
        }

        return $this->get('/exchange-create', $params);
    }

    /**
     * Create payment order
     *
     * @param string $from Source currency
     * @param string $to Target currency
     * @param float $amountTo Desired amount to receive
     * @param string $destinationAddress Address to send exchanged funds
     * @param string $refundAddress Address for refunds
     * @param string|null $destinationTag Tag for destination address
     * @param string|null $refundTag Tag for refund address
     * @return array
     * @throws GuzzleException
     */
    public function createPayment(
        string $from,
        string $to,
        float $amountTo,
        string $destinationAddress,
        string $refundAddress,
        ?string $destinationTag = null,
        ?string $refundTag = null
    ): array {
        $params = [
            'from' => $from,
            'to' => $to,
            'amountTo' => $amountTo,
            'destinationAddress' => $destinationAddress,
            'refundAddress' => $refundAddress,
        ];

        if ($destinationTag !== null) {
            $params['destinationTag'] = $destinationTag;
        }

        if ($refundTag !== null) {
            $params['refundTag'] = $refundTag;
        }

        return $this->get('/payment/exchange-create', $params);
    }

    /**
     * Handle emergency action for expired payment
     *
     * @param string $id Order ID
     * @param int $needExchange 1 to continue exchange at market rate, 0 to refund
     * @param string|null $refundAddress Required if needExchange=0
     * @param string|null $refundTag Tag for refund address
     * @return bool
     * @throws GuzzleException
     */
    public function handleEmergency(
        string $id,
        int $needExchange,
        ?string $refundAddress = null,
        ?string $refundTag = null
    ): bool {
        $params = [
            'id' => $id,
            'needExchange' => $needExchange,
        ];

        if ($refundAddress !== null) {
            $params['refundAddress'] = $refundAddress;
        }

        if ($refundTag !== null) {
            $params['refundTag'] = $refundTag;
        }

        $response = $this->get('/payment/emergency', $params);
        return $response['result'] ?? false;
    }

    /**
     * Get exchange status
     *
     * @param string $id Order ID
     * @return array
     * @throws GuzzleException
     */
    public function getExchangeStatus(string $id): array
    {
        return $this->get('/exchange-status', ['id' => $id]);
    }

    /**
     * Get multiple exchange statuses
     *
     * @param array $ids List of order IDs
     * @return array
     * @throws GuzzleException
     */
    public function getMultipleExchangeStatuses(array $ids): array
    {
        $response = $this->httpClient->get('/exchanges-status', [
            'headers' => [
                'ids' => json_encode($ids),
            ],
            'query' => [
                'key' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get exchange history
     *
     * @param int|null $page Page number
     * @param int|null $limit Number of records, max 100
     * @return array
     * @throws GuzzleException
     */
    public function getExchangeHistory(?int $page = null, ?int $limit = null): array
    {
        $params = [];
        if ($page !== null) {
            $params['page'] = $page;
        }
        if ($limit !== null) {
            $params['limit'] = $limit;
        }

        return $this->get('/exchange-history', $params);
    }

    /**
     * Make GET request to API
     *
     * @param string $endpoint API endpoint
     * @param array $params Query parameters
     * @return array
     * @throws GuzzleException
     */
    private function get(string $endpoint, array $params = []): array
    {
        $params['key'] = $this->apiKey;
        
        $response = $this->httpClient->get($endpoint, [
            'query' => $params,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
} 