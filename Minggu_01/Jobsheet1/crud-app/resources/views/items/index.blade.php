<!DOCTYPE html> 
<html> 
<head> 
    <title>Item List</title> 
</head> 
<body> 
    <h1>Items</h1> 

    <!-- Menampilkan pesan sukses jika ada, misalnya setelah tambah, edit, atau hapus item -->
    @if(session('success')) 
        <p>{{ session('success') }}</p> 
    @endif 

    <!-- Tombol untuk menambah item baru, mengarah ke form create -->
    <a href="{{ route('items.create') }}">Add Item</a> 

    <ul> 
        <!-- Looping semua item yang dikirim dari ItemController -->
        @foreach ($items as $item) 
        <li> 
            <!-- Menampilkan nama item -->
            {{ $item->name }} - 

            <!-- Link untuk edit item -->
            <a href="{{ route('items.edit', $item) }}">Edit</a> 

            <!-- Form untuk menghapus item -->
            <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;"> 
                @csrf               <!-- Token keamanan untuk mencegah CSRF -->
                @method('DELETE')   <!-- Mengubah method menjadi DELETE -->
                <button type="submit">Delete</button> 
            </form> 
        </li> 
        @endforeach 
    </ul> 
</body> 
</html>