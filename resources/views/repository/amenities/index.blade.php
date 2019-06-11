@extends('backpack::layout')

@section('header')
    <script src="{{ asset('js/app.js') }}" defer></script>

    <section class="content-header">
        <h1>
            Amenities Data Store
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    <div id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <store-select-component :fitnessclubs="{{ json_encode($clubs)  }}"></store-select-component>
                </div>
            </div>
        </div>
    </div>
@endsection
