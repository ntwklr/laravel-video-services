<?php

namespace Ntwklr\VideoServices\Http\Controllers;

use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use League\Flysystem\Util\MimeType;
use Ntwklr\VideoServices\Exceptions\VideoNotFoundException;
use Response;

class Thumbnail extends BaseController
{
    public function __invoke(Request $request, $service, $id, $file)
    {
        $basePath = config('video-services.storage.path');
        $imageFile = "{$basePath}/{$service}/{$id}/{$file}";

        if (! File::exists($imageFile)) {
            $prefix = config('video-services.cache.prefix');

            if (! cache()->has("{$prefix}:{$service}:video:{$id}")) {
                throw new VideoNotFoundException("{$service}:{$id}");
            }

            $prefix = config('video-services.cache.prefix');
            $ttl = config('video-services.cache.ttl');
            $video = cache()->get("{$prefix}:{$service}:video:{$id}");

            $thumbnail = cache()->remember("{$prefix}:{$service}:thumbnail:{$id}", now()->addSeconds($ttl), function () use ($video) {
                return $video->getThumbnail();
            });

            $imageFile = "{$basePath}/{$thumbnail->path}";
        }

        return Response::file($imageFile, [
            'Content-Type' => MimeType::detectByFilename($imageFile),
            'Content-Length' => File::size($imageFile),
            'Cache-Control' => 'max-age=2592000, public',
            'Expires' => date_create('+1 month')->format('D, d M Y H:i:s') . ' GMT'
        ]);
    }
}
