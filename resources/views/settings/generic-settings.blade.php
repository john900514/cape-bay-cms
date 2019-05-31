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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $page_name }} Settings</div>

                    <div class="card-body">

                        <div id="dashMenu">
                            @foreach($menu_options as $order => $option)
                                <div class="dash-menu-link">
                                    <h1><a href="{{ $option['route'] }}" {{ !is_null($option['onclick']) ? 'onclick='.$option['onclick'] : '' }}><i class="{{ $option['icon'] }}"></i> <span>{{ $option['name'] }}</span></a></h1>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
