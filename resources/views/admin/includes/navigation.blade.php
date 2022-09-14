<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" src="{{asset('/admin-assets/img/profile_small.jpg')}}" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{auth('admin')->user()->name}}</span>
                        <span class="text-muted text-xs block">Admin <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li>
                            <form id="logout-navbar-form" action="{{ url('admin/logout') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{ url('admin/logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-navbar-form').submit();">Logout</a>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">
                    LI+
                </div>
            </li>
            <li class="{{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
                <a href="{{ url('admin/dashboard') }}">
                    <i class="fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="{{ (request()->is('admin/users')) ? 'active' : '' }}">
                <a href="{{ url('admin/users') }}">
                    <i class="fa fa-users"></i>
                    <span class="nav-label">Users</span>
                </a>
            </li>

            <li
                class="{{ (request()->is('admin/trnsl-keys') || request()->is('admin/trnsl-keys/*')) ? 'active' : '' }}">
                <a href="{{url('admin/trnsl-keys')}}">
                    <i class="fa fa-key"></i>
                    <span class="nav-label">Translation Keys</span>
                </a>
            </li>

            <li
                class="{{ (request()->is('admin/trnsl-requests') || request()->is('admin/trnsl-requests/*')) ? 'active' : '' }}">
                <a href="{{url('admin/trnsl-requests')}}">
                    <i class="fa fa-language"></i>
                    <span class="nav-label">Translation Requests</span>
                </a>
            </li>

            <li
                class="{{ (request()->is('admin/pushtry-requests') || request()->is('admin/pushtry-requests/*')) ? 'active' : '' }}">
                <a href="{{url('admin/pushtry-requests')}}">
                    <i class="fa fa-bell"></i>
                    <span class="nav-label">PushTry Requests</span>
                </a>
            </li>
            <li class="{{ (request()->is('admin/ssl-checker') || request()->is('admin/ssl-checker/*')) ? 'active' : '' }}">
                <a href="{{url('admin/ssl-checker')}}">
                    <i class="fa fa-lock"></i>
                    <span class="nav-label">SSL Checker</span>
                </a>
            </li>
            <li class="{{ (request()->is('admin/logs') || request()->is('admin/logs/*')) ? 'active' : '' }}">
                <a href="{{url('admin/logs')}}">
                    <i class="fa fa-bug"></i>
                    <span class="nav-label">Logs</span>
                </a>
            </li>
            <li class="{{ (request()->is('admin/jobs-table') || request()->is('admin/jobs/*')) ? 'active' : '' }}">
                <a href="{{url('admin/jobs-table')}}">
                    <i class="fa fa-clock-o"></i>
                    <span class="nav-label">Jobs Table</span>
                </a>
            </li>
            <li class="{{ (request()->is('admin/chrome-ext') || request()->is('admin/chrome-ext/*')) ? 'active' : '' }}">
                <a href="{{url('admin/chrome-ext')}}">
                    <i class="fa fa-chrome"></i>
                    <span class="nav-label">Chrome Ext</span>
                </a>
            </li>
        </ul>

    </div>
</nav>