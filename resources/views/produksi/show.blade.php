<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Produksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-2xl font-semibold mb-4 text-center">Detail Produksi #{{ $produksi->id }}</h1>
        <hr>
        <br>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Produk:</label>
                            <p class="text-lg">{{ $produksi->produk->nama }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">SMV (Menit):</label>
                            <p class="text-lg">{{ $produksi->smv }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai:</label>
                            <p class="text-lg">{{ \Carbon\Carbon::parse($produksi->waktu_mulai)->format('d-m-Y') }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai:</label>
                            <p class="text-lg">{{ \Carbon\Carbon::parse($produksi->waktu_selesai)->format('d-m-Y') }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Pekerja:</label>
                            <p class="text-lg">{{ $produksi->jumlah_pekerja }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Total SMV (Menit):</label>
                            <p class="text-lg">{{ $produksi->total_smv }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Hasil per 1 Jam (Pcs):</label>
                            <p class="text-lg">{{ $produksi->hasil_per_jam }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Hasil per 8 Jam (Pcs):</label>
                            <p class="text-lg">{{ $produksi->hasil_per_8_jam }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Hasil per 8 Jam (Pcs):</label>
                            <p class="text-lg">
                                @if ($produksi->mesinJahit)
                                    {{ $produksi->mesinJahit->nama_mesin }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                    </div>
                </div>

                <hr>
                <div class="mt-6">
                    <h2 class="font-semibold text-lg mb-2">Karyawan yang Terlibat:</h2>
                    <ul class="list-disc list-inside">
                        @if ($produksi->karyawan_id)
                                            @php
                                                $karyawanIds = explode(',', $produksi->karyawan_id); // Split into array
                                                $karyawanNames = [];
                                                foreach ($karyawanIds as $id) {
                                                    $karyawan = App\Models\Karyawan::find($id); // Find employee by ID
                                                    if ($karyawan) {
                                                        $karyawanNames[] = $karyawan->nama;
                                                    }
                                                }
                                            @endphp
                                            {{ implode(', ', $karyawanNames) }}
                                        @endif
                    </ul>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('produksi.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
