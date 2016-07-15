<?php

namespace App\Phphub\Creators;

use App\Phphub\Listeners\UserCreatorListener;
use App\User;

class UserCreator
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    // 观察者
    public function create(UserCreatorListener $observer, $data)
    {
        $user = User::create($data);

        if (!$user) {
            return $observer->userValidationError();
        }

        $user->cacheAvatar();

        return $observer->userCreated($user);
    }
}
