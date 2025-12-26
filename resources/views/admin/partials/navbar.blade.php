<header class="bg-white h-14 sm:h-16 border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30">
    <!-- Left side: Menu button + Breadcrumb -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Hamburger Menu Button (Mobile) -->
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors"
            aria-label="Toggle menu">
            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm text-slate-500">
            <span class="hidden sm:inline">Dashboard</span>
            <span class="hidden sm:inline">/</span>
            <span class="text-slate-900 font-medium truncate max-w-[150px] sm:max-w-none">@yield('page-title', 'หน้าแรก')</span>
        </div>
    </div>
    
    <!-- Right side: User info + Logout -->
    <div class="flex items-center gap-2 sm:gap-4">
        <span class="text-slate-700 text-xs sm:text-sm hidden sm:inline">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit"
                class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg transition duration-200 text-xs sm:text-sm whitespace-nowrap"
            >
                <span class="hidden sm:inline">ออกจากระบบ</span>
                <span class="sm:hidden">ออก</span>
            </button>
        </form>
    </div>
</header>

