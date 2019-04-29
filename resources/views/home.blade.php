@extends('layouts.app')

@section('head-styles')
<style>
    .card-header {
        background-color: lightslategrey;
        color: white;
    }

    #dashMenu {
        display: flex;
        flex-flow: row wrap;
    }

    .dash-menu-link {
        width: 50%;
    }

    .dash-menu-link a {
        text-decoration: none;
        color: black;
        font-size: 0.85em;
    }

    .dash-menu-link a span:hover {
        color: slategray;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

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
