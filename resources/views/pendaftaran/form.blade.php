<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran LAKMUD V</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-[figtree]">

    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-emerald-800">Formulir Pendaftaran LAKMUD V</h1>
            <p class="text-gray-600 mt-2">Isi data diri Anda dengan lengkap dan benar.</p>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-gray-100">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pendaftar.store') }}" method="POST" enctype="multipart/form-data" id="formPendaftaran">
                @csrf
                
                <h3 class="text-lg font-bold text-emerald-800 mb-4 border-b pb-2">1. Data Akun Sistem</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Aktif</label>
                        <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Password (Untuk login nanti)</label>
                        <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required minlength="8">
                    </div>
                </div>

                <h3 class="text-lg font-bold text-emerald-800 mb-4 border-b pb-2">2. Biodata Peserta</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIA (Opsional)</label>
                        <input type="text" name="nia" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Delegasi (Ranting/PAC)</label>
                        <input type="text" name="delegasi" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="alamat" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="3" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jabatan di Organisasi</label>
                        <input type="text" name="jabatan" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                        <input type="text" name="no_hp" placeholder="0812..." class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username Instagram</label>
                        <input type="text" name="username_ig" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ukuran Kaos</label>
                        <select name="ukuran_kaos" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-bold text-emerald-800 mb-4">3. Upload Berkas Pendukung</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sertifikat MAKESTA (JPG/PNG/PDF)</label>
                            <input type="file" name="file_sertifikat" accept=".jpg,.jpeg,.png,.pdf" class="file-upload-input block w-full text-sm text-gray-600 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition" required>
                            <p class="mt-1 text-xs text-gray-500">Maks. 2MB</p>
                            <p class="mt-1 text-xs text-red-600 hidden error-msg"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surat Rekomendasi (JPG/PNG/PDF)</label>
                            <input type="file" name="file_rekom" accept=".jpg,.jpeg,.png,.pdf" class="file-upload-input block w-full text-sm text-gray-600 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition" required>
                            <p class="mt-1 text-xs text-gray-500">Maks. 2MB</p>
                            <p class="mt-1 text-xs text-red-600 hidden error-msg"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pas Foto 3x4 Background Merah (Hanya JPG/PNG)</label>
                            <input type="file" name="file_foto" accept=".jpg,.jpeg,.png" class="file-upload-input block w-full text-sm text-gray-600 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition" required>
                            <p class="mt-1 text-xs text-gray-500">Maks. 2MB</p>
                            <p class="mt-1 text-xs text-red-600 hidden error-msg"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kartu Identitas (KTP/Kartu Pelajar) (JPG/PNG/PDF)</label>
                            <input type="file" name="file_identitas" accept=".jpg,.jpeg,.png,.pdf" class="file-upload-input block w-full text-sm text-gray-600 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition" required>
                            <p class="mt-1 text-xs text-gray-500">Maks. 2MB</p>
                            <p class="mt-1 text-xs text-red-600 hidden error-msg"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Follow IG (@pacipnuippnu.kauman) (JPG/PNG/PDF)</label>
                            <input type="file" name="file_bukti_ig" accept=".jpg,.jpeg,.png,.pdf" class="file-upload-input block w-full text-sm text-gray-600 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition" required>
                            <p class="mt-1 text-xs text-gray-500">Maks. 2MB</p>
                            <p class="mt-1 text-xs text-red-600 hidden error-msg"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Follow IG (@kaderisasi_pacipnuippnukauman)</label>
                            <input type="file" name="file_bukti_ig_kaderisasi" accept=".jpg,.jpeg,.png,.pdf" class="file-upload-input block w-full text-sm text-gray-600 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition" required>
                            <p class="mt-1 text-xs text-gray-500">Maks. 2MB</p>
                            <p class="mt-1 text-xs text-red-600 hidden error-msg"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t pt-6 text-center">
                    <label class="block text-sm font-medium text-gray-700 mb-2">4. Tanda Tangan Digital (Tanda tangan di dalam kotak bawah)</label>
                    <div class="mx-auto w-full max-w-sm h-48 border-2 border-dashed border-gray-400 rounded-lg bg-white relative overflow-hidden shadow-inner">
                        <svg id="ttd-svg" class="absolute inset-0 w-full h-full cursor-crosshair touch-none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="100%" height="100%" fill="transparent" />
                            <g id="svg-paths" fill="none" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></g>
                        </svg>
                    </div>
                    <div class="mt-2">
                        <input type="hidden" name="ttd_data" id="ttd_data">
                        <button type="button" onclick="clearTTD()" class="text-sm text-red-600 font-bold hover:underline">Hapus Tanda Tangan</button>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 text-lg">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const svg = document.getElementById('ttd-svg');
        const pathsGroup = document.getElementById('svg-paths');
        const inputHidden = document.getElementById('ttd_data');
        let isDrawing = false;
        let currentPath = null;

        function getCoords(e) {
            const rect = svg.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        const startDrawing = (e) => {
            isDrawing = true;
            if (!svg.hasAttribute('viewBox')) {
                svg.setAttribute('viewBox', `0 0 ${svg.clientWidth} ${svg.clientHeight}`);
            }
            const { x, y } = getCoords(e);
            currentPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
            currentPath.setAttribute("d", `M ${x} ${y}`);
            pathsGroup.appendChild(currentPath);
        };

        const draw = (e) => {
            if (!isDrawing) return;
            e.preventDefault();
            const { x, y } = getCoords(e);
            const d = currentPath.getAttribute("d");
            currentPath.setAttribute("d", `${d} L ${x} ${y}`);
        };

        const endDrawing = () => {
            isDrawing = false;
            // Update input hidden setiap selesai satu tarikan garis
            inputHidden.value = svg.outerHTML;
        };

        svg.addEventListener('mousedown', startDrawing);
        svg.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', endDrawing);

        svg.addEventListener('touchstart', startDrawing);
        svg.addEventListener('touchmove', draw);
        window.addEventListener('touchend', endDrawing);

        function clearTTD() {
            pathsGroup.innerHTML = '';
            inputHidden.value = '';
        }

        // Validasi File Upload
        document.querySelectorAll('.file-upload-input').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const errorElement = this.parentElement.querySelector('.error-msg');
                if (!file) {
                    errorElement.classList.add('hidden');
                    return;
                }

                // Cek ukuran max 2MB
                if (file.size > 2 * 1024 * 1024) {
                    this.value = '';
                    errorElement.textContent = 'Ukuran file melebihi 2MB.';
                    errorElement.classList.remove('hidden');
                    return;
                }

                // Cek tipe file (foto hanya jpg/png, lainnya +pdf)
                const isPhoto = this.name === 'file_foto';
                const allowedExtensions = isPhoto ? ['jpg', 'jpeg', 'png'] : ['jpg', 'jpeg', 'png', 'pdf'];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                
                if (!allowedExtensions.includes(fileExtension)) {
                    this.value = '';
                    errorElement.textContent = `File tidak didukung. Harap unggah: ${allowedExtensions.join(', ')}.`;
                    errorElement.classList.remove('hidden');
                    return;
                }

                errorElement.classList.add('hidden');
            });
        });
    </script>
</body>
</html>