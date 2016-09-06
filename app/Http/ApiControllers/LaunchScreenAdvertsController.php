<?php

namespace App\Http\ApiControllers;

use App\Transformers\LaunchScreenAdvertTransformer;
use Authorizer;
use Response;

class LaunchScreenAdvertsController extends Controller
{
    public function index()
    {
        $adverts = $this->adverts->all();

        return $this->response()->collection($adverts, new LaunchScreenAdvertTransformer());
    }
}
