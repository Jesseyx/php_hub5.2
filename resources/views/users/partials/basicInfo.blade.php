<div class="box">

    <div class="padding-sm user-basic-info">
        <div>
            <div class="media">

                <div class="media-left">
                    <div class="image">
                        @if ($currentUser && $currentUser->id == $user->id)
                            <a class="popover-with-html" href="{{ route('users.edit_avatar', $user->id) }}" data-content="修改头像">
                                <img class="media-object avatar-112 avatar img-thumbnail" src="{{ $user->present()->gravatar(200) }}">
                            </a>
                        @else
                            <img class="media-object avatar-112 avatar img-thumbnail" src="{{ $user->present()->gravatar(200) }}">
                        @endif
                    </div>

                    @if ($user->present()->hasBadge())
                        <div class="role-label">
                            <span class="label label-success role">{!! $user->present()->badgeName() !!}</span>
                        </div>
                    @endif
                </div>

                <div class="media-body">
                    <h3 class="media-heading">
                        {{ $user->name }}
                    </h3>
                    <div class="item">
                        {{ $user->real_name }}
                    </div>
                    <div class="item">
                        第 {{ $user->id }} 位会员
                    </div>
                    <div class="item number">
                        注册于 <span class="timeago">{{ $user->created_at }}</span>
                    </div>

                    @if($user->last_actived_at)
                        <div class="item number">
                            活跃于 <span class="timeago">{{ $user->last_actived_at }}</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <hr>

        <div class="follow-info row">
            <div class="col-xs-4">
                <a class="counter" href="{{ route('users.followers', $user->id) }}">{{ $user->follower_count }}</a>
                <a class="text" href="{{ route('users.followers', $user->id) }}">关注者</a>
            </div>
            <div class="col-xs-4">
                <a class="counter" href="{{ route('users.replies', $user->id) }}">{{ $user->reply_count }}</a>
                <a class="text" href="{{ route('users.replies', $user->id) }}">评论</a>
            </div>
            <div class="col-xs-4">
                <a class="counter" href="{{ route('users.topics', $user->id) }}">{{ $user->topic_count }}</a>
                <a class="text" href="{{ route('users.topics', $user->id) }}">话题</a>
            </div>
        </div>

        <hr>

        <div class="topic-author-box text-center">
            <ul class="list-inline">
                @if ($user->github_name)
                    <li class="popover-with-html" data-content="{{ $user->github_name }}">
                        <a href="https://github.com/{{ $user->github_name }}" target="_blank">
                            <i class="fa fa-github-alt"></i> GitHub
                        </a>
                    </li>
                @endif

                @if ($user->weibo_link)
                    <li class="popover-with-html" data-content="{{ $user->weibo_name }}">
                        <a class="weibo" href="{{ $user->weibo_link }}" rel="nofollow" target="_blank"><i class="fa fa-weibo"></i> Weibo
                        </a>
                    </li>
                @endif

                @if ($user->wechat_qrcode)
                    <li class="popover-with-html" data-content="<img src='{{ $user->wechat_qrcode }}' style='width:100%'>">
                        <i class="fa fa-wechat"></i> WeChat
                    </li>
                @endif

                @if ($user->twitter_account)
                    <li class="popover-with-html" data-content="{{ $user->twitter_account }}">
                        <a class="twitter" href="https://twitter.com/{{ $user->twitter_account }}" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i> Twitter
                        </a>
                    </li>
                @endif

                @if ($user->linkedin)
                    <li class="popover-with-html" data-content="点击查看 LinkedIn 个人资料">
                        <a class="linkedin" href="{{ $user->linkedin }}" rel="nofollow" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn
                        </a>
                    </li>
                @endif

                @if ($user->personal_website)
                    <li class="popover-with-html" data-content="{{ $user->personal_website }}">
                        <a class="url" href="http://{{ $user->personal_website }}" rel="nofollow" target="_blank">
                            <i class="fa fa-globe"></i> {{ lang('Website') }}
                        </a>
                    </li>
                @endif

                @if ($user->company)
                    <li class="popover-with-html" data-content="{{ $user->company }}"><i class="fa fa-users"></i> {{ lang('Company') }}</li>
                @endif

                @if ($user->city)
                    <li class="popover-with-html" data-content="{{ $user->city }}"><i class="fa fa-map-marker"></i> {{ lang('City') }}</li>
                @endif
            </ul>
        </div>

        @if(Auth::check())
            <hr>
        @endif

        @if ($currentUser && ($currentUser->id == $user->id || Entrust::can('manage_users')))
            <a id="user-edit-button" class="btn btn-primary btn-block" href="{{ route('users.edit', $user->id) }}">
                <i class="fa fa-edit"></i> {{ lang('Edit Profile') }}
            </a>
        @endif

        @if(Auth::check() && $currentUser->id != $user->id)
            <!--{{ $isFollowing = $currentUser && $currentUser->isFollowing($user->id) ? true : false }}-->

            <a id="user-edit-button" class="btn btn-{{ !$isFollowing ? 'warning' : 'danger' }} btn-block" href="javascript:void(0);" data-method="post" data-url="{{ route('users.doFollow', $user->id) }}">
                <i class="fa {{ !$isFollowing ? 'fa-plus' : 'fa-minus' }}"></i> {{ !$isFollowing ? lang('Follow') : lang('Unfollow') }}
            </a>
        @endif

        @if ($currentUser && Entrust::can('manage_users') && ($currentUser->id != $user->id))
            <a id="user-edit-button" class="btn btn-{{ $user->is_banned == 'yes' ? 'warning' : 'danger' }} btn-block" href="javascript:void(0);" data-method="post" data-url="{{ route('users.blocking', $user->id) }}"  onclick="return confirm('{{ lang('Are you sure want to '. ($user->is_banned == 'yes' ? 'unblock' : 'block') . ' this User?') }}')">
                <i class="fa fa-times"></i> {{ $user->is_banned == 'yes' ? lang('Unblock User') : lang('Block User') }}
            </a>
        @endif

        {{-- @if(Auth::check() && Auth::id() == $user->id)
          @include('users.partials.login_QR')
        @endif --}}
    </div>

</div>

<div class="box text-center">
    <div class="padding-sm user-basic-nav">
        <ul class="list-group">
            <a class="{{ navViewActive('users.topics') }}" href="{{ route('users.topics', $user->id) }}">
                <li class="list-group-item"><i class="text-md fa fa-list-ul"></i> Ta 发布的话题</li>
            </a>

            <a class="{{ navViewActive('users.replies') }}" href="{{ route('users.replies', $user->id) }}">
                <li class="list-group-item"><i class="text-md fa fa-comment"></i> Ta 发表的回复</li>
            </a>

            <a class="{{ navViewActive('users.following') }}" href="{{ route('users.following', $user->id) }}">
                <li class="list-group-item"><i class="text-md fa fa-eye"></i> Ta 关注的用户</li>
            </a>

            <a class="{{ navViewActive('users.votes') }}" href="{{ route('users.votes', $user->id) }}">
                <li class="list-group-item"><i class="text-md fa fa-thumbs-up"></i> Ta 赞过的话题</li>
            </a>

        </ul>
    </div>
</div>

@if(Auth::check() && Auth::id() == $user->id)
  @include('users.partials.login_QR')
@endif
