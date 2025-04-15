<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class DetailpenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Detail Penjualan']
        ];

        $page = (object) [
            'title' => 'Detail Penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'detailPenjualan';

        $barang = BarangModel::select('barang_id', 'barang_nama')->get();

        return view('detail.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $details = PenjualanDetailModel::select('detail_id', 'penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->with('barang')
            ->with('penjualan');

        $barang_id = $request->input('filter_barang');
        if (!empty($barang_id)) {
            $details->where('barang_id', $barang_id);
        }
            
        return DataTables::of($details)
            ->addIndexColumn()
            ->addColumn('aksi', function ($detail) {
                $btn =  '<button onclick="modalAction(\''.url('/detailPenjualan/' . $detail->detail_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/detailPenjualan/' . $detail->detail_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/detailPenjualan/' . $detail->detail_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();

        return view('detailPenjualan.create_ajax')
            ->with('barang', $barang)
            ->with('penjualan', $penjualan);
    }
    public function store_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'harga'         => 'required|integer',
                'jumlah'        => 'required|integer',
                'barang_id'     => 'required|integer',
                'penjualan_id'  => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            PenjualanDetailModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }
        redirect('/');
    }
    public function show_ajax(string $id)
    {
        $detail = PenjualanDetailModel::select('detail_id', 'harga', 'jumlah', 'barang_id', 'penjualan_id')
            ->with('barang')
            ->with('penjualan')
            ->find($id);
            
        return view('detailPenjualan.show_ajax', ['detail' => $detail]);
    }
    public function edit_ajax(string $id)
    {
        $detail = PenjualanDetailModel::find($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();
        
        return view('detailPenjualan.edit_ajax', ['detail' => $detail, 'barang' => $barang, 'penjualan' => $penjualan]);
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'harga'         => 'required|integer',
                'jumlah'        => 'required|integer',
                'barang_id'     => 'required|integer',
                'penjualan_id'  => 'required|integer'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $check = PenjualanDetailModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $detail = PenjualanDetailModel::find($id);
        return view('detailPenjualan.confirm_ajax', ['detail' => $detail]);
    }
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $detail = PenjualanDetailModel::find($id);
            if ($detail) {
                $detail->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function import()
    {
        return view('detailPenjualan.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_detail_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_detail_penjualan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
    
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'penjualan_id' => $value['A'],
                            'barang_id'    => $value['B'],
                            'harga'        => $value['C'],
                            'jumlah'       => $value['D'],
                            'created_at'   => now(),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    PenjualanDetailModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                }
            }
    
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
    
        return redirect('/');
    }
    public function export_excel()
    {
        $detail = PenjualanDetailModel::select('penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->orderBy('penjualan_id')
            ->with('penjualan')
            ->with('barang')
            ->get();
    
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Barang');
        $sheet->setCellValue('E1', 'Jumlah Barang');
    
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    
        $no = 1;
        $baris = 2;
        foreach ($detail as $row) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $row->penjualan->penjualan_kode);
            $sheet->setCellValue('C' . $baris, $row->barang->barang_nama);
            $sheet->setCellValue('D' . $baris, $row->harga);
            $sheet->setCellValue('E' . $baris, $row->jumlah);
            $baris++;
        }
    
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Detail Penjualan');
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Detail Penjualan ' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $detail = PenjualanDetailModel::select('penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->orderBy('penjualan_id')
            ->with('penjualan')
            ->with('barang')
            ->get();
    
        $pdf = Pdf::loadView('detailPenjualan.export_pdf', ['detail' => $detail]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();
    
        return $pdf->stream('Detail Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }        
}