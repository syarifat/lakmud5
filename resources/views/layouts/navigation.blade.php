<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('logo.png') }}" alt="Logo LAKMUD" class="block h-10 w-auto">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @php $role = Auth::user()->role; @endphp
                    
                    @if($role == 'admin')
                        @include('layouts.partials.nav.admin')
                    @elseif($role == 'peserta')
                        @include('layouts.partials.nav.peserta')
                    @elseif($role == 'inspel')
                        @include('layouts.partials.nav.inspel')
                    @elseif($role == 'pendamping')
                        @include('layouts.partials.nav.pendamping')
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col text-right mr-2">
                                <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] uppercase tracking-widest text-emerald-600">{{ $role }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100 shadow-inner">
        <div class="pt-2 pb-3 space-y-1">
            @if($role == 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.pendaftar.index')" :active="request()->routeIs('admin.pendaftar.*')">Verifikasi Pendaftar</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">Manajemen User</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.pemateri.index')" :active="request()->routeIs('admin.pemateri.*')">Master Pemateri</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.materi.index')" :active="request()->routeIs('admin.materi.*')">Master Materi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.kelompok.index')" :active="request()->routeIs('admin.kelompok.*')">Manajemen Kelompok</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">Rekap Laporan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.slide-cv')" :active="request()->routeIs('admin.slide-cv')">Slide CV Pemateri</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.idcard.index')" :active="request()->routeIs('admin.idcard.*')">Cetak ID Card</x-responsive-nav-link>
            @elseif($role == 'peserta')
                <x-responsive-nav-link :href="route('peserta.dashboard')" :active="request()->routeIs('peserta.dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peserta.absensi')" :active="request()->routeIs('peserta.absensi')">Tap Absensi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peserta.nilai-pemateri')" :active="request()->routeIs('peserta.nilai-pemateri')">Nilai Pemateri</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peserta.nilai-inspel')" :active="request()->routeIs('peserta.nilai-inspel')">Nilai Inspel</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peserta.refleksi')" :active="request()->routeIs('peserta.refleksi')">Refleksi Harian</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('peserta.ujian')" :active="request()->routeIs('peserta.ujian*')">Upload Jawaban Pre/Post Test</x-responsive-nav-link>
            @elseif($role == 'inspel')
                <x-responsive-nav-link :href="route('inspel.dashboard')" :active="request()->routeIs('inspel.dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inspel.penilaian')" :active="request()->routeIs('inspel.penilaian*')">Input Penilaian Peserta</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inspel.pemateri')" :active="request()->routeIs('inspel.pemateri*')">Data Pemateri</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inspel.absensi')" :active="request()->routeIs('inspel.absensi')">Monitor Absensi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inspel.refleksi')" :active="request()->routeIs('inspel.refleksi*')">Review Refleksi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inspel.laporan.index')" :active="request()->routeIs('inspel.laporan.*')">Rekap Laporan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inspel.slide-cv')" :active="request()->routeIs('inspel.slide-cv')">Slide CV Pemateri</x-responsive-nav-link>
            @elseif($role == 'pendamping')
                <x-responsive-nav-link :href="route('pendamping.dashboard')" :active="request()->routeIs('pendamping.dashboard')">Dashboard & Anggota</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pendamping.absensi')" :active="request()->routeIs('pendamping.absensi')">Monitor Absensi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pendamping.observasi')" :active="request()->routeIs('pendamping.observasi*')">Observasi Harian</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>