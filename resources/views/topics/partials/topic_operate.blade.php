<div class="panel-footer operate">
    <div class="pull-left" style="font-size: 15px;">

        <a href="http://service.weibo.com/share/share.php?url={{ request()->url() }}&type=3&pic=&title={{ $topic->title }}" target="_blank" title="{{ lang('Share on Weibo') }}">
            <i class="fa fa-weibo"></i>
        </a>

        <a href="https://twitter.com/intent/tweet?url={{ request()->url() }}&text={{ $topic->title }}&via=phphub.org" target="_blank" title="{{ lang('Share on Twitter') }}">
            <i class="fa fa-twitter"></i>
        </a>

        <a href="http://www.facebook.com/sharer.php?u={{ request()->url() }}" target="_blank" title="{{ lang('Share on Facebook') }}">
            <i class="fa fa-facebook"></i>
        </a>

        <a href="https://plus.google.com/share?url={{ request()->url() }}" target="_blank" title="{{ lang('Share on Google Plus') }}">
            <i class="fa fa-google-plus"></i>
        </a>

    </div>

    <div class="pull-right">
        <!-- 关注 -->
        @if ($currentUser && \App\Attention::isUserAttentedTopic($currentUser, $topic))
            <a id="topic-attent-cancel-button" class="active" href="javascript:;" data-ajax="post" data-url="{{ route('attentions.createOrDelete', $topic->id) }}" data-lang-cancel={{ lang('Cancel') }} data-lang-attent={{ lang('Attent') }}>
                <i class="glyphicon glyphicon-eye-open"></i> <span>{{ lang('Cancel') }}</span>
            </a>
        @else
            <a id="topic-attent-button" href="javascript:;" data-ajax="post" data-url="{{ route('attentions.createOrDelete', $topic->id) }}" data-lang-cancel={{ lang('Cancel') }} data-lang-attent={{ lang('Attent') }}>
                <i class="glyphicon glyphicon-eye-open"></i> <span>{{ lang('Attent') }}</span>
            </a>
        @endif
        <!-- 收藏 -->
        @if ($currentUser && \App\Favorite::isUserFavoritedTopic($currentUser, $topic))
            <a id="topic-favorite-cancel-button" href="javascript:;" data-ajax="post" data-url="{{ route('favorites.createOrDelete', $topic->id) }}" data-lang-cancel={{ lang('Cancel') }} data-lang-attent={{ lang('Favorite') }}>
                <i class="glyphicon glyphicon-bookmark"></i> <span>{{ lang('Cancel') }}</span>
            </a>
        @else
            <a id="topic-favorite-button" href="javascript:;" data-ajax="post" data-url="{{ route('favorites.createOrDelete', $topic->id) }}" data-lang-cancel={{ lang('Cancel') }} data-lang-attent={{ lang('Favorite') }}>
                <i class="glyphicon glyphicon-bookmark"></i> <span>{{ lang('Favorite') }}</span>
            </a>
        @endif

        <!-- 主题管理 -->
        @if ($currentUser && $currentUser->can('manage_topics'))
            <a id="topic-recomend-button" class="admin{{ $topic->is_excellent == 'yes' ? ' active' : '' }}" href="javascript:;" title="{{ lang('Mark as Excellent') }}" data-ajax="post" data-url="{{ route('topics.recommend', [$topic->id]) }}">
                <i class="fa fa-trophy"></i>
            </a>

            @if ($topic->order >= 0)
                <a id="topic-pin-button" class="admin{{ $topic->order > 0 ? ' active' : '' }}" href="javascript:;" title="{{ lang('Pin it on Top') }}" data-ajax="post" data-url="{{ route('topics.pin', [$topic->id]) }}">
                    <i class="fa fa-thumb-tack"></i>
                </a>
            @endif

            @if ($topic->order <= 0)
                <a id="topic-sink-button" class="admin{{ $topic->order < 0 ? ' active' : '' }}" href="javascript:;" title="{{ lang('Sink This Topic') }}" data-ajax="post" data-url="{{ route('topics.sink', [$topic->id]) }}">
                    <i class="fa fa-anchor"></i>
                </a>
            @endif

            <a id="topic-delete-button" class="admin" href="javascript:;" title="{{ lang('Delete') }}" data-ajax="delete" data-url="{{ route('topics.destroy', [$topic->id]) }}">
                <i class="fa fa-trash-o"></i>
            </a>
        @endif


        @if ($currentUser && $currentUser->can('manage_topics') || $currentUser->id == $topic->user_id)
            <a id="topic-append-button" class="admin" href="javascript:;" title="{{ lang('Append') }}" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus"></i>
            </a>

            <a id="topic-edit-button" class="admin" href="{{ route('topics.edit', [$topic->id]) }}" title="{{ lang('Edit') }}">
                <i class="fa fa-pencil-square-o"></i>
            </a>
        @endif
    </div>

    <div class="clearfix"></div>
</div>
