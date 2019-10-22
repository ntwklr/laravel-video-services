<?php

namespace Ntwklr\VideoServices\Clients\Youtube;

use Config;
use Illuminate\Support\Arr;
use Ntwklr\VideoServices\Clients\AbstractClient;

class ThumbnailClient extends AbstractClient
{
    public function __construct()
    {
        $this->config = Config::get('video-services.services.youtube.thumbnails');

        $this->client = $this->getClient();
    }

    public function request(string $url)
    {
        $parsed_url = parse_url($url);

        if (! empty($parsed_url['host'])) {
            $this->client = $this->getClient($parsed_url['scheme'] . "://" . $parsed_url['host']);
            $data = $this->get($parsed_url['path']);
        } else {
            $data = $this->get($url, 'maxres');
        }

        return $this->transform($data);
    }

    public function get($path, $size = null)
    {
        if ($size === null) {
            return $this->client->get($path)->getBody();
        }

        $size = Arr::get($this->config('sizes'), $size, $this->config('sizes.maxres'));

        return $this->client->get("{$path}/{$size}")->getBody();
    }

    public function transform($data)
    {
        return $data;
    }
}
