<div style="text-align: center; margin: 25px 0 15px;">

    <p style="margin-bottom: 10px;">
        <a href="https://phphub.org/topics/1531">客户端</a> 登录二维码
    </p>
    <img style="height: 180px; width: 180px;" src="data:image/png;base64,{{ base64_encode($user->present()->loginQR(180)) }}">
    <br/><br/>

    {{ Form::open(['url' => route('users.regenerate_login_token'), 'accept-charset' => 'UTF-8']) }}
        {{ Form::submit('重新生成', ['id' => 'topic-create-submit', 'class' => 'btn btn-sm btn-default']) }}
    {{ Form::close() }}

</div>
