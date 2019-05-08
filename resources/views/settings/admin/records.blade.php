@extends('layouts.app')

@section('head-styles')
    <style>
        .card-header {
            background-color: lightslategrey;
            color: white;
        }
    </style>
@endsection

@section('content')
    @if($module == 'default')
        <records-mgnt-component :clientele="{{ json_encode($clients)  }}"></records-mgnt-component>
    @elseif($module == 'loadRepo')
        <records-repo-view-component :resultdata="{{ json_encode($repoData) }}"></records-repo-view-component>
    @endif
@endsection
