<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin LAKMUD V') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-emerald-800">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-sm text-gray-600">Pantau perkembangan persiapan LAKMUD V Kauman 2026 secara real-time di sini.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border-l-4 border-blue-500 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-50 text-blue-500 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Pendaftar</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pendaftar'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border-l-4 border-emerald-500 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-emerald-50 text-emerald-500 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Lolos Seleksi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['lolos'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border-l-4 border-amber-500 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-amber-50 text-amber-500 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Perlu Verifikasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-emerald-800 rounded-2xl p-6 text-white shadow-lg">
                    <h4 class="text-lg font-bold mb-2">Verifikasi Pendaftar</h4>
                    <p class="text-emerald-100 text-sm mb-4">Ada pendaftar baru yang menunggu verifikasi berkas dan tanda tangan digital.</p>
                    <a href="{{ route('admin.pendaftar.index') }}" class="inline-block bg-white text-emerald-800 px-4 py-2 rounded-lg font-bold text-sm hover:bg-emerald-50 transition">
                        Lihat Semua Pendaftar
                    </a>
                </div>
                
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h4 class="text-lg font-bold mb-2 text-gray-900">Informasi Acara</h4>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li>📍 Tempat: SMP Negeri 2 Kauman [cite: 32]</li>
                        <li>📅 Tanggal: 27 - 30 Juni 2026 [cite: 32]</li>
                        <li>🏷️ Tema: Steady Growth [cite: 3, 9]</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>