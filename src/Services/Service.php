<?php

namespace Ntwklr\VideoServices\Services;

abstract class Service
{
    abstract protected function transform(array $data);
}
