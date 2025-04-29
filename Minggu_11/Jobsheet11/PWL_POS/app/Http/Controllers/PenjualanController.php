<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PenjualanController extends Controller
{
    public function index()
    {
        $activeMenu = 'penjualan';
        $breadcrumb = (object) [
            'title' => 'Data Transaksi Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        return view('penjualan.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb
        ]);
    }

    public function list(Request $request)
    {
        try {
            $penjualan = PenjualanModel::with(['user', 'PenjualanDetail'])
                ->select('t_penjualan.*');

            return DataTables::eloquent($penjualan)
                ->addIndexColumn()
                ->addColumn('penjualan_kode', fn($s) => $s->penjualan_kode)
                ->addColumn('pembeli', fn($s) => $s->pembeli)
                ->addColumn('penjualan_tanggal', function($s) {
                    return $s->penjualan_tanggal->format('d-m-Y H:i');
                })
                ->addColumn('total', function ($s) {
                    return 'Rp ' . number_format(
                        $s->PenjualanDetail->sum(fn($detail) => $detail->harga * $detail->jumlah),
                        0,
                        ',',
                        '.'
                    );
                })
                ->addColumn('nama', fn($s) => $s->user->nama ?? 'System')
                ->addColumn('aksi', function ($s) {
                    return '<div class="text-center">' .
                        '<button onclick="showDetail(\'' . url('/penjualan/'. $s->penjualan_id . '/show_ajax').'\')" class="btn btn-sm btn-info mr-1">Detail</button>' .
                        '<button onclick="modalAction(\'' . url('/penjualan/'. $s->penjualan_id . '/delete_ajax').'\')" class="btn btn-sm btn-danger">Hapus</button>' .
                        '</div>';
                })
                ->rawColumns(['aksi'])
                ->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show_ajax($id)
    {
        $penjualan = PenjualanModel::with(['PenjualanDetail.barang'])->findOrFail($id);
        return view('penjualan.show_ajax', compact('penjualan'));
    }

    public function create_ajax()
    {
        $barang = BarangModel::with(['stok'])
            ->whereHas('stok', function ($query) {
                $query->where('stok_jumlah', '>', 0);
            })
            ->select('barang_id', 'barang_nama', 'harga_jual')
            ->get();

        return view('penjualan.create_ajax', ['barang' => $barang]);
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembeli' => 'required|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:m_barang,barang_id',
            'items.*.jumlah' => 'required|integer|min:1'
        ], [
            'pembeli.required' => 'Nama pembeli wajib diisi',
            'items.required' => 'Minimal 1 barang harus dipilih',
            'items.*.jumlah.min' => 'Jumlah minimal 1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate kode transaksi
            $lastId = PenjualanModel::max('penjualan_id') ?? 0;
            $penjualan_kode = 'DIRD' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            // Simpan header transaksi
            $penjualan = PenjualanModel::create([
                'user_id' => auth()->user()->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $penjualan_kode,
                'penjualan_tanggal' => now()->setTimezone('Asia/Jakarta'),
            ]);

            // Simpan item dan update stok
            foreach ($request->items as $item) {
                $barang = BarangModel::with('stok')->findOrFail($item['barang_id']);

                // Validasi stok
                if (!$barang->stok || $barang->stok->stok_jumlah < $item['jumlah']) {
                    throw new \Exception("Stok {$barang->barang_nama} tidak mencukupi!");
                }

                // Simpan detail
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $item['barang_id'],
                    'harga' => $barang->harga_jual,
                    'jumlah' => $item['jumlah']
                ]);

                // Update stok
                $barang->stok->update([
                    'stok_jumlah' => $barang->stok->stok_jumlah - $item['jumlah'],
                    'user_id' => auth()->user()->user_id
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan',
                'penjualan_id' => $penjualan->penjualan_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirm_ajax($id)
    {
        $penjualan = PenjualanModel::with(['user', 'PenjualanDetail'])->find($id);
        return view('penjualan.confirm_ajax', compact('penjualan'));
    }

    public function delete_ajax(Request $request, $id)
    {
        try {
            $penjualan = PenjualanModel::with('PenjualanDetail')->findOrFail($id);

            // Mengembalikkan jumlah stok
            // foreach ($penjualan->PenjualanDetail as $detail) {
            //     $stok = StokModel::where('barang_id', $detail->barang_id)->first();
            //     if ($stok) {
            //         $stok->update([
            //             'stok_jumlah' => $stok->stok_jumlah + $detail->jumlah,
            //             'user_id' => auth()->user()->user_id
            //         ]);
            //     }
            // }

            // Hapus detail transaksi
            PenjualanDetailModel::where('penjualan_id', $id)->delete();

            // Hapus transaksi
            $penjualan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export_excel()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
                                  ->orderBy('penjualan_kode')
                                  ->with('user')
                                  ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Tanggal Penjualan');
        $sheet->setCellValue('E1', 'Kasir');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($penjualan as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->penjualan_kode);
            $sheet->setCellValue('C'.$baris, $value->pembeli);
            $sheet->setCellValue('D'.$baris, $value->penjualan_tanggal);
            $sheet->setCellValue('E'.$baris, $value->user->nama);
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan '.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
}