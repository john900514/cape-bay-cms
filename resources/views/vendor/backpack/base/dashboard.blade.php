@extends('backpack::layout')

@section('header')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ env('APP_URL') }}/css/font-awesome/css/all.min.css" rel="stylesheet">

    <section class="content-header">
        <h1>
            {{ trans('backpack::base.dashboard') }}<small>Sup, dude?</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>

    <style>
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

        @media screen and (min-width: 721px) {
            .inner-widget-wrap {
                display:flex;
                flex-flow: row wrap;
            }
        }

        @media screen and (max-width: 720px) {
            .inner-widget-wrap {
                display:flex;
                flex-flow: column;
            }
        }

    </style>
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(count($widgets) > 0)
                        <div id="widgetSection">
                            <h1>Widget Dashboard</h1>
                            @foreach($widgets as $client_name => $section_widget)
                                <div class="section-widget-section">
                                    <h2> {!! $client_name !!}</h2>
                                    <div class="inner-widget-wrap">
                                        @foreach($section_widget as $idx => $widget)
                                            {!! $widget['vue_component'] !!}
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
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
@endsection
