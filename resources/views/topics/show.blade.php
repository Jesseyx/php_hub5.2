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
                    <div id="replies-empty-block" class="empty-block hide">{{ lang('No comments') }}~~</div>
                @else
                    <ul class="list-group row"></ul>
                    <div id="replies-empty-block" class="empty-block">{{ lang('No comments') }}~~</div>
                @endif

                <!-- Pager -->
                <div class="pull-right" style="padding-right: 20px;">

                </div>
            </div>
        </div>

        <!-- Reply Box -->
        <div class="reply-box form box-block">
            @include('layouts.partials.errors')

        </div>
    </div>

    @include('layouts.partials.sidebar')
@stop

