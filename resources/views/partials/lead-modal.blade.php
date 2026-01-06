<div id="lead-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4" x-data="{ submitting: false }" x-cloak>
    <div id="lead-modal-overlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm z-[9998]" onclick="closeLeadModal()"></div>
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-lg relative z-[10000] overflow-hidden max-h-[90vh] overflow-y-auto mx-auto">
        <button 
            onclick="closeLeadModal()"
            class="absolute top-4 right-4 p-2 hover:bg-slate-100 rounded-full transition-colors z-10"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="p-6 sm:p-8">
            <div class="text-center mb-6 sm:mb-8 pt-2 sm:pt-4">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 text-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">รับข้อเสนอพิเศษ</h3>
                <p class="text-sm sm:text-base text-slate-500">
                    กรอกข้อมูลเพื่อรับสิทธิพิเศษและดูรายละเอียดโครงการ 
                    <span id="lead-property-title" class="font-bold text-slate-900"></span>
                </p>
            </div>

            <form id="lead-form" 
                  @submit.prevent="
                      submitting = true;
                      fetch('{{ route('leads.store') }}', {
                          method: 'POST',
                          headers: {
                              'Content-Type': 'application/json',
                              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                          },
                          body: JSON.stringify({
                              property_id: document.getElementById('lead-property-id').value,
                              name: document.getElementById('lead-name').value,
                              phone: document.getElementById('lead-phone').value,
                              email: document.getElementById('lead-email').value
                          })
                      })
                      .then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              // Track Google Analytics
                              if (typeof gtag !== 'undefined') {
                                  gtag('event', 'lead_submission', {
                                      'event_category': 'Lead',
                                      'event_label': document.getElementById('lead-property-title').textContent
                                  });
                              }
                              // Redirect to affiliate link
                              window.location.href = data.redirect_url;
                          } else {
                              alert('เกิดข้อผิดพลาด: ' + (data.message || 'กรุณาลองใหม่อีกครั้ง'));
                              submitting = false;
                          }
                      })
                      .catch(error => {
                          console.error('Error:', error);
                          alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                          submitting = false;
                      });
                  "
                  class="space-y-4">
                <input type="hidden" id="lead-property-id" name="property_id">
                
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <input 
                        id="lead-name"
                        required
                        type="text" 
                        name="name"
                        placeholder="ชื่อ-นามสกุล"
                        class="w-full pl-11 sm:pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm sm:text-base"
                    />
                </div>
                
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <input 
                        id="lead-phone"
                        required
                        type="tel" 
                        name="phone"
                        placeholder="เบอร์โทรศัพท์ (10 หลัก)"
                        pattern="[0-9]{9,10}"
                        maxlength="10"
                        class="w-full pl-11 sm:pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm sm:text-base"
                    />
                </div>
                
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input 
                        id="lead-email"
                        type="email" 
                        name="email"
                        placeholder="อีเมล (ไม่บังคับ)"
                        class="w-full pl-11 sm:pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm sm:text-base"
                    />
                </div>
                
                <button 
                    type="submit"
                    :disabled="submitting"
                    class="w-full bg-blue-600 text-white py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all mt-4 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="!submitting">รับข้อมูลโครงการ</span>
                    <span x-show="submitting">กำลังส่งข้อมูล...</span>
                    <svg x-show="!submitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <svg x-show="submitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                
                <p class="text-[10px] sm:text-xs text-center text-slate-400 px-4 sm:px-8">
                    โดยการคลิกปุ่ม "รับข้อมูลโครงการ" คุณตกลงยอมรับเงื่อนไขการใช้บริการและนโยบายความเป็นส่วนตัวของเรา
                </p>
            </form>
        </div>
    </div>
</div>

