@extends('layouts.default')

@section('title')
    编辑个人资料_@parent
@stop

@section('content')
    <div class="users-show">

        <div class="col-md-3 box" style="padding: 15px;">
            @include('users.partials.basicInfo')
        </div>

        <div class="main-col col-md-9 left-col">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="alert alert-warning">
                        {!! lang('avatar_notice') !!} {!! link_to_route('users.refresh_cache', lang('Update Cache'), $user->id) !!} .
                    </div>

                    @include('layouts.partials.errors')

                    {{ Form::model($user, ['method' => 'PATCH', 'url' => route('users.update', $user->id), 'accept-charset' => 'UTF-8']) }}
                        <div class="form-group">
                            {{ Form::text('github_name', null, ['class' => 'form-control', 'placeholder' => lang('GitHub Name')]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::text('real_name', null, ['class' => 'form-control', 'placeholder' => lang('Real Name')]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => lang('City')]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::text('company', null, ['class' => 'form-control', 'placeholder' => lang('Company')]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::text('twitter_account', null, ['class' => 'form-control', 'placeholder' => lang('twitter_placeholder')]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::text('personal_website', null, ['class' => 'form-control', 'placeholder' => lang('personal_website_placebolder')]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::text('introduction', null, ['class' => 'form-control', 'placeholder' => lang('introduction_placeholder')]) }}
                        </div>

                        <div class="form-group status-post-submit">
                            {{ Form::submit(lang('Publish'), ['id' => 'user-edit-submit', 'class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>

    </div>
@stop
