<?php

namespace App\Services;

/**
 * Interface CurrencyServiceInterface
 * @package App\Services
 */
interface CurrencyServiceInterface
{
    /**
     * @param float $amount
     * @return array
     */
    public function convert(float $amount): array;
}
