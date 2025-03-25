<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar data barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables public function list(Request $request)
    public function list(Request $request)
    {
        $barang = UserModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->with('barang');

        // filter data user berdasarkan level_id
        if ($request->barang_id) {
            $barang->where('level_id', $request->level_id);
        };


        return DataTables::of($barang)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/user/' . $barang->barang_id) . '" class="btn btn-success sm">Detail</a> ';
                $btn .= '<a href="' . url('/user/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user/' . $barang->barang_id) . '">'
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
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $barang = BarangModel::all();
        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data user baru
    public function store(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'harga_beli' => 'required|float', // password harus diisi dan minimal 5 karakter
            'harga_jual' => 'required|float'  // level_id harus diisi dan berupa angka
        ]);

        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }


    // Menampilkan detail user

    public function show(string $id)
    {
        $barang = BarangModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit user

    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $barang = BarangModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Barang'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }


    // Menyimpan perubahan data user

    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'harga_beli' => 'required|float', // password harus diisi dan minimal 5 karakter
            'harga_jual' => 'required|float'  // level_id harus diisi dan berupa angka
        ]);

        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

     // Menghapus data user
     public function destroy(string $id)
     {
         $check = BarangModel::find($id);
         if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
             return redirect('/barang')->with('error', 'Data user tidak ditemukan');
         }
 
         try {
             BarangModel::destroy($id); // Hapus data level
             return redirect('/barang')->with('success', 'Data user berhasil dihapus');
         } catch (\Illuminate\Database\QueryException $e) {

             // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
             return redirect('/barang')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }
}
