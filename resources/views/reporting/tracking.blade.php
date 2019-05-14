@extends('layouts.app')

@section('head-styles')
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
    @if($module == 'default')
        <livetracking-dash-component :clientele="{{ json_encode($clients) }}"></livetracking-dash-component>
    @elseif($module == 'showTracker')
        <livetracking-view-component :trackerimport="{{ json_encode($tracker) }}"></livetracking-view-component>
    @endif
@endsection
