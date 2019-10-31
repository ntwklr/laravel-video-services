<?php

namespace Ntwklr\VideoServices\Repositories;

use Illuminate\Support\Collection;
use Ntwklr\VideoServices\Exceptions\VideoNotFoundException;
use Ntwklr\VideoServices\Models\Video as VideoModel;

class Video
{
    public static function find($url)
    {
        try {
            $model = VideoModel::getServiceModel($url);
            $modelName = strtolower(class_basename($model));

            $prefix = config('video-services.cache.prefix');
            $type = $model::guessType($url);
            $id = $model::guessId($url, $type);
            $ttl = config('video-services.cache.ttl');

            return cache()->remember("{$prefix}:{$modelName}:{$type}:{$id}", now()->addSeconds($ttl), function () use ($model, $url) {
                return $model::find($url);
            });
        } catch (\Exception $e) {
            return new Collection();
        }
    }

    public static function thumbnail($url)
    {
        try {
            $model = VideoModel::getServiceModel($url);
            $modelName = strtolower(class_basename($model));

            $prefix = config('video-services.cache.prefix');
            $type = $model::guessType($url);
            $id = $model::guessId($url, $type);
            $ttl = config('video-services.cache.ttl');

            if(! cache()->has("{$prefix}:{$modelName}:{$type}:{$id}")) {
                throw new VideoNotFoundException("{$modelName}:{$id}");
            }

            return cache()->remember("{$prefix}:{$modelName}:thumbnail:{$id}", now()->addSeconds($ttl), function () use ($model, $url) {
                $video = $model::find($url);

                return $video->getThumbnail();
            });
        } catch (\Exception $e) {
            return new Collection();
        }
    }
}
