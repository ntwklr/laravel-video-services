<?php

namespace Ntwklr\VideoServices\Clients\Youtube;

use Config;
use Illuminate\Support\Str;
use Ntwklr\VideoServices\Clients\AbstractClient;
use Ntwklr\VideoServices\Models\Youtube;

class Information extends AbstractClient
{
    public function __construct()
    {
        $this->config = Config::get('video-services.services.youtube.api');

        $this->client = $this->getClient();
    }

    public function get(string $url)
    {
        $id = Youtube::guessId($url);

        if(Youtube::guessType($url) === 'playlist') {
            $data = $this->request('playlistItems', [
                'query' => [
                    'playlistId' => $id,
                    'part' => 'snippet,contentDetails',
                ]
            ]);

            if (count($data['items']) === 0) {
                throw new \Exception('Video not found for given ID in API json response');
            }

            return $this->transformList($data['items']);
        }

        $data = $this->request('videos', [
            'query' => [
                'id' => $id,
                'part' => 'snippet,contentDetails',
            ]
        ]);

        if (count($data['items']) === 0) {
            throw new \Exception('Video not found for given ID in API json response');
        }

        return $this->transformItem($data['items'][0]);
    }

    public function request(string $uri, array $body = [])
    {
        $body['query']['key'] = $this->config('key');

        $response = $this->client->get($uri, $body)->getBody();

        return json_decode($response, true);
    }

    public function transformItem($data)
    {
        return [
            'id' => ! empty($data['id']) ? $data['id'] : null,
            'title' => ! empty($data['snippet']['title']) ? $data['snippet']['title'] : null,
            'description' => ! empty($data['snippet']['description']) ? $data['snippet']['description'] : null,
            'tags' => ! empty($data['snippet']['tags']) ? $data['snippet']['tags'] : null,
            'thumbnails' => ! empty($data['snippet']['thumbnails']) ? $data['snippet']['thumbnails'] : null,
            'published_at' => ! empty($data['snippet']['publishedAt']) ? $data['snippet']['publishedAt'] : null,
            'duration' => ! empty($data['contentDetails']['duration']) ? $data['contentDetails']['duration'] : null
        ];
    }

    public function transformList($data)
    {
        $return = [];

        foreach ($data as $item) {
            $return[] = $this->transformItem($item);
        }

        return $return;
    }
}
