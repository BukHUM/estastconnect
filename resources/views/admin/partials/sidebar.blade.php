<aside 
    x-show="sidebarOpen || window.innerWidth >= 1024"
    x-cloak
    @click.away="if (window.innerWidth < 1024) sidebarOpen = false"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-x-full lg:translate-x-0"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full lg:translate-x-0"
    class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 shadow-xl lg:shadow-none">
    <!-- Sidebar Header -->
    <div class="p-4 sm:p-6 flex items-center justify-between border-b border-slate-800 lg:border-b-0">
        <h1 class="text-lg sm:text-xl font-bold flex items-center gap-2">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            <span class="hidden sm:inline">Estate Connect</span>
            <span class="sm:hidden">EC</span>
        </h1>
        <!-- Close button for mobile -->
        <button 
            @click="sidebarOpen = false"
            class="lg:hidden p-2 rounded-lg hover:bg-slate-800 transition-colors"
            aria-label="Close menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-2 sm:px-4 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" 
           @click="if (window.innerWidth < 1024) sidebarOpen = false"
           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="truncate">ภาพรวมระบบ</span>
        </a>

        <a href="{{ route('admin.properties.index') }}" 
           @click="if (window.innerWidth < 1024) sidebarOpen = false"
           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.properties.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            <span class="truncate">รายการทรัพย์สิน</span>
        </a>

        <a href="{{ route('admin.scraper.index') }}" 
           @click="if (window.innerWidth < 1024) sidebarOpen = false"
           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.scraper.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span class="truncate">จัดการ Scraper</span>
        </a>

        <a href="{{ route('admin.leads.index') }}" 
           @click="if (window.innerWidth < 1024) sidebarOpen = false"
           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.leads.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="truncate">Lead Customers</span>
        </a>

        <a href="{{ route('admin.users.index') }}" 
           @click="if (window.innerWidth < 1024) sidebarOpen = false"
           class="flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="truncate">จัดการผู้ใช้</span>
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-3 sm:p-4 border-t border-slate-800 text-xs text-slate-400 hidden lg:block">
        <div>Dev Mode: Active</div>
        <div>Laravel {{ app()->version() }}</div>
    </div>
</aside>
