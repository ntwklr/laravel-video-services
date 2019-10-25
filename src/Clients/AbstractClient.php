<?php

namespace Ntwklr\VideoServices\Clients;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

abstract class AbstractClient
{
    protected $config;
    protected $client;

    abstract public function transformItem($data);

    abstract public function get(string $url);

    abstract public function request(string $uri, array $body = []);

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
