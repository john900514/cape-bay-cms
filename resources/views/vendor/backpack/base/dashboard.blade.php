@extends('backpack::layout')

@section('header')
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
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
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
@endsection
