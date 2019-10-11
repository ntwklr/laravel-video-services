<?php

namespace Ntwklr\VideoServices;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

/**
 * Class VideoServices
 *
 * @package Ntwklr\VideoServices
 */
class Facade extends IlluminateFacade
{
    /**
     * Get the registered name of the component.
     *
     * @see \Ntwklr\VideoServices\VideoServices
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'videoservices';
    }
}
