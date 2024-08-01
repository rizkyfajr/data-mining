<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kinerja Produksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-2xl font-semibold mb-4 text-center">Laporan Kinerja Produksi</h1>

                <form action="{{ route('laporan-kinerja.download') }}" method="GET" class="mb-4">
                    <div class="flex space-x-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai:</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="mt-1 p-2 border rounded-md shadow-sm focus:ring focus:ring-opacity-50" value="{{ $tanggalMulai }}">
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai:</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="mt-1 p-2 border rounded-md shadow-sm focus:ring focus:ring-opacity-50" value="{{ $tanggalSelesai }}">
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-1">Filter</button>
                        </div>
                        <div>
                            <a href="{{ route('laporan-kinerja.download', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-1">
                                Unduh Laporan CSV
                            </a>
                        </div>
                    </div>
                </form>

                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 bg-gray-200">Produk</th>
                            <th class="px-4 py-2 bg-gray-200">Total Produksi</th>
                            <th class="px-4 py-2 bg-gray-200">Rata-rata SMV</th>
                            <th class="px-4 py-2 bg-gray-200">Rata-rata Jumlah Pekerja</th>
                            <th class="px-4 py-2 bg-gray-200">Rata-rata Waktu Produksi (Hari)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanKinerja as $kinerja)
                            <tr>
                                <td class="border px-4 py-2">{{ $kinerja->produk->nama }}</td>
                                <td class="border px-4 py-2">{{ $kinerja->totalProduksi }}</td>
                                <td class="border px-4 py-2">{{ $kinerja->rataRataSmv }}</td>
                                <td class="border px-4 py-2">{{ $kinerja->rataRataJumlahPekerja }}</td>
                                <td class="border px-4 py-2">{{ $kinerja->rataRataWaktuProduksi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
