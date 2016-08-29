@extends('layouts.default')

@section('title')
    {{ $topic->title }}_@parent
@stop

@section('description')
    {{ $topic->excerpt }}
@stop

@section('content')

    <div class="col-md-9 topics-show main-col">
        <!-- Topic Detial -->
        <div class="topic panel panel-default padding-md">
            <div class="infos panel-heading">

                <h1 class="panel-title topic-title">{{ $topic->title }}</h1>

                @include('topics.partials.meta')
            </div>

            <div class="content-body entry-content panel-body ">

                @include('topics.partials.body', array('body' => $topic->body))

                <div class="ribbon-container" data-lang-excellent="{{ lang('This topic has been mark as Excenllent Topic.') }}" data-lang-wiki="{{ lang('This is a Community Wiki.') }}">
                    @include('topics.partials.ribbon')
                </div>
            </div>

            <div class="appends-container" data-lang-append="{{ lang('Append') }}">
                @foreach ($topic->appends as $index => $append)

                    <div class="appends">
                        <span class="meta">{{ lang('Append') }} {{ $index }} &nbsp;·&nbsp; <abbr class="timeago" title="{{ $append->created_at }}">{{ $append->created_at }}</abbr></span>
                        <div class="sep5"></div>
                        <div class="markdown-reply append-content">
                            {!! $append->content !!}
                        </div>
                    </div>

                @endforeach
            </div>

            @include('topics.partials.topic_operate', ['manage_topics' => $currentUser ? $currentUser->can("manage_topics") : false])
        </div>


        <div class="votes-container panel panel-default padding-md">
            <div class="panel-body vote-box text-center">

                <div class="btn-group">

                    <a id="up-vote" class="vote btn btn-primary{{ $topic->user->payment_qrcode ? '' : ' btn-inverted' }} popover-with-html{{ $currentUser && $topic->votes()->ByWhom(Auth::id())->withType('upvote')->count() ? ' active' : '' }}" href="javascript:void(0);" title="{{ lang('Up Vote') }}" data-ajax="post" data-url="{{ route('topics.upvote', $topic->id) }}" data-content="点赞相当于收藏，可以在个人页面的「赞过的话题」导航里查看">
                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        点赞
                    </a>

                    @if( $topic->user->payment_qrcode )
                        <div class="or"></div>
                        <button class="btn btn-warning popover-with-html" data-toggle="modal" data-target="#payment-qrcode-modal" data-content="如果觉得我的文章对您有用，请随意打赏。你的支持将鼓励我继续创作！">
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            打赏
                        </button>
                    @endif
                </div>

                <div class="voted-users">
                    @if(count($votedUsers))
                        <div class="user-lists">
                            @foreach($votedUsers as $votedUser)
                                <a href="{{ route('users.show', $votedUser->id) }}" data-userId="{{ $votedUser->id }}">
                                    <img class="img-thumbnail avatar avatar-middle" src="{{ $votedUser->present()->gravatar() }}" style="width: 48px; height: 48px;">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="user-lists">

                        </div>
                        <div class="vote-hint">
                            成为第一个点赞的人吧 <img class="emoji" title=":bowtie:" alt=":bowtie:" src="https://dn-phphub.qbox.me/assets/images/emoji/bowtie.png" align="absmiddle" />
                        </div>
                    @endif

                    <a class="voted-template" href="" data-userId="" style="display: none;">
                        <img class="img-thumbnail avatar avatar-middle" src="" style="width: 48px; height: 48px;">
                    </a>
                </div>

            </div>
        </div>

        <!-- Reply List -->
        <div class="replies panel panel-default list-panel replies-index padding-md">
            <div class="panel-heading">
                <div class="total">{{ lang('Total Reply Count') }}: <b>{{ $replies->total() }}</b> </div>
            </div>

            <div class="panel-body">
                @if (count($replies))
                    @include('topics.partials.replies', ['manage_topics' => $currentUser ? $currentUser->can("manage_topics") : false])
                @else
                    <ul class="list-group row"></ul>
                @endif

                <div id="replies-empty-block" class="empty-block{{ count($replies) ? ' hide' : '' }}">{{ lang('No comments') }}~~</div>

                <!-- Pager -->
                <div class="pull-right" style="padding-right: 20px;">
                    {!! $replies->appends(request()->except('page'))->render() !!}
                </div>
            </div>
        </div>

        <!-- Reply Box -->
        <div class="reply-box form box-block">

            @include('layouts.partials.errors')

            {{ Form::open(['id' => 'reply-form', 'url' => route('replies.store'), 'accept-charset' => 'UTF-8']) }}

                {{ Form::hidden('topic_id', $topic->id) }}

                @include('topics.partials.composing_help_block')

                <div class="form-group">
                    @if ($currentUser)
                        @if ($currentUser->verified)
                            {{ Form::textarea('body', '', ['id' => 'reply_content', 'class' => 'form-control', 'placeholder' => lang('Please using markdown.'), 'rows' => 5, 'cols' => 50, 'style' => 'overflow: hidden;']) }}
                        @else
                            {{ Form::textarea('body', '', ['id' => 'reply_content', 'class' => 'form-control', 'placeholder' => lang('You need to verify the email for commenting.'), 'rows' => 5, 'cols' => 50, 'disabled' => 'disabled']) }}
                        @endif
                    @else
                        {{ Form::textarea('body', '', ['id' => 'reply_content', 'class' => 'form-control', 'placeholder' => lang('User Login Required for commenting.'), 'rows' => 5, 'cols' => 50, 'disabled' => 'disabled']) }}
                    @endif
                </div>

                <div class="form-group reply-post-submit">
                    {{ Form::submit(lang('Reply'), ['id' => 'reply-create-submit', 'class' => 'btn btn-primary' . $currentUser ? '' : ' disabled']) }}
                    <span class="help-inline" title="Or Command + Enter">Ctrl+Enter</span>
                </div>

                <div class="box preview markdown-reply" id="preview-box" style="display: none;"></div>

            </form>
        </div>

    </div>

    @if( $topic->user->payment_qrcode )
        @include('topics.partials.payment_qrcode_modal')
    @endif

    @include('layouts.partials.sidebar')

    @include('layouts.partials.bottombanner')

@stop

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function() {
            var $config = {
                title               : '{{ $topic->title }} from PHPHub - PHP，Laravel的中文社区 #laravel# @phphub {{ $topic->user->id != 1 ? '@李桂龙_CJ' : '' }} {{ $topic->user->weibo_name ? '@'.$topic->user->weibo_name : '' }}',
                wechatQrcodeTitle   : "微信扫一扫：分享", // 微信二维码提示文字
                wechatQrcodeHelper  : '<p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p>',
                sites               : ['weibo', 'wechat', 'facebook', 'twitter', 'google', 'qzone', 'qq', 'douban'],
            };

            socialShare('.social-share-cs', $config);

            Config.following_users =  @if($currentUser) {!! $currentUser->present()->followingUsersJson() !!} @else [] @endif;
            PHPHub.initAutocompleteAtUser();
        });

    </script>
@stop
