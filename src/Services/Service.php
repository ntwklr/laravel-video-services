<?php

namespace Ntwklr\VideoServices\Services;

abstract class Service
{
    abstract protected function info(string $url);
    abstract protected function transform(array $data);
}
