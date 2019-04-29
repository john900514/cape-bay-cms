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

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
