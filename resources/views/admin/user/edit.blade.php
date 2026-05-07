<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100">
                <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <x-input-label for="name" value="Nama Lengkap" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="$user->name" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="$user->email" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="role" value="Role" />
                            <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="inspel" {{ $user->role == 'inspel' ? 'selected' : '' }}>Inspel (Instruktur Pelatih)</option>
                                <option value="pendamping" {{ $user->role == 'pendamping' ? 'selected' : '' }}>Pendamping</option>
                                <option value="peserta" {{ $user->role == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                <option value="pendaftar" {{ $user->role == 'pendaftar' ? 'selected' : '' }}>Pendaftar</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="rfid_uid" value="RFID UID" />
                            <x-text-input id="rfid_uid" name="rfid_uid" type="text" class="mt-1 block w-full font-mono" :value="$user->rfid_uid" placeholder="Tempelkan kartu ke reader..." />
                        </div>

                        <div class="mb-4 md:col-span-2">
                            <x-input-label for="password" value="Password Baru (Kosongkan jika tidak ingin ganti)" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 border-t pt-6">
                        <a href="{{ route('admin.user.index') }}" class="px-4 py-2 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Update Data User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>