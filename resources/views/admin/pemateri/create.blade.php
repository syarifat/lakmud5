<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pemateri Baru') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">
                
                <form action="{{ route('admin.pemateri.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- 1. Data Diri Pemateri -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b border-slate-100 pb-2">1. Informasi Data Diri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nama" value="Nama Lengkap & Gelar" />
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full text-sm rounded-xl" required />
                            </div>
                            
                            <div>
                                <x-input-label for="materi_id" value="Materi yang Diampu" />
                                <select id="materi_id" name="materi_id" class="mt-1 block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="">-- Pilih Materi (Opsional) --</option>
                                    @foreach($materis as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama_materi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="tempat_lahir" value="Tempat Lahir" />
                                <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" class="mt-1 block w-full text-sm rounded-xl" required />
                            </div>
                            
                            <div>
                                <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                                <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full text-sm rounded-xl" required />
                            </div>

                            <div>
                                <x-input-label for="no_telp" value="Nomor HP / WhatsApp" />
                                <x-text-input id="no_telp" name="no_telp" type="text" class="mt-1 block w-full text-sm rounded-xl" required />
                            </div>

                            <div>
                                <x-input-label for="instagram" value="Akun Instagram (Opsional)" />
                                <x-text-input id="instagram" name="instagram" type="text" class="mt-1 block w-full text-sm rounded-xl" placeholder="Contoh: @username" />
                            </div>

                            <div>
                                <x-input-label for="email" value="Alamat Email (Opsional)" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-sm rounded-xl" />
                            </div>

                            <div>
                                <x-input-label for="motto" value="Motto Hidup" />
                                <x-text-input id="motto" name="motto" type="text" class="mt-1 block w-full text-sm rounded-xl" required />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="alamat" value="Alamat Lengkap" />
                                <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm" required></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="foto" value="Foto Profil Pemateri (Maks 2MB)" />
                                <input type="file" name="foto" id="foto" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Riwayat Pendidikan -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b border-slate-100 pb-2">2. Riwayat Pendidikan (Tingkat, Sekolah, Tahun Lulus)</h3>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach(['SD Sederajat', 'SMP Sederajat', 'SMA Sederajat', 'Perguruan Tinggi (S1)', 'Pascasarjana (S2)'] as $tingkat)
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                                    <div class="text-sm font-bold text-slate-700">
                                        {{ $tingkat }}
                                    </div>
                                    <div>
                                        <input type="text" name="pendidikan[{{ $tingkat }}][sekolah]" placeholder="Nama Sekolah / Universitas" 
                                            class="block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" />
                                    </div>
                                    <div>
                                        <input type="text" name="pendidikan[{{ $tingkat }}][tahun]" placeholder="Tahun Lulus" 
                                            class="block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 3. Riwayat Organisasi (Dynamic) -->
                    <div class="space-y-4" x-data="{ 
                        organisasis: [{ nama: '', jabatan: '', tahun: '' }] 
                    }">
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b border-slate-100 pb-2">3. Riwayat Organisasi</h3>
                        
                        <div class="space-y-4">
                            <template x-for="(org, index) in organisasis" :key="index">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end bg-slate-50/30 p-4 rounded-2xl border border-slate-100">
                                    <div>
                                        <x-input-label value="Nama Organisasi" />
                                        <input type="text" :name="'organisasi['+index+'][nama]'" x-model="org.nama" class="mt-1 block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required />
                                    </div>
                                    <div>
                                        <x-input-label value="Jabatan" />
                                        <input type="text" :name="'organisasi['+index+'][jabatan]'" x-model="org.jabatan" class="mt-1 block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required />
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <x-input-label value="Tahun / Periode" />
                                            <input type="text" :name="'organisasi['+index+'][tahun]'" x-model="org.tahun" class="mt-1 block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Contoh: 2021-2023" required />
                                        </div>
                                        <button type="button" @click="organisasis.splice(index, 1)" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg mt-6">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="organisasis.push({ nama: '', jabatan: '', tahun: '' })" 
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-750 bg-indigo-50 hover:bg-indigo-100 px-4 py-2.5 rounded-xl transition">
                            + Tambah Riwayat Organisasi
                        </button>
                    </div>

                    <!-- 4. Riwayat Pengkaderan (Dynamic) -->
                    <div class="space-y-4" x-data="{ 
                        pengkaderans: [{ tingkat: '', nama: '', tahun: '' }] 
                    }">
                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b border-slate-100 pb-2">4. Riwayat Pengkaderan</h3>
                        
                        <div class="space-y-4">
                            <template x-for="(pk, index) in pengkaderans" :key="index">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end bg-slate-50/30 p-4 rounded-2xl border border-slate-100">
                                    <div>
                                        <x-input-label value="Tingkat Pengkaderan" />
                                        <input type="text" :name="'pengkaderan['+index+'][tingkat]'" x-model="pk.tingkat" class="mt-1 block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Contoh: LAKMUD / LKD / PKD" required />
                                    </div>
                                    <div>
                                        <x-input-label value="Nama Kegiatan / Tempat" />
                                        <input type="text" :name="'pengkaderan['+index+'][nama]'" x-model="pk.nama" class="mt-1 block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required />
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <x-input-label value="Tahun" />
                                            <input type="text" :name="'pengkaderan['+index+'][tahun]'" x-model="pk.tahun" class="mt-1 block w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Contoh: 2022" required />
                                        </div>
                                        <button type="button" @click="pengkaderans.splice(index, 1)" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg mt-6">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="pengkaderans.push({ tingkat: '', nama: '', tahun: '' })" 
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-750 bg-indigo-50 hover:bg-indigo-100 px-4 py-2.5 rounded-xl transition">
                            + Tambah Riwayat Pengkaderan
                        </button>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('admin.pemateri.index') }}" 
                            class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-xl transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-xl transition shadow-md hover:shadow-lg">
                            Simpan Pemateri
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>