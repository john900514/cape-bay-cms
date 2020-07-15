<li class="c-sidebar-nav-item">
    <a href="{{ backpack_url('dashboard') }}" class="router-link-exact-active c-active c-sidebar-nav-link"><i class="c-sidebar-nav-icon fad fa-tachometer-alt-fast"></i> <span>{{ trans('backpack::base.dashboard') }}</span><span class="badge badge-primary"> NEW! </span></a>
</li>
<li class="c-sidebar-nav-title">Navigation</li>
<li class="c-sidebar-nav-dropdown">
    <a class="c-sidebar-nav-dropdown-toggle" href="#">
        <i class="fad fa-link c-sidebar-nav-icon"></i>Clients
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
            <a href="#/base/breadcrumbs" class="c-sidebar-nav-link" target="_self">AllCommerce</a></li>
        <li class="c-sidebar-nav-item">
            <a href="#/base/cards"       class="c-sidebar-nav-link" target="_self">TruFit Athletic Clubs</a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="#/base/carousels"   class="c-sidebar-nav-link" target="_self">THE Athletic Club</a>
        </li>
    </ul>
</li>

<li class="c-sidebar-nav-title">Admin</li>
<li class="c-sidebar-nav-item">
    <a href="{!! backpack_url('crud-clients') !!}" class="c-sidebar-nav-link" target="_self"><i class="fad fa-link c-sidebar-nav-icon"></i>Client Management</a>
</li>
<li class="c-sidebar-nav-item">
    <a href="{!! backpack_url('crud-users') !!}" class="c-sidebar-nav-link" target="_self"><i class="fad fa-user-alien c-sidebar-nav-icon"></i>Users</a>
</li>
<li class="c-sidebar-nav-item">
    <a href="{!! backpack_url('crud-roles') !!}" class="c-sidebar-nav-link" target="_self"><i class="fad fa-paint-roller c-sidebar-nav-icon"></i>Roles</a>
</li>
<li class="c-sidebar-nav-item">
    <a href="{!! backpack_url('crud-abilities') !!}" class="c-sidebar-nav-link" target="_self"><i class="fad fa-biking-mountain c-sidebar-nav-icon"></i>Abilities</a>
</li>
