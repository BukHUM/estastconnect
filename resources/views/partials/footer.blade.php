<footer class="bg-slate-900 text-slate-400 py-12 sm:py-16 px-4 border-t border-slate-800">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 sm:gap-12">
        <div class="col-span-1 md:col-span-2">
            <div class="flex items-center gap-2 text-white font-bold text-xl sm:text-2xl mb-4 sm:mb-6">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>PropFinder</span>
            </div>
            <p class="max-w-md mb-4 sm:mb-8 leading-relaxed text-sm sm:text-base">
                เราคือตัวกลางในการรวบรวมโครงการอสังหาริมทรัพย์คุณภาพดีทั่วประเทศไทย 
                เพื่อให้คุณได้พบกับที่อยู่อาศัยที่ตรงใจในราคาที่ดีที่สุด
            </p>
        </div>
        <div>
            <h4 class="text-white font-bold mb-4 sm:mb-6 text-sm sm:text-base">เมนู</h4>
            <ul class="space-y-3 sm:space-y-4 text-xs sm:text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-blue-400 transition-colors">โครงการทั้งหมด</a></li>
                <li><a href="{{ route('home', ['type' => 'condo']) }}" class="hover:text-blue-400 transition-colors">รีวิวคอนโด</a></li>
                <li><a href="#" class="hover:text-blue-400 transition-colors">วิธีการซื้อขาย</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-4 sm:mb-6 text-sm sm:text-base">ติดต่อ</h4>
            <ul class="space-y-3 sm:space-y-4 text-xs sm:text-sm">
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    info@propfinder.com
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    02-xxx-xxxx
                </li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto border-t border-slate-800 mt-8 sm:mt-16 pt-6 sm:pt-8 text-center text-xs sm:text-sm">
        © {{ date('Y') }} PropFinder Platform. All rights reserved. (Affiliate Partner of Prop2Share)
    </div>
</footer>

