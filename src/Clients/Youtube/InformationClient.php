<?php

namespace Ntwklr\VideoServices\Clients\Youtube;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Config;
use Illuminate\Support\Str;
use Ntwklr\VideoServices\Clients\AbstractClient;

class InformationClient extends AbstractClient
{
    public function __construct()
    {
        $this->config = Config::get('video-services.services.youtube.api');

        $this->client = $this->getClient();
    }

    public function request(string $url)
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

        $data = $this->get('videos', [
            'query' => [
                'id' => $id,
                'part' => 'snippet,contentDetails',
            ]
        ]);

        if (empty($data['items'][0])) {
            throw new \Exception('Video not found for given ID in API json response');
        }

        return $this->transform($data['items'][0]);
    }

    public function get($uri, $body)
    {
        $body['query']['key'] = $this->config('key');

        $response = $this->client->get($uri, $body)->getBody();

        return json_decode($response, true);
    }

    public function transform($data)
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
}
