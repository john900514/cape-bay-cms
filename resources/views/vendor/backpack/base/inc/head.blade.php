<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
@if (config('backpack.base.meta_robots_content'))
<meta name="robots" content="{{ config('backpack.base.meta_robots_content', 'noindex, nofollow') }}">
@endif

{{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="https://capeandbay.com/wp-content/uploads/2019/11/cropped-favicon-32x32.png" sizes="32x32">
<link rel="icon" href="https://capeandbay.com/wp-content/uploads/2019/11/cropped-favicon-192x192.png" sizes="192x192">
<link rel="apple-touch-icon-precomposed" href="https://capeandbay.com/wp-content/uploads/2019/11/cropped-favicon-180x180.png">

<title>
  {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
</title>

<link rel="stylesheet" href="{!! asset('css/app.css') !!}"/>

@yield('before_styles')
@stack('before_styles')

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<style>
    @media screen {
        #app {
            width: 100%;
        }

        .c-header-brand.a {
            width: 50%;
            justify-content: left;
        }
        .c-header-brand.img {
            width: 65%;
            object-fit: contain;
        }

        .c-body .c-main {
            margin: 0 3%;
        }

        .c-dark-theme .img-light {
            display: none;
        }

        .c-dark-theme .img-dark {
            display: flex;
        }

        .img-light {
            display: flex;
            transition: 0.5s;
        }

        .img-dark {
            display: none;
        }

        .c-subheader .breadcrumb {
            background-color: inherit;
        }
    }

    @media screen and (max-width: 999px) {
        .content-header .small-h1 {
            font-size: 45%;
        }
    }

    @media screen and (min-width: 1000px) {
        .content-header .small-h1 {
            font-size: 55%;
        }
    }
</style>
<!-- BackPack Base CSS -->



@yield('after_styles')
@stack('after_styles')
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
