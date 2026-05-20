<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pendaftar: {{ $data->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-bold text-emerald-800 border-b pb-2 mb-4">Biodata</h3>
                        <p class="text-sm text-gray-600 mb-1">Delegasi: <span class="font-bold text-gray-900">{{ $data->delegasi }}</span></p>
                        <p class="text-sm text-gray-600 mb-1">Tempat, Tgl Lahir: <span class="font-bold text-gray-900">{{ $data->tempat_lahir }}, {{ $data->tanggal_lahir }}</span></p>
                        <p class="text-sm text-gray-600 mb-1">No. WhatsApp: <span class="font-bold text-gray-900">{{ $data->no_hp }}</span></p>
                        <p class="text-sm text-gray-600 mb-1">Ukuran Kaos: <span class="font-bold text-gray-900">{{ $data->ukuran_kaos }}</span></p>
                    </div>

                    <div>
                        <div class="flex justify-between items-end border-b pb-2 mb-4">
                            <h3 class="text-lg font-bold text-emerald-800">Tanda Tangan</h3>
                            <a href="{{ asset('storage/' . $data->file_ttd) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Buka di Tab Baru</a>
                        </div>
                        <div class="border rounded bg-gray-50 h-48 overflow-x-auto relative">
                            <img src="{{ asset('storage/' . $data->file_ttd) }}" class="min-w-[400px] w-full h-full object-contain object-left" alt="TTD">
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-bold text-emerald-800 border-b pb-2 mb-4">Berkas Pendukung</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="{{ asset('storage/' . $data->file_sertifikat) }}" target="_blank" class="block p-4 border rounded hover:bg-gray-50 text-center text-xs font-bold">Sertifikat Makesta</a>
                        <a href="{{ asset('storage/' . $data->file_rekom) }}" target="_blank" class="block p-4 border rounded hover:bg-gray-50 text-center text-xs font-bold">Surat Rekomendasi</a>
                        <a href="{{ asset('storage/' . $data->file_foto) }}" target="_blank" class="block p-4 border rounded hover:bg-gray-50 text-center text-xs font-bold">Pas Foto</a>
                        <a href="{{ asset('storage/' . $data->file_identitas) }}" target="_blank" class="block p-4 border rounded hover:bg-gray-50 text-center text-xs font-bold">ID Card/KTP</a>
                        <a href="{{ asset('storage/' . $data->file_bukti_ig) }}" target="_blank" class="block p-4 border rounded hover:bg-gray-50 text-center text-xs font-bold">Bukti Follow IG @pacipnuippnu.kauman</a>
                        <a href="{{ asset('storage/' . $data->file_bukti_ig_kaderisasi) }}" target="_blank" class="block p-4 border rounded hover:bg-gray-50 text-center text-xs font-bold">Bukti Follow IG @kaderisasi_pacipnuippnukauman</a>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t flex gap-4">
                    <form action="{{ route('admin.pendaftar.verifikasi', $data->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="lolos">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-lg hover:bg-emerald-700 transition">
                            Loloskan Peserta
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.pendaftar.verifikasi', $data->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="status" value="tolak">
                        <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-200 font-bold py-3 rounded-lg hover:bg-red-100 transition">
                            Batalkan / Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>