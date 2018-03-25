<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi\Tests;

use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;
use Kirkaracha\PropublicaCongressApi\PropublicaCongressApiFacade;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PropublicaCongressApi::class
        ];
    }

    /**
     * Load package alias
     *
     * @param $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'PropublicaCongressApi' => PropublicaCongressApiFacade::class,
        ];
    }
}
