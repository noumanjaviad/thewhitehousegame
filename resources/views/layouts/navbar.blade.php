<!-- BEGIN: Head-->
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
            </li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">

            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">{{Auth::User()->name}}</span><span class="user-status">Admin</span></div><span class="avatar">{{--<img class="round"  src="{{asset()}}" alt="avatar" height="40" width="40">--}}<img src="{{asset('app-assets/images/logo/TWH-logo.png')}}" height="40" width="40" ><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">{{--<a class="dropdown-item" href=""><i class="mr-50" data-feather="user"></i> Profile</a>--}}
                    <div class="dropdown-divider"></div><a class="dropdown-item" href="{{route('logout')}}"><i class="mr-50" data-feather="power"></i>Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- END: Head-->
