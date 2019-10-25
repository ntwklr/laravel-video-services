<?php

namespace Ntwklr\VideoServices\Models;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Jenssegers\Model\Model as JenssegersModel;
use Ntwklr\VideoServices\Exceptions\ServiceNotFoundException;

class Video extends JenssegersModel
{
    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'description' => 'string',
        'thumbnails' => 'collection',
        'tags' => 'collection'
    ];

    public function getPublishedAtAttribute($value)
    {
        return Carbon::createFromTimeString($value);
    }

    public function getDurationAttribute($value)
    {
        return CarbonInterval::fromString($value);
    }

    protected static function guessService($url)
    {
        $services = config('video-services.services');

        $parsed_url = parse_url($url);
        $parsed_url['host'] = str_replace('www.', '', $parsed_url['host']);

        $serviceArray = Arr::where($services, function ($item, $key) use ($parsed_url) {
            return in_array($parsed_url['host'], $item['urls']) ? $key : null;
        });

        $serviceName = ucfirst(array_key_first($serviceArray));

        $modelClass = "Ntwklr\\VideoServices\\Models\\" . $serviceName;

        if (! class_exists($modelClass)) {
            throw new ServiceNotFoundException($serviceName);
        }

        return $modelClass;
    }

    public static function getServiceModel($url)
    {
        return static::guessService($url);
    }

    /**
     * Create a collection of models from plain arrays.
     *
     * @param  array  $items
     * @return array
     */
    public static function hydrate(array $items)
    {
        $instance = new static;

        $items = array_map(function ($item) use ($instance) {
            return $instance->newInstance($item);
        }, $items);

        return collect($items);
    }
}
