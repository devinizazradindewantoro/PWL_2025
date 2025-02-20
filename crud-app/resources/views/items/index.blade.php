<!DOCTYPE html> 
<html> 
<head> 
    <title>Item List</title> 
</head> 
<body> 
    <h1>Items</h1> 

    <!-- Menampilkan pesan notifikasi jika ada aksi yang berhasil dilakukan (tambah, edit, atau hapus) -->
    @if(session('success')) 
        <p>{{ session('success') }}</p> 
    @endif 

    <!-- Tombol untuk menuju halaman tambah item baru -->
    <a href="{{ route('items.create') }}">Add Item</a> 

    <ul> 
        <!-- Melakukan iterasi untuk menampilkan semua item yang dikirim dari ItemController -->
        @foreach ($items as $item) 
        <li> 
            <!-- Menampilkan nama item yang ada -->
            {{ $item->name }} - 

            <!-- Tautan untuk mengedit item yang dipilih -->
            <a href="{{ route('items.edit', $item) }}">Edit</a> 

            <!-- Form untuk menghapus item yang ada -->
            <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;"> 
                @csrf               <!-- Token keamanan untuk menghindari serangan CSRF -->
                @method('DELETE')   <!-- Menggunakan metode DELETE agar sesuai dengan standar RESTful -->
                <button type="submit">Delete</button> 
            </form> 
        </li> 
        @endforeach 
    </ul> 
</body> 
</html>
