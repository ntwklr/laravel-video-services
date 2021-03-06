<?php

namespace Ntwklr\VideoServices;

use Ntwklr\VideoServices\Repositories\Video;

class VideoServices
{
    public function find($url)
    {
        return Video::find($url);
    }

    public function playlist($url)
    {
        return (Video::getServiceModel($url))::playlist($url);
    }
}
