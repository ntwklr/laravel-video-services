<?php

namespace Ntwklr\VideoServices\Repositories;

use Illuminate\Support\Collection;
use Ntwklr\VideoServices\Models\Video as VideoModel;

class Video
{
    public static function find($url): VideoModel
    {
        try {
            $model = VideoModel::getServiceModel($url);
            $modelName = strtolower(class_basename($model));

            $prefix = config('video-services.cache.prefix');
            $type = $model::guessType($url);
            $id = $model::guessId($url);
            $ttl = config('video-services.cache.ttl');

            return cache()->remember("{$prefix}:{$modelName}:{$type}:{$id}", now()->addSeconds($ttl), function () use ($model, $url) {
                return $model::find($url);
            });
        } catch (\Exception $e) {
            return new Collection();
        }
    }

    public static function thumbnail($id)
    {

    }

    public static function playlist(): Collection
    {

    }
}
