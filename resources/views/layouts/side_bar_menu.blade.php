<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header expanded" style="height: auto">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand mt-0" style="width: 100px" > <span class="brand-logo">
                       <img src="{{asset('/app-assets/images/logo/TWH-logo.png')}}" style="max-width: 80px">
                           </span>
                    {{--<h2 class="brand-text" style="margin-top: 20px;" >KFS</h2>--}}
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item {{Request::is('admin/dashboard')? 'active' : ''}} "><a class="d-flex align-items-center" href="{{url('/admin/dashboard')}}"><i data-feather='home'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
            </li>
        </ul>
        {{-- <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><i data-feather='user'></i><span class="menu-title text-truncate" data-i18n="Dashboards">User</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    <li class="{{Request::is('admin/user/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href=""><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create User</span></a>
                    </li>
                    <li class="{{Request::is('admin/user')? 'active' : ''}}"><a class="d-flex align-items-center" href=""><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show User</span></a>
                    </li>
                </ul>
            </li>
        </ul> --}}
         {{-- user menu --}}
         <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/user-icon.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:12px"  data-i18n="Dashboards">User</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/country-birth.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:12px" data-i18n="Dashboards">Country Birth</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        <ul class="menu-content">
                            <li class="{{Request::is('admin/ucb/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('ucb.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create Country Birth</span></a>
                            </li>
                            <li class="{{Request::is('admin/ucb')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('ucb.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show Country Birth</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/ethnic-icon.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:12px" data-i18n="Dashboards">Ethnicity</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        <ul class="menu-content">
                            <li class="{{Request::is('admin/ethnicity/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('ethnicity.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create ethnicity</span></a>
                            </li>
                            <li class="{{Request::is('admin/ethnicity')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('ethnicity.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show ethnicity</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/employment-icons.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:12px" data-i18n="Dashboards">Employement</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                        <ul class="menu-content">
                            <li class="{{Request::is('admin/employement/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('employement.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create employement</span></a>
                            </li>
                            <li class="{{Request::is('admin/employement')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('employement.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show employement</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        {{-- end user menu  --}}
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/candidates-icon.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:12px" data-i18n="Dashboards">Candidates</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    
                    <li class="{{Request::is('admin/candidate/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('candidate.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create Candidte</span></a>
                    </li>
                    <li class="{{Request::is('admin/candidate')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('candidate.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show Candidates</span></a>
                    </li>
                </ul>
            </li>
        </ul>
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/voter-party.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:14px" data-i18n="Dashboards">Voter Party</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    <li class="{{Request::is('admin/parties/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('parties.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create Voter Party</span></a>
                    </li>
                    <li class="{{Request::is('admin/parties')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('parties.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show parties</span></a>
                    </li>
                </ul>
            </li>
        </ul>
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/state-icon.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:14px" data-i18n="Dashboards">State</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    <li class="{{Request::is('admin/state/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('state.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create State</span></a>
                    </li>
                    <li class="{{Request::is('admin/state')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('state.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show States</span></a>
                    </li>
                </ul>
            </li>
        </ul>
        {{-- <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><i data-feather='box'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Ucb</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    <li class="{{Request::is('admin/ucb/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('ucb.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create Ucb</span></a>
                    </li>
                    <li class="{{Request::is('admin/ucb')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('ucb.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show Ucb</span></a>
                    </li>
                </ul>
            </li>
        </ul> --}}
        {{-- <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><i data-feather='box'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Ethnicity</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    <li class="{{Request::is('admin/ethnicity/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('ethnicity.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create ethnicity</span></a>
                    </li>
                    <li class="{{Request::is('admin/ethnicity')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('ethnicity.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show ethnicity</span></a>
                    </li>
                </ul>
            </li>
        </ul> --}}
        {{-- <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><i data-feather='box'></i><span class="menu-title text-truncate" data-i18n="Dashboards">Employement</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    <li class="{{Request::is('admin/employement/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('employement.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Create employement</span></a>
                    </li>
                    <li class="{{Request::is('admin/employement')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('employement.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show employement</span></a>
                    </li>
                </ul>
            </li>
        </ul> --}}
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item"><a class="d-flex align-items-center" href=""><img src="{{asset('/assets/image/previous-election.png')}}"  alt="Menu Icon" style="width: 20px; height: 20px;"><span class="menu-title text-truncate" style="margin-left:14px" data-i18n="Dashboards">Previous Election</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
                <ul class="menu-content">
                    <li class="{{Request::is('admin/previous_election/create')? 'active' : ''}}" ><a class="d-flex align-items-center" href="{{route('previous_election.create')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Insert Previos Election Data </span></a>
                    </li>
                    <li class="{{Request::is('admin/previous_election')? 'active' : ''}}"><a class="d-flex align-items-center" href="{{route('previous_election.index')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="eCommerce">Show previous election</span></a>
                    </li>
                </ul>
            </li>
        </ul>
       
    </div>
</div>
<!-- END: Main Menu !-->
