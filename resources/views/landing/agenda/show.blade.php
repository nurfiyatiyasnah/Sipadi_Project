@extends('layouts.public')
 
@section('title', $agenda->judul_event . ' - SIPADI Bukittinggi')
 
@section('content')
<div class="mx-auto max-w-7xl px-6 py-12 lg:px-12 space-y-8">
    
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('landing') }}" class="hover:text-[#04241e] transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="{{ route('agenda.index') }}" class="hover:text-[#04241e] transition">Agenda</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="font-medium text-slate-800">Detail</span>
    </nav>
 
    {{-- Main Layout Grid (2 Columns: Detail Content left, Calendar right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start relative">
        
        {{-- Left Column: Main Event Content --}}
        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm space-y-8">
                {{-- Date and Tag --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-slate-500 font-sans">
                        {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->locale('id')->translatedFormat('d F Y') : '-' }}
                    </span>
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                    <span class="rounded-full bg-[#04241e]/5 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#04241e]">
                        {{ $agenda->kategori ?? 'Kegiatan' }}
                    </span>
                </div>
 
                {{-- Title --}}
                <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-bold leading-tight text-[#04241e]">
                    {{ $agenda->judul_event }}
                </h1>
 
                {{-- Event Image (if uploaded) --}}
                @if($agenda->gambar)
                    <div class="rounded-2xl overflow-hidden shadow-sm border border-slate-100 max-h-[400px]">
                        <img src="{{ Storage::url($agenda->gambar) }}" alt="{{ $agenda->judul_event }}" class="w-full h-full object-cover">
                    </div>
                @endif
 
                {{-- Deskripsi/Tentang Acara --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                        <span class="h-1.5 w-6 rounded-full bg-[#04241e]"></span>
                        Tentang Acara
                    </h3>
                    <div class="text-slate-650 text-sm md:text-base leading-relaxed whitespace-pre-line font-light font-sans">
                        {{ $agenda->deskripsi }}
                    </div>
                </div>
 
                {{-- Google Map Embed for Location (Styled like Homepage Map) --}}
                @if($agenda->lokasi)
                    <div class="space-y-4 pt-6 border-t border-slate-100">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                            <span class="h-1.5 w-6 rounded-full bg-[#04241e]"></span>
                            Lokasi Kegiatan
                        </h3>
                        <p class="text-xs font-bold text-slate-650 flex items-center gap-2 font-sans">
                            <i class="fa-solid fa-location-dot text-[#04241e]"></i>
                            <span>{{ $agenda->lokasi }}</span>
                        </p>
                        <div class="relative h-[300px] min-h-[300px] rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-lg group">
                            <iframe src="{{ $embedUrl }}" 
                                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0"></iframe>
                            <!-- Hover Link Button -->
                            <a href="{{ $mapUrl }}" target="_blank" class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm hover:bg-white text-[#061b3a] font-bold text-xs px-3.5 py-2 rounded-xl shadow-md border border-slate-100 flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-map-location-dot"></i>
                                Buka Google Maps
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
 
        {{-- Right Column: Calendar Widget & Details --}}
        <div x-data="calendarController()" class="space-y-6">
            
            {{-- Calendar Container --}}
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
                                        'bg-[#ffdc7c] text-[#04241e] font-bold rounded-xl': isEventDate(day) && !isSelected(day),
                                        'hover:bg-slate-50 rounded-xl': !isSelected(day) && !isEventDate(day),
                                        'text-red-500 font-bold': isSunday(day) && !isSelected(day) && !isEventDate(day),
                                        'text-slate-800': !isSelected(day) && !isSunday(day)
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
 
            {{-- Card: Agenda Lainnya / Agenda Terkait --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-6">
                <h4 class="text-sm font-bold text-[#04241e] font-serif border-b border-slate-100 pb-3">Agenda Lainnya</h4>
                <div class="space-y-4">
                    @foreach($otherEvents as $index => $other)
                        <div class="{{ $index > 0 ? 'border-t border-slate-100/80 pt-4' : '' }} space-y-2">
                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400">
                                <span class="rounded bg-[#04241e]/5 text-[#04241e] px-1.5 py-0.5 uppercase tracking-wider">{{ $other->kategori ?? 'Kegiatan' }}</span>
                                <span class="font-sans">{{ $other->tanggal_mulai ? $other->tanggal_mulai->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                            <h5 class="text-xs font-bold text-slate-800 hover:text-[#04241e] leading-snug">
                                <a href="{{ route('agenda.show', $other->slug) }}">{{ $other->judul_event }}</a>
                            </h5>
                            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 font-sans">
                                <i class="fa-regular fa-clock text-[9px]"></i>
                                <span>{{ $other->jam_mulai ? substr($other->jam_mulai, 0, 5) : '--:--' }} WIB</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('agenda.index') }}" class="block text-center text-[10px] font-bold text-[#8c741c] hover:text-[#725e17] hover:underline uppercase tracking-widest pt-2 focus:outline-none">
                    Lihat Semua Agenda
                </a>
            </div>
 
            {{-- Event Details Card: Time & Location --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm space-y-5">
                {{-- Waktu --}}
                <div class="flex gap-4 items-start">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 flex-shrink-0 mt-0.5">
                        <i class="fa-regular fa-clock text-base"></i>
                    </span>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Waktu Pelaksanaan</p>
                        <p class="text-sm font-bold text-slate-800 mt-1 leading-normal">
                            {{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->locale('id')->translatedFormat('l, d F Y') : '-' }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $agenda->jam_mulai ? substr($agenda->jam_mulai, 0, 5) : '--:--' }} 
                            @if($agenda->jam_selesai)
                                - {{ substr($agenda->jam_selesai, 0, 5) }}
                            @endif
                            WIB
                        </p>
                    </div>
                </div>
 
                {{-- Lokasi --}}
                <div class="flex gap-4 items-start border-t border-slate-50 pt-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-500 flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-location-dot text-base"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Lokasi</p>
                        <p class="text-sm font-bold text-slate-800 mt-1 leading-normal truncate">
                            {{ $agenda->lokasi ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5 truncate">Dinas Perpustakaan Daerah Bukittinggi</p>
                    </div>
                </div>
            </div>
 
        </div>
    </div>
</div>
 
{{-- Alpine.js Script for the Interactive Calendar Widget --}}
<script>
    const allEvents = @json($allEvents);
    const eventDateStr = "{{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->toDateString() : '' }}";
 
    function calendarController() {
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
 
        const todayObj = new Date();
 
        // Initialize view based on the current event date, otherwise current real date
        let initialYear = todayObj.getFullYear();
        let initialMonth = todayObj.getMonth();
        let initialDateVal = todayObj.getDate();
 
        if (eventDateStr) {
            const parts = eventDateStr.split('-');
            initialYear = parseInt(parts[0], 10);
            initialMonth = parseInt(parts[1], 10) - 1; // 0-indexed
            initialDateVal = parseInt(parts[2], 10);
        }
 
        return {
            year: initialYear,
            month: initialMonth, // 0-indexed
            selectedDate: initialDateVal,
            monthName: '',
            no_of_days: [],
            blankdays: [],
            selectedDateString: '',
            selectedEvents: [],
 
            init() {
                this.updateCalendar();
            },
 
            updateCalendar() {
                this.monthName = monthNames[this.month];
                
                // Days in month
                const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                // Day of week of the first day of month (0 = Sun, 1 = Mon, etc.)
                const firstDayIndex = new Date(this.year, this.month, 1).getDay();
 
                // Blank slots for calendar grid alignment
                const blankdaysArray = [];
                for (let i = 0; i < firstDayIndex; i++) {
                    blankdaysArray.push(i);
                }
                this.blankdays = blankdaysArray;
 
                // Days list in month
                const daysArray = [];
                for (let i = 1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }
                this.no_of_days = daysArray;
 
                this.updateSelectedDateString();
            },
 
            updateSelectedDateString() {
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(this.selectedDate).padStart(2, '0');
                this.selectedDateString = `${this.year}-${m}-${d}`;
                this.updateSelectedEvents();
            },
 
            updateSelectedEvents() {
                this.selectedEvents = allEvents.filter(e => e.tanggal_mulai === this.selectedDateString);
            },
 
            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                // Reset selected date to 1st of next month or maintain if in range
                const daysInNextMonth = new Date(this.year, this.month + 1, 0).getDate();
                if (this.selectedDate > daysInNextMonth) {
                    this.selectedDate = 1;
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
                const daysInNextMonth = new Date(this.year, this.month + 1, 0).getDate();
                if (this.selectedDate > daysInNextMonth) {
                    this.selectedDate = 1;
                }
                this.updateCalendar();
            },
 
            selectDate(day) {
                this.selectedDate = day;
                this.updateSelectedDateString();
                const matched = allEvents.find(e => e.tanggal_mulai === this.selectedDateString);
                if (matched) {
                    window.location.href = matched.url;
                }
            },
 
            isSelected(day) {
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                return `${this.year}-${m}-${d}` === this.selectedDateString;
            },
 
            isEventDate(day) {
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                return `${this.year}-${m}-${d}` === eventDateStr;
            },
 
            isSunday(day) {
                const d = new Date(this.year, this.month, day).getDay();
                return d === 0;
            },
 
            hasEvent(day) {
                const m = String(this.month + 1).padStart(2, '0');
                const d = String(day).padStart(2, '0');
                const targetStr = `${this.year}-${m}-${d}`;
                return allEvents.some(event => event.tanggal_mulai === targetStr);
            }
        }
    }
</script>
@endsection
