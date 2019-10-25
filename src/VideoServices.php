<?php

namespace Ntwklr\VideoServices;

use Ntwklr\VideoServices\Models\Video;

class VideoServices
{
    public function find($url)
    {
        return (Video::getServiceModel($url))::find($url);
    }

    public function playlist($url)
    {
        return (Video::getServiceModel($url))::playlist($url);
    }
}
