<?php

namespace Ntwklr\VideoServices\Exceptions;

use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class VideoNotFoundException extends VideoServiceException implements NotFoundExceptionInterface
{
    public function __construct($service, $message = null, $code = 404, Throwable $previous = null)
    {
        if ($message === null) {
            $message = sprintf('You have requested a non-existent video "%s".', $service);
        }

        parent::__construct($message, $code, $previous);
    }
}
