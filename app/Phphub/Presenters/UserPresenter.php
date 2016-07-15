<?php

namespace App\Phphub\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
{
    /**
     * Present a link to the user gravatar.
     */
    public function gravatar($size = 100)
    {
        if (config('phphub.url_static') && $this->avatar) {
            return cdn('uploads/avatars/' . $this->avatar) . "?imageView2/1/w/{$size}/h/{$size}";
        }

        $github_id = $this->github_id;
        $domainNumber = rand(0, 3);

        return "https://avatars{$domainNumber}.githubusercontent.com/u/{$github_id}?v=2&s={$size}";
    }
}
