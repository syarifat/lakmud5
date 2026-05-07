<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kelompok: {{ $kelompok->nama_kelompok }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm sm:rounded-xl border border-gray-100">
                <form action="{{ route('admin.kelompok.update', $kelompok->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="nama_kelompok" value="Nama / Nomor Kelompok" />
                            <x-text-input id="nama_kelompok" name="nama_kelompok" type="text" class="mt-1 block w-full" :value="$kelompok->nama_kelompok" required />
                        </div>

                        <div>
                            <x-input-label for="pendamping_id" value="Ganti Pendamping" />
                            <select name="pendamping_id" id="pendamping_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                                @foreach($pendampings as $p)
                                    <option value="{{ $p->id }}" {{ $kelompok->pendamping_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-10">
                        <a href="{{ route('admin.kelompok.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition">Update Kelompok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>