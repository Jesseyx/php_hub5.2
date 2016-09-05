<?php

namespace App\Http\ApiControllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Authorizer;

class OauthController extends Controller
{
    public function issueAccessToken()
    {
        return response()->json(Authorizer::issueAccessToken());
    }
}
