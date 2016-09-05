<?php

namespace App\Http\ApiControllers;

use Authorizer;
use Response;

class OauthController extends Controller
{
    public function issueAccessToken()
    {
        return Response::json(Authorizer::issueAccessToken());
    }
}
