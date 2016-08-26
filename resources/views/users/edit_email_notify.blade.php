@extends('layouts.default')

@section('title')
    {{ lang('Edit Email Notify') }}_@parent
@stop

@section('content')
    <div class="users-show">
        <div class="col-md-3 box" style="padding: 15px;">
            @include('users.partials.setting_nav')
        </div>

        <div class="main-col col-md-9 left-col">
            <div class="panel panel-default padding-md">
                <div class="panel-body">
                    <h2><i class="fa fa-cog" aria-hidden="true"></i> {{ lang('Edit Email Notify') }}</h2>
                    <hr>

                    @include('layouts.partials.errors')

                    {{ Form::open(['url' => route('users.update_email_notify', $user->id), 'class' => 'form-horizontal', 'accept-charset' => 'UTF-8']) }}
                        <div class="form-group">
                            {{ Form::label('', lang('Send email when replied'), ['class' => 'col-sm-3 control-label']) }}
                            <div class="col-sm-9">
                                <input class="bootstrap-switch" type="checkbox" name="email_notify_enabled"{{ $user->email_notify_enabled == 'yes' ? ' checked' : '' }}>
                            </div>
                        </div>

                        <br>
                        <br>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {{ Form::submit(lang('Apply Changes'), ['id' => 'user-edit-submit', 'class' => 'btn btn-primary btn-lg', 'type' => 'submit']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
