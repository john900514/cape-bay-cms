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
    </style>
@endsection

@section('content')
    @if($module == 'default')
        <reporting-view-component :clientele="{{ json_encode($clients)  }}"></reporting-view-component>
    @elseif($module == 'showReport')
        <reporting-report-component :resultdata="{{ json_encode($report) }}"></reporting-report-component>
    @endif
@endsection
