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

    public function transform(array $data)
    {
        return [
            'id' => ! empty($data['id']) ? $data['id'] : null,
            'title' => ! empty($data['snippet']['title']) ? $data['snippet']['title'] : null,
            'description' => ! empty($data['snippet']['description']) ? $data['snippet']['description'] : null,
            'thumbnail' => ! empty($data['snippet']['thumbnails']) ? end($data['snippet']['thumbnails'])['url'] : null,
            'tags' => ! empty($data['snippet']['tags']) ? $data['snippet']['tags'] : null,
            'published_at' => ! empty($data['snippet']['publishedAt']) ? Carbon::createFromTimeString($data['snippet']['publishedAt']) : null,
            'duration' => ! empty($data['contentDetails']['duration']) ? CarbonInterval::fromString($data['contentDetails']['duration']) : null
        ];
    }

    protected function requestData(string $url)
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

        $data = json_decode($response, true);

        if (empty($data['items'][0])) {
            throw new \Exception('Video not found for given ID in API json response');
        }

        return $data['items'][0];
    }
}
