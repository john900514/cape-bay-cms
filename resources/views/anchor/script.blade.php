<script>
    let anchorCMS = {
        backpackUser: {!! json_encode(backpack_user()->toArray()) !!},
        backpackURL: '{{ backpack_url() }}',
        dashboardURL: '{{ backpack_url('dashboard') }}',
        transDash: '{{ trans('backpack::base.dashboard') }}'
    };
</script>
<script src="{{ asset('js/app.js') }}" defer></script>
