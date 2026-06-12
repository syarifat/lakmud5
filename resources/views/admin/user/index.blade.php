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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-3">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
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