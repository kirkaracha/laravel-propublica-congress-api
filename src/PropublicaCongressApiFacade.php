<?php declare(strict_types=1);

namespace Kirkaracha\PropublicaCongressApi;

use Illuminate\Support\Facades\Facade;

class PropublicaCongressApiFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'propublica-congress-api';
    }
}
