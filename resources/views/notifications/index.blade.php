@extends('layouts.default')

@section('title')
    {{ lang('My Notifications') }} @parent
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ lang('My Notifications') }}
        </div>

        @if (count($notifications))

        @else
            <div class="panel-body">
                <div class="empty-block">{{ lang('You dont have any notice yet!') }}</div>
            </div>
        @endif
    </div>
@stop
