@extends('backpack::layout')

@section('header')
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ env('APP_URL') }}/css/font-awesome/css/all.min.css" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <section class="content-header">
        <h1>
             @if(is_null($tracker)) Live Tracking @else {{ $tracker['name'] }} @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>

    <style>
    .card-header {
        background-color: lightslategrey !important;
        color: white;
    }

    #clientSelect {
        text-align: center;
    }

    .card-header > button {
        background-color: Transparent;
        background-repeat:no-repeat;
        border: none;
        cursor:pointer;
        overflow: hidden;
        outline:none;
        font-size: 1.25em;
        color: #fff;
    }

    .card-header > button .fal {
        font-weight: 400 !important;

    }
</style>
@endsection

@section('content')
    <div id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    @if($module == 'default')
                        <livetracking-dash-component :clientele="{{ json_encode($clients) }}"></livetracking-dash-component>
                    @elseif($module == 'showTracker')
                        <livetracking-view-component :trackerimport="{{ json_encode($tracker) }}" soundfile="{{ $soundFile }}"></livetracking-view-component>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
