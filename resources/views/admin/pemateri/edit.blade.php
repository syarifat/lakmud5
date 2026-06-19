<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pemateri: {{ $pemateri->nama }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.pemateri.update', $pemateri->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="nama" value="Nama Lengkap & Gelar" />
                            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="$pemateri->nama" required />
                        </div>
                        <div>
                            <x-input-label for="materi_id" value="Materi yang Diampu" />
                            <select id="materi_id" name="materi_id" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Materi (Opsional) --</option>
                                @foreach($materis as $m)
                                    <option value="{{ $m->id }}" {{ $pemateri->materi_id == $m->id ? 'selected' : '' }}>{{ $m->nama_materi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="jabatan" value="Jabatan / Instansi" />
                            <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" :value="$pemateri->jabatan" placeholder="Contoh: Instruktur LAKMUD V" />
                        </div>
                        <div>
                            <x-input-label for="pekerjaan" value="Pekerjaan" />
                            <x-text-input id="pekerjaan" name="pekerjaan" type="text" class="mt-1 block w-full" :value="$pemateri->pekerjaan" required />
                        </div>
                        <div>
                            <x-input-label for="tempat_lahir" value="Tempat Lahir" />
                            <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" class="mt-1 block w-full" :value="$pemateri->tempat_lahir" required />
                        </div>
                        <div>
                            <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                            <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full" :value="$pemateri->tanggal_lahir" required />
                        </div>
                        <div>
                            <x-input-label for="no_telp" value="Nomor Telepon / WhatsApp" />
                            <x-text-input id="no_telp" name="no_telp" type="text" class="mt-1 block w-full" :value="$pemateri->no_telp" required />
                        </div>
                        <div>
                            <x-input-label for="hobi" value="Hobi" />
                            <x-text-input id="hobi" name="hobi" type="text" class="mt-1 block w-full" :value="$pemateri->hobi" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="motto" value="Motto Hidup" />
                            <x-text-input id="motto" name="motto" type="text" class="mt-1 block w-full" :value="$pemateri->motto" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="alamat" value="Alamat Lengkap" />
                            <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>{{ $pemateri->alamat }}</textarea>
                        </div>
                        <div class="md:col-span-2 text-center border-t pt-4">
                            <p class="text-xs text-gray-500 mb-2">Foto Saat Ini:</p>
                            <img src="{{ $pemateri->foto ? asset('storage/' . $pemateri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($pemateri->nama) }}" class="h-24 w-24 rounded-lg mx-auto object-cover border mb-4">
                            <x-input-label for="foto" value="Ganti Foto (Kosongkan jika tidak ingin ganti)" />
                            <input type="file" name="foto" class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('admin.pemateri.index') }}" class="px-4 py-2 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Update Pemateri</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>