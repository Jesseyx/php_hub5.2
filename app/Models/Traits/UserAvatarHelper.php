<?php

namespace App\Models\Traits;

use GuzzleHttp\Client;

trait UserAvatarHelper
{
    /**
     * Cache avatar from the third party
     */
    public function cacheAvatar()
    {
        // Download Image
        $guzzle = new Client();
        $response = $guzzle->get($this->image_url);

        // Get ext
        $content_type = explode('/', $response->getHeader('Content-Type')[0]);
        $ext = array_pop($content_type);

        $avatar_name = $this->id . '_' . time() . '.' . $ext;
        $save_path = public_path('uploads/avatars/') . $avatar_name;

        // Save File
        $content = $response->getBody()->getContents();
        // 将一个字符串写入文件
        file_put_contents($save_path, $content);

        // Delete old file
        if ($this->avatar) {
            // 删除文件, 抑制错误
            @unlink(public_path('uploads/avatars/') . $this->avatar);
        }

        // Save to database
        $this->avatar = $avatar_name;
        $this->save();
    }

    public function updateAvatar($file)
    {

    }
}
