<script>
    let anchorCMS = {
        backpackUser: {!! json_encode(backpack_user()->toArray()) !!},
        backpackURL: '{{ backpack_url() }}',
        dashboardURL: '{{ Bouncer::is(backpack_user())->a('client') ? env('APP_URL').'/dashboard' :backpack_url('dashboard') }}',
        transDash: '{{ trans('backpack::base.dashboard') }}'
    };
</script>
<script src="{{ asset('js/app.js') }}" defer></script>
