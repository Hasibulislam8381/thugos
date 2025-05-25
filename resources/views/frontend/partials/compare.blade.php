{{-- <a href="{{ route('compare') }}" class="d-flex align-items-center text-reset">

    <span class="flex-grow-1 ml-1">
        @if (Session::has('compare'))
            <span class="badge badge-primary badge-inline badge-pill">{{ count(Session::get('compare')) }}</span>
        @else
            <span class="badge badge-primary badge-inline badge-pill">0</span>
        @endif
        <span class="nav-box-text d-none d-xl-block opacity-70">{{ translate('ACCOUNT') }}</span>
    </span>
</a> --}}
@auth
    @if (isAdmin())
        <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0 login_menu">
            <i class="fa-regular fa-user rounded-border panel_user  "></i>
            <a href="{{ route('admin.dashboard') }}"
                class="text-reset d-inline-block  nav_menu_text ">{{ translate('My Panel') }}</a>
        </li>
    @else
        <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0 dropdown">
            <!-- ... Existing code for notifications ... -->
        </li>

        <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
            <i class="fa-regular fa-user rounded-border panel_user color_white"></i>
            <a href="{{ route('dashboard') }}"
                class="text-reset d-inline-block py-2 nav_menu_text color_white">{{ translate('My Panel') }}</a>
        </li>
    @endif

    <li class="list-inline-item">
        <span class="logout_icon"><i class="fa-solid fa-arrow-right-from-bracket color_white"></i></span> <a
            href="{{ route('logout') }}"
            class="text-reset d-inline-block py-2 nav_menu_text  color_white">{{ translate('Logout') }}</a>
    </li>
@else
    <!-- User is not authenticated, show login and registration links -->

    <span class="pt-3 badge badge-inline badge-pill"><i class="fa-regular fa-user rounded-border color_white"></i><a
            href="{{ route('user_login') }}"
            class="text-reset d-inline-block  py-2 nav_menu_text color_white">{{ translate('ACCOUNT') }}</a>


        {{-- <a href="{{ route('register') }}"
            class="text-reset d-inline-block  py-2">{{ translate('Register') }}</a> --}}



    </span>

@endauth
