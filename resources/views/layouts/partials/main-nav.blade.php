<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ route('second', ['dashboards', 'index']) }}" class="logo-dark">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
            <img src="/images/logo-dark.png" class="logo-lg" alt="logo dark">
        </a>

        <a href="{{ route('second', ['dashboards', 'index']) }}" class="logo-light">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
            <img src="/images/logo-light.png" class="logo-lg" alt="logo light">
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">


            {!! \App\Traits\SideBarTrait::sidebarButtons() !!}

            @if(request()->has('dev') || session('dev_mode'))
                <!-- --- REFERENCE MENU --- -->
                <li class="menu-title mt-4 text-primary text-center"></li>
                @include('layouts.partials.original-sidebar-items')
            @endif
        </ul>
    </div>
</div>