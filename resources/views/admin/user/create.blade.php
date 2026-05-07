<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah User Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <x-input-label for="name" value="Nama Lengkap" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="role" value="Role" />
                            <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="admin">Admin</option>
                                <option value="inspel">Inspel (Instruktur Pelatih)</option>
                                <option value="pendamping">Pendamping</option>
                                <option value="peserta">Peserta</option>
                                <option value="pendaftar">Pendaftar</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="rfid_uid" value="RFID UID (Opsional)" />
                            <x-text-input id="rfid_uid" name="rfid_uid" type="text" class="mt-1 block w-full" placeholder="Tempelkan kartu ke reader..." />
                        </div>
                        <div class="mb-4 md:col-span-2">
                            <x-input-label for="password" value="Password" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('admin.user.index') }}" class="px-4 py-2 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg">Batal</a>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>