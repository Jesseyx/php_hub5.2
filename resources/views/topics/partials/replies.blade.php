<ul class="list-group row">
    @foreach($replies as $index => $reply)
        <li class="list-group-item media" style="">
            <div class="avatar pull-left">
                <a href="">
                    <img class="media-object img-thumbnail avatar avatar-middle" src="{{ $reply->user->present()->gravatar }}" title="{{ $reply->user->name }}" style="width: 48px; height: 48px;">
                </a>
            </div>

            <div class="infos">
                <div class="media-heading">
                    <a class="remove-padding-left author" href="" title="{{ $reply->user->name }}">
                        {{ $reply->user->name }}
                    </a>
                    @if ($reply->user->introduction)
                        <span class="introduction">
                            ，{{ str_limit($reply->user->introduction, 68) }}
                        </span>
                    @endif

                    <span class="operate pull-right">
                        @if ($currentUser && $reply->user_id != $currentUser->id)
                            <a class="comment-vote" href="">
                                <i class="fa fa-thumbs-o-up" style="font-size: 14px;"></i> <span class="vote-count">{{ $reply->vote_count ?: '' }}</span>
                            </a>
                            <span> •  </span>
                        @endif

                        @if ($currentUser && $currentUser->id == $reply->user_id)
                            <a href="">
                                <i class="fa fa-trash-o"></i>
                            </a>
                            <span> •  </span>
                        @endif

                        <a class="fa fa-reply" href="javascript:void(0)" title="回复 {{ $reply->user->name }}"></a>
                    </span>

                    <div class="meta">
                        <a class="anchor" href=""></a>

                        <span> •  </span>
                        <abbr class="timeago" title="{{ $reply->created_at }}">{{ $reply->created_at }}</abbr>
                    </div>
                </div>

                <div class="media-body markdown-reply content-body">
                    {!! $reply->body !!}
                </div>
            </div>
        </li>
    @endforeach
</ul>
