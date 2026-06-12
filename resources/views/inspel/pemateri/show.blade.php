<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('inspel.pemateri') }}" class="text-gray-400 hover:text-gray-650 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Curriculum Vitae Pemateri') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- CV Header -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center font-extrabold text-3xl shadow-inner border border-rose-100">
                    {{ substr($data->nama, 0, 1) }}
                </div>
                <div class="text-center sm:text-left">
                    <h3 class="text-2xl font-extrabold text-gray-950">{{ $data->nama }}</h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">{{ $data->pekerjaan }}</p>
                    <span class="inline-block mt-3 px-3 py-1 bg-gray-100 text-gray-750 text-xs font-semibold rounded-full">
                        Motto: "{{ $data->motto }}"
                    </span>
                </div>
            </div>

            <!-- CV Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Biodata Ringkas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:col-span-1 space-y-4">
                    <h4 class="font-bold text-gray-900 border-b border-gray-100 pb-2">Informasi Pribadi</h4>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-xs text-gray-400 block">Tempat, Tanggal Lahir</span>
                            <span class="font-semibold text-gray-800">{{ $data->tempat_lahir }}, {{ \Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">No. Telepon / HP</span>
                            <span class="font-semibold text-gray-800">{{ $data->no_telp }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Alamat Rumah</span>
                            <span class="font-semibold text-gray-800 leading-relaxed">{{ $data->alamat }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Hobi</span>
                            <span class="font-semibold text-gray-800">{{ $data->hobi }}</span>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Hidup / Pendidikan & Organisasi -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Riwayat Pendidikan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                            🎓 Riwayat Pendidikan
                        </h4>
                        @if($data->riwayatPendidikans->isEmpty())
                            <p class="text-sm text-gray-500 italic">Tidak ada data riwayat pendidikan.</p>
                        @else
                            <div class="relative border-l-2 border-emerald-100 ml-3 space-y-6 py-1">
                                @foreach($data->riwayatPendidikans as $rp)
                                    <div class="relative pl-5">
                                        <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border border-white"></div>
                                        <div class="text-sm font-bold text-gray-900">{{ $rp->nama_sekolah }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $rp->jenjang }} &bull; Tahun {{ $rp->tahun }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Organisasi -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                            🤝 Riwayat Organisasi
                        </h4>
                        @if($data->riwayatOrganisasis->isEmpty())
                            <p class="text-sm text-gray-500 italic">Tidak ada data riwayat organisasi.</p>
                        @else
                            <div class="relative border-l-2 border-rose-100 ml-3 space-y-6 py-1">
                                @foreach($data->riwayatOrganisasis as $ro)
                                    <div class="relative pl-5">
                                        <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full border border-white"></div>
                                        <div class="text-sm font-bold text-gray-900">{{ $ro->nama_organisasi }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $ro->jabatan }} &bull; Tahun {{ $ro->tahun }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
