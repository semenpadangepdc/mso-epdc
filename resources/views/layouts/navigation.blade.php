<style>
    /* ============================================================================
    NAVIGATION - CUSTOM STYLING
    Using MSO color palette for consistency
    ============================================================================ */
    
    :root {
        --primary-red: #DC2626;
        --dark-red: #991B1B;
        --light-red: #FEE2E2;
    }

    .nav-link-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-bottom: 2px solid transparent;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6B7280;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .nav-link-custom:hover {
        color: var(--dark-red);
        border-bottom-color: var(--light-red);
    }

    .nav-link-custom.active {
        color: var(--primary-red);
        border-bottom-color: var(--primary-red);
        font-weight: 600;
    }

    .nav-link-custom i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .responsive-nav-link-custom {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border-left: 4px solid transparent;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6B7280;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .responsive-nav-link-custom:hover {
        background-color: var(--light-red);
        color: var(--dark-red);
        border-left-color: var(--primary-red);
    }

    .responsive-nav-link-custom.active {
        background-color: var(--light-red);
        color: var(--primary-red);
        border-left-color: var(--primary-red);
        font-weight: 600;
    }

    .responsive-nav-link-custom i {
        margin-right: 0.75rem;
        font-size: 1.125rem;
    }

    /* ============================================================
    DROPDOWN MENU
    ============================================================ */

    .nav-dropdown-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .nav-dropdown-trigger {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-bottom: 2px solid transparent;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6B7280;
        transition: all 0.3s ease;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
        cursor: pointer;
        height: 100%;
        white-space: nowrap;
    }

    .nav-dropdown-trigger:hover,
    .nav-dropdown-trigger.active {
        color: var(--dark-red);
        border-bottom-color: var(--primary-red);
    }

    .nav-dropdown-trigger.active {
        color: var(--primary-red);
        font-weight: 600;
    }

    .nav-dropdown-trigger i { font-size: 1rem; }

    .nav-dropdown-trigger .chevron {
        transition: transform 0.2s ease;
        font-size: 0.75rem;
        opacity: 0.6;
    }

    .nav-dropdown-wrapper.open .chevron {
        transform: rotate(180deg);
    }

    .nav-dropdown-panel {
        display: none;
        position: absolute;
        top: calc(100% + 2px);
        left: 0;
        min-width: 220px;
        background: #ffffff;
        border: 1px solid #E5E7EB;
        border-top: 3px solid var(--primary-red);
        border-radius: 0 0 10px 10px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        z-index: 100;
        overflow: hidden;
    }

    .nav-dropdown-wrapper.open .nav-dropdown-panel {
        display: block;
    }

    .nav-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.7rem 1.1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .nav-dropdown-item:hover {
        background: var(--light-red);
        color: var(--dark-red);
        border-left-color: var(--primary-red);
    }

    .nav-dropdown-item.active {
        background: var(--light-red);
        color: var(--primary-red);
        border-left-color: var(--primary-red);
        font-weight: 600;
    }

    .nav-dropdown-item i {
        font-size: 0.9rem;
        width: 1rem;
        text-align: center;
        opacity: 0.8;
    }

    .nav-dropdown-divider {
        border: none;
        border-top: 1px solid #F3F4F6;
        margin: 0.25rem 0;
    }

    .nav-dropdown-group-label {
        padding: 0.5rem 1.1rem 0.25rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9CA3AF;
    }

    /* ============================================================
    RESPONSIVE: sub-menu (mobile accordion)
    ============================================================ */
    .responsive-sub-menu {
        display: none;
        background: #FAFAFA;
        border-left: 4px solid var(--light-red);
    }

    .responsive-sub-menu.open {
        display: block;
    }

    .responsive-sub-menu-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-left: 4px solid transparent;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6B7280;
        transition: all 0.3s ease;
        width: 100%;
        background: none;
        border-top: none;
        border-right: none;
        border-bottom: none;
        cursor: pointer;
        text-align: left;
    }

    .responsive-sub-menu-trigger:hover,
    .responsive-sub-menu-trigger.active {
        background-color: var(--light-red);
        color: var(--dark-red);
        border-left-color: var(--primary-red);
    }

    .responsive-sub-menu-trigger.active { font-weight: 600; color: var(--primary-red); }

    .responsive-sub-menu-trigger .chevron {
        transition: transform 0.2s ease;
        font-size: 0.75rem;
    }

    .responsive-sub-menu-trigger.open .chevron { transform: rotate(180deg); }

    .responsive-sub-item {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.65rem 1rem 0.65rem 2rem;
        font-size: 0.838rem;
        font-weight: 500;
        color: #6B7280;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }

    .responsive-sub-item:hover {
        background: var(--light-red);
        color: var(--dark-red);
        border-left-color: var(--primary-red);
    }

    .responsive-sub-item.active {
        background: var(--light-red);
        color: var(--primary-red);
        border-left-color: var(--primary-red);
        font-weight: 600;
    }

    .responsive-sub-item i { font-size: 0.875rem; width: 1rem; text-align: center; }

    /* Badge "New" */
    .nav-badge-new {
        display: inline-flex;
        align-items: center;
        padding: 0.1rem 0.4rem;
        background: var(--primary-red);
        color: #fff;
        font-size: 0.6rem;
        font-weight: 700;
        border-radius: 999px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        line-height: 1.4;
        margin-left: auto;
    }

    /* Badge "Admin" — ungu untuk membedakan dari badge merah */
    .nav-badge-admin {
        display: inline-flex;
        align-items: center;
        padding: 0.1rem 0.4rem;
        background: #7C3AED;
        color: #fff;
        font-size: 0.6rem;
        font-weight: 700;
        border-radius: 999px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        line-height: 1.4;
        margin-left: 0.35rem;
    }
</style>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        {{ __('Dashboard') }}
                    </a>

                    <!-- MSO -->
                    <a href="{{ route('mso.index') }}"
                       class="nav-link-custom {{ request()->routeIs('mso.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        MSO
                    </a>

                    <!-- Activity Log -->
                    <a href="{{ url('/activity-log') }}"
                       class="nav-link-custom {{ request()->is('activity-log*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        Activity Log
                    </a>

                    <!-- Production Calendar -->
                    <a href="{{ url('/production-calendar') }}"
                       class="nav-link-custom {{ request()->is('production-calendar*') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>
                        Prod. Calendar
                    </a>

                    <!-- ==========================================
                         MONITORING (Dropdown)
                         ========================================== -->
                    <div class="nav-dropdown-wrapper {{ request()->is('monitoring-material*') || request()->routeIs('nomenclatures.*') ? 'active' : '' }}"
                         id="monitoringDropdown">
                        <button type="button"
                                class="nav-dropdown-trigger {{ request()->is('monitoring-material*') || request()->routeIs('nomenclatures.*') ? 'active' : '' }}"
                                onclick="toggleDropdown('monitoringDropdown')">
                            <i class="fas fa-chart-bar"></i>
                            Monitoring
                            <i class="fas fa-chevron-down chevron"></i>
                        </button>

                        <div class="nav-dropdown-panel">

                            <div class="nav-dropdown-group-label">Material &amp; Jasa</div>

                            <a href="{{ route('monitoring.index') }}"
                               class="nav-dropdown-item {{ request()->routeIs('monitoring.index') ? 'active' : '' }}">
                                <i class="fas fa-list-alt"></i>
                                Daftar Monitoring
                            </a>

                            <a href="{{ route('monitoring.resume') }}"
                               class="nav-dropdown-item {{ request()->routeIs('monitoring.resume') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie"></i>
                                Resume &amp; Lead Time
                                <span class="nav-badge-new">New</span>
                            </a>

                            <hr class="nav-dropdown-divider">

                            <div class="nav-dropdown-group-label">Lainnya</div>

                            <a href="{{ route('nomenclatures.index') }}"
                               class="nav-dropdown-item {{ request()->routeIs('nomenclatures.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                Nomenclatures
                            </a>

                        </div>
                    </div>
                    <!-- END MONITORING DROPDOWN -->

                    <!-- ==========================================
                         ADMIN (Dropdown) — hanya tampil untuk role Admin
                         Material Master + Manajemen User digabung
                         ========================================== -->
                    @role('Admin')
                    <div class="nav-dropdown-wrapper {{ request()->routeIs('material-master.*') || request()->routeIs('admin.users.*') ? 'active' : '' }}"
                         id="adminDropdown">
                        <button type="button"
                                class="nav-dropdown-trigger {{ request()->routeIs('material-master.*') || request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                onclick="toggleDropdown('adminDropdown')">
                            <i class="fas fa-shield-alt"></i>
                            Admin
                            <span class="nav-badge-admin">Admin</span>
                            <i class="fas fa-chevron-down chevron"></i>
                        </button>

                        <div class="nav-dropdown-panel">

                            <div class="nav-dropdown-group-label">Master Data</div>

                            <a href="{{ route('material-master.index') }}"
                               class="nav-dropdown-item {{ request()->routeIs('material-master.*') ? 'active' : '' }}">
                                <i class="fas fa-database"></i>
                                Material Master
                            </a>

                            <hr class="nav-dropdown-divider">

                            <div class="nav-dropdown-group-label">Pengguna</div>

                            <a href="{{ route('admin.users.index') }}"
                               class="nav-dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users-cog"></i>
                                Manajemen User
                            </a>

                        </div>
                    </div>
                    @endrole
                    <!-- END ADMIN DROPDOWN -->

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="responsive-nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                {{ __('Dashboard') }}
            </a>

            <!-- MSO -->
            <a href="{{ route('mso.index') }}"
               class="responsive-nav-link-custom {{ request()->routeIs('mso.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                MSO
            </a>

            <!-- Activity Log -->
            <a href="{{ url('/activity-log') }}"
               class="responsive-nav-link-custom {{ request()->is('activity-log*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                Activity Log
            </a>

            <!-- Production Calendar -->
            <a href="{{ url('/production-calendar') }}"
               class="responsive-nav-link-custom {{ request()->is('production-calendar*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                Prod. Calendar
            </a>

            <!-- ==========================================
                 MONITORING (Mobile Accordion)
                 ========================================== -->
            <div id="mobileMonitoringWrapper">
                <button type="button"
                        id="mobileMonitoringTrigger"
                        class="responsive-sub-menu-trigger {{ request()->is('monitoring-material*') || request()->routeIs('nomenclatures.*') ? 'active' : '' }}"
                        onclick="toggleMobileSubMenu('mobileMonitoringMenu', 'mobileMonitoringTrigger')">
                    <span style="display:flex; align-items:center; gap:0.75rem;">
                        <i class="fas fa-chart-bar" style="font-size:1.125rem;"></i>
                        Monitoring
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>

                <div id="mobileMonitoringMenu"
                     class="responsive-sub-menu {{ request()->is('monitoring-material*') || request()->routeIs('nomenclatures.*') ? 'open' : '' }}">

                    <a href="{{ route('monitoring.index') }}"
                       class="responsive-sub-item {{ request()->routeIs('monitoring.index') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i>
                        Daftar Monitoring
                    </a>

                    <a href="{{ route('monitoring.resume') }}"
                       class="responsive-sub-item {{ request()->routeIs('monitoring.resume') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        Resume &amp; Lead Time
                    </a>

                    <a href="{{ route('nomenclatures.index') }}"
                       class="responsive-sub-item {{ request()->routeIs('nomenclatures.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        Nomenclatures
                    </a>

                </div>
            </div>
            <!-- END MONITORING MOBILE -->

            <!-- ==========================================
                 ADMIN (Mobile Accordion) — hanya untuk role Admin
                 Material Master + Manajemen User digabung
                 ========================================== -->
            @role('Admin')
            <div id="mobileAdminWrapper">
                <button type="button"
                        id="mobileAdminTrigger"
                        class="responsive-sub-menu-trigger {{ request()->routeIs('material-master.*') || request()->routeIs('admin.users.*') ? 'active' : '' }}"
                        onclick="toggleMobileSubMenu('mobileAdminMenu', 'mobileAdminTrigger')">
                    <span style="display:flex; align-items:center; gap:0.75rem;">
                        <i class="fas fa-shield-alt" style="font-size:1.125rem;"></i>
                        Admin
                        <span class="nav-badge-admin">Admin</span>
                    </span>
                    <i class="fas fa-chevron-down chevron"></i>
                </button>

                <div id="mobileAdminMenu"
                     class="responsive-sub-menu {{ request()->routeIs('material-master.*') || request()->routeIs('admin.users.*') ? 'open' : '' }}">

                    <a href="{{ route('material-master.index') }}"
                       class="responsive-sub-item {{ request()->routeIs('material-master.*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        Material Master
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="responsive-sub-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        Manajemen User
                    </a>

                </div>
            </div>
            @endrole
            <!-- END ADMIN MOBILE -->

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    // ============================================================
    // DROPDOWN TOGGLE (desktop)
    // Menutup dropdown lain saat satu dibuka
    // ============================================================
    function toggleDropdown(wrapperId) {
        document.querySelectorAll('.nav-dropdown-wrapper').forEach(function (wrapper) {
            if (wrapper.id !== wrapperId) {
                wrapper.classList.remove('open');
            }
        });
        document.getElementById(wrapperId).classList.toggle('open');
    }

    // Tutup semua dropdown jika klik di luar
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.nav-dropdown-wrapper').forEach(function (wrapper) {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove('open');
            }
        });
    });

    // ============================================================
    // MOBILE SUB-MENU TOGGLE (accordion)
    // ============================================================
    function toggleMobileSubMenu(menuId, triggerId) {
        const menu    = document.getElementById(menuId);
        const trigger = document.getElementById(triggerId);
        menu.classList.toggle('open');
        trigger.classList.toggle('open');
    }
</script>