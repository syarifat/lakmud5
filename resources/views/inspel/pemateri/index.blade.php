<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pemateri LAKMUD V') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Narasumber / Pemateri</h3>
                </div>

                @if($pemateri->isEmpty())
                    <div class="text-center py-16 text-gray-500 font-medium">
                        Belum ada pemateri yang didaftarkan.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach($pemateri as $p)
                            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6 flex flex-col justify-between hover:shadow transition">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">
                                            {{ substr($p->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-base">{{ $p->nama }}</h4>
                                            <p class="text-xs text-gray-500">{{ $p->pekerjaan }}</p>
                                        </div>
                                    </div>
                                    <hr class="border-gray-200">
                                    <div class="space-y-1 text-xs text-gray-650">
                                        <div>📞 No Telp: <span class="font-semibold text-gray-800">{{ $p->no_telp }}</span></div>
                                        <div>📍 Alamat: <span class="font-semibold text-gray-800">{{ $p->alamat }}</span></div>
                                        <div>💡 Motto: <span class="font-semibold text-gray-800 italic">"{{ $p->motto }}"</span></div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('inspel.pemateri.show', $p->id) }}" class="block text-center bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs py-2 rounded-xl transition shadow-sm">
                                        Lihat CV Lengkap
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
