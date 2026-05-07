<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pemateri: {{ $pemateri->nama }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.pemateri.update', $pemateri->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 gap-4">
                        <div class="mb-4">
                            <x-input-label for="nama" value="Nama Lengkap & Gelar" />
                            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="$pemateri->nama" required />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="jabatan" value="Jabatan / Instansi" />
                            <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" :value="$pemateri->jabatan" required />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="no_hp" value="Nomor WhatsApp" />
                            <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="$pemateri->no_hp" required />
                        </div>
                        <div class="mb-4 text-center border-t pt-4">
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