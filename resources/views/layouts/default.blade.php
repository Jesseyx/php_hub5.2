<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            PHPHub - 中国最靠谱的 PHP 和 Laravel 开发者社区
        @show
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="keywords" content="PHP,Laravel,PHP论坛,Laravel论坛,PHP社区,Laravel社区" />
    <meta name="author" content="The PHP China Community." />
    <meta name="description" content="@section('description') PHPHub 是 PHP 和 Laravel 的中文社区，致力于推动 Laravel，php-fig 等 PHP 新技术，新理念在中国的发展，是国内最靠谱的 PHP 论坛。 @show" />
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ elixir('assets/css/styles.css') }}">

    <link rel="shortcut icon" href="{{ cdn('favicon.ico') }}"/>
    <script>
        var Config = {
            cdnDomain: '{{ getCdnDomain() }}',
            user_id: {{ $currentUser ? $currentUser->id : 0 }},
            user_avatar: {!! $currentUser ? "'" . $currentUser->present()->gravatar() . "'" : "''" !!},
            user_link: {!! $currentUser ? "'" . route('users.show', $currentUser->id) . "'" : "''" !!},
            routes: {
                'notificationsCount' : '{{ route('notifications.count') }}',
                'upload_image' : '{{ route('upload_image') }}'
            },
            token: '{{ csrf_token() }}',
            environment: '{{ app()->environment() }}',
            following_users: []
        };

        // 是否显示：提醒用户安装 crx 谷歌插件
        var ShowCrxHint = '{{ isset($show_crx_hint) ? $show_crx_hint : 'no' }}';
    </script>

    @yield('styles')
</head>
<body id="body">
    <div id="wrap">
        @include('layouts.partials.nav')

        <div class="container main-container">
            @if(\Auth::check() && !\Auth::user()->verified && !request()->is('email-verification-required'))
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    邮箱未激活，请前往 {{ \Auth::user()->email }} 查收激活邮件，激活后才能完整地使用社区功能，如发帖和回帖。未收到邮件？请前往 <a href="{{ route('email-verification-required') }}">重发邮件</a> 。
                </div>
            @endif

            @include('flash::message')

            @yield('content')

        </div>

        @include('layouts.partials.footer')
    </div>

    <script src="{{ elixir('assets/js/scripts.js') }}"></script>

    @yield('scripts')

    @if (app()->environment() == 'production')
        <!-- 统计代码 -->
    @endif
</body>
</html>
