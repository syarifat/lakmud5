<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Pemateri Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.pemateri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div class="mb-4">
                            <x-input-label for="nama" value="Nama Lengkap & Gelar" />
                            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="jabatan" value="Jabatan / Instansi" />
                            <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" placeholder="Contoh: Ketua PC IPNU Tulungagung" required />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="no_hp" value="Nomor WhatsApp" />
                            <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="mb-4">
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