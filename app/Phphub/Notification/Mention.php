<?php

namespace App\Phphub\Notification;

use App\User;

class Mention
{
    protected $body_original;
    protected $userNames;
    protected $users = [];
    protected $body_parsed;

    protected function getMentionedUserNames()
    {
        preg_match_all("/(\S*)\@([^\r\n\s]*)/i", $this->body_original, $atListTmp);

        $userNames = [];

        foreach ($atListTmp[2] as $k => $v) {
            // 排除 12232@sadasd 等类似 email 的，或者长度超过用户名长度的
            if ($atListTmp[1][$k] || strlen($v) > 25) {
                continue;
            }
            $userNames[] = $v;
        }

        return array_unique($userNames);
    }

    protected function replace()
    {
        $this->body_parsed = $this->body_original;

        foreach ($this->users as $user) {
            $search = '@' . $user->name;
            // 替换为 markdown 格式
            $place = '[' . $search .'](' . '#' .')';
            $this->body_parsed = str_replace($search, $place, $this->body_parsed);
        }
    }

    public function parse($body)
    {
        $this->body_original = $body;
        $this->userNames = $this->getMentionedUserNames();

        count($this->userNames) > 0 && $this->users = User::whereIn('name', $this->userNames)->get();

        $this->replace();

        return $this->body_parsed;
    }
}
