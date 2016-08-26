@extends('layouts.default')

@section('title')
    {{ lang('Edit Profile') }}_@parent
@stop

@section('content')
    <div class="users-show">

        <div class="col-md-3 box" style="padding: 15px;">
            @include('users.partials.setting_nav')
        </div>

        <div class="main-col col-md-9 left-col">
            <div class="panel panel-default padding-md">
                <div class="panel-body">
                    <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Profile') }}</h2>
                    <hr>

                    @include('layouts.partials.errors')

                    {{ Form::model($user, ['class' => 'form-horizontal', 'method' => 'PATCH', 'url' => route('users.update', $user->id), 'accept-charset' => 'UTF-8', 'enctype' => 'multipart/form-data']) }}
                        <div class="form-group">
                            {{ Form::label('github_name', lang('GitHub Name'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('github_name', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                请跟 GitHub 上保持一致
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('email', lang('Email'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('email', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('Email example: name@website.com') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('real_name', lang('Real Name'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('real_name', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('Real Name example: 李小明') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('city', lang('City'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('city', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('City example: BeiJing') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('company', lang('Company'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('company', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('Company example: Alibaba') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('weibo_name', lang('Weibo Username'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('weibo_name', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('Weibo Username example: PHPHub') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('weibo_link', '微博个人页面', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('weibo_link', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                微博个人主页链接，如：http://weibo.com/phphub
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('twitter_account', lang('twitter_placeholder'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('twitter_account', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('twitter_placeholder_hint') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('linkedin', lang('LinkedIn'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('linkedin', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                你的 <a href="https://www.linkedin.com">LinkedIn</a> 主页完整 URL 地址，如：https://cn.linkedin.com/in/summerblue
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('personal_website', lang('personal_website_placebolder'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::text('personal_website', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('personal_website_placebolder_hint') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('wechat_qrcode', '微信账号二维码', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::file('wechat_qrcode') }}
                                @if($user->wechat_qrcode)
                                    <img class="payment-qrcode" src="{{ $user->wechat_qrcode }}" alt="" />
                                @endif
                            </div>
                            <div class="col-sm-4 help-block">
                                你的微信个人账号，或者订阅号
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('payment_qrcode', '支付二维码', ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::file('payment_qrcode') }}
                                @if($user->wechat_qrcode)
                                    <img class="payment-qrcode" src="{{ $user->payment_qrcode }}" alt="" />
                                @endif
                            </div>
                            <div class="col-sm-4 help-block">
                                文章打赏时使用，微信支付二维码
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('introduction', lang('introduction_placeholder'), ['class' => 'col-sm-2 control-label']) }}
                            <div class="col-sm-6">
                                {{ Form::textarea('introduction', null, ['class' => 'form-control', 'row' => 3, 'cols' => 50]) }}
                            </div>
                            <div class="col-sm-4 help-block">
                                {{ lang('introduction_placeholder_hint') }}，大部分情况下会在你的头像和名字旁边显示
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-6">
                                {{ Form::submit(lang('Apply Changes'), ['id' => 'user-edit-submit', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>

    </div>
@stop
