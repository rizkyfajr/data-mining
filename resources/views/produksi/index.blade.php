<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Produksi') }}
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
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-2xl font-semibold">Daftar Produksi</h1>
                        <a href="{{ route('produksi.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Produksi
                        </a>
                    </div>

                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 bg-gray-200">Kode Produksi</th>
                                <th class="px-4 py-2 bg-gray-200">Karyawan</th>
                                <th class="px-4 py-2 bg-gray-200">Produk</th>
                                <th class="px-4 py-2 bg-gray-200">Jumlah Karyawan</th>
                                <th class="px-4 py-2 bg-gray-200">SMV</th>
                                <th class="px-4 py-2 bg-gray-200">Tanggal</th>
                                <th class="px-4 py-2 bg-gray-200">Waktu Mulai</th>
                                <th class="px-4 py-2 bg-gray-200">Waktu Selesai</th>
                                <th class="px-4 py-2 bg-gray-200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produksi as $p)
                                <tr>
                                    <td class="border px-4 py-2">{{ $p->kode_produksi }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($p->karyawan_id)
                                            @php
                                                $karyawanIds = explode(',', $p->karyawan_id); // Split into array
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
                                    </td>

                                    <td class="border px-4 py-2">{{ $p->produk->nama }}</td>
                                    <td class="border px-4 py-2">{{ $p->jumlah_pekerja }}</td>
                                    <td class="border px-4 py-2">{{ $p->smv }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($p->waktu_mulai)->format('d-m-Y') }}</td>
                                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($p->waktu_selesai)->format('d-m-Y') }}</td>
                                    <td class="border px-4 py-2">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('produksi.edit', $p->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">Edit</a>
                                            <a href="{{ route('produksi.show', $p->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs">Detail</a>
                                            <form action="{{ route('produksi.destroy', $p->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">Hapus</button>

                                            </form>
                                        </div>
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

