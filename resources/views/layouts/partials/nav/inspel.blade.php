<x-nav-link :href="route('inspel.dashboard')" :active="request()->routeIs('inspel.dashboard')">Dashboard</x-nav-link>
<x-nav-link :href="route('inspel.penilaian')" :active="request()->routeIs('inspel.penilaian*')">Input Penilaian Peserta</x-nav-link>
<x-nav-link :href="route('inspel.pemateri')" :active="request()->routeIs('inspel.pemateri*')">Data Pemateri</x-nav-link>
<x-nav-link :href="route('inspel.absensi')" :active="request()->routeIs('inspel.absensi')">Monitor Absensi</x-nav-link>
<x-nav-link :href="route('inspel.refleksi')" :active="request()->routeIs('inspel.refleksi*')">Review Refleksi</x-nav-link>
<x-nav-link :href="route('inspel.laporan.index')" :active="request()->routeIs('inspel.laporan.*')">Rekap Laporan</x-nav-link>
<x-nav-link :href="route('inspel.slide-cv')" :active="request()->routeIs('inspel.slide-cv')">Slide CV Pemateri</x-nav-link>