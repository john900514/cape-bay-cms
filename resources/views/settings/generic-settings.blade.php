@extends('backpack::layout')

@section('header')
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ env('APP_URL') }}/css/font-awesome/css/all.min.css" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <section class="content-header">
        <h1>
            {!! $page_name !!} Settings
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
                    <div class="box-body">
                        <div id="dashMenu">
                            @foreach($setting_options as $order => $option)
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


    </div>
@endsection
