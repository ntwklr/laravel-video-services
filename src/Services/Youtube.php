<?php

namespace Ntwklr\VideoServices\Services;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Youtube extends Service
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('video-services.services.youtube.api.url'),
            'timeout' => 10.0,
            'verify' => false,
            'query' => [
                'key' => config('video-services.services.youtube.api.key')
            ]
        ]);
    }

    public function get($url)
    {
        $parsed_url = parse_url($url);
        $parsed_url['host'] = str_replace('www.', '', $parsed_url['host']);
        parse_str(!empty($parsed_url['query']) ? $parsed_url['query'] : '', $params_url);

        if (Str::contains($parsed_url['host'], 'youtube.com')) {
            $id = $params_url['v'];
        } elseif (Str::contains($parsed_url['host'], 'youtu.be')) {
            $id = explode('/', $parsed_url['path'])[1];
        }

        $response = $this->client->get("/videos", [
            'query' => [
                'id' => $id,
                'part' => 'snippet,contentDetails',
            ],
        ])->getBody();

        return $this->transform(json_decode($response, true));
    }

    protected function transform(array $data)
    {
        return (object) [
            'id' => $data['id'],
            'title' => $data['snippet']['title'],
            'description' => ! empty($data['snippet']['description']) ? $data['snippet']['description'] : null,
            'thumbnail' => end($data['snippet']['thumbnails'])['url'],
            'tags' => ! empty($data['snippet']['tags']) ? $data['snippet']['tags'] : null,
            'published_at' => Carbon::createFromTimeString($data['snippet']['publishedAt']),
            'duration' => CarbonInterval::fromString($data['contentDetails']['duration'])
        ];
    }
}
