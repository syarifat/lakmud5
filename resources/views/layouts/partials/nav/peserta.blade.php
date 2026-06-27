<x-nav-link :href="route('peserta.dashboard')" :active="request()->routeIs('peserta.dashboard')">Dashboard</x-nav-link>
<x-nav-link :href="route('peserta.absensi')" :active="request()->routeIs('peserta.absensi')">Tap Absensi</x-nav-link>
<x-nav-link :href="route('peserta.nilai-pemateri')" :active="request()->routeIs('peserta.nilai-pemateri')">Nilai Pemateri</x-nav-link>
<x-nav-link :href="route('peserta.nilai-inspel')" :active="request()->routeIs('peserta.nilai-inspel')">Nilai Inspel</x-nav-link>
<x-nav-link :href="route('peserta.refleksi')" :active="request()->routeIs('peserta.refleksi')">Refleksi Harian</x-nav-link>
<x-nav-link :href="route('peserta.ujian')" :active="request()->routeIs('peserta.ujian*')">Upload Jawaban Pre/Post Test</x-nav-link>