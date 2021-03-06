<?php

namespace App\Phphub\Creators;

use App\Models\User;
use App\Phphub\Listeners\UserCreatorListener;

/**
* This class can call the following methods on the observer object:
*
* userValidationError($errors)
* userCreated($user)
*/
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
            return $observer->userValidationError($user->getErrors());
        }

        $user->cacheAvatar();

        return $observer->userCreated($user);
    }
}
