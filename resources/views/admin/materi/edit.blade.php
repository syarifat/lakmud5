<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Materi: {{ $materi->nama_materi }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100">
                <form action="{{ route('admin.materi.update', $materi->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <x-input-label for="nama_materi" value="Nama Materi" />
                        <x-text-input id="nama_materi" name="nama_materi" type="text" class="mt-1 block w-full" :value="$materi->nama_materi" required />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="deskripsi" value="Deskripsi/Pokok Pembahasan" />
                        <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="4">{{ $materi->deskripsi }}</textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.materi.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold">Batal</a>
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700">Update Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>