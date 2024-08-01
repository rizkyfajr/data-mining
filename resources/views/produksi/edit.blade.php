<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produksi') }}
        </h2>
    </x-slot>

    {{-- ... (pesan sukses dan error) ... --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6  
 text-gray-900">
                    <h1 class="text-2xl font-semibold  
 mb-4">Edit Produksi</h1>

                    <form action="{{ route('produksi.update', $produksi->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="produk_id" class="block text-gray-700 text-sm font-bold mb-2">Produk:</label>
                            <select id="produk_id" name="produk_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option  
 value="">Pilih Produk</option>
                                @foreach($produk as $item)
                                    <option value="{{ $item->id }}" data-smv="{{ $item->smv }}" {{ old('produk_id', $produksi->produk_id) == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('produk_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="smv" class="block text-gray-700 text-sm font-bold mb-2">Waktu Standar SMV (Menit):</label>
                            <input type="number" id="smv" name="smv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
 min="0" step="0.01" value="{{ old('smv', $produksi->smv) }}" readonly>
                            @error('smv')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="mesin_jahit_id" class="block text-gray-700 text-sm font-bold mb-2">Mesin Jahit:</label>
                            <select id="mesin_jahit_id" name="mesin_jahit_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option  
 value="">Pilih Mesin Jahit</option>
                                @foreach($mesinJahit as $mesin)
                                    <option value="{{ $mesin->id }}" {{ old('mesin_jahit_id', $produksi->mesin_jahit_id) == $mesin->id ? 'selected' : '' }}>{{ $mesin->nama_mesin }}</option>
                                @endforeach
                            </select>
                            @error('mesin_jahit_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="penambahan_waktu" class="block text-gray-700 text-sm font-bold mb-2">Penambahan Waktu Senggang (Menit):</label>
                            <div id="penambahan_waktu">{{ $produksi->smv * 1.2 }}</div>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_mulai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai:</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
 value="{{ old('tanggal_mulai', $produksi->tanggal_mulai) }}">
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_selesai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai:</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
 value="{{ old('tanggal_selesai', $produksi->tanggal_selesai) }}">
                            @error('tanggal_selesai')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah_pekerja" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Pekerja:</label>
                            <input type="number" id="jumlah_pekerja" name="jumlah_pekerja" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
 min="1" value="{{ old('jumlah_pekerja', $produksi->jumlah_pekerja) }}">
                            @error('jumlah_pekerja')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="karyawan_terpilih" class="block text-gray-700 text-sm font-bold mb-2">Oprator yang dapat mengerjakan:</label>
                            <ul id="karyawan-terpilih">
                                @php
                                    $karyawanIds = is_array($produksi->karyawan_id) ? $produksi->karyawan_id : [$produksi->karyawan_id];
                                @endphp

                                @foreach($karyawanIds as $karyawanId)
                                    <li>
                                        {{ \App\Models\Karyawan::find($karyawanId)->nama }}
                                        <input type="hidden" name="karyawan_id[]" value="{{ $karyawanId }}">
                                    </li>
                                @endforeach

                            </ul>
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
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold  
 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update
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

