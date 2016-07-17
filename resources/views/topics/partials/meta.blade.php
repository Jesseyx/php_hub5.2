<div class="meta inline-block">
    <a href="">
        {{ $topic->category->name }}
    </a>
    •
    <a href="">
        {{ $topic->user->name }}
    </a>

    @if ($topic->user->present()->hasBadge())
        <span class="label label-warning" style="position: relative;">
            {{ $topic->user->present()->badgeName() }}
        </span>
    @endif
    •
    {{ lang('at') }} <abbr class="timeago" title="{{ $topic->created_at }}">{{ $topic->created_at }}</abbr>
    •

    @if (count($topic->lastReplyUser))
        {{ lang('Last Reply by') }}
        <a href="">
            {{ $topic->lastReplyUser->name }}
        </a>
        {{ lang('at') }} <abbr class="timeago" title="{{ $topic->updated_at }}">{{ $topic->updated_at }}</abbr>
    @endif

    {{ $topic->view_count }} {{ lang('Reads') }}
</div>
<div class="clearfix"></div>
