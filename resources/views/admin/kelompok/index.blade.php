<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Kelompok</h2>
            <a href="{{ route('admin.kelompok.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700 transition">
                + Tambah Kelompok
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($kelompoks as $k)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-emerald-800">{{ $k->nama_kelompok }}</h3>
                            <span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full font-bold uppercase">LAKMUD V</span>
                        </div>
                        <p class="text-sm text-gray-500">Pendamping:</p>
                        <p class="font-semibold text-gray-900 mb-4">{{ $k->pendamping->name ?? 'Belum ditentukan' }}</p>
                    </div>
                    
                    <div class="flex gap-2 pt-4 border-t">
                        <a href="{{ route('admin.kelompok.edit', $k->id) }}" class="flex-1 text-center py-2 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100">Edit</a>
                        <form action="{{ route('admin.kelompok.destroy', $k->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus kelompok ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full text-center py-2 text-xs font-bold text-red-600 bg-red-50 rounded-lg hover:bg-red-100">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach

                @if($kelompoks->isEmpty())
                <div class="md:col-span-3 bg-white p-12 text-center rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 italic">Belum ada kelompok yang dibuat.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>