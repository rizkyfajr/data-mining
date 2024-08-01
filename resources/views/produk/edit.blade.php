<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">Edit Produk</h1>

                    <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                            <input type="text" id="nama" name="nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('nama', $produk->nama) }}">
                        </div>

                        <div class="mb-4">
                            <label for="smv" class="block text-gray-700 text-sm font-bold mb-2">SMV:</label>
                            <input type="text" id="smv" name="smv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('smv', $produk->smv) }}">
                        </div>

                        <div class="mb-4">
                            <label for="target_produksi_harian" class="block text-gray-700 text-sm font-bold mb-2">Target Produksi Harian:</label>
                            <input type="text" id="target_produksi_harian" name="target_produksi_harian" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('target_produksi_harian', $produk->target_produksi_harian) }}">
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

