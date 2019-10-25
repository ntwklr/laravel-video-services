<?php

namespace Ntwklr\VideoServices\Models;

use Illuminate\Support\Str;
use Ntwklr\VideoServices\Clients\Youtube\Information;
use Ntwklr\VideoServices\Clients\Youtube\Thumbnail;

class Youtube extends Video
{
    public static function find($url)
    {
        $client = new Information();

        return new static((array) $client->get($url));
    }

    public static function playlist($url)
    {
        $client = new Information();

        return static::hydrate((array) $client->get($url));
    }

    public function getThumbnail($size = 'default')
    {
        if ($this->thumbnails->has($size)) {
            $thumbnail = (object) $this->thumbnails->get($size);
        } else {
            $thumbnail = (object) $this->thumbnails->sortByDesc('width')->first();
        }

        $client = new Thumbnail();
        $thumbnail->blob = $client->get($thumbnail->url);
    }

    public static function guessType($url)
    {
        $parsed_url = parse_url($url);

        if ((! empty($parsed_url['path'])) && explode('/', $parsed_url['path'])[1] === 'playlist') {
            return 'playlist';
        }

        return 'video';
    }

    public static function guessId($url)
    {
        $parsed_url = parse_url($url);

        if(static::guessType($url) === 'playlist') {
            return explode('=', $parsed_url['query'])[1];
        }

        if (! empty($parsed_url['host'])) {
            $parsed_url['host'] = str_replace('www.', '', $parsed_url['host']);
            parse_str(! empty($parsed_url['query']) ? $parsed_url['query'] : '', $params_url);

            if (Str::contains($parsed_url['host'], 'youtube.com')) {
                $id = $params_url['v'];
            } elseif (Str::contains($parsed_url['host'], 'youtu.be')) {
                $id = explode('/', $parsed_url['path'])[1];
            }
        } elseif (! empty($parsed_url['path'])) {
            $id = $parsed_url['path'];
        } else {
            $id = $url;
        }

        return $id;
    }
}
