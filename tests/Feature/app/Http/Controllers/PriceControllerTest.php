<?php

namespace Feature\app\Http\Controllers;

use Laravel\Lumen\Testing\TestCase;

class PriceControllerTest extends TestCase
{

    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testRequestShouldNotValidateWithNonNumericPrice()
    {
        $request = $this->get(route('price-conversion', ['amount' => 'abc']));

        $request->assertResponseStatus(422);
        $request->seeJson(['amount' => ['The amount must be a number.']]);
    }

    public function testRequestShouldNotValidateWithOutOfRangePrice()
    {
        $requestInferior = $this->get(route('price-conversion', ['amount' => -1]));
        $requestSuperior = $this->get(route('price-conversion', ['amount' => 1000000]));

        $requestInferior->assertResponseStatus(422);
        $requestInferior->seeJson(['amount' => ['The amount must be between 0 and 999999.']]);
        $requestSuperior->assertResponseStatus(422);
        $requestSuperior->seeJson(['amount' => ['The amount must be between 0 and 999999.']]);
    }

    public function testRequestShouldReturnSuccess()
    {
        $request = $this->get(route('price-conversion', ['amount' => 10]));
        $request->assertResponseStatus(200);
    }
}
