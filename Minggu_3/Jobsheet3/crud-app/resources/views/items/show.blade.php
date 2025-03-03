<!DOCTYPE html> 
<html> 
<head> 
    <title>Detail Item</title> 
</head> 
<body> 
    <h1>Detail Item</h1> 

    <!-- Menampilkan detail nama item -->
    <p><strong>Name:</strong> {{ $item->name }}</p> 

    <!-- Menampilkan detail deskripsi item -->
    <p><strong>Description:</strong> {{ $item->description }}</p> 

    <!-- Link untuk edit item ini -->
    <a href="{{ route('items.edit', $item) }}">Edit</a> 

    <!-- Form untuk menghapus item ini -->
    <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;"> 
        @csrf <!-- Token keamanan untuk mencegah CSRF -->
        @method('DELETE') <!-- Method DELETE untuk menghapus data -->
        <button type="submit">Delete</button> 
    </form> 

    <!-- Link kembali ke halaman list item -->
    <a href="{{ route('items.index') }}">Back to List</a> 
</body> 
</html>