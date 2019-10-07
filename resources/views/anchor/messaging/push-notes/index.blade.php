@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Push Notifications
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li><a href="{{ backpack_url('dashbord') }}">{{ trans('backpack::base.dashboard') }}</a></li>
            <li class="active">Push Notifications</li>
        </ol>
    </section>
@endsection

@section('content')
    <v-app id="app" class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <!-- put content here.... -->
                    <push-notifications-menu></push-notifications-menu>
                </div>
            </div>
        </div>
    </v-app>
@endsection
