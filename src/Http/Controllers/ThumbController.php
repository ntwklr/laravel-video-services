<?php

namespace Ntwklr\VideoServices\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Ntwklr\VideoServices\Exceptions\ServiceNotFoundException;

class ThumbController extends BaseController
{
    public function __invoke(Request $request, $service, $id)
    {
        $serviceName = ucfirst($service);
        $serviceClass = "Ntwklr\\VideoServices\\Services\\" . $serviceName;

        if(! class_exists($serviceClass)) {
            throw new ServiceNotFoundException($serviceName);
        }

        $service = new $serviceClass();

        $info = $service->get($id);
    }
}
