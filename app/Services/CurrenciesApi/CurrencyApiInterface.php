<?php

namespace App\Services\CurrenciesApi;

/**
 * Interface CurrencyApiInterface
 * @package App\Services\CurrenciesApi
 */
interface CurrencyApiInterface
{
    /**
     * @param float $amount
     * @return array
     */
    public function convert(float $amount): array;
}
