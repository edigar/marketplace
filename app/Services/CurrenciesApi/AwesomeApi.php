<?php

namespace App\Services\CurrenciesApi;

use App\Exceptions\CurrencyApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

/**
 * Class AwesomeApi
 * @package App\Services\CurrenciesApi
 */
class AwesomeApi implements CurrencyApiInterface
{
    /** @var Client $httpClient */
    private $httpClient;

    /** @var string $url */
    private $url;

    /** @var array $validCurrencies */
    private $validCurrencies;

    /**
     * AwesomeApi constructor.
     *
     * @param Client $httpClient
     * @param string $url
     * @param array $validCurrencies
     * @return void
     */
    public function __construct(Client $httpClient, string $url, array $validCurrencies)
    {
        $this->httpClient = $httpClient;
        $this->url = $url;
        $this->validCurrencies = $validCurrencies;
    }

    /**
     * Gets conversion amount to different currencies
     *
     * @param float $amount
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function convert(float $amount): array
    {
        $exchangeRates = $this->getExchangeRate();

        $conversions = [];
        foreach ($exchangeRates as $exchangeRates) {
            $conversions[$exchangeRates->codein] = (float)$exchangeRates->bid * $amount;
        }

        return $conversions;
    }

    /**
     * Get exchange rate from awesomeApi api
     *
     * @return \stdClass
     * @throws \GuzzleHttp\Exception\GuzzleException|CurrencyApiException|Throwable
     */
    public function getExchangeRate(): \stdClass
    {
        try {
            $response = $this->httpClient->request('GET', $this->getUri());
        } catch (GuzzleException $guzzleException) {
            if ($guzzleException->getCode() == 0) {
                throw new CurrencyApiException('Unreachable currency api (' . $this->url . ')', 500);
            }

            preg_match('#\{(.*?)\}#', $guzzleException->getMessage(), $apiResponse);
            if(isset($apiResponse[1])) {
                $apiResponse = json_decode($apiResponse[0], true);
                throw new CurrencyApiException(
                    'Currency api error. API message: ' . $apiResponse['message'],
                    500
                );
            }

            throw new CurrencyApiException('Currency api problem', 500);
        }

        return $response->getBody() != null ? json_decode($response->getBody()->getContents()) : new \stdClass();
    }

    /**
     * Build awesomeApi uri
     *
     * @return string
     * @throws CurrencyApiException|Throwable
     */
    private function getUri(): string
    {
        throw_if(
            $this->validCurrencies == null || empty($this->validCurrencies),
            new CurrencyApiException('Currencies not set', 503)//\Exception('Currencies not set', 503)
        );

        $urn = '/last/';
        foreach ($this->validCurrencies as $currency) {
            $urn .= 'BRL-' . $currency . ',';
        }

        return $this->url . substr($urn,0,-1);
    }
}
