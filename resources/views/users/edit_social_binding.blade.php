@extends('layouts.default')

@section('title')
    {{ lang('Edit Social Binding') }}_@parent
@stop

@section('content')

    <div class="users-show">

        <div class="col-md-3 box" style="padding: 15px">
            @include('users.partials.setting_nav')
        </div>

        <div class="main-col col-md-9 left-col">

            <div class="panel panel-default padding-md">

                <div class="panel-body ">

                    <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Social Binding') }}</h2>
                    <hr>

                    @include('layouts.partials.errors')

                    <div class="alert alert-warning">
                        {{ lang('Setup multiple Bindings allow you to login to the same account with different binding site account.') }}
                    </div>

                    {{ Form::open(['url' => route('users.update_email_notify', $user->id), 'class' => 'form-horizontal', 'accept-charset' => 'UTF-8']) }}

                        <div class="form-group">
                            {{ Form::label('', lang('Register Binding'), ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                <a class="btn btn-success login-btn weichat-login-btn{{ $user->register_source == ' wechat' ? '' : ' hide' }}" role="button">
                                    <i class="fa fa-weixin"></i>
                                    {{ lang('WeChat') }}
                                </a>

                                <a class="btn btn-info login-btn{{ $user->register_source == 'github' ? '' : ' hide' }}" role="button">
                                    <i class="fa fa-github-alt"></i>
                                    {{ lang('GitHub') }}
                                </a>

                                <span class="padding-sm">{{ lang('Not allow to change register binding account') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('', lang('Available Bindings'), ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                @if($user->register_source != 'wechat')
                                    @if($user->wechat_openid)
                                        <a href="javascript:void(0);" class="btn btn-success login-btn">
                                    @else
                                        <a class="btn btn-default login-btn" href="{{ route('auth.oauth', ['driver' => 'wechat']) }}">
                                    @endif
                                            <i class="fa fa-weixin"></i>
                                                {{ lang('WeChat') }}
                                        </a>
                                @endif

                                @if($user->register_source != 'github')
                                    @if($user->github_id > 0)
                                        <a href="javascript:void(0);" class="btn btn-info login-btn">
                                    @else
                                        <a class="btn btn-default login-btn" href="{{ route('auth.oauth', ['driver' => 'github']) }}">
                                    @endif
                                            <i class="fa fa-github-alt"></i>
                                                {{ lang('GitHub') }}
                                        </a>
                                @endif

                                @if($user->github_id > 0 && $user->wechat_openid)
                                    <span class="padding-sm">{{ lang('Already binded to this account') }}</span>
                                @else
                                    <span class="padding-sm">{{ lang('Click to bind to this account') }}</span>
                                @endif

                            </div>
                        </div>
                        <br>
                        <br>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
