<ul class="list-group">
    @foreach($replies as $index => $reply)
        <li class="list-group-item">

            @if (count($reply->topic))
                <a class="remove-padding-left" href="{{ route('topics.show', [$reply->topic_id]) }}" title="{{ $reply->topic->title }}">
                    {{ $reply->topic->title }}
                </a>
                <span class="meta">
                    at <span class="timeago" title="{{ $reply->created_at }}">{{ $reply->created_at }}</span>
                </span>
                <div class="reply-body markdown-reply content-body">
                    {!! $reply->body !!}
                </div>
            @else
                <div class="deleted text-center">{{ lang('Data has been deleted.') }}</div>
            @endif

        </li>
    @endforeach
</ul>
