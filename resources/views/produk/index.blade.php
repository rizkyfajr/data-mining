<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-2xl font-semibold">Daftar Produk</h1>
                        <a href="{{ route('produk.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Produk
                        </a>
                    </div>

                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 bg-gray-200">Nama</th>
                                <th class="px-4 py-2 bg-gray-200">SMV</th>
                                <th class="px-4 py-2 bg-gray-200">Target Produksi Harian</th>
                                <th class="px-4 py-2 bg-gray-200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $p)
                                <tr>
                                    <td class="border px-4 py-2">{{ $p->nama }}</td>
                                    <td class="border px-4 py-2">{{ $p->smv }}</td>
                                    <td class="border px-4 py-2">{{ $p->target_produksi_harian }}</td>
                                    <td class="border px-4 py-2">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('produk.edit', $p->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">
                                                Edit
                                            </a>
                                            <form action="{{ route('produk.destroy', $p->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">
                                                    Hapus
                                                </button>
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

