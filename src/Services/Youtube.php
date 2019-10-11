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
            'verify' => false
        ]);
    }

    public function info(string $url)
    {
        $parsed_url = parse_url($url);

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

        $response = $this->client->get("videos", [
            'query' => [
                'id' => $id,
                'part' => 'snippet,contentDetails',
                'key' => config('video-services.services.youtube.api.key')
            ],
        ])->getBody();

        return $this->transform(json_decode($response, true));
    }

    protected function transform(array $data)
    {
        if (empty($data['items'][0])) {
            throw new \Exception('Video not found for given ID in API json response');
        }

        $data = $data['items'][0];

        return (object) [
            'id' => ! empty($data['id']) ? $data['id'] : null,
            'title' => ! empty($data['snippet']['title']) ? $data['snippet']['title'] : null,
            'description' => ! empty($data['snippet']['description']) ? $data['snippet']['description'] : null,
            'thumbnail' => end($data['snippet']['thumbnails'])['url'],
            'tags' => ! empty($data['snippet']['tags']) ? $data['snippet']['tags'] : null,
            'published_at' => Carbon::createFromTimeString($data['snippet']['publishedAt']),
            'duration' => CarbonInterval::fromString($data['contentDetails']['duration'])
        ];
    }
}
