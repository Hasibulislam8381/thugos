<a href="{{ route('wishlists.index') }}" class="d-flex align-items-center text-reset">
    <i class="la la-heart-o la-2x opacity-80 "></i>
    <span class="nav-box-text d-none d-xl-block  nav_menu_text ">{{ translate('FAVOURITE') }}</span>
    <span class="flex-grow-1 ml-1">
        @if (Auth::check())
            <span class="badge badge-secondary badge-inline badge-pill">{{ count(Auth::user()->wishlists) }}</span>
        @else
            <span class="badge badge-secondary badge-inline badge-pill">0</span>
        @endif

    </span>
</a>
