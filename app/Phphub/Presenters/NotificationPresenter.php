<?php

namespace App\Phphub\Presenters;

use Laracasts\Presenter\Presenter;

class NotificationPresenter extends Presenter
{
    public function lableUp()
    {
        switch ($this->type) {
            case 'comment_append':
                $label = lang('Commented topic has new update:');
                break;

            default:
                break;
        }

        return $label;
    }
}
