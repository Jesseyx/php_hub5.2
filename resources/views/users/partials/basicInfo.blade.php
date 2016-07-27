<div style="text-align: center;">
    <a href="">
        <img class="img-thumbnail users-show-avatar" src="{{ $user->present()->gravatar(380) }}" style="width: 100%; margin: 4px 0px 15px; min-height: 190px;">
    </a>
</div>

<dl class="dl-horizontal">
    <dt><lable>&nbsp; </lable></dt>
    <dd>{{ lang('User ID:') }} {{ $user->id }}</dd>

    <dt><label>Name:</label></dt>
    <dd><strong>{!! $user->name !!}</strong></dd>

    @if ($user->present()->hasBadge())
        <dt><label>Role:</label></dt>
        <dd><span class="label label-warning">{!! $user->present()->badgeName() !!}</span></dd>
    @endif

    @if ($user->real_name)
        <dt class="adr"><label> {{ lang('Real Name') }}:</label></dt>
        <dd><span class="org">{!! $user->real_name !!}</span></dd>
    @endif

    <dt><label>Github:</label></dt>
    <dd>
        <a href="https://github.com/{{ $user->github_name }}" target="_blank">
            <i class="fa fa-github-alt"></i> {{ $user->github_name }}
        </a>
    </dd>

    @if ($user->company)
        <dt class="adr"><label> {{ lang('Company') }}:</label></dt>
        <dd><span class="org">{!! $user->company !!}</span></dd>
    @endif

    @if ($user->city)
        <dt class="adr"><label> {{ lang('City') }}:</label></dt>
        <dd><span class="org"><i class="fa fa-map-marker"></i> {!! $user->city !!}</span></dd>
    @endif

    @if ($user->twitter_account)
        <dt><label><span>Twitter</span>:</label></dt>
        <dd>
            <a href="https://twitter.com/{{ $user->twitter_account }}" rel="nofollow" class="twitter" target="_blank"><i class="fa fa-twitter"></i> {!! '@' . $user->twitter_account !!}
            </a>
        </dd>
    @endif

    @if ($user->personal_website)
        <dt><label>{{ lang('Blog') }}:</label></dt>
        <dd>
            <a href="http://{{ $user->personal_website }}" rel="nofollow" target="_blank" class="url">
                <i class="fa fa-globe"></i> {!! str_limit($user->personal_website, 22) !!}
            </a>
        </dd>
    @endif

    <dt><label>Since:</label></dt>
    <dd><span>{{ $user->created_at }}</span></dd>
</dl>

<div class="clearfix"></div>

@if ($currentUser && ($currentUser->id == $user->id))
    <a id="user-edit-button" class="btn btn-primary btn-block" href="">
        <i class="fa fa-edit"></i> {{ lang('Edit Profile') }}
    </a>
@endif

{{-- @if(Auth::check() && Auth::id() == $user->id)
  @include('users.partials.login_QR')
@endif --}}
