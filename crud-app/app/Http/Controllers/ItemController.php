// Mendefinisikan namespace untuk controller
namespace App\Http\Controllers;

// Mengimport model Item
use App\Models\Item;

// Mengimport Request untuk menangani input form
use Illuminate\Http\Request;

// Deklarasi class ItemController yang mewarisi Controller
class ItemController extends Controller {

    // Menampilkan daftar item
    public function index() {
        // Mengambil semua data item dari database
        $items = Item::All();
        // Mengirim data item ke view items.index
        return view('items.index', compact('items'));
    }

    // Menampilkan form tambah item baru
    public function create() {
        // Mengarahkan ke halaman form create item
        return view('items.create');
    }

    // Menyimpan data item baru ke database
    public function store(Request $request) {
        // Validasi input form
        $request->validate([
            'name' => 'required', // Nama harus diisi
            'description' => 'required', // Deskripsi harus diisi
        ]);
        // Simpan data item baru ke database
        Item::create($request->only(['name', 'description']));
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan');
    }

    // Menampilkan detail item tertentu
    public function show(Item $item) {
        // Mengirim data item ke view item.show
        return view('item.show', compact('item'));
    }

    // Menampilkan form edit item yang dipilih
    public function edit(Item $item) {
        // Mengirim data item ke view item.edit
        return view('item.edit', compact('item'));
    }

    // Menyimpan perubahan data item ke database
    public function update(Request $request, Item $item) {
        // Validasi input form
        $request->validate([
            'name' => 'required', // Nama harus diisi
            'description' => 'required', // Deskripsi harus diisi
        ]);
        // Update data item yang dipilih
        $item->update($request->only(['name', 'description']));
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui');
    }

    // Menghapus item dari database
    public function destroy(Item $item) {
        // Hapus item yang dipilih dari database
        $item->delete();
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus');
    }
}