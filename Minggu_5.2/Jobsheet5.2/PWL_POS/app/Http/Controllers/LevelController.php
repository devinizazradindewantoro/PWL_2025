<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Level User',
            'list' => ['Home', 'Level User']
        ];

        $page = (object) [
            'title' => 'Daftar level user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables public function list(Request $request)
    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

        // filter data user berdasarkan level_id
        if ($request->level_id) {
            $level->where('level_id', $request->level_id);
        };


        return DataTables::of($level)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-success sm">Detail</a> ';
                $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level User',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah level user baru'
        ];

        $level = LevelModel::all();
        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
                'level_kode' => 'required|string|unique:m_level,level_kode',
                'level_name' => 'required|string'
            ]);
    
            LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_name' => $request->level_name
            ]);

            return redirect('/level')->with('success', 'Data Level berhasil disimpan');
    }


    // Menampilkan detail user

    public function show(string $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Level'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit user

    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit level'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }


    // Menyimpan perubahan data user

    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
            'level_name' => 'required|string'
        ]);

        $level = LevelModel::findOrFail($id);
        $level->update([
            'level_kode' => $request->level_kode,
            'level_name' => $request->level_name
        ]);

        return redirect('/level')->with('success', 'Data Level berhasil diperbarui');
    }

     // Menghapus data user
     public function destroy(string $id)
     {
         $level = LevelModel::find($id);
         if (!$level) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
             return redirect('/level')->with('error', 'Data level tidak ditemukan');
         }
 
         try {
             LevelModel::destroy($id); // Hapus data level
             return redirect('/level')->with('success', 'Data user berhasil dihapus');
         } catch (\Illuminate\Database\QueryException $e) {

             // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
             return redirect('/level')->with('error', 'Data Level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }
}
