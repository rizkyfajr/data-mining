<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
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
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-4">Dashboard Produksi</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h2 class="text-lg font-semibold mb-2">Produksi Terbaru</h2>
                            <table class="w-full table-auto">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Produk</th>
                                        <th class="px-4 py-2">Tanggal</th>
                                        <th class="px-4 py-2">SMV</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produksiTerbaru as $produksi)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $produksi->produk->nama }}</td>
                                        <td class="border px-4 py-2">{{ $produksi->tanggal }}</td>
                                        <td class="border px-4 py-2">{{ $produksi->smv }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold mb-2">Grafik Produksi</h2>
                            <div id="produksi-chart" style="height: 300px; border: 1px solid #ccc; border-radius: 5px; padding: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);"></div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    var options = {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'Total Produksi',
            data:  @json($dataGrafik->pluck('total_produksi')->toArray()) // Ubah menjadi array biasa
        }],
        xaxis: {
            type: 'datetime',
            categories: @json($dataGrafik->pluck('tanggal')->toArray()) // Ubah menjadi array biasa
        }
    };

    var chart = new ApexCharts(document.querySelector("#produksi-chart"), options);

    chart.render();
</script>
