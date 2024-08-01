<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Karyawan') }}
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
                    <h1 class="text-2xl font-semibold mb-4">Tambah Karyawan</h1>
                    <form action="{{ route('karyawan.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                            <input type="text" id="nama" name="nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="spesialisasi" class="block text-gray-700 text-sm font-bold mb-2">Spesialisasi:</label>
                            <select id="spesialisasi" name="spesialisasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Penjahit">Penjahit</option>
                                <option value="Pemotong">Pemotong</option>
                                <option value="Quality Control">Quality Control</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tingkat_keahlian" class="block text-gray-700 text-sm font-bold mb-2">Tingkat Keahlian:</label>
                            <select id="tingkat_keahlian" name="tingkat_keahlian" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Junior">Junior</option>
                                <option value="Menengah">Menengah</option>
                                <option value="Senior">Senior</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="gaji" class="block text-gray-700 text-sm font-bold mb-2">Gaji:</label>
                            <input type="text" id="gaji" name="gaji" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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
