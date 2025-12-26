import React, { useState, useMemo } from 'react';
import { 
  Search, 
  MapPin, 
  Home, 
  ChevronRight, 
  Filter, 
  Star, 
  X, 
  Phone, 
  User, 
  Mail,
  ArrowRight
} from 'lucide-react';

const App = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('ทั้งหมด');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedProperty, setSelectedProperty] = useState(null);

  // Mock data for properties
  const properties = [
    {
      id: 1,
      title: "Life Ladprao Valley",
      location: "ห้าแยกลาดพร้าว, กรุงเทพฯ",
      type: "คอนโด",
      price: "5,490,000",
      image: "https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&h=500",
      tag: "แนะนำ",
      affiliateUrl: "https://prop2share.com/l/123"
    },
    {
      id: 2,
      title: "Noble State 39",
      location: "พร้อมพงษ์, กรุงเทพฯ",
      type: "คอนโด",
      price: "12,900,000",
      image: "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&h=500",
      tag: "Luxury",
      affiliateUrl: "https://prop2share.com/l/456"
    },
    {
      id: 3,
      title: "Centro Bangna",
      location: "บางนา, กรุงเทพฯ",
      type: "บ้านเดี่ยว",
      price: "8,500,000",
      image: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&h=500",
      tag: "ครอบครัว",
      affiliateUrl: "https://prop2share.com/l/789"
    },
    {
      id: 4,
      title: "The Line Sukhumvit 101",
      location: "ปุณณวิถี, กรุงเทพฯ",
      type: "คอนโด",
      price: "4,200,000",
      image: "https://images.unsplash.com/photo-1460317442991-0ec239397148?auto=format&fit=crop&w=800&h=500",
      tag: "ใกล้รถไฟฟ้า",
      affiliateUrl: "https://prop2share.com/l/101"
    }
  ];

  const filteredProperties = useMemo(() => {
    return properties.filter(p => 
      (selectedCategory === 'ทั้งหมด' || p.type === selectedCategory) &&
      p.title.toLowerCase().includes(searchTerm.toLowerCase())
    );
  }, [searchTerm, selectedCategory]);

  const handleOpenModal = (property) => {
    setSelectedProperty(property);
    setIsModalOpen(true);
  };

  const handleLeadSubmit = (e) => {
    e.preventDefault();
    // จำลองการบันทึกข้อมูลลูกค้าลง Database ของเราก่อน
    console.log("บันทึกข้อมูลลูกค้าแล้ว เตรียมส่งไปหน้า Affiliate");
    window.location.href = selectedProperty.affiliateUrl;
  };

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-900">
      {/* Navbar */}
      <nav className="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div className="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
          <div className="flex items-center gap-2 text-blue-600 font-bold text-2xl">
            <Home className="fill-current" />
            <span>PropFinder</span>
          </div>
          <div className="hidden md:flex gap-8 text-sm font-medium text-slate-600">
            <a href="#" className="hover:text-blue-600 transition-colors">หน้าแรก</a>
            <a href="#" className="hover:text-blue-600 transition-colors">ค้นหาโครงการ</a>
            <a href="#" className="hover:text-blue-600 transition-colors">บทความ</a>
            <a href="#" className="hover:text-blue-600 transition-colors">ติดต่อเรา</a>
          </div>
          <button className="bg-blue-600 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-blue-700 transition-all shadow-md">
            ฝากขายทรัพย์
          </button>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="bg-blue-900 text-white py-20 px-4 relative overflow-hidden">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-400 via-transparent to-transparent"></div>
        </div>
        <div className="max-w-4xl mx-auto text-center relative z-10">
          <h1 className="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
            ค้นหาโครงการอสังหาฯ <br />
            ที่ตอบโจทย์ไลฟ์สไตล์คุณ
          </h1>
          <p className="text-blue-100 text-lg mb-10 opacity-90">
            รวบรวมบ้านและคอนโดทำเลดี พร้อมข้อเสนอสุดพิเศษที่คุณไม่ควรพลาด
          </p>
          
          {/* Search Box */}
          <div className="bg-white p-2 rounded-2xl shadow-2xl flex flex-col md:flex-row gap-2 max-w-2xl mx-auto">
            <div className="flex-1 relative">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={20} />
              <input 
                type="text" 
                placeholder="ค้นหาชื่อโครงการ หรือ ทำเล..."
                className="w-full pl-12 pr-4 py-3 text-slate-800 focus:outline-none"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
            <button className="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
              ค้นหา <ArrowRight size={18} />
            </button>
          </div>
        </div>
      </section>

      {/* Main Content */}
      <main className="max-w-7xl mx-auto px-4 py-16">
        {/* Category Filters */}
        <div className="flex items-center gap-4 mb-10 overflow-x-auto pb-4 scrollbar-hide">
          <div className="p-3 bg-white border border-slate-200 rounded-xl text-slate-400">
            <Filter size={20} />
          </div>
          {['ทั้งหมด', 'คอนโด', 'บ้านเดี่ยว', 'ที่ดิน'].map((cat) => (
            <button 
              key={cat}
              onClick={() => setSelectedCategory(cat)}
              className={`px-6 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all ${
                selectedCategory === cat 
                ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' 
                : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-400'
              }`}
            >
              {cat}
            </button>
          ))}
        </div>

        {/* Property Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {filteredProperties.map((prop) => (
            <div key={prop.id} className="group bg-white rounded-3xl overflow-hidden border border-slate-200 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
              <div className="relative h-64 overflow-hidden">
                <img 
                  src={prop.image} 
                  alt={prop.title} 
                  className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                />
                <div className="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-blue-600 flex items-center gap-1 shadow-sm">
                  <Star size={14} className="fill-blue-600" /> {prop.tag}
                </div>
              </div>
              <div className="p-6">
                <div className="flex justify-between items-start mb-2">
                  <h3 className="text-xl font-bold text-slate-900 leading-tight">{prop.title}</h3>
                  <div className="text-xs font-bold text-slate-400 uppercase tracking-wider">{prop.type}</div>
                </div>
                <div className="flex items-center gap-1 text-slate-500 text-sm mb-4">
                  <MapPin size={16} />
                  <span>{prop.location}</span>
                </div>
                <div className="flex items-end justify-between border-t border-slate-100 pt-4">
                  <div>
                    <span className="text-xs text-slate-400 block mb-1">ราคาเริ่มต้น</span>
                    <span className="text-2xl font-black text-blue-600">฿{prop.price}</span>
                  </div>
                  <button 
                    onClick={() => handleOpenModal(prop)}
                    className="bg-slate-900 text-white p-3 rounded-2xl hover:bg-blue-600 transition-colors shadow-lg"
                  >
                    <ChevronRight size={20} />
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </main>

      {/* Footer */}
      <footer className="bg-slate-900 text-slate-400 py-16 px-4 border-t border-slate-800">
        <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
          <div className="col-span-1 md:col-span-2">
            <div className="flex items-center gap-2 text-white font-bold text-2xl mb-6">
              <Home className="fill-blue-600 text-blue-600" />
              <span>PropFinder</span>
            </div>
            <p className="max-w-md mb-8 leading-relaxed">
              เราคือตัวกลางในการรวบรวมโครงการอสังหาริมทรัพย์คุณภาพดีทั่วประเทศไทย 
              เพื่อให้คุณได้พบกับที่อยู่อาศัยที่ตรงใจในราคาที่ดีที่สุด
            </p>
          </div>
          <div>
            <h4 className="text-white font-bold mb-6">เมนู</h4>
            <ul className="space-y-4 text-sm">
              <li><a href="#" className="hover:text-blue-400 transition-colors">โครงการทั้งหมด</a></li>
              <li><a href="#" className="hover:text-blue-400 transition-colors">รีวิวคอนโด</a></li>
              <li><a href="#" className="hover:text-blue-400 transition-colors">วิธีการซื้อขาย</a></li>
            </ul>
          </div>
          <div>
            <h4 className="text-white font-bold mb-6">ติดต่อ</h4>
            <ul className="space-y-4 text-sm">
              <li className="flex items-center gap-2"><Mail size={16} /> info@propfinder.com</li>
              <li className="flex items-center gap-2"><Phone size={16} /> 02-xxx-xxxx</li>
            </ul>
          </div>
        </div>
        <div className="max-w-7xl mx-auto border-t border-slate-800 mt-16 pt-8 text-center text-xs">
          © 2025 PropFinder Platform. All rights reserved. (Affiliate Partner of Prop2Share)
        </div>
      </footer>

      {/* Lead Capture Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div className="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onClick={() => setIsModalOpen(false)}></div>
          <div className="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden animate-in fade-in zoom-in duration-200">
            <button 
              onClick={() => setIsModalOpen(false)}
              className="absolute top-4 right-4 p-2 hover:bg-slate-100 rounded-full transition-colors"
            >
              <X size={20} />
            </button>
            
            <div className="p-8">
              <div className="text-center mb-8 pt-4">
                <div className="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <Star size={32} className="fill-current" />
                </div>
                <h3 className="text-2xl font-bold text-slate-900 mb-2">รับข้อเสนอพิเศษ</h3>
                <p className="text-slate-500">กรอกข้อมูลเพื่อรับสิทธิพิเศษและดูรายละเอียดโครงการ <span className="font-bold text-slate-900">{selectedProperty?.title}</span></p>
              </div>

              <form onSubmit={handleLeadSubmit} className="space-y-4">
                <div className="relative">
                  <User className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                  <input 
                    required
                    type="text" 
                    placeholder="ชื่อ-นามสกุล"
                    className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                  />
                </div>
                <div className="relative">
                  <Phone className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                  <input 
                    required
                    type="tel" 
                    placeholder="เบอร์โทรศัพท์"
                    className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                  />
                </div>
                <div className="relative">
                  <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                  <input 
                    type="email" 
                    placeholder="อีเมล (ไม่บังคับ)"
                    className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                  />
                </div>
                
                <button 
                  type="submit"
                  className="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all mt-4 flex items-center justify-center gap-2"
                >
                  รับข้อมูลโครงการ <ChevronRight size={20} />
                </button>
                <p className="text-[10px] text-center text-slate-400 px-8">
                  โดยการคลิกปุ่ม "รับข้อมูลโครงการ" คุณตกลงยอมรับเงื่อนไขการใช้บริการและนโยบายความเป็นส่วนตัวของเรา
                </p>
              </form>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default App;