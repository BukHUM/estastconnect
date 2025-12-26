import React, { useState } from 'react';
import { 
  LayoutDashboard, 
  Database, 
  RefreshCw, 
  CheckCircle, 
  AlertCircle, 
  ExternalLink, 
  Search, 
  Filter, 
  MoreVertical,
  Wand2,
  Trash2,
  Eye,
  FileText,
  Clock,
  Settings
} from 'lucide-react';

const App = () => {
  const [isScraping, setIsScraping] = useState(false);
  const [selectedTab, setSelectedTab] = useState('pending');

  // Mock data for properties
  const [properties, setProperties] = useState([
    {
      id: 1,
      title: "Life Ladprao Valley",
      type: "Condo",
      price: "5,490,000",
      status: "pending",
      scrapedAt: "2023-10-25 14:30",
      source: "Prop2Share",
      hasAiDescription: false,
      thumbnail: "https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=200&h=150"
    },
    {
      id: 2,
      title: "Noble State 39",
      type: "Condo",
      price: "12,900,000",
      status: "published",
      scrapedAt: "2023-10-24 09:15",
      source: "Prop2Share",
      hasAiDescription: true,
      thumbnail: "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=200&h=150"
    },
    {
      id: 3,
      title: "Centro Bangna",
      type: "House",
      price: "8,500,000",
      status: "failed",
      scrapedAt: "2023-10-23 18:00",
      source: "Prop2Share",
      hasAiDescription: false,
      thumbnail: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=200&h=150"
    }
  ]);

  const runScraper = () => {
    setIsScraping(true);
    setTimeout(() => setIsScraping(false), 3000);
  };

  return (
    <div className="flex h-screen bg-slate-50 font-sans text-slate-900">
      {/* Sidebar */}
      <aside className="w-64 bg-slate-900 text-white flex flex-col">
        <div className="p-6">
          <h1 className="text-xl font-bold flex items-center gap-2">
            <Database className="text-blue-400" />
            PropSystem v1
          </h1>
        </div>
        <nav className="flex-1 px-4 space-y-1">
          <NavItem icon={<LayoutDashboard size={20} />} label="ภาพรวมระบบ" active />
          <NavItem icon={<Database size={20} />} label="รายการทรัพย์สิน" />
          <NavItem icon={<RefreshCw size={20} />} label="จัดการ Scraper" />
          <NavItem icon={<FileText size={20} />} label="Lead Customers" />
          <NavItem icon={<Settings size={20} />} label="ตั้งค่าระบบ" />
        </nav>
        <div className="p-4 border-t border-slate-800 text-xs text-slate-400">
          Dev Mode: Active (Laravel v10)
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-y-auto">
        <header className="bg-white h-16 border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
          <div className="flex items-center gap-4 text-sm text-slate-500">
            <span>Dashboard</span>
            <span>/</span>
            <span className="text-slate-900 font-medium">จัดการเนื้อหาจากการ Scrap</span>
          </div>
          <div className="flex items-center gap-4">
            <button 
              onClick={runScraper}
              disabled={isScraping}
              className={`flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all ${
                isScraping ? 'bg-slate-100 text-slate-400' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-200'
              }`}
            >
              <RefreshCw size={16} className={isScraping ? 'animate-spin' : ''} />
              {isScraping ? 'กำลังดึงข้อมูล...' : 'รัน Scraper ทันที'}
            </button>
            <div className="w-8 h-8 rounded-full bg-slate-200 border border-slate-300"></div>
          </div>
        </header>

        <div className="p-8 space-y-8">
          {/* Stats Cards */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            <StatCard label="รอตรวจสอบ" value="12" icon={<Clock className="text-amber-500" />} color="bg-amber-50" />
            <StatCard label="เผยแพร่แล้ว" value="145" icon={<CheckCircle className="text-emerald-500" />} color="bg-emerald-50" />
            <StatCard label="Scrap พลาด" value="3" icon={<AlertCircle className="text-red-500" />} color="bg-red-50" />
            <StatCard label="ยอดคลิก Affiliate" value="1.2k" icon={<ExternalLink className="text-blue-500" />} color="bg-blue-50" />
          </div>

          {/* Table Control */}
          <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div className="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div className="flex gap-2">
                <TabButton 
                  label="รอตรวจสอบ (Pending)" 
                  active={selectedTab === 'pending'} 
                  onClick={() => setSelectedTab('pending')} 
                />
                <TabButton 
                  label="ออนไลน์ (Published)" 
                  active={selectedTab === 'published'} 
                  onClick={() => setSelectedTab('published')} 
                />
              </div>
              <div className="flex gap-2">
                <div className="relative">
                  <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                  <input 
                    type="text" 
                    placeholder="ค้นหาโครงการ..." 
                    className="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                  />
                </div>
                <button className="p-2 border border-slate-200 rounded-lg hover:bg-slate-50">
                  <Filter size={18} className="text-slate-600" />
                </button>
              </div>
            </div>

            {/* Property Table */}
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead className="bg-slate-50 text-slate-500 uppercase text-xs font-semibold">
                  <tr>
                    <th className="px-6 py-4">ข้อมูลเบื้องต้น</th>
                    <th className="px-6 py-4">ประเภท/ราคา</th>
                    <th className="px-6 py-4">สถานะ</th>
                    <th className="px-6 py-4">Scraped At</th>
                    <th className="px-6 py-4">AI Rewrite</th>
                    <th className="px-6 py-4 text-right">จัดการ</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {properties.map((prop) => (
                    <tr key={prop.id} className="hover:bg-slate-50/80 transition-colors">
                      <td className="px-6 py-4">
                        <div className="flex items-center gap-4">
                          <img src={prop.thumbnail} alt="" className="w-16 h-12 rounded object-cover bg-slate-100" />
                          <div>
                            <div className="font-semibold text-slate-900">{prop.title}</div>
                            <div className="text-xs text-slate-400">ID: {prop.id} | {prop.source}</div>
                          </div>
                        </div>
                      </td>
                      <td className="px-6 py-4">
                        <div>{prop.type}</div>
                        <div className="font-medium text-blue-600">฿{prop.price}</div>
                      </td>
                      <td className="px-6 py-4">
                        <StatusBadge status={prop.status} />
                      </td>
                      <td className="px-6 py-4 text-slate-500">
                        {prop.scrapedAt}
                      </td>
                      <td className="px-6 py-4">
                        {prop.hasAiDescription ? (
                          <span className="flex items-center gap-1 text-emerald-600 font-medium text-xs bg-emerald-50 px-2 py-1 rounded">
                            <CheckCircle size={12} /> พร้อมใช้
                          </span>
                        ) : (
                          <button className="flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium text-xs">
                            <Wand2 size={12} /> สั่ง AI เขียนให้
                          </button>
                        )}
                      </td>
                      <td className="px-6 py-4 text-right">
                        <div className="flex justify-end gap-2">
                          <button className="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all">
                            <Eye size={18} />
                          </button>
                          <button className="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all">
                            <Wand2 size={18} />
                          </button>
                          <button className="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all">
                            <Trash2 size={18} />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            <div className="p-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
              <span>แสดง 3 จาก 160 รายการ</span>
              <div className="flex gap-2 text-slate-600">
                <button className="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">ก่อนหน้า</button>
                <button className="px-3 py-1 border border-slate-200 rounded bg-slate-900 text-white">1</button>
                <button className="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">2</button>
                <button className="px-3 py-1 border border-slate-200 rounded hover:bg-slate-50">ถัดไป</button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
};

// Sub-components
const NavItem = ({ icon, label, active = false }) => (
  <a href="#" className={`flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors ${active ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800'}`}>
    {icon}
    <span>{label}</span>
  </a>
);

const StatCard = ({ label, value, icon, color }) => (
  <div className="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
    <div>
      <p className="text-sm text-slate-500 mb-1">{label}</p>
      <p className="text-2xl font-bold">{value}</p>
    </div>
    <div className={`p-3 rounded-lg ${color}`}>
      {icon}
    </div>
  </div>
);

const TabButton = ({ label, active, onClick }) => (
  <button 
    onClick={onClick}
    className={`px-4 py-2 rounded-lg text-sm font-medium transition-all ${
      active ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-100'
    }`}
  >
    {label}
  </button>
);

const StatusBadge = ({ status }) => {
  const styles = {
    pending: 'bg-amber-100 text-amber-700',
    published: 'bg-emerald-100 text-emerald-700',
    failed: 'bg-red-100 text-red-700'
  };
  const labels = {
    pending: 'รอตรวจ',
    published: 'ออนไลน์',
    failed: 'ผิดพลาด'
  };
  return (
    <span className={`px-2.5 py-0.5 rounded-full text-xs font-semibold ${styles[status]}`}>
      {labels[status]}
    </span>
  );
};

export default App;