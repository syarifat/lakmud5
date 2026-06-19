<x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
<x-nav-link href="{{ route('admin.pendaftar.index') }}" :active="request()->routeIs('admin.pendaftar.*')">Verifikasi Pendaftar</x-nav-link>
<x-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">Manajemen User</x-nav-link>
<x-nav-link :href="route('admin.pemateri.index')" :active="request()->routeIs('admin.pemateri.*')">Master Pemateri</x-nav-link>
<x-nav-link :href="route('admin.materi.index')" :active="request()->routeIs('admin.materi.*')">Master Materi</x-nav-link>
<x-nav-link :href="route('admin.kelompok.index')" :active="request()->routeIs('admin.kelompok.*')">Manajemen Kelompok</x-nav-link>
<x-nav-link href="#">Manajemen RFID</x-nav-link>
<x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">Laporan Total</x-nav-link>