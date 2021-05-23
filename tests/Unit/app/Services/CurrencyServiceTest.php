<?php

namespace Unit\app\Services;

use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyServiceTest extends TestCase
{
    public function testItCanConvert()
    {
        $awesomeApiMock = $this->createMock('\App\Services\CurrenciesApi\AwesomeApi');
        $awesomeApiMock->expects($this->once())->method('convert')->with(10);

        $currencyService = new CurrencyService($awesomeApiMock);
        $currencyService->convert(10);
    }
}
