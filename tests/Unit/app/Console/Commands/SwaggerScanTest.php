<?php

namespace Unit\app\Console\Commands;

use Laravel\Lumen\Testing\TestCase;

/**
 * Class SwaggerScanTest
 * @package Unit\app\Console\Commands
 */
class SwaggerScanTest extends TestCase
{

    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function test_abc()
    {
        $this->artisan('swagger:scan');

        $this->assertFileExists(__DIR__ . '/../../../../../public/swagger.json');
    }
}
