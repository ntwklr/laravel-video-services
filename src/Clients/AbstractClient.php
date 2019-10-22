<?php

namespace Ntwklr\VideoServices\Clients;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

abstract class AbstractClient
{
    protected $config;
    protected $client;

    abstract public function transform($data);

    abstract public function request(string $url);

    protected function getClient($url = null)
    {
        if($url === null) {
            $url = $this->config('url');
        }

        return new Client([
            'base_uri' => $url,
            'timeout' => $this->config('timeout'),
            'verify' => false
        ]);
    }

    protected function config($key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return Arr::get($this->config, $key);
    }
}
