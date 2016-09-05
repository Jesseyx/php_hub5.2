<div class="navbar navbar-default navbar-static-top topnav" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand">
                <img src="{{ cdn('assets/images/logo2.png') }}" alt="PHPHub" />
            </a>
        </div>

        <duv id="top-navbar-collapse" class="navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{ request()->is('topics*') && !request()->is('categories*') ? 'active' : '' }}">
                    <a href="{{ route('topics.index') }}" target="_blank">{{ lang('Topics') }}</a>
                </li>
                <li class="{{ request()->is('categories/5') ? 'active' : '' }}">
                    <a href="{{ route('categories.show', [5, 'filter' => 'recent']) }}">教程</a>
                </li>
                <li class="{{ request()->is('categories/1') ? 'active' : '' }}">
                    <a href="{{ route('categories.show', 1) }}">{{ lang('Jobs') }}</a>
                </li>
                <li class="{{ request()->is('categories/3') ? 'active' : '' }}">
                    <a href="{{ route('categories.show', 3) }}">{{ lang('Q&A') }}</a>
                </li>
                <li class="{{ request()->is('sites') ? 'active' : '' }}">
                    <a href="{{ route('sites.index') }}">{{ lang('Sites') }}</a>
                </li>
                <li class="{{ request()->is('topics/2541') ? ' active' : '' }}">
                    <a href="https://phphub.org/topics/2541">Wiki</a>
                </li>
            </ul>

            <div class="navbar-right">

                <form class="navbar-form navbar-left" method="GET" action="{{ route('search') }}" accept-charset="utf-8" target="_blank">
                    <div class="form-group">
                        <input class="form-control search-input mac-style" name="q" type="text" placeholder="{{ lang('Search') }}">
                    </div>
                </form>

                <ul class="nav navbar-nav github-login">
                    @if (Auth::check())
                        <li>
                            <a class="popover-with-html" href="{{ isset($category) ? route('topics.create', ['category_id' => $category->id]) : route('topics.create') }}" data-placement="bottom" data-content="添加主题">
                                <i class="fa fa-plus text-md"></i>
                            </a>
                        </li>
                        <li>
                            <a class="text-warning" href="{{ route('notifications.index') }}" style="margin-top: -4px;">
                                <span id="notification-count" class="badge badge-{{ $currentUser->notification_count > 0 ? 'important' : 'fade' }}" data-content="消息提醒">
                                    {{ $currentUser->notification_count }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a id="dLabel" href="#" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="avatar-topnav" src="{{ $currentUser->present()->gravatar }}" alt="Summer" />
                                {{ $currentUser->name }}
                                <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                                @if (Auth::user()->can('visit_admin'))
                                    <li>
                                        <a class="no-pjax" href="/admin" target="_blank">
                                            <i class="fa fa-tachometer text-md"></i> 管理后台
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <a class="button" href="{{ route('users.show', $currentUser->id) }}" data-lang-loginout="{{ lang('Are you sure want to logout?') }}">
                                        <i class="fa fa-user text-md"></i> 个人中心
                                    </a>
                                </li>

                                <li>
                                    <a class="button" href="{{ route('users.edit', $currentUser->id) }}" >
                                        <i class="fa fa-cog text-md"></i> 编辑资料
                                    </a>
                                </li>

                                <li>
                                    <a id="login-out" class="button" href="{{ route('logout') }}" data-lang-loginout="{{ lang('Are you sure want to logout?') }}">
                                        <i class="fa fa-sign-out"></i> {{ lang('Logout') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <a class="btn btn-success login-btn weichat-login-btn" href="{{ route('auth.oauth', ['driver' => 'wechat']) }}">
                            <i class="fa fa-weixin"></i>
                            {{ lang('Login') }}
                        </a>

                        <a class="btn btn-info login-btn" href="{{ route('auth.oauth', ['driver' => 'github']) }}">
                            <i class="fa fa-github-alt"></i>
                            {{ lang('Login') }}
                        </a>
                    @endif
                </ul>
            </div>
        </duv>
    </div>
</div>
