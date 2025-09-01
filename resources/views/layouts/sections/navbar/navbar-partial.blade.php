@php
    use App\Helpers\NotificationHelper;use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
@endphp

    <!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4 ms-0">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">@include('_partials.macros')</span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
        </a>

        <!-- Display menu close icon only for horizontal-menu with navbar-full -->
        @if (isset($menuHorizontal))
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="icon-base ti tabler-x icon-sm d-flex align-items-center justify-content-center"></i>
            </a>
        @endif
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base ti tabler-menu-2 icon-md"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

    @if (!isset($menuHorizontal))
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">
                <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete-vertical"></span>
            </div>
        </div>
        <!-- /Search -->
    @endif

    <ul class="navbar-nav flex-row align-items-center ms-md-auto">
        @if (isset($menuHorizontal))
            <!-- Search -->
            <li class="nav-item navbar-search-wrapper btn btn-text-secondary btn-icon rounded-pill">
                <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete-horizontal"></span>
            </li>
            <!-- /Search -->
        @endif

        @if ($configData['hasCustomizer'] == true)
            <!-- Style Switcher -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill" id="nav-theme"
                   href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-sun icon-22px theme-icon-active text-heading"></i>
                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                    <li>
                        <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light"
                                aria-pressed="false">
                            <span><i class="icon-base ti tabler-sun icon-22px me-3" data-icon="sun"></i>Light</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                            <span><i class="icon-base ti tabler-moon-stars icon-22px me-3" data-icon="moon-stars"></i>Dark</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system"
                                aria-pressed="false">
            <span><i class="icon-base ti tabler-device-desktop-analytics icon-22px me-3"
                     data-icon="device-desktop-analytics"></i>System</span>
                        </button>
                    </li>
                </ul>
            </li>
            <!-- / Style Switcher-->
        @endif

        <!-- Quick links  -->
        <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
            <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
               href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <i class="icon-base ti tabler-layout-grid-add icon-22px text-heading"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-0">
                <div class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h6 class="mb-0 me-auto">Quick Links</h6>
                    </div>
                </div>
                <div class="dropdown-shortcuts-list scrollable-container">
                    <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-calendar icon-26px text-heading"></i>
              </span>
                            <a href="{{ url('app/calendar') }}" class="stretched-link">Calendar</a>
                            <small>Appointments</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-file-dollar icon-26px text-heading"></i>
              </span>
                            <a href="{{ url('app/invoice/list') }}" class="stretched-link">Invoice App</a>
                            <small>Manage Accounts</small>
                        </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-user icon-26px text-heading"></i>
              </span>
                            <a href="{{ url('app/user/list') }}" class="stretched-link">User App</a>
                            <small>Manage Users</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-users icon-26px text-heading"></i>
              </span>
                            <a href="{{ url('app/access-roles') }}" class="stretched-link">Role Management</a>
                            <small>Permission</small>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <!-- Quick links -->

        <!-- Notification -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
            <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
               href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
    <span class="position-relative">
      <i class="icon-base ti tabler-bell icon-22px text-heading"></i>
      <span class="badge rounded-pill bg-danger badge-dot badge-notifications border {{ NotificationHelper::getUnreadNotificationCount() > 0 ? '' : 'd-none' }}" id="notification-bell-badge"></span>
    </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-0">
                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h6 class="mb-0 me-auto">Notification</h6>
                        <div class="d-flex align-items-center h6 mb-0">
                            <span class="badge bg-label-primary me-2" id="notification-count">{{ NotificationHelper::getUnreadNotificationCount() }} New</span>
                            <a href="javascript:void(0)" class="dropdown-notifications-all p-2 btn btn-icon" data-bs-toggle="tooltip"
                               data-bs-placement="top" title="Mark all as read"><i
                                    class="icon-base ti tabler-mail-opened text-heading"></i></a>
                        </div>
                    </div>
                </li>
                <li class="dropdown-notifications-list scrollable-container">
                    <ul class="list-group list-group-flush pt-0" id="notification-list">
                        @foreach(NotificationHelper::getLatestNotifications() as $notification)
                            <a href="{{ $notification->link }}" class="list-group-item list-group-item-action dropdown-notifications-item notification-link border-0" data-notification-id="{{ $notification->id }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-info"><i class="ti ti-bell"></i></span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="small mb-1">{{ $notification->title }}</h6>
                                        <small class="mb-1 d-block text-body">{{ $notification->body }}</small>
                                        <small class="text-body-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        @if(!$notification->read_at)
                                            <span class="badge badge-dot"></span> <br>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </ul>
                </li>
                <li class="border-top">
                    <div class="d-grid p-4">
                        <a class="btn btn-primary btn-sm d-flex" href="/user/profile/{{ Auth::id() }}/#notifications">
                            <small class="align-middle">View all notifications</small>
                        </a>
                    </div>
                </li>
            </ul>
        </li>
        <!--/ Notification -->
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt
                         class="rounded-circle" />
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item mt-0"
                       href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar avatar-online">
                                    <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}"
                                         alt class="rounded-circle" />
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">
                                    @if (Auth::check())
                                        {{ Auth::user()->name }}
                                    @else
                                        John Doe
                                    @endif
                                </h6>
                                <small class="text-body-secondary">{{ ucwords( Auth::user()->primaryRole->name)  }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                </li>
                <li>
                    <a class="dropdown-item"
                       href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                        <i class="icon-base ti tabler-user me-3 icon-md"></i><span class="align-middle">My Profile</span> </a>
                </li>
                @if (Auth::check() && Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <li>
                        <a class="dropdown-item" href="{{ route('api-tokens.index') }}">
                            <i class="icon-base ti tabler-settings me-3 icon-md"></i><span class="align-middle">API Tokens</span> </a>
                    </li>
                @endif
                <li>
                    <a class="dropdown-item" href="{{ url('pages/account-settings-billing') }}">
            <span class="d-flex align-items-center align-middle">
              <i class="flex-shrink-0 icon-base ti tabler-file-dollar me-3 icon-md"></i><span
                    class="flex-grow-1 align-middle">Billing</span>
              <span class="flex-shrink-0 badge bg-danger d-flex align-items-center justify-content-center">4</span>
            </span>
                    </a>
                </li>
                @if (Auth::User() && Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <li>
                        <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                        <h6 class="dropdown-header">Manage Team</h6>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item"
                           href="{{ Auth::user() ? route('teams.show', Auth::user()->currentTeam->id) : 'javascript:void(0)' }}">
                            <i class="icon-base bx bx-cog icon-md me-3"></i><span>Team Settings</span>
                        </a>
                    </li>
                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <li>
                            <a class="dropdown-item" href="{{ route('teams.create') }}">
                                <i class="icon-base bx bx-user icon-md me-3"></i><span>Create New Team</span>
                            </a>
                        </li>
                    @endcan
                    @if (Auth::user()->allTeams()->count() > 1)
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <h6 class="dropdown-header">Switch Teams</h6>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                    @endif
                    @if (Auth::user())
                        @foreach (Auth::user()->allTeams() as $team)
                            {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want to use jetstream. --}}

                            {{-- <x-switchable-team :team="$team" /> --}}
                        @endforeach
                    @endif
                @endif
                <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                </li>
                @if (Auth::check())
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Logout</span>
                        </a>
                    </li>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}">
                        @csrf
                    </form>
                @else
                    <li>
                        <div class="d-grid px-2 pt-2 pb-1">
                            <a class="btn btn-sm btn-danger d-flex"
                               href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}" target="_blank">
                                <small class="align-middle">Login</small>
                                <i class="icon-base ti tabler-login ms-2 icon-14px"></i>
                            </a>
                        </div>
                    </li>
                @endif
            </ul>
        </li>
        <!--/ User -->
    </ul>
</div>
