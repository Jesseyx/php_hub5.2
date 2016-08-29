<ul class="list">
    @foreach ($sidebarTopics as $sidebarTopic)
        <li>
            <a class="popover-with-html" href="{{ route('topics.show', $sidebarTopic->id) }}" data-content="{{ $sidebarTopic->title }}">
                {{ $sidebarTopic->title }}
            </a>
        </li>
    @endforeach
</ul>
