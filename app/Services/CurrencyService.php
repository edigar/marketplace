<?php

namespace App\Services;

use App\Services\CurrenciesApi\CurrencyApiInterface;

/**
 * Class CurrencyService
 * @package App\Services
 */
class CurrencyService implements CurrencyServiceInterface
{
    /** @var CurrencyApiInterface $currencyApi */
    private $currencyApi;

    /**
     * CurrencyService constructor.
     *
     * @param CurrencyApiInterface $currencyApi
     * @return void
     */
    public function __construct(CurrencyApiInterface $currencyApi)
    {
        $this->currencyApi = $currencyApi;
    }

    /**
     * @param float $amount
     * @return array
     */
    public function convert(float $amount): array
    {
        return $this->currencyApi->convert($amount);
    }
}
