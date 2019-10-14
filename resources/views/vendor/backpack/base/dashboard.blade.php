@extends('backpack::layout', ['client_id'=> $client_id])

@section('header')
    <section class="content-header">
        <h1>
            {{ trans('backpack::base.dashboard') }}<small>Hello, {!! backpack_user()->name !!}!</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    <v-app id="app" class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <!-- @todo - pass in the client id -->
                        <dashboard
                            :client-i-d="{!! $client_id !!}"
                        ></dashboard>
                </div>
            </div>
        </div>
    </v-app>
@endsection

@section('after_scripts')

@endsection
