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

    public static function guessId($url, $type = null)
    {
        $parsed_url = parse_url($url);

        if ($type === null) {
            $type = static::guessType($url);
        }

        if ($type === 'playlist') {
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

    public static function guessType($url)
    {
        $parsed_url = parse_url($url);

        if ((! empty($parsed_url['path'])) && explode('/', $parsed_url['path'])[1] === 'playlist') {
            return 'playlist';
        }

        return 'video';
    }

    public function getThumbnail($size = null)
    {
        if ($size === null) {
            $thumbnail = (object) $this->thumbnails->sortByDesc('width')->first();
        } elseif ($this->thumbnails->has($size)) {
            $thumbnail = (object) $this->thumbnails->get($size);
        }

        $client = new Thumbnail();
        $thumbnail->path = $client->get($thumbnail->url);

        return $thumbnail;
    }
}
