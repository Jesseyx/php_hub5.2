@extends('layouts.default')

@section('title')
    {{ lang('Create New Account') }}_@parent
@stop

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ lang('Create New Account') }}</h3>
                </div>

                <div class="panel-body">
                    {{ Form::open() }}
                    <div class="form-group">
                        {{ Form::label('name', lang('Avatar'), ['class' => 'control-label']) }}
                        <div class="form-group">
                            <img src="{{ $githubUser['avatar_url'] }}" alt="" width="100%">
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', lang('Username'), ['class' => 'control-label']) }}
                        {{ Form::text('name', ($githubUser['name'] ?: ''), ['class' => 'form-control']) }}
                        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group{{ $errors->has('github_name') ? ' has-error' : '' }}">
                        {{ Form::label('github_name', 'Github Name', ['class' => 'control-label']) }}

                        {{ Form::text('github_name', (isset($githubUser['github_name']) ? $githubUser['github_name'] : $githubUser['name']), ['class' => 'form-control', 'readonly' => 'readonly']) }}

                        {!! $errors->first('github_name', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {{ Form::label('email', lang('Email'), ['class' => 'control-label']) }}
                        {{ Form::text('email', $githubUser['email'] ?: '', ['class' => 'form-control']) }}
                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                    </div>

                    {{ Form::hidden('github_id', $githubUser['id']) }}

                    {{ Form::submit(lang('Confirm'), ['class' => 'btn btn-lg btn-success btn-block']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
