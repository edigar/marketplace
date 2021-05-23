<?php

namespace Unit\app\Services\CurrenciesApi;

use App\Services\CurrenciesApi\AwesomeApi;
use PHPUnit\Framework\TestCase;

class AwesomeApiTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \App\Exceptions\CurrencyApiException
     * @throws \Throwable
     */
    public function testGetExchangeRateCanRequest()
    {
        $httpClientMock = $this->createMock('\GuzzleHttp\Client');
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'test.com/last/BRL-USD,BRL-EUR');

        $awesomeApi = new AwesomeApi($httpClientMock, 'test.com', ['USD', 'EUR']);

        $awesomeApi->getExchangeRate();
    }

    /**
     * @throws \ReflectionException
     */
    public function testItCanGenerateUri()
    {
        $getUri = $this->setPrivateMethodAccessible('App\Services\CurrenciesApi\AwesomeApi', 'getUri');

        $awesomeApi = new AwesomeApi(new \GuzzleHttp\Client(), 'test.com', ['USD', 'EUR']);
        $uri = $getUri->invokeArgs($awesomeApi, []);

        $this->assertEquals('test.com/last/BRL-USD,BRL-EUR', $uri);
    }

    /**
     * @throws \ReflectionException
     */
    private function setPrivateMethodAccessible(string $class, $method): \ReflectionMethod
    {
        $classReflection = new \ReflectionClass($class);
        $privateMethod = $classReflection->getMethod($method);
        $privateMethod->setAccessible(true);

        return $privateMethod;
    }
}
