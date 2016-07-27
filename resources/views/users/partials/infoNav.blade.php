<ul class="nav nav-tabs user-info-nav" role="tablist">
    <li>
        <a href="">{{ lang('Basic Info') }}</a>
    </li>
    <li>
        <a href="">{{ lang('Replies') }}</a>
    </li>
    <li>
        <a href="">{{ lang('Topics') }}</a>
    </li>
    <li>
        <a href="">{{ lang('Favorites') }}</a>
    </li>
    @if(Auth::check() && Auth::id() == $user->id)
        <!-- <li>
            <a href="" >{{ lang('Access Tokens') }}</a>
        </li> -->
    @endif
</ul>
