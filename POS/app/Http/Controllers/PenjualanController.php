<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanModel; 
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
    
        $page = (object)[
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];
    
        $activeMenu = 'penjualan';
        $user = UserModel::all(); 
    
        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'user_id', 'pembeli', 'penjualan_tanggal')
            ->with('user');
    
        if ($request->user_id) {
            $penjualan->where('user_id', $request->user_id);
        }
    
        return DataTables::of($penjualan)
            ->addIndexColumn() 
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah penjualan baru'
        ];

        $user = UserModel::all(); 
        $activeMenu = 'penjualan';

        return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'penjualan_kode' => 'required|string|max:50',
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:100',
            'penjualan_tanggal' => 'required|date',
        ]);

        PenjualanModel::create([
            'penjualan_kode' => $request->penjualan_kode,
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_tanggal' => $request->penjualan_tanggal,
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }

    public function show(string $id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $penjualan = PenjualanModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit penjualan'
        ];

        $user = UserModel::all(); 
        $activeMenu = 'penjualan';

        return view('penjualan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'penjualan_kode' => 'required|string|max:50',
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:100',
            'penjualan_tanggal' => 'required|date',
        ]);

        PenjualanModel::find($id)->update([
            'penjualan_kode' => $request->penjualan_kode,
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_tanggal' => $request->penjualan_tanggal,
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = PenjualanModel::find($id);
        if (!$check) { 
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            PenjualanModel::destroy($id); 

            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
    {
        $user = UserModel::select('user_id', 'nama')->get();
        return view('penjualan.create_ajax')->with('user', $user);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'penjualan_kode' => 'required|string|max:50',
                'user_id' => 'required|integer|exists:m_user,user_id',
                'pembeli' => 'required|string|max:100',
                'penjualan_tanggal' => 'required|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false, 
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), 
                ]);
            }

            PenjualanModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $user = UserModel::select('user_id', 'nama')->get();

        return view('penjualan.edit_ajax', ['penjualan' => $penjualan, 'user' => $user]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'penjualan_kode' => 'required|string|max:50',
                'user_id' => 'required|integer|exists:m_user,user_id',
                'pembeli' => 'required|string|max:100',
                'penjualan_tanggal' => 'required|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { 
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = PenjualanModel::find($id); 
            if ($check) {
                $check->update($request->all()); 
                return response()->json([
                    'status'  => true,
                    'message' => 'Data penjualan berhasil diupdate'
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
        $penjualan = PenjualanModel::with('user')->find($id);

        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                $penjualan->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data penjualan berhasil dihapus'
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

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        
        return view('penjualan.show_ajax', [
            'penjualan' => $penjualan
        ]);
    }

    public function import()
    {
        return view('penjualan.import');
    }
    
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_penjualan'); 
    
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
                            'user_id'          => $value['A'],
                            'pembeli'          => $value['B'],
                            'penjualan_kode'   => $value['C'],
                            'penjualan_tanggal' => $value['D'],
                            'created_at'       => now(),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    PenjualanModel::insertOrIgnore($insert);
                }
    
                return response()->json([
                    'status'  => true,
                    'message' => 'Data penjualan berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
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
        $sheet->setCellValue('B1', 'Nama User');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Kode Penjualan');
        $sheet->setCellValue('E1', 'Tanggal Penjualan');
    
        $sheet->getStyle('A1:E1')->getFont()->setBold(true); 
    
        $no = 1; 
        $baris = 2; 
        foreach ($penjualan as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->user->nama); 
            $sheet->setCellValue('C'.$baris, $value->pembeli);
            $sheet->setCellValue('D'.$baris, $value->penjualan_kode);
            $sheet->setCellValue('E'.$baris, $value->penjualan_tanggal);
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
    
    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
                                  ->orderBy('user_id')
                                  ->with('user')
                                  ->get();
    
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait'); 
        $pdf->setOption("isRemoteEnabled", true); 
        $pdf->render();
    
        return $pdf->stream('Data Penjualan '.date('Y-m-d H:i:s').'.pdf');
    }
}