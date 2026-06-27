<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen User</h2>
            <a href="{{ route('admin.user.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700">
                + Tambah Panitia/User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div>
            @endif

            <!-- WhatsApp API Direct Redirect Prompt -->
            @if(session('wa_link'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-500 text-white rounded-full p-2.5 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436.002 9.858-4.417 9.86-9.86.002-2.638-1.02-5.118-2.88-6.98C16.58 1.895 14.1 1.842 12.014 1.84c-5.437 0-9.86 4.418-9.863 9.864 0 1.81.488 3.586 1.414 5.15L2.593 21.16l4.054-1.006z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold">Kirim Link Reset Sandi ke WhatsApp</h4>
                            <p class="text-xs text-emerald-600">Klik tombol jika halaman WhatsApp tidak otomatis terbuka.</p>
                        </div>
                    </div>
                    <a href="{{ session('wa_link') }}" target="_blank" id="waAutoOpen"
                       class="w-full sm:w-auto text-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg flex items-center justify-center gap-2 transition shadow-sm shadow-emerald-600/20">
                        Kirim Sekarang
                    </a>
                </div>
                
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setTimeout(function() {
                            var waLink = document.getElementById('waAutoOpen');
                            if (waLink) {
                                window.open(waLink.href, '_blank');
                            }
                        }, 500);
                    });
                </script>
            @endif

            <!-- Filter Role -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-150">
                <form method="GET" action="{{ route('admin.user.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-grow">
                        <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Filter Berdasarkan Role</label>
                        <select id="role" name="role" class="w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                            <option value="inspel" {{ request('role') == 'inspel' ? 'selected' : '' }}>Inspel</option>
                            <option value="pendamping" {{ request('role') == 'pendamping' ? 'selected' : '' }}>Pendamping</option>
                            <option value="pendaftar" {{ request('role') == 'pendaftar' ? 'selected' : '' }}>Pendaftar</option>
                        </select>
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button type="submit" class="flex-grow sm:flex-none bg-emerald-600 text-white font-bold text-sm px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition">
                            Filter
                        </button>
                        @if(request()->filled('role'))
                            <a href="{{ route('admin.user.index') }}" class="flex-grow sm:flex-none text-center bg-gray-100 text-gray-750 font-bold text-sm px-6 py-2.5 rounded-lg hover:bg-gray-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama & Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFID UID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $user->role == 'peserta' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $user->role == 'inspel' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $user->role == 'pendamping' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $user->role == 'pendaftar' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    {{ $user->rfid_uid ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-3">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    
                                    <form action="{{ route('admin.user.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Buat link reset sandi untuk {{ $user->name }} dan kirim via WhatsApp?')" class="inline m-0">
                                        @csrf
                                        <button type="submit" class="text-amber-600 hover:text-amber-900">
                                            Reset Sandi
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" class="inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>