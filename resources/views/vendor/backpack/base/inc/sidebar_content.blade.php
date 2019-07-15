<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
@if(Bouncer::is(backpack_user())->a('god'))
    <li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
@endif

@if(Bouncer::is(backpack_user())->a('god', 'master', 'admin'))
<li><a href='{{ backpack_url('clients') }}'><i class='fa fa-tag'></i> <span>Clients</span></a></li>
@endif

@foreach($menu_options as $order => $option)
    <li><a href="{{ backpack_url() .'/'. $option['route'] }}" {{ !is_null($option['onclick']) ? 'onclick='.$option['onclick'] : '' }}><i class="{{ $option['icon'] }}"></i> <span>{{ $option['name'] }}</span></a></li>
@endforeach
