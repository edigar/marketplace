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
}
