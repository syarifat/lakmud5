<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Kelompok Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm sm:rounded-xl border border-gray-100">
                <form action="{{ route('admin.kelompok.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="nama_kelompok" value="Nama / Nomor Kelompok" />
                            <x-text-input id="nama_kelompok" name="nama_kelompok" type="text" class="mt-1 block w-full" placeholder="Contoh: Kelompok 01 - KH. Hasyim Asy'ari" required />
                        </div>

                        <div>
                            <x-input-label for="pendamping_id" value="Pilih Pendamping" />
                            <select name="pendamping_id" id="pendamping_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="">-- Pilih Pendamping --</option>
                                @foreach($pendampings as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-gray-500 italic">*Jika daftar kosong, pastikan Anda sudah menambahkan user dengan role 'pendamping'.</p>
                        </div>

                        <div>
                            <x-input-label value="Pilih Anggota Kelompok (Peserta)" class="mb-2" />
                            @if($pesertas->isEmpty())
                                <p class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-xl border">Tidak ada peserta yang belum memiliki kelompok saat ini.</p>
                            @else
                                <div class="border rounded-xl p-4 max-h-60 overflow-y-auto space-y-2 bg-gray-50/50">
                                    @foreach($pesertas as $peserta)
                                        <label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 cursor-pointer hover:bg-emerald-50/30 transition">
                                            <input type="checkbox" name="peserta_ids[]" value="{{ $peserta->id }}" 
                                                class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <div class="text-sm">
                                                <p class="font-bold text-gray-900 leading-tight">{{ $peserta->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $peserta->email }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-xs text-gray-500 italic">*Hanya menampilkan peserta yang belum diploting ke kelompok manapun.</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-10">
                        <a href="{{ route('admin.kelompok.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition">Bentuk Kelompok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>