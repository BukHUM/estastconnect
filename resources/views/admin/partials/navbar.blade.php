<header class="bg-white h-14 sm:h-16 border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 shadow-sm">
    <!-- Left side: Menu button + Breadcrumb -->
    <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
        <!-- Hamburger Menu Button (Mobile) -->
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors flex-shrink-0"
            aria-label="Toggle menu">
            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm text-slate-500 min-w-0">
            <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline hover:text-slate-700 transition-colors">Dashboard</a>
            <span class="hidden sm:inline text-slate-300">/</span>
            <span class="text-slate-900 font-semibold truncate">@yield('page-title', 'หน้าแรก')</span>
        </div>
    </div>
    
    <!-- Right side: User info + Logout -->
    <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
        <div class="hidden sm:flex items-center gap-2 text-slate-700 text-xs sm:text-sm">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="truncate max-w-[120px]">{{ Auth::user()->name }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button 
                type="submit"
                class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg transition duration-200 text-xs sm:text-sm whitespace-nowrap shadow-sm hover:shadow-md"
            >
                <span class="hidden sm:inline">ออกจากระบบ</span>
                <span class="sm:hidden">ออก</span>
            </button>
        </form>
    </div>
</header>

