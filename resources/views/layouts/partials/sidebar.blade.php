<div class="col-md-3 side-bar">
    <div class="panel panel-default corner-radius">
        @if (isset($category))
            <div class="panel-heading text-center">
                <h3 class="panel-title">{{ $category->name }}</h3>
            </div>
        @endif

        <div class="panel-body text-center">
            <div class="btn-group">
                <a class="btn btn-primary btn-lg" href="">
                    <i class="glyphicon glyphicon-pencil"> </i> {{ lang('New Topic') }}
                </a>
            </div>
        </div>
    </div>

    <div class="panel panel-default corner-radius">
        <div class="panel-body text-center" style="padding: 7px; padding-top: 8px;">
            <a href="http://www.ucloud.cn/site/seo.html?utm_source=zanzhu&utm_campaign=phphub&utm_medium=display&utm_content=shengji&ytag=phphubshenji" target="_blank" rel="nofollow" title="" style="line-height: 66px;">
                <img src="http://ww1.sinaimg.cn/large/6d86d850jw1f2xfmssojsj20dw03cjs5.jpg" width="100%">
            </a>
        </div>
    </div>
</div>
