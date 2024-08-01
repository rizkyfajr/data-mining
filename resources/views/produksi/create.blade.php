<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produksi') }}
        </h2>
    </x-slot>
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.697z"/></svg>
        </span>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 1"/></svg>
        </span>
    </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">Tambah Produksi</h1>

                    <form action="{{ route('produksi.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="produk_id" class="block text-gray-700 text-sm font-bold mb-2">Produk:</label>
                            <select id="produk_id" name="produk_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Pilih Produk</option>
                                @foreach($produk as $item)
                                    <option value="{{ $item->id }}" data-smv="{{ $item->smv }}" {{ old('produk_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('produk_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="smv" class="block text-gray-700 text-sm font-bold mb-2">Waktu Standar SMV (Menit):</label>
                            <input type="number" id="smv" name="smv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="0" step="0.01" value="{{ old('smv') }}" readonly>
                            @error('smv')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="mesin_jahit_id" class="block text-gray-700 text-sm font-bold mb-2">Mesin Jahit:</label>
                            <select id="mesin_jahit_id" name="mesin_jahit_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Pilih Mesin Jahit</option>
                                @foreach($mesinJahit as $mesin)
                                    <option value="{{ $mesin->id }}" {{ old('mesin_jahit_id') == $mesin->id ? 'selected' : '' }}>{{ $mesin->nama_mesin }}</option>
                                @endforeach
                            </select>
                            @error('mesin_jahit_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="penambahan_waktu" class="block text-gray-700 text-sm font-bold mb-2">Penambahan Waktu Senggang (Menit):</label>
                            <div id="penambahan_waktu"></div>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_mulai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai:</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_selesai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai:</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah_pekerja" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Pekerja:</label>
                            <input type="number" id="jumlah_pekerja" name="jumlah_pekerja" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="1" value="{{ old('jumlah_pekerja', 1) }}">
                            @error('jumlah_pekerja')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="mb-4">
                            <label for="karyawan_terpilih" class="block text-gray-700 text-sm font-bold mb-2">Oprator yang dapat mengerjakan:</label>
                            <ul id="karyawan-terpilih"></ul>
                        </div>


                        <div class="mb-4">
                            <label for="total_smv" class="block text-gray-700 text-sm font-bold mb-2">Total SMV (Menit):</label>
                            <div id="total_smv"></div>
                        </div>

                        <div class="mb-4">
                            <label for="hasil_per_jam" class="block text-gray-700 text-sm font-bold mb-2">Hasil per 1 Jam (Pcs):</label>
                            <div id="hasil_per_jam"></div>
                        </div>

                        <div class="mb-4">
                            <label for="hasil_per_8_jam" class="block text-gray-700 text-sm font-bold mb-2">Hasil per 8 Jam (Pcs):</label>
                            <div id="hasil_per_8_jam"></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>

    const smvInput = document.getElementById('smv');
    const penambahanWaktuDiv = document.getElementById('penambahan_waktu');
    const jumlahPekerjaInput = document.getElementById('jumlah_pekerja');
    const karyawanTerpilihList = document.getElementById('karyawan-terpilih');
    const totalSmvDiv = document.getElementById('total_smv');
    const hasilPerJamDiv = document.getElementById('hasil_per_jam');
    const hasilPer8JamDiv = document.getElementById('hasil_per_8_jam');
    const produkSelect = document.getElementById('produk_id');
    const tanggalMulaiInput = document.getElementById('tanggal_mulai');
    const tanggalSelesaiInput = document.getElementById('tanggal_selesai');

    // Event listener
    smvInput.addEventListener('input', updateHasil);
    jumlahPekerjaInput.addEventListener('input', updateKaryawanTerpilih);
    jumlahPekerjaInput.addEventListener('input', updateHasil);
    tanggalMulaiInput.addEventListener('input', updateKaryawanTerpilih);
    tanggalSelesaiInput.addEventListener('input', updateKaryawanTerpilih);

    function updateHasil() {
        const smv = parseFloat(smvInput.value) || 0;
        const jumlahPekerja = parseInt(jumlahPekerjaInput.value) || 0;
        const tanggalMulai = tanggalMulaiInput.value;

        if (smv > 0 && jumlahPekerja > 0) {
            const penambahanWaktu = smv * 1.2;
            const totalSmv = 3600 / penambahanWaktu;
            const hasilPerJam = 1 * totalSmv * jumlahPekerja;
            const hasilPer8Jam = hasilPerJam * 8;


            penambahanWaktuDiv.textContent = penambahanWaktu.toFixed(2) + ' menit';
            totalSmvDiv.textContent = totalSmv.toFixed(2) + ' menit';
            hasilPerJamDiv.textContent = hasilPerJam.toFixed(2);
            hasilPer8JamDiv.textContent = hasilPer8Jam.toFixed(2);
        } else {
            penambahanWaktuDiv.textContent = '-';
            totalSmvDiv.textContent = '-';
            hasilPerJamDiv.textContent = '-';
            hasilPer8JamDiv.textContent = '-';
        }
    }

    produkSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const smv = selectedOption.dataset.smv;

        if (smv) {
            smvInput.value = smv; // Set nilai SMV pada input field
        } else {
            smvInput.value = ''; // Kosongkan input field jika tidak ada SMV
        }
        updateKaryawanTerpilih(); // Panggil fungsi untuk update karyawan terpilih juga
        updateHasil();
    });



    function updateKaryawanTerpilih() {
        const jumlahPekerja = parseInt(jumlahPekerjaInput.value) || 0;
        const produkId = produkSelect.value;
        const tanggalMulai = tanggalMulaiInput.value;
        const tanggalSelesai = tanggalSelesaiInput.value;

    fetch(`/checkkar/karyawan-cocok?produk_id=${produkId}&jumlah_pekerja=${jumlahPekerja}&tanggal_mulai=${tanggalMulai}&tanggal_selesai=${tanggalSelesai}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            karyawanTerpilihList.innerHTML = '';

            if (data.length > 0) {
                // Filter karyawan yang cocok berdasarkan jumlah pekerja
                const karyawanTerpilih = data.slice(0, jumlahPekerja);

                karyawanTerpilih.forEach(karyawan => {
                const li = document.createElement('li');
                li.textContent = `${karyawan.nama} (Estimasi Selesai: ${karyawan.estimasi_selesai})`;

                // Buat input tersembunyi untuk menyimpan karyawan_id
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'karyawan_id[]'; // Nama input harus berupa array
                hiddenInput.value = karyawan.id;

                li.appendChild(hiddenInput); // Tambahkan input ke elemen li
                karyawanTerpilihList.appendChild(li);
            });


                // Jika jumlah karyawan yang cocok kurang dari jumlah yang dibutuhkan, tampilkan pesan
                if (data.length < jumlahPekerja) {
                    const li = document.createElement('li');
                    li.textContent = `Hanya ${data.length} karyawan yang cocok.`;
                    karyawanTerpilihList.appendChild(li);
                }
            } else {
                const li = document.createElement('li');
                li.textContent = 'Tidak ada karyawan yang cocok';
                karyawanTerpilihList.appendChild(li);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            karyawanTerpilihList.innerHTML = '<li>Error fetching karyawan data.</li>';
        });
    }


    produkSelect.addEventListener('change', updateKaryawanTerpilih);
</script>

