<div class="col-md-3 side-bar">
    <div class="panel panel-default corner-radius">
        @if (isset($category))
            <div class="panel-heading text-center">
                <h3 class="panel-title">{{ $category->name }}</h3>
            </div>
        @endif

        <div class="panel-body text-center">
            <div class="btn-group">
                <a class="btn btn-primary btn-lg" href="{{ isset($category) ? route('topics.create', ['category_id' => $category->id]) : route('topics.create') }}">
                    <i class="glyphicon glyphicon-pencil"> </i> {{ lang('New Topic') }}
                </a>
            </div>
        </div>
    </div>

    @if (Route::currentRouteName() == 'topics.index')
        <div class="panel panel-default corner-radius">
            <div class="panel-heading text-center">
                <h3 class="panel-title">{{ lang('App Download') }}</h3>
            </div>

            <div class="panel-body text-center" style="padding: 7px;">
                <a href="https://phphub.org/topics/1531" target="_blank" rel="nofollow" title="">
                    <img src="https://dn-phphub.qbox.me/uploads/images/201512/08/1/cziZFHqkm8.png" style="width:240px;">
                </a>
            </div>
        </div>
    @endif

    <div class="panel panel-default corner-radius">
        <div class="panel-body text-center" style="padding: 7px; padding-top: 8px;">
            <a href="http://www.ucloud.cn/site/seo.html?utm_source=zanzhu&utm_campaign=phphub&utm_medium=display&utm_content=shengji&ytag=phphubshenji" target="_blank" rel="nofollow" title="" style="line-height: 66px;">
                <img src="http://ww1.sinaimg.cn/large/6d86d850jw1f2xfmssojsj20dw03cjs5.jpg" width="100%">
            </a>
        </div>
    </div>

    @if (isset($links) && count($links))
        <div class="panel panel-default corner-radius">
            <div class="panel-heading text-center">
                <h3 class="panel-title">{{ lang('Links') }}</h3>
            </div>

            <div class="panel-body text-center" style="padding-top: 5px;">
                @foreach ($links as $link)
                    <a href="{{ $link->link }}" target="_blank" title="{{ $link->title }}" rel="nofollow">
                        <img src="{{ $link->cover }}" style="width: 150px; margin: 3px 0;">
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if (isset($categoryTopics) && count($categoryTopics))
        <div class="panel panel-default corner-radius">
            <div class="panel-heading text-center">
                <h3 class="panel-title">{{ lang('Same Category Topics') }}</h3>
            </div>

            <div class="panel-body">
                <ul class="list">
                    @foreach($categoryTopics as $categoryTopic)
                        <li>
                            <a href="{{ route('topics.show', $categoryTopic->id) }}">
                                {{ $categoryTopic->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="panel panel-default corner-radius">
        <div class="panel-heading text-center">
            <h3 class="panel-title">{{ lang('Tips and Tricks') }}</h3>
        </div>

        <div class="panel-body">
            {!! $siteTip->body !!}
        </div>
    </div>

    @if (Route::currentRouteName() == 'topics.index')
        <div class="panel panel-default corner-radius">
            <div class="panel-heading text-center">
                <h3 class="panel-title">{{ lang('Site Status') }}</h3>
            </div>

            <div class="panel-body">
                <ul>
                    <li>{{ lang('Total User') }}: {{ $siteStat->user_count }} </li>
                    <li>{{ lang('Total Topic') }}: {{ $siteStat->topic_count }} </li>
                    <li>{{ lang('Total Reply') }}: {{ $siteStat->reply_count }} </li>
                </ul>
            </div>
        </div>
    @endif
</div>
