<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - PropFinder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    <div class="flex h-screen overflow-hidden relative">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-75 z-40 lg:hidden"
             x-cloak
             style="display: none;"></div>

        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto min-w-0 lg:ml-64 transition-all duration-300">
            <!-- Navigation Bar -->
            @include('admin.partials.navbar')

            <!-- Page Content -->
            <div class="p-4 sm:p-6 lg:p-8 max-w-full">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700 px-4 py-3 rounded-r-lg shadow-sm animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg shadow-sm animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
    
    <script>
        document.addEventListener('alpine:init', () => {
            // Get the body element with x-data
            const body = document.querySelector('body[x-data*="sidebarOpen"]');
            if (!body) return;
            
            // Wait for Alpine to initialize
            Alpine.nextTick(() => {
                const data = Alpine.$data(body);
                if (!data) return;
                
                // Set initial state
                if (window.innerWidth >= 1024) {
                    data.sidebarOpen = true;
                }
                
                // Handle resize
                function handleResize() {
                    if (window.innerWidth >= 1024) {
                        data.sidebarOpen = true;
                    } else {
                        data.sidebarOpen = false;
                    }
                }
                
                window.addEventListener('resize', handleResize);
                
                // Handle body overflow
                Alpine.effect(() => {
                    if (data.sidebarOpen) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
            });
        });
    </script>
</body>
</html>

