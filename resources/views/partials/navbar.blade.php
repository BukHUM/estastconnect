<nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-blue-600 font-bold text-xl sm:text-2xl">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>PropFinder</span>
            </a>
            
            <div class="hidden md:flex gap-6 lg:gap-8 text-sm font-medium text-slate-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">
                    หน้าแรก
                </a>
                <a href="{{ route('home', ['type' => 'condo']) }}" class="hover:text-blue-600 transition-colors">
                    คอนโด
                </a>
                <a href="{{ route('home', ['type' => 'house']) }}" class="hover:text-blue-600 transition-colors">
                    บ้าน
                </a>
                <a href="#" class="hover:text-blue-600 transition-colors">
                    บทความ
                </a>
                <a href="#" class="hover:text-blue-600 transition-colors">
                    ติดต่อเรา
                </a>
            </div>
            
            <a href="#" class="bg-blue-600 text-white px-4 sm:px-5 py-2 rounded-full text-xs sm:text-sm font-semibold hover:bg-blue-700 transition-all shadow-md">
                <span class="hidden sm:inline">ฝากขายทรัพย์</span>
                <span class="sm:hidden">ฝากขาย</span>
            </a>
        </div>
    </div>
</nav>

