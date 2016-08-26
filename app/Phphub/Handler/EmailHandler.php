<?php

namespace App\Phphub\Handler;

use App\Models\User;
use Illuminate\Mail\Message;
use Jrean\UserVerification\Facades\UserVerification;
use Mail;
use Naux\Mail\SendCloudTemplate;

class EmailHandler
{
    public function sendActivateMail(User $user)
    {
        echo date('H:i:s') . PHP_EOL;
        UserVerification::generate($user);

        $token = $user->verification_token;
        // 第一个参数是包含邮件信息的视图名称
        // 第二个参数是你想要传递到该视图的数组数据
        // 第三个参数是接收消息实例的闭包回调——允许你自定义收件人、主题以及邮件其他方面的信息
        Mail::send('emails.fake', [], function (Message $message) use ($user, $token) {
            $message->subject(lang('Please verify your email address'));

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('template_active', [
                'name' => $user->name,
                'url'  => url('verification', $token) . '?email=' . urlencode($user->email),
            ]));

            $message->to($user->email);
        });
    }
}
