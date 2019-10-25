<?php

namespace Ntwklr\VideoServices\Clients\Youtube;

use Config;
use Illuminate\Support\Arr;
use Ntwklr\VideoServices\Clients\AbstractClient;

class Thumbnail extends AbstractClient
{
    public function __construct()
    {
        $this->config = Config::get('video-services.services.youtube.thumbnails');

        $this->client = $this->getClient();
    }

    public function request(string $uri, array $body = [])
    {
        if(empty($body)) {
            return $this->client->get($uri)->getBody();
        }

        $size = Arr::get($this->config('sizes'), $body['size'], $this->config('sizes.maxres'));

        return $this->client->get("{$uri}/{$size}")->getBody();
    }

    public function get(string $url)
    {
        $parsed_url = parse_url($url);

        if (! empty($parsed_url['host'])) {
            $this->client = $this->getClient($parsed_url['scheme'] . "://" . $parsed_url['host']);
            $data = $this->request($parsed_url['path']);
        } else {
            $data = $this->request($url, ['maxres']);
        }

        return $this->transformItem($data);
    }

    public function transformItem($data)
    {
        return $data;
    }
}
