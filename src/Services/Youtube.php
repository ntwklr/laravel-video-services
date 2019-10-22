<?php

namespace Ntwklr\VideoServices\Services;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Youtube extends Service
{
    protected $client;

    protected $url;

    public function __construct(string $url)
    {
        $this->client = new Client([
            'base_uri' => config('video-services.services.youtube.api.url'),
            'timeout' => 10.0,
            'verify' => false
        ]);

        $this->url = $url;
    }

    public function get()
    {
        $this->fill($this->transform($this->requestData($this->url)));

        return $this;
    }


}
