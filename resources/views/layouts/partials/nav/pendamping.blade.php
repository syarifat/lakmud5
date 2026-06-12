<x-nav-link :href="route('pendamping.dashboard')" :active="request()->routeIs('pendamping.dashboard')">Dashboard & Anggota</x-nav-link>
<x-nav-link :href="route('pendamping.absensi')" :active="request()->routeIs('pendamping.absensi')">Monitor Absensi</x-nav-link>
<x-nav-link :href="route('pendamping.observasi')" :active="request()->routeIs('pendamping.observasi*')">Observasi Harian</x-nav-link>