<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SwaggerScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swagger:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a swagger.json file at the public directory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $path = dirname(dirname(__DIR__));
        $outputPath = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'public/swagger.json';
        $this->info('Scanning ' . $path);
        $openApi = \OpenApi\Generator::scan([$path]);
        header('Content-Type: application/json');
        file_put_contents($outputPath, $openApi->toJson());
        $this->info('Output ' . $outputPath);
    }
}
