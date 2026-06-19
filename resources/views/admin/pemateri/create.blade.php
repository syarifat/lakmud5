<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Pemateri Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.pemateri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="nama" value="Nama Lengkap & Gelar" />
                            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="materi_id" value="Materi yang Diampu" />
                            <select id="materi_id" name="materi_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Materi (Opsional) --</option>
                                @foreach($materis as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_materi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="jabatan" value="Jabatan / Instansi" />
                            <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" placeholder="Contoh: Instruktur LAKMUD V" />
                        </div>
                        <div>
                            <x-input-label for="pekerjaan" value="Pekerjaan" />
                            <x-text-input id="pekerjaan" name="pekerjaan" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="tempat_lahir" value="Tempat Lahir" />
                            <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                            <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="no_telp" value="Nomor Telepon / WhatsApp" />
                            <x-text-input id="no_telp" name="no_telp" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="hobi" value="Hobi" />
                            <x-text-input id="hobi" name="hobi" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="motto" value="Motto Hidup" />
                            <x-text-input id="motto" name="motto" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="alamat" value="Alamat Lengkap" />
                            <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="foto" value="Foto Pemateri (JPG/PNG, Maks 2MB)" />
                            <input type="file" name="foto" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('admin.pemateri.index') }}" class="px-4 py-2 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Simpan Pemateri</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>