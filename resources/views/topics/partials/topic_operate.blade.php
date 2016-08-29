<div class="panel-footer operate">

    <div class="pull-left hidden-xs">
        <div class="social-share-cs "></div>
    </div>

    <div class="pull-right actions">

        @if ($currentUser && $manage_topics )
            <a id="topic-recomend-button" class="admin popover-with-html{{ $topic->is_excellent == 'yes' ? ' active' : '' }}" href="javascript:void(0);" data-ajax="post" data-url="{{ route('topics.recommend', [$topic->id]) }}" data-content="推荐主题，加精的帖子会出现在首页">
                <i class="fa fa-trophy"></i>
            </a>

            @if ($topic->order >= 0)
                <a id="topic-pin-button" class="admin popover-with-html{{ $topic->order > 0 ? ' active' : '' }}" href="javascript:void(0);" data-ajax="post" data-url="{{ route('topics.pin', [$topic->id]) }}" data-content="帖子置顶，会在列表页置顶">
                    <i class="fa fa-thumb-tack"></i>
                </a>
            @endif

            @if ($topic->order <= 0)
                <a id="topic-sink-button" class="admin popover-with-html{{ $topic->order < 0 ? ' active' : '' }}" href="javascript:void(0);" data-ajax="post" data-url="{{ route('topics.sink', [$topic->id]) }}" data-content="沉贴，帖子将会被降低排序优先级">
                    <i class="fa fa-anchor"></i>
                </a>
            @endif

            <a id="topic-delete-button" class="admin  popover-with-html" href="javascript:void(0);" data-method="delete" data-url="{{ route('topics.destroy', [$topic->id]) }}" data-content="{{ lang('Delete') }}">
                <i class="fa fa-trash-o"></i>
            </a>
        @endif

        @if ( $currentUser && ($manage_topics || $currentUser->id == $topic->user_id) )
            <a id="topic-append-button" class="admin  popover-with-html" href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal" data-content="帖子附言，添加附言后所有参与讨论的用户都能收到消息提醒，包括点赞和评论的用户">
                <i class="fa fa-plus"></i>
            </a>

            <a id="topic-edit-button" class="admin  popover-with-html" href="{{ route('topics.edit', [$topic->id]) }}" data-content="{{ lang('Edit') }}">
                <i class="fa fa-pencil-square-o"></i>
            </a>
        @endif

    </div>
    <div class="clearfix"></div>
</div>


<div id="exampleModal" class="modal fade" tabindex="-1" role="" aria-labelledby="exampleModalLabel" >
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="exampleModalLabel" class="modal-title">{{ lang('Append Content') }}</h4>
            </div>

            {{ Form::open(['url' => route('topics.append', $topic->id), 'accept-charset' => 'UTF-8']) }}
                <div class="modal-body">

                    <div class="alert alert-warning">
                        {{ lang('append_notice') }}
                    </div>

                    <div class="form-group">
                        {{ Form::textarea('content', null, ['class' => 'form-control', 'cols' => 50, 'rows' => 10, 'placeholder' => lang('Please using markdown.'), 'style' => 'min-height: 20px;' ]) }}
                    </div>

                </div>

                <div class="modal-footer">
                    {{ Form::button(lang('Close'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) }}
                    {{ Form::button(lang('Submit'), ['class' => 'btn btn-primary', 'type' => 'submit']) }}
                </div>
            {{ Form::close() }}

        </div>
    </div>
</div>
