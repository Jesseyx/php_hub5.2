<?php

namespace App\Http\ApiControllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Auth;
use Authorizer;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Gate;
use Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UsersController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);
        $user->links = true; // 在 Transformer 中返回 links，我的评论 web view
        return $this->response()->item($user, new UserTransformer());
    }

    public function me()
    {
        return $this->show(Auth::id());
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);

        if (Gate::denies('update', $user)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $user = $request->performUpdate($user);
            return $this->response()->item($user, new UserTransformer());
        } catch (\Exception $e) {
            throw new UpdateResourceFailedException('无法更新用户信息：'. output_msb($e->getMessageBag()));
        }
    }
}
