@extends('layout.HUdefault')
@section('title')
    Home
@stop
@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
                    <h1>{{ Lang::get('dashboard.title') }}</h1>
                    <p>{{ Lang::get('home.welcome') }}
                        <br /><br />{{ Lang::get('home.see-menu') }}</p>
                    <ul>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.learningprocess') }}</b> {{ Lang::get('home.steps.1') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.progress') }}</b> {{ Lang::get('home.steps.2') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.analysis') }}</b> {{ Lang::get('home.steps.3') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.deadlines') }}</b> {{ Lang::get('home.steps.4') }}</li>
                        <li>{{ Lang::get('home.with-tile') }} <b>{{ Lang::get('home.profile') }}</b> {{ Lang::get('home.steps.5') }}</li>
                    </ul>
                    <p>{{ Lang::get('home.goodluck') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <br /><a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px" height="16px" /> {{ Lang::get('home.tips') }}</a>
                </div>
            </div>
        </div>
@stop
