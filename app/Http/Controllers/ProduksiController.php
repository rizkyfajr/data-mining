<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Karyawan;
use App\Models\MesinJahit;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon; // Import the Carbon class
//library KNN
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Transformers\OneHotEncoder;
use Rubix\ML\Transformers\MinMaxNormalizer; // Import the MinMaxNormalizer class
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Kernels\Distance\Euclidean;
use Rubix\ML\Pipeline;


class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produksi = Produksi::with('karyawan', 'produk')->get();
        return view('produksi.index', compact('produksi'));
    }

    public function create()
    {
        $karyawan = Karyawan::all();
        $produk = Produk::all();
        $mesinJahit = MesinJahit::all();
        return view('produksi.create', compact('karyawan', 'produk', 'mesinJahit'));
    }



    public function store(Request $request){
        try {
            $validatedData = $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'smv' => 'required|numeric|min:0',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'jumlah_pekerja' => 'required|integer|min:1',
                'karyawan_id' => 'required|array',
                'karyawan_id.*' => 'exists:karyawan,id',
                'mesin_jahit_id' => 'required|exists:mesin_jahit,id',
            ]);

            $produk = Produk::find($validatedData['produk_id']);

            // Generate kode produksi
            $tanggal = Carbon::parse($validatedData['tanggal_mulai'])->format('Ymd'); // Format tanggal
            $nomorUrut = Produksi::where('tanggal', $validatedData['tanggal_mulai'])->count() + 1; // Nomor urut produksi pada tanggal tersebut
            $kodeProduksi = "PROD-{$tanggal}-{$nomorUrut}";

            if (!$produk) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            $jumlahPekerja = count($validatedData['karyawan_id']);

            // 1. Persiapan Data untuk KNN
            $dataset = $this->siapkanDatasetKNN();

              // 2. Latih Model KNN (jika dataset tersedia)
        if ($dataset !== null) {
            $estimator = new Pipeline([
                $this->encoder,
                new MinMaxNormalizer(),
            ], new KNearestNeighbors(3));

            $estimator->train($dataset);

            // 3. Prediksi Waktu Selesai
            $input = [
                'smv' => $validatedData['smv'],
                'jumlah_pekerja' => $validatedData['jumlah_pekerja'],
                'produk_id' => $validatedData['produk_id'],
            ];

            // One-Hot Encode dan Normalisasi input baru menggunakan Pipeline
            $inputDataset = new Unlabeled([$input]);
            $transformedInput = $estimator->transform($inputDataset);

            try {
                $predictedWaktuSelesai = $estimator->predict($transformedInput)[0];
            } catch (\Throwable $e) {
                Log::error('Error prediksi KNN:', ['exception' => $e]);
                return back()->with('error', 'Terjadi kesalahan saat memprediksi waktu selesai. Error: ' . $e->getMessage());
            }
        } else {
            // *** Penanganan jika dataset null (tidak ada data historis) ***


            $tanggalMulaiCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['tanggal_mulai']);
            $waktuPerPekerja = $validatedData['smv'] * $produk->target_produksi_harian / 3600;
            $totalWaktu = $waktuPerPekerja * $validatedData['jumlah_pekerja'];

            $predictedWaktuSelesai = $tanggalMulaiCarbon->copy()->addHours($totalWaktu)->format('Y-m-d');


        }
                // 4. Simpan Data Produksi dengan Prediksi
                $produksi = Produksi::create([
                    'produk_id' => $validatedData['produk_id'],
                    'smv' => $validatedData['smv'],
                    'tanggal' => $validatedData['tanggal_mulai'],
                    'jumlah_pekerja' => $validatedData['jumlah_pekerja'],
                    'waktu_mulai' => $validatedData['tanggal_mulai'],
                    'waktu_selesai' => $predictedWaktuSelesai,
                    'karyawan_id' => implode(',', $validatedData['karyawan_id']),
                    'kode_produksi' => $kodeProduksi,
                    'mesin_jahit_id' => $validatedData['mesin_jahit_id'],
                ]);

                foreach ($validatedData['karyawan_id'] as $karyawanId) {
                    $karyawan = Karyawan::find($karyawanId);
                    $estimasiSelesai = $this->hitungEstimasiSelesai(
                        $karyawan,
                        $produk,
                        $jumlahPekerja,
                        $validatedData['tanggal_mulai'],
                        $validatedData['tanggal_selesai']
                    );
                }

                return redirect()->route('produksi.index')->with('success', 'Data produksi berhasil ditambahkan.');
            }
        catch (\Throwable $e) {
            Log::error('Error menyimpan data produksi:', ['exception' => $e]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data produksi. Error: ' . $e->getMessage());
        }

    return redirect()->back()->with('error', 'Tidak ada data historis yang cukup untuk prediksi.' );
}




    private function siapkanDatasetKNN()
    {
         // 1. Ambil Data Produksi Historis
    $produksiHistorics = Produksi::all();

    // Periksa apakah ada data historis yang cukup
    if ($produksiHistorics->isEmpty()) {
        $produksiHistorics = collect([
            (object) [
                'smv' => 245.00,
                'jumlah_pekerja' => 1,
                'produk_id' => 2,
                'waktu_selesai' => now()->addDays(7)->format('Y-m-d') // Contoh waktu selesai dummy
            ]
            // ... tambahkan data dummy lainnya sesuai kebutuhan
        ]);
    }

    // Ambil nama kolom dari tabel produksi
    $columnNames = \Schema::getColumnListing('produksi');

    // 2. Persiapkan Data
    $samples = [];
    $labels = [];

    foreach ($produksiHistorics as $produksi) {
        $sample = [];
        foreach ($columnNames as $columnName) {
            // Abaikan kolom ID, created_at, updated_at, dan waktu_selesai (karena ini adalah label)
            if (!in_array($columnName, ['id', 'created_at', 'updated_at', 'waktu_selesai'])) {
                // Konversi nilai ke float jika memungkinkan
                $sample[$columnName] = is_numeric($produksi->$columnName) ? floatval($produksi->$columnName) : $produksi->$columnName;
            }
        }
        $samples[] = $sample;
        $labels[] = strtotime($produksi->waktu_selesai); // Konversi waktu_selesai ke timestamp
    }

    // 3. Buat Dataset
    try {
        $dataset = new Labeled($samples, $labels);
    } catch (\InvalidArgumentException $e) {
        // Tangani error jika dataset tidak valid (misalnya, ada nilai yang tidak sesuai)
        Log::error('Error membuat dataset:', ['exception' => $e]);
        return null;
    }

    // 4. One-Hot Encoding
    $this->encoder = new OneHotEncoder();
    $this->encoder->fit($dataset);
    $samples = $dataset->samples();

    try {
        $encodedSamples = $this->encoder->transform($samples);
    } catch (\Throwable $e) {
        Log::error('OneHotEncoder error:', ['exception' => $e]);
        return response()->json(['error' => 'Gagal melakukan one-hot encoding'], 500);
    }

    // 5. Normalisasi
    $normalizer = new MinMaxNormalizer();
    $unlabeledDataset = new Unlabeled($encodedSamples);
    $normalizer->fit($unlabeledDataset);
    $normalizedSamples = $normalizer->transform($encodedSamples);

    // Buat dataset baru dengan sample yang sudah di-encode dan dinormalisasi
    $dataset = new Labeled($normalizedSamples, $labels);

    return $dataset;
    }


    public function getKaryawanCocok(Request $request)
    {
        $produkId = $request->input('produk_id');
        $jumlahPekerja = $request->input('jumlah_pekerja', 1);
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $produk = Produk::find($produkId);

        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $request->validate([
            'tanggal_mulai' => 'required|date_format:Y-m-d',
        ]);

        $tanggalMulaiCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalMulai);
        $tanggalSelesaiCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalSelesai);

        // Ambil semua spesialisasi yang dibutuhkan
        $spesialisasiDiperlukan = ['Penjahit', 'Pemotong', 'Quality Control'];
        // $spesialisasiDiperlukan = ['Penjahit'];

        // Inisialisasi array untuk menyimpan karyawan terpilih
        $karyawanTerpilih = [];

        foreach ($spesialisasiDiperlukan as $spesialisasi) {
            // Cari karyawan yang cocok dengan spesialisasi ini dan memiliki beban kerja paling sedikit
            $karyawan = Karyawan::where('spesialisasi', $spesialisasi)
                ->whereDoesntHave('produksi', function ($query) use ($tanggalMulaiCarbon) {
                    $query->where('tanggal', $tanggalMulaiCarbon);
                })
                ->orderBy('produksi_count', 'asc') // Prioritaskan beban kerja sedikit
                ->orderBy('smv', 'asc') // Prioritaskan SMV rendah
                ->limit($jumlahPekerja)
                ->first();

            if ($karyawan) {
                $karyawanTerpilih[] = $karyawan;
            } else {
                // Jika tidak ada karyawan yang cocok, tambahkan pesan error
                return response()->json(['error' => 'Tidak ada karyawan yang cocok untuk spesialisasi ' . $spesialisasi], 404);
            }
        }

        // Jika jumlah karyawan terpilih kurang dari jumlah yang dibutuhkan, tambahkan pesan error
        if (count($karyawanTerpilih) < $jumlahPekerja) {
            return response()->json(['error' => 'Jumlah karyawan yang cocok tidak mencukupi'], 404);
        }

        // Hitung estimasi tanggal selesai untuk setiap karyawan terpilih
        foreach ($karyawanTerpilih as $karyawan) {
            $karyawan->estimasi_selesai = $this->hitungEstimasiSelesai($karyawan, $produk, $jumlahPekerja, $tanggalMulai, $tanggalSelesai);
        }

        return response()->json($karyawanTerpilih);

    }



    private function hitungEstimasiSelesai($karyawan, $produk, $jumlahPekerja, $tanggalMulai, $tanggalSelesai)
    {
        // Validasi tanggal
        $tanggalMulaiCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalMulai);
        $tanggalSelesaiCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalSelesai);

         // Hitung total waktu produksi dalam menit
        $totalWaktuProduksi = $produk->smv * 1.20 * $produk->target_produksi_harian;

        // Hitung waktu produksi per pekerja dalam menit
        $waktuPerPekerja = $totalWaktuProduksi / $jumlahPekerja;

        // Inisialisasi tanggal estimasi selesai dengan tanggal mulai
        $estimasiSelesai = $tanggalMulaiCarbon->copy();

        // Tambahkan waktu produksi per pekerja untuk setiap pekerja
        for ($i = 0; $i < $jumlahPekerja; $i++) {
            $estimasiSelesai->addMinutes($waktuPerPekerja);

            // Jika estimasi selesai melebihi tanggal selesai yang ditentukan, hentikan perhitungan
            if ($estimasiSelesai->gt($tanggalSelesaiCarbon)) {
                break;
            }
        }

        return $estimasiSelesai->format('Y-m-d');
    }




    public function estimasiWaktu(Request $request)
    {
        // ... (logika perhitungan estimasi waktu)
    }
    /**
     * Display the specified resource.
     */
    public function show(produksi $produksi)
    {
        $produksi->load('mesinJahit');
        $produksi->total_smv = 3600 / ($produksi->smv * 1.2);
        $produksi->hasil_per_jam = 1 * $produksi->total_smv * $produksi->jumlah_pekerja;
        $produksi->hasil_per_8_jam = $produksi->hasil_per_jam * 8;

        // Kirim data produksi ke view
        return view('produksi.show', compact('produksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(produksi $produksi)
    {
        $produk = Produk::all();
        $mesinJahit = MesinJahit::all();

        // Pastikan data karyawan_id di-decode menjadi array sebelum dikirim ke view
        $produksi->karyawan_id = json_decode($produksi->karyawan_id, true);
        return view('produksi.edit', compact('produksi', 'produk', 'mesinJahit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produksi $produksi)
    {
        try {
            $validatedData = $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'smv' => 'required|numeric|min:0',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'jumlah_pekerja' => 'required|integer|min:1',
                'mesin_jahit_id' => 'required|exists:mesin_jahit,id',
                'karyawan_id' => 'required|array',
                'karyawan_id.*' => 'exists:karyawan,id',
            ]);

            $produk = Produk::find($validatedData['produk_id']);

            if (!$produk) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            $jumlahPekerja = count($validatedData['karyawan_id']);

            // 1. Persiapan Data untuk KNN
            $dataset = $this->siapkanDatasetKNN();

            // 2. Latih Model KNN (jika dataset tersedia)
            if ($dataset !== null) {
                $estimator = new Pipeline([
                    $this->encoder,
                    new MinMaxNormalizer(),
                ], new KNearestNeighbors(3));

                $estimator->train($dataset);

                // 3. Prediksi Waktu Selesai
                $input = [
                    'smv' => $validatedData['smv'],
                    'jumlah_pekerja' => $validatedData['jumlah_pekerja'],
                    'produk_id' => $validatedData['produk_id'],
                ];

                // One-Hot Encode dan Normalisasi input baru menggunakan Pipeline
                $inputDataset = new Unlabeled([$input]);
                $transformedInput = $estimator->transform($inputDataset);

                try {
                    $predictedWaktuSelesai = $estimator->predict($transformedInput)[0];
                } catch (\Throwable $e) {
                    Log::error('Error prediksi KNN:', ['exception' => $e]);
                    return back()->with('error', 'Terjadi kesalahan saat memprediksi waktu selesai. Error: ' . $e->getMessage());
                }
            } else {
                // *** Penanganan jika dataset null (tidak ada data historis) ***
                // Pilih salah satu opsi di bawah ini:

                $tanggalMulaiCarbon = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['tanggal_mulai']);
                $waktuPerPekerja = $validatedData['smv'] * $produk->target_produksi_harian / 3600;
                $totalWaktu = $waktuPerPekerja * $validatedData['jumlah_pekerja'];

                $predictedWaktuSelesai = $tanggalMulaiCarbon->copy()->addHours($totalWaktu)->format('Y-m-d');


            }

            // 3. Update Data Produksi
            $produksi->update([
                'produk_id' => $validatedData['produk_id'],
                'smv' => $validatedData['smv'],
                'tanggal' => $validatedData['tanggal_mulai'],
                'jumlah_pekerja' => $validatedData['jumlah_pekerja'],
                'waktu_mulai' => $validatedData['tanggal_mulai'],
                'waktu_selesai' => $predictedWaktuSelesai ?? $validatedData['tanggal_selesai'],
                'mesin_jahit_id' => $validatedData['mesin_jahit_id'],
                'karyawan_id' => json_encode($validatedData['karyawan_id']), // Simpan sebagai JSON
            ]);

            foreach ($validatedData['karyawan_id'] as $karyawanId) {
                $karyawan = Karyawan::find($karyawanId);
                $estimasiSelesai = $this->hitungEstimasiSelesai(
                    $karyawan,
                    $produk,
                    $jumlahPekerja,
                    $validatedData['tanggal_mulai'],
                    $validatedData['tanggal_selesai']
                );
            }

            return redirect()->route('produksi.index')->with('success', 'Data produksi berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Error mengupdate data produksi:', ['exception' => $e]);
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data produksi. Error: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(produksi $produksi)
    {
        $produksi->delete();

        // Redirect ke halaman daftar produksi dengan pesan sukses
        return redirect()->route('produksi.index')->with('success', 'Data produksi berhasil dihapus.');
    }
}
