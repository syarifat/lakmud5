<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Data Pendaftar LAKMUD V
            </h2>
            <button onclick="toggleExportModal(true)" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export PDF Custom
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delegasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pendaftar as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $p->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->delegasi }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->no_hp }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($p->status_lulus)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lolos</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="inline-flex items-center px-3 py-1 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Detail & Verifikasi
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 italic">Belum ada pendaftar yang masuk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Export Custom PDF -->
    <div id="exportModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleExportModal(false)"></div>

            <!-- Position modal in center -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.pendaftar.export') }}" method="GET" target="_blank" onsubmit="return validateExportForm()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start w-full">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-semibold text-gray-900 mb-4" id="modal-title">
                                    Pilih Field yang Ingin Diexport ke PDF
                                </h3>

                                <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs text-emerald-700 font-medium text-left">
                                                Layout PDF akan otomatis menyesuaikan menjadi Landscape jika memilih lebih dari 5 kolom untuk kenyamanan membaca.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Checkbox Pilih Semua -->
                                <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                                    <input id="select_all" type="checkbox" onchange="toggleSelectAll(this)" class="h-4.5 w-4.5 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                    <label for="select_all" class="ml-2 text-sm font-bold text-gray-900 cursor-pointer">Pilih Semua Field</label>
                                </div>

                                <!-- Fields list -->
                                <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <input id="field_nama" name="fields[]" type="checkbox" value="nama" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_nama" class="ml-2 cursor-pointer">Nama Lengkap</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_email" name="fields[]" type="checkbox" value="email" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_email" class="ml-2 cursor-pointer">Email</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_nia" name="fields[]" type="checkbox" value="nia" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_nia" class="ml-2 cursor-pointer">NIA</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_delegasi" name="fields[]" type="checkbox" value="delegasi" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_delegasi" class="ml-2 cursor-pointer">Delegasi</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_ttl" name="fields[]" type="checkbox" value="ttl" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_ttl" class="ml-2 cursor-pointer">Tempat Tanggal Lahir</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_alamat" name="fields[]" type="checkbox" value="alamat" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_alamat" class="ml-2 cursor-pointer">Alamat</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_jabatan" name="fields[]" type="checkbox" value="jabatan" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_jabatan" class="ml-2 cursor-pointer">Jabatan</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_no_hp" name="fields[]" type="checkbox" value="no_hp" checked onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_no_hp" class="ml-2 cursor-pointer">No. WhatsApp</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_username_ig" name="fields[]" type="checkbox" value="username_ig" onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_username_ig" class="ml-2 cursor-pointer">Username Instagram</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_ukuran_kaos" name="fields[]" type="checkbox" value="ukuran_kaos" onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_ukuran_kaos" class="ml-2 cursor-pointer">Ukuran Kaos</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="field_status_lulus" name="fields[]" type="checkbox" value="status_lulus" onchange="onFieldChange()" class="field-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                        <label for="field_status_lulus" class="ml-2 cursor-pointer">Status Kelulusan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Export ke PDF
                        </button>
                        <button type="button" onclick="toggleExportModal(false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleExportModal(show) {
            const modal = document.getElementById('exportModal');
            if (show) {
                modal.classList.remove('hidden');
                onFieldChange();
            } else {
                modal.classList.add('hidden');
            }
        }

        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll('.field-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = master.checked;
            });
        }

        function onFieldChange() {
            const checkboxes = document.querySelectorAll('.field-checkbox');
            const master = document.getElementById('select_all');
            const total = checkboxes.length;
            const checkedCount = document.querySelectorAll('.field-checkbox:checked').length;
            
            master.checked = (checkedCount === total);
            master.indeterminate = (checkedCount > 0 && checkedCount < total);
        }

        function validateExportForm() {
            const checkedCount = document.querySelectorAll('.field-checkbox:checked').length;
            if (checkedCount === 0) {
                alert('Silakan pilih minimal 1 field untuk diexport!');
                return false;
            }
            toggleExportModal(false);
            return true;
        }
    </script>
</x-app-layout>