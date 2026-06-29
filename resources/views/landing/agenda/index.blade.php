@extends('layouts.public')
 
@section('title', 'Agenda Kegiatan - SIPADI Bukittinggi')
 
@section('content')
<div x-data="agendaController()" class="mx-auto max-w-7xl px-6 py-12 lg:px-12 space-y-8">
    
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('landing') }}" class="hover:text-[#04241e] transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="font-medium text-slate-800">Agenda</span>
    </nav>
 
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 border-b border-slate-200/60 pb-8">
        <div class="space-y-3">
            <h1 class="font-serif text-4xl lg:text-5xl font-bold text-[#04241e]">Agenda Kegiatan</h1>
            <p class="text-slate-500 max-w-2xl text-base">
                Ikuti berbagai kegiatan, seminar, dan acara literasi yang diselenggarakan oleh Dinas Perpustakaan dan Kearsipan Kota Bukittinggi.
            </p>
        </div>
        
        {{-- Time Filters --}}
        <div class="flex flex-wrap gap-2">
            <button @click="setTimeFilter('semua')"
                    :class="timeFilter === 'semua' ? 'bg-[#04241e] border-[#04241e] text-white font-bold' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold'"
                    class="rounded-xl px-4 py-2.5 text-xs transition border focus:outline-none">
                Semua
            </button>
            <button @click="setTimeFilter('akan_datang')"
                    :class="timeFilter === 'akan_datang' ? 'bg-[#04241e] border-[#04241e] text-white font-bold' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold'"
                    class="rounded-xl px-4 py-2.5 text-xs transition border focus:outline-none">
                Akan Datang
            </button>
            <button @click="setTimeFilter('berlangsung')"
                    :class="timeFilter === 'berlangsung' ? 'bg-[#04241e] border-[#04241e] text-white font-bold' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold'"
                    class="rounded-xl px-4 py-2.5 text-xs transition border focus:outline-none">
                Berlangsung
            </button>
            <button @click="setTimeFilter('selesai')"
                    :class="timeFilter === 'selesai' ? 'bg-[#04241e] border-[#04241e] text-white font-bold' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold'"
                    class="rounded-xl px-4 py-2.5 text-xs transition border focus:outline-none">
                Selesai
            </button>
        </div>
    </div>
 
    {{-- Search Form --}}
    <div class="flex justify-end">
        <div class="w-full md:w-80 relative">
            <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" x-model="searchQuery"
                   placeholder="Cari agenda..."
                   class="h-11 w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm outline-none transition focus:border-[#04241e] placeholder:text-slate-400">
        </div>
    </div>
 
    {{-- Main Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start relative">
        
        {{-- Left Column: Agenda List --}}
        <div class="space-y-6">
            
            {{-- Results Header / Filter Info --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/50 pb-4">
                <p class="text-sm font-semibold text-slate-500">
                    Menampilkan <span x-text="getFilteredList().length" class="text-slate-800 font-bold"></span> Event & Agenda 
                    (<span x-text="getFilteredList().length > 0 ? '1 - ' + getFilteredList().length : '0'" class="font-bold"></span> dari <span x-text="allEvents.length" class="font-bold"></span>)
                </p>
                <div class="flex items-center gap-2 self-end">
                    <span class="text-xs font-bold text-slate-400">Urutkan:</span>
                    <select x-model="sortBy" class="text-xs font-bold bg-white border border-slate-200 rounded-xl px-3 py-2 outline-none focus:border-[#04241e] cursor-pointer">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                    </select>
                </div>
            </div>
 
            {{-- Calendar Filter Reset Alert --}}
            <div x-show="selectedCalendarDate" class="flex items-center justify-between bg-[#04241e]/5 rounded-2xl p-4 border border-[#04241e]/10 text-xs text-slate-650" style="display: none;">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-calendar-check text-sm text-[#04241e]"></i>
                    <span>Menampilkan agenda khusus untuk tanggal <strong x-text="formatEventDate(selectedCalendarDate)"></strong></span>
                </div>
                <button @click="clearCalendarFilter()" class="font-bold text-[#8c741c] hover:text-[#725e17] hover:underline focus:outline-none">
                    Lihat Semua Tanggal
                </button>
            </div>
 
            {{-- List Wrapper --}}
            <div class="space-y-6">
                <template x-for="event in getFilteredList()" :key="event.id_event">
                    <div class="flex flex-col sm:flex-row gap-6 items-start bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200/60 transition duration-300">
                        {{-- Date block (left) --}}
                        <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-100 rounded-2xl p-4 w-20 flex-shrink-0">
                            <span class="text-3xl font-extrabold text-[#04241e]" x-text="getDayNum(event.tanggal_mulai)"></span>
                            <span class="text-xs font-bold text-slate-400 uppercase mt-1.5" x-text="getMonthNameIndo(event.tanggal_mulai)"></span>
                        </div>
                        {{-- Title & Desc (right) --}}
                        <div class="space-y-2 flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="rounded bg-[#04241e]/5 text-[#04241e] px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider" x-text="event.kategori"></span>
                            </div>
                            <h3 class="font-serif text-lg md:text-xl font-bold text-slate-800 hover:text-[#04241e] transition leading-snug">
                                <a :href="event.url" x-text="event.judul_event" class="focus:outline-none"></a>
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed mt-2" x-text="event.deskripsi"></p>
                            
                            {{-- Info Row --}}
                            <div class="flex flex-wrap items-center gap-y-2 gap-x-4 pt-3 text-[11px] text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-clock text-xs"></i>
                                    <span x-text="event.jam_mulai"></span> WIB
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1.5 min-w-0 truncate">
                                    <i class="fa-solid fa-location-dot text-xs"></i>
                                    <span x-text="event.lokasi" class="truncate"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </template>
 
                {{-- Empty List State --}}
                <div x-show="getFilteredList().length === 0" class="bg-white rounded-[2rem] p-12 border border-slate-100 shadow-sm text-center space-y-6">
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-400 text-3xl">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </span>
                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-slate-700">Tidak ada agenda ditemukan</h3>
                        <p class="text-slate-500 text-sm max-w-sm mx-auto">Coba ubah kata kunci pencarian Anda, atur ulang filter waktu, atau klik tanggal lain di kalender.</p>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Right Column: Calendar Widget & List status --}}
        <div class="space-y-6">
            
            {{-- Calendar Container Card --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm select-none">
                <!-- Month/Year Header -->
                <div class="flex items-center justify-between mb-5">
                    <button @click="prevMonth()" class="text-slate-400 hover:text-slate-800 transition p-1 rounded-lg hover:bg-slate-50">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <span class="font-serif font-bold text-slate-800 text-sm md:text-base" x-text="monthName + ' ' + year"></span>
                    <button @click="nextMonth()" class="text-slate-400 hover:text-slate-800 transition p-1 rounded-lg hover:bg-slate-50">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
 
                <!-- Days of the Week Grid -->
                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 uppercase mb-3">
                    <div class="text-red-500">Min</div>
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div>Sab</div>
                </div>
 
                <!-- Calendar Days Grid -->
                <div class="grid grid-cols-7 gap-y-2 gap-x-1 text-center text-xs font-semibold text-slate-700">
                    <!-- Empty grid spaces for offset -->
                    <template x-for="blank in blankdays">
                        <div class="py-1.5 text-slate-200"></div>
                    </template>
                    
                    <!-- Days -->
                    <template x-for="(day, index) in no_of_days" :key="index">
                        <div class="relative py-1 flex flex-col items-center justify-center">
                            <button @click="selectDate(day)"
                                    :class="{
                                        'bg-[#04241e] text-[#ffdc7c] font-bold rounded-xl': isSelected(day),
                                        'bg-[#ffdc7c]/50 text-[#04241e] font-bold rounded-xl': isToday(day) && !isSelected(day),
                                        'hover:bg-slate-50 rounded-xl': !isSelected(day) && !isToday(day),
                                        'text-slate-800': !isSelected(day)
                                    }"
                                    class="h-8 w-8 flex items-center justify-center transition relative focus:outline-none">
                                <span x-text="day"></span>
                            </button>
                            <!-- Event Dot Indicator -->
                            <div x-show="hasEvent(day)"
                                 :class="{
                                     'bg-[#ffdc7c]': isSelected(day),
                                     'bg-[#04241e]': !isSelected(day)
                                 }"
                                 class="absolute bottom-0.5 h-1 w-1 rounded-full"></div>
                        </div>
                    </template>
                </div>
            </div>
 
            {{-- Selected Date Status Warning --}}
            <div x-show="selectedCalendarDate && getEventsForDate(selectedCalendarDate).length === 0" 
                 class="bg-red-50/40 border border-red-100/50 rounded-[2rem] p-6 text-center space-y-4 shadow-sm"
                 style="display: none;">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500 text-2xl mx-auto shadow-sm">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </span>
                <div class="space-y-1">
                    <h4 class="font-bold text-slate-800 text-sm">Oops, event & agenda tidak ditemukan</h4>
                    <p class="text-xs text-slate-405 leading-relaxed">Coba atur ulang filter pencarian Anda.</p>
                </div>
            </div>
 
        </div>
    </div>
</div>
 
{{-- Alpine.js Script for dynamic list & calendar logic --}}
<script>
    function agendaController() {
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
 
        const todayObj = new Date();
        const todayStr = todayObj.getFullYear() + '-' + String(todayObj.getMonth() + 1).padStart(2, '0') + '-' + String(todayObj.getDate()).padStart(2, '0');
 
        return {
            // Data list
            allEvents: @json($allEvents),
            today: todayStr,
            
            // Filters
            searchQuery: '',
            timeFilter: 'semua',
            sortBy: 'terbaru',
            selectedCalendarDate: '',
 
            // Calendar states
            year: todayObj.getFullYear(),
            month: todayObj.getMonth(), // 0-indexed
            selectedDate: todayObj.getDate(),
            monthName: '',
            no_of_days: [],
            blankdays: [],
 
            init() {
                this.updateCalendar();
                // We default selectedCalendarDate to empty so we show all events by default
                this.selectedCalendarDate = '';
            },
 
            updateCalendar() {
                this.monthName = monthNames[this.month];
                
                // Days in month
                const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                // Day of week of the first day of month
                const firstDayIndex = new Date(this.year, this.month, 1).getDay();
 
                const blankdaysArray = [];
                for (let i = 0; i < firstDayIndex; i++) {
                    blankdaysArray.push(i);
                }
                this.blankdays = blankdaysArray;
 
                const daysArray = [];
                for (let i = 1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }
                this.no_of_days = daysArray;
            },
 
            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.updateCalendar();
            },
 
            nextMonth() {
                if (this.month === 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.updateCalendar();
            },
 
            selectDate(day) {
                this.selectedDate = day;
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                const targetDate = `${this.year}-${m}-${d}`;
 
                // Toggle calendar date filter
                if (this.selectedCalendarDate === targetDate) {
                    this.selectedCalendarDate = '';
                } else {
                    this.selectedCalendarDate = targetDate;
                }
            },
 
            clearCalendarFilter() {
                this.selectedCalendarDate = '';
            },
 
            isSelected(day) {
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                return `${this.year}-${m}-${d}` === this.selectedCalendarDate;
            },
 
            isToday(day) {
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                return `${this.year}-${m}-${d}` === this.today;
            },
 
            hasEvent(day) {
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                const targetStr = `${this.year}-${m}-${d}`;
                return this.allEvents.some(event => event.tanggal_mulai === targetStr);
            },
 
            getEventsForDate(dateStr) {
                if (!dateStr) return [];
                return this.allEvents.filter(event => event.tanggal_mulai === dateStr);
            },
 
            getFilteredList() {
                let list = [...this.allEvents];
 
                // Search match
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    list = list.filter(event => 
                        event.judul_event.toLowerCase().includes(q) || 
                        event.deskripsi.toLowerCase().includes(q)
                    );
                }
 
                // Time filter match
                if (this.timeFilter !== 'semua') {
                    if (this.timeFilter === 'akan_datang') {
                        list = list.filter(event => event.tanggal_mulai > this.today);
                    } else if (this.timeFilter === 'berlangsung') {
                        list = list.filter(event => {
                            const start = event.tanggal_mulai;
                            const end = event.tanggal_selesai || start;
                            return (this.today >= start && this.today <= end);
                        });
                    } else if (this.timeFilter === 'selesai') {
                        list = list.filter(event => {
                            const start = event.tanggal_mulai;
                            const end = event.tanggal_selesai || start;
                            return (end < this.today);
                        });
                    }
                }
 
                // Calendar selection filter
                if (this.selectedCalendarDate) {
                    list = list.filter(event => event.tanggal_mulai === this.selectedCalendarDate);
                }
 
                // Sorting
                if (this.sortBy === 'terbaru') {
                    list.sort((a, b) => b.tanggal_mulai.localeCompare(a.tanggal_mulai));
                } else {
                    list.sort((a, b) => a.tanggal_mulai.localeCompare(b.tanggal_mulai));
                }
 
                return list;
            },
 
            setTimeFilter(filter) {
                this.timeFilter = filter;
            },
 
            // Parsing Helpers
            getDayNum(dateStr) {
                if (!dateStr) return '';
                return parseInt(dateStr.split('-')[2], 10);
            },
 
            getMonthNameIndo(dateStr) {
                if (!dateStr) return '';
                const months = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                const monthIndex = parseInt(dateStr.split('-')[1], 10) - 1;
                return months[monthIndex];
            },
 
            formatEventDate(dateStr) {
                if (!dateStr) return '-';
                const parts = dateStr.split('-');
                const day = parseInt(parts[2], 10);
                const month = this.getMonthNameIndo(dateStr);
                const year = parts[0];
                return `${day} ${month} ${year}`;
            }
        }
    }
</script>
@endsection
