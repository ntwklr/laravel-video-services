<?php

namespace Ntwklr\VideoServices\Exceptions;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class VideoServiceException extends InvalidArgumentException implements NotFoundExceptionInterface
{

}
