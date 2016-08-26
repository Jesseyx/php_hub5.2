@extends('layouts.default')

@section('title')
    {{ lang('Photo Upload') }}_@parent
@stop

@section('content')
    <div class="users-show">
        
        <div class="col-md-3 box" style="padding: 15px;">
            @include('users.partials.setting_nav')
        </div>
        
        <div class="main-col col-md-9 left-col">
            <div class="panel panel-default padding-md">
                <div class="panel panel-default padding-md">
                    <h2><i class="fa fa-picture-o" aria-hidden="true"></i> {{ lang('Please Choose a Photo') }}</h2>
                    <hr>

                    @include('layouts.partials.errors')

                    {{ Form::open(['method' => 'PATCH', 'url' => route('users.update_avatar', $user->id), 'enctype' => 'multipart/form-data', 'accept-charset' => 'UTF-8']) }}
                        <div id="image-preview-div">
                            {{ Form::label('exampleInputFile', lang('Selected image:')) }}
                            <br>
                            <img id="preview-img" class="avatar-preview-img" src="{{$user->present()->gravatar(380)}}">
                        </div>

                        <div class="form-group">
                            {{ Form::file('avatar', ['id' => 'file', 'required' => 'required']) }}
                        </div>
                        <br>

                        {{ Form::button(lang('Photo Upload'), ['id' => 'upload-button', 'class' => 'btn btn-lg btn-primary', 'type' => 'submit', 'disabled' => 'disabled']) }}

                        <div id="loading" class="alert alert-info" role="alert" style="display: none;">
                            {{ lang('Uploading image...') }}
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                </div>
                            </div>
                        </div>
                        <div id="message"></div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        
    </div>
@stop
