@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Push Notifications
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>

    @if($module == 'default')
        <style>
            #appSelect {
                display: flex;
                flex-flow: column;
                text-align: center;
                margin: 2em 25%
            }
        </style>

        <script>
            function selectApp()
            {
                if(parseInt($('#appSelector').val()) !== 0) {
                    window.location.href = 'messaging/'+$('#appSelector').val();
                }
            }
        </script>
    @elseif($module == 'showtable')
        <style>
            .overflow-hidden {
                height: 30em;
                overflow: scroll;
            }

            #myTable,
            #myTable>thead,
            #myTable>tbody,
            #myTable>tfoot,
            #myTable>tbody>tr,
            #myTable>thead>tr {
                width: 100%;
                display: flex;
                flex-flow: row wrap;
            }

            #myTable>thead,
            #myTable>tbody,
            #myTable>tfoot {
                display: block;
                width: 100%;
                overflow-y: scroll;
            }

            #myTable>thead,
            #myTable>tfoot {
                height: auto;
            }

            #myTable>tbody {
                max-height: 20em;
            }

            #myTable>thead>tr>th,
            #myTable>thead>tr>td,
            #myTable>tbody>tr>th,
            #myTable>tbody>tr>td,
            #myTable>tfoot>tr>th,
            #myTable>tfoot>tr>td {
                display: inline-block;
                width: 19%;
            }

            #myTable>thead>tr>th:first-child,
            #myTable>thead>tr>td:first-child,
            #myTable>tbody>tr>th:first-child,
            #myTable>tbody>tr>td:first-child,
            #myTable>tfoot>tr>th:first-child,
            #myTable>tfoot>tr>td:first-child {
                width: 5%;
            }

        </style>

        <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
        <script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>
    @endif
@endsection

@section('content')
    <div id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    @if($module == 'default')
                        <div id="appSelect">
                            <label>Select an App</label>
                            <select id="appSelector" onchange="selectApp()">
                                @foreach($apps_available as $app_id => $app_name)
                                    <option value="{!! $app_id !!}" >{!! $app_name !!}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($module == 'showtable')
                        @include('messaging.partials.showtable')
                    @elseif($module == 'unauthorized')
                        <div id="appSelect">
                            <h1> Unauthorized </h1>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    @if($module == 'showtable')
        <!-- DATA TABLES -->
        <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

        <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

        <!-- CRUD LIST CONTENT - crud_list_styles stack -->
        @stack('crud_list_styles')
    @endif
@endsection

@section('after_scripts')
    @if($module == 'showtable')
        @include('messaging.partials.vuejs')
    @endif
@endsection
