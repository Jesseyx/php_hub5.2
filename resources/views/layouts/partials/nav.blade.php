<div class="navbar navbar-default navbar-static-top topnav" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand">PHPHub</a>
        </div>

        <duv id="top-navbar-collapse" class="navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#" target="_blank">{{ lang('Topics') }}</a>
                </li>
                <li>
                    <a href="#" target="_blank">{{ lang('Jobs') }}</a>
                </li>
                <li>
                    <a href="#" target="_blank">{{ lang('Wiki') }}</a>
                </li>
                <li>
                    <a href="#" target="_blank">{{ lang('Document') }}</a>
                </li>
                <li>
                    <a href="https://cs.phphub.org/" target="_blank">LTS 速查表</a>
                </li>
            </ul>

            <div class="navbar-right">

                <ul class="nav navbar-nav github-login">
                    @if (Auth::check())
                        <li>

                        </li>
                        <li>
                            <a href="">
                                <i class="fa fa-user"></i> {{ $currentUser->name }}
                            </a>
                        </li>
                        <li>
                            <a class="button" href="">
                                <i class="fa fa-sign-out"></i> {{ lang('Logout') }}
                            </a>
                        </li>
                    @else
                        <a id="login-btn" class="btn btn-info" href="{{ URL::route('login') }}">
                            <i class="fa fa-github-alt"></i>
                            {{ lang('Login') }}
                        </a>
                    @endif
                </ul>
            </div>
        </duv>
    </div>
</div>
