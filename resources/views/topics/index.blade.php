@extends('layouts.default')

@section('title')
    {{ lang('Topic List') }}_@parent
@stop

@section('content')
    @if (!request('filter') && !isset($category))
        @include('layouts.partials.topbanner')
    @endif

    <div class="col-md-9 topics-index main-col">
        <div class="panel panel-default">

            <div class="panel-heading">
                @if (isset($category))
                    <div class="pull-left panel-title">{{ lang('Current Category') }}: {{ $category->name }}</div>
                @endif

                @include('topics.partials.filter')

                <div class="clearfix"></div>
            </div>

            @if (!$topics->isEmpty())
                <div class="panel-body remove-padding-horizontal">
                    @include('topics.partials.topics', ['column' => false])
                </div>
                
                <div class="panel-footer text-right remove-padding-horizontal pager-footer">
                    <!-- Pager -->
                    {!! $topics->appends(request()->except('page', '_pjax'))->render() !!}
                </div>

            @else
                <div class="panel-body">
                    <div class="empty-block">{{ lang('Dont have any data Yet') }}~~</div>
                </div>
            @endif

        </div>

        <!-- Nodes Listing -->

    </div>

    @include('layouts.partials.sidebar')

    @include('layouts.partials.bottombanner')
@stop
