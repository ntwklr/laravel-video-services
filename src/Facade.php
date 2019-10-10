<?php

namespace Ntwklr\VideoServices;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return VideoServices::class;
    }
}
