<?php

namespace Ntwklr\VideoServices;

use Illuminate\Support\Arr;
use Ntwklr\VideoServices\Services;

class VideoServices
{
    public function get($url)
    {
        $serviceName = $this->guessService($url);
        $serviceClass = "Ntwklr\VideoServices\Services\{$serviceName}";

        $service = new $serviceClass();

        return $service->get($url);
    }

    protected function guessService($url)
    {
        $services = config('video-services.services');

        $parsed_url = parse_url($url);
        $parsed_url['host'] = str_replace('www.', '', $parsed_url['host']);

        Arr::where($services, function ($item, $key) use ($parsed_url) {
           return Arr::has($item, "urls.{$parsed_url['host']}");
        });
    }
}
