<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Review Refleksi Harian Peserta') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg">Lembar Refleksi Harian Terkirim</h3>
                    <p class="text-xs text-gray-500 mt-1">Daftar lembar refleksi harian yang telah diisi oleh peserta LAKMUD V</p>
                </div>

                @if($refleksis->isEmpty())
                    <div class="text-center py-16 text-gray-500 font-medium">
                        Belum ada peserta yang mengumpulkan lembar refleksi harian.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th scope="col" class="px-6 py-3.5 w-12">No</th>
                                    <th scope="col" class="px-6 py-3.5">Nama Peserta</th>
                                    <th scope="col" class="px-6 py-3.5 text-center w-36">Hari Ke-</th>
                                    <th scope="col" class="px-6 py-3.5 text-center w-48">Tanggal Pengisian</th>
                                    <th scope="col" class="px-6 py-3.5 text-center w-36">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($refleksis as $index => $r)
                                    <tr class="hover:bg-gray-50/50 transition align-middle">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-950">{{ $r->peserta->name }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $r->peserta->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">
                                            Hari {{ $r->hari_ke }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-500">
                                            {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <a href="{{ route('inspel.refleksi.show', $r->id) }}" 
                                                class="inline-block bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                                                Tinjau Esai
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
