<?php

namespace Ntwklr\VideoServices\Clients\Youtube;

use File;
use Illuminate\Support\Arr;
use Ntwklr\VideoServices\Clients\AbstractClient;

class Thumbnail extends AbstractClient
{
    public function __construct()
    {
        $this->config = config('video-services.services.youtube.thumbnails');

        $this->client = $this->getClient();
    }

    public function get(string $url)
    {
        $basePath = config('video-services.storage.path');

        $parsed_url = parse_url($url);

        if ($parsed_url['host'] === 'i.ytimg.com') {
            $path = str_replace('/vi/', '', $parsed_url['path']);
        } else {
            $this->client = $this->getClient($parsed_url['scheme'] . "://" . $parsed_url['host']);
            $path = $parsed_url['path'];
        }

        $data = $this->request($path);

        $pathArray = explode('/', $path);
        $id = $pathArray[0];
        $image = $pathArray[1];
        $imagePath = "youtube/{$id}/{$image}";

        $destination = File::dirname("{$basePath}/{$imagePath}");

        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0775, true);
        }

        File::put("{$basePath}/{$imagePath}", $data);

        return $this->transformItem($imagePath);
    }

    public function request(string $uri, array $body = [])
    {
        if (empty($body)) {
            return $this->client->get($uri)->getBody();
        }

        $size = Arr::get($this->config('sizes'), $body['size'], $this->config('sizes.maxres'));

        return $this->client->get("{$uri}/{$size}")->getBody();
    }

    public function transformItem($data)
    {
        return $data;
    }
}
