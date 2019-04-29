@extends('layouts.app')

@section('head-styles')
<style>
    .card-header {
        background-color: lightslategrey;
        color: white;
    }

    #clientSelect {
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Live Tracking</div>

                <div class="card-body">
                    <div id="clientSelect">
                        <select>
                        @foreach($clients as $value => $client_name)
                            <option value="{{ $value }}">{{ $client_name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
