@extends('layouts.default')

@section('title')
    {{ $topic->title }}_@parent
@stop

@section('description')
    {{ $topic->excerpt }}
@stop

@section('content')
    <div class="col-md-9 topics-show main-col">
        <!-- Topic Detail -->
        <div class="topic panel panel-default">
            <div class="infos panel-heading">
                <div class="pull-right avatar_large">
                    <a href="">
                        <img class="img-thumbnail avatar" src="{{ $topic->user->present()->gravatar }}" style="width: 65px; height: 65px;">
                    </a>
                </div>

                <h1 class="panel-title topic-title">{{ $topic->title }}</h1>
                
                <div class="votes">
                    <a href="">
                        <li class="fa fa-chevron-up"></li>
                        <span id="vote-count">{{ $topic->vote_count }}</span>
                    </a>
                     &nbsp;
                    <a href="">
                        <li class="fa fa-chevron-down"></li>
                    </a>
                </div>

                @include('topics.partials.meta')
            </div>

            <div class="content-body entry-content panel-body">
                @include('topics.partials.body', array('body' => $topic->body))

                <div class="ribbon-container" data-lang-excellent="{{ lang('This topic has been mark as Excenllent Topic.') }}" data-lang-wiki="{{ lang('This is a Community Wiki.') }}">
                    @include('topics.partials.ribbon')
                </div>
            </div>

            <div class="appends-container">

            </div>
            
        </div>

        <!-- Reply List -->
        <div class="replies panel panel-default list-panel replies-index">
            <div class="panel-heading">
                <div class="total">
                    {{ lang('Total Reply Count') }}: <b>{{ count($replies) }}</b>
                </div>
            </div>

            <div class="panel-body">
                @if (count($replies))
                    @include('topics.partials.replies')
                @else
                    <ul class="list-group row"></ul>
                @endif

                <div id="replies-empty-block" class="empty-block{{ count($replies) ? '' : ' hide' }}">{{ lang('No comments') }}~~</div>

                <!-- Pager -->
                <div class="pull-right" style="padding-right: 20px;">

                </div>
            </div>
        </div>

        <!-- Reply Box -->
        <div class="reply-box form box-block">
            @include('layouts.partials.errors')

            {{ Form::open(['url' => route('replies.store'), 'id' => 'reply-form', 'accept-charset' => 'UTF-8']) }}
                {{ Form::hidden('topic_id', $topic->id) }}

                @include('topics.partials.composing_help_block')

                <div class="form-group">
                    @if ($currentUser)
                        {{ Form::textarea('body', null, ['id' => 'reply_content', 'class' => 'form-control', 'rows' => 5, 'cols' => 50, 'placeholder' => lang('Please using markdown.'), 'style' => 'overflow: hidden;']) }}
                    @else
                        {{ Form::textarea('body', null, ['id' => 'reply_content', 'class' => 'form-control', 'rows' => 5, 'cols' => 50, 'placeholder' => lang('User Login Required for commenting.'), 'disabled' => 'disabled']) }}
                    @endif
                </div>

                <div class="form-group status-post-submit">
                    {{ Form::submit(lang('Reply'), ['id' => 'reply-create-submit', 'class' => 'btn btn-primary' . ($currentUser ? '' : ' disabled')]) }}
                    <span class="help-inline" title="Or Command + Enter">Ctrl+Enter</span>
                </div>

                <div id="preview-box" class="box preview markdown-reply" style="display: none;"></div>
            {{ Form::close() }}
        </div>
    </div>

    @include('layouts.partials.sidebar')
    @include('layouts.partials.bottombanner')
@stop

