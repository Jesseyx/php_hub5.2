@if (count($topics))
    <ul class="list-group row topic-list">
        @foreach ($topics as $topic)
            <li class="list-group-item media{{ $column ? ' col-sm-6' : '' }}" style="margin-top: 0px;">
                <a class="reply_last_time hidden-xs" href="#">
                    @if ($topic->reply_count > 0 && count($topic->lastReplyUser))
                        <img class="user_small_avatar" src="{{ $topic->lastReplyUser->present()->gravatar }}">
                    @else
                        <img class="user_small_avatar" src="{{ $topic->user->present()->gravatar }}">
                    @endif

                    <span class="timeago">{{ $topic->updated_at }}</span>
                </a>

                <div class="avatar pull-left">
                    <a href="#">
                        <img class="media-object img-thumbnail avatar avatar-middle" src="{{ $topic->user->present()->gravatar }}" alt="{{ $topic->user->name }}">
                    </a>
                </div>

                <span class="reply_count_area hidden-xs">
                    <span class="count_of_replies" title="回复数">
                        {{ $topic->reply_count }}
                    </span>
                    <span class="count_seperator">/</span>
                    <span class="count_of_visits" title="查看数">
                        {{ $topic->view_count }}
                    </span>
                </span>

                <div class="infos">
                    <div class="media-heading">

                        <a href="" title="{{ $topic->title }}">
                            {{ $topic->title }}
                        </a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <div class="empty-block">
        {{ lang('Dont have any data Yet') }}~~
    </div>
@endif
