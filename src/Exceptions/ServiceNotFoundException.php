<?php

namespace Ntwklr\VideoServices\Exceptions;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ServiceNotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
        public function __construct($service, $message = null, $code = 404, Throwable $previous = null)
        {
            if($message === null) {
                $message = sprintf('You have requested a non-existent service "%s".', $service);
            }

            parent::__construct($message, $code, $previous);
        }
}
