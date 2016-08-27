@extends('layouts.default')

@section('content')

    <div class="box text-center site-intro rm-link-color">
        {!! lang('site_intro') !!}
    </div>

    @include('layouts.partials.topbanner')

    <div class="panel panel-default list-panel home-topic-list">
        <div class="panel-heading">
            <h3 class="panel-title text-center">
                {{ lang('Excellent Topics') }} &nbsp;
                <a href="{{ route('feed') }}" target="_blank" style="color: #E5974E; font-size: 14px;">
                    <i class="fa fa-rss"></i>
                </a>
            </h3>
        </div>

        <div class="panel-body ">
            @include('pages.partials.topics')
        </div>

        <div class="panel-footer text-right">

            <a class="more-excellent-topic-link" href="topics?filter=excellent">
                {{ lang('More Excellent Topics') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>

@stop
