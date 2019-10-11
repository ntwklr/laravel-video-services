<?php

namespace Ntwklr\VideoServices;

use Illuminate\Support\Arr;
use Ntwklr\VideoServices\Exceptions\ServiceNotFoundException;

class VideoServices
{
    public function get($url)
    {
        $serviceName = ucfirst($this->guessService($url));
        $serviceClass = "Ntwklr\\VideoServices\\Services\\" . $serviceName;

        if(! class_exists($serviceClass)) {
            throw new ServiceNotFoundException($serviceName);
        }

        $service = new $serviceClass();

        return $service->info($url);
    }

    protected function guessService($url)
    {
        $services = config('video-services.services');

        $parsed_url = parse_url($url);
        $parsed_url['host'] = str_replace('www.', '', $parsed_url['host']);

        $serviceArray = Arr::where($services, function ($item, $key) use ($parsed_url) {
           return in_array($parsed_url['host'], $item['urls']) ? $key : null;
        });

        return array_key_first($serviceArray);
    }
}
