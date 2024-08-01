<?php
namespace App\Http\Controllers;

use App\Models\KinerjaProduksi;
use App\Models\Produksi;
use App\Models\Produk;
use App\Models\Karyawan;
use App\Models\MesinJahit; // Pastikan Anda memiliki model ini
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use League\Csv\Writer;

class LaporanKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
    $tanggalSelesai = $request->input('tanggal_selesai');

    $laporanKinerja = $this->generateLaporanKinerja($tanggalMulai, $tanggalSelesai);

    // Pass the $tanggalMulai and $tanggalSelesai variables to the view
    return view('laporan_kinerja.index', compact('laporanKinerja', 'tanggalMulai', 'tanggalSelesai'));
    }

    private function generateLaporanKinerja($tanggalMulai = null, $tanggalSelesai = null)
    {
        $laporanKinerja = [];

        // Ambil semua produk
        $produk = Produk::all();

        foreach ($produk as $produk) {
            // Ambil data produksi untuk produk ini
            // $produksiProduk = Produksi::where('produk_id', $produk->id)->get();
            $query = Produksi::where('produk_id', $produk->id);
            if ($tanggalMulai) {
                $query->where('tanggal', '>=', $tanggalMulai);
            }
            if ($tanggalSelesai) {
                $query->where('tanggal', '<=', $tanggalSelesai);
            }
            $produksiProduk = $query->get();
            // Inisialisasi data kinerja untuk produk ini
            $kinerja = new KinerjaProduksi();
            $kinerja->produk = $produk;
            $kinerja->totalProduksi = $produksiProduk->count();
            $kinerja->rataRataSmv = $produksiProduk->avg('smv');
            $kinerja->rataRataJumlahPekerja = $produksiProduk->avg('jumlah_pekerja');

            // Hitung total waktu produksi dan rata-rata waktu produksi per produk
            $totalWaktuProduksi = 0;
            foreach ($produksiProduk as $produksi) {
                $waktuMulai = Carbon::parse($produksi->waktu_mulai);
                $waktuSelesai = Carbon::parse($produksi->waktu_selesai);
                $totalWaktuProduksi += $waktuMulai->diffInDays($waktuSelesai) + 1; // Tambahkan 1 hari untuk hari pertama
            }
            $kinerja->rataRataWaktuProduksi = $produksiProduk->isNotEmpty() ? ($totalWaktuProduksi / $produksiProduk->count()) : 0;

            // Ambil data karyawan yang terlibat dalam produksi produk ini (opsional)
            $karyawanIds = $produksiProduk->pluck('karyawan_id')->flatten()->unique();
            $kinerja->karyawanTerlibat = Karyawan::whereIn('id', $karyawanIds)->get();

            // Ambil data mesin jahit yang digunakan dalam produksi produk ini (opsional)
            $mesinJahitIds = $produksiProduk->pluck('mesin_jahit_id')->flatten()->unique(); // Sesuaikan dengan nama kolom di tabel produksi
            $kinerja->mesinJahitDigunakan = MesinJahit::whereIn('id', $mesinJahitIds)->get();

            $laporanKinerja[] = $kinerja;
        }

        return $laporanKinerja;
    }
    public function download(Request $request)
{
    $tanggalMulai = $request->input('tanggal_mulai');
    $tanggalSelesai = $request->input('tanggal_selesai');

    $laporanKinerja = $this->generateLaporanKinerja($tanggalMulai, $tanggalSelesai);


    // Buat objek CSV writer
    $csv = Writer::createFromFileObject(new \SplTempFileObject());

    // Tulis header kolom
    $csv->insertOne(['Produk', 'Total Produksi', 'Rata-rata SMV', 'Rata-rata Jumlah Pekerja', 'Rata-rata Waktu Produksi (Hari)']);

    // Tulis data laporan kinerja
    foreach ($laporanKinerja as $kinerja) {
        $csv->insertOne([
            $kinerja->produk->nama,
            $kinerja->totalProduksi,
            $kinerja->rataRataSmv,
            $kinerja->rataRataJumlahPekerja,
            $kinerja->rataRataWaktuProduksi,
        ]);
    }

    // Kembalikan respons download CSV
    return response((string) $csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Transfer-Encoding' => 'binary',
        'Content-Disposition' => 'attachment; filename="laporan_kinerja_produksi.csv"',
    ]);
}
}
