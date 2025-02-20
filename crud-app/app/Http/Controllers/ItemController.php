<?php

// Namespace untuk mengatur lokasi controller
namespace App\Http\Controllers;

// Import model Item
use App\Models\Item;
// Import Request untuk menangani input dari form
use Illuminate\Http\Request;

// Deklarasi class ItemController yang mewarisi Controller bawaan Laravel
class ItemController extends Controller
{
    // Function index untuk menampilkan semua item yang ada di database
    public function index()
    {
        $items = Item::All(); // Mengambil semua data item dari tabel 'items'
        return view('items.index', compact('items')); // Mengirim data item ke view item.index
    }

    // Function create untuk menampilkan form tambah item baru
    public function create()
    {
        return view('items.create'); // Mengarahkan ke halaman form create item
    }

    // Function store untuk menyimpan data item baru ke database
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'name' => 'required',           // Nama wajib diisi
            'description' => 'required',    // Deskripsi wajib diisi
        ]);
        
        // Simpan data item baru ke database
        Item::create($request->only(['name', 'description'])); // Hanya simpan name dan description
        
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan');
    }

    // Function show untuk menampilkan detail item tertentu
    // Parameter $item adalah item yang akan ditampilkan, diambil otomatis dari Route Model Binding
    public function show(Item $item)
    {
        return view('item.show', compact('item')); // Mengirim data item ke view item.show
    }

    // Function edit untuk menampilkan form edit item yang dipilih
    // Parameter $item adalah item yang akan diedit
    public function edit(Item $item)
    {
        return view('item.edit', compact('item')); // Mengirim data item ke view item.edit
    }

    // Function update untuk menyimpan perubahan data item ke database
    // Parameter $request berisi data form yang diinputkan user
    // Parameter $item adalah item yang akan diupdate
    public function update(Request $request, Item $item)
    {
        // Validasi input form
        $request->validate([
            'name' => 'required',           // Nama wajib diisi
            'description' => 'required',    // Deskripsi wajib diisi
        ]);

        // Update data item yang dipilih
        $item->update($request->only(['name', 'description'])); // Update name dan description
        
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui');
    }

    // Function destroy untuk menghapus item dari database
    // Parameter $item adalah item yang akan dihapus
    public function destroy(Item $item)
    {
        $item->delete(); // Hapus item yang dipilih dari database
        
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus');
    }
}