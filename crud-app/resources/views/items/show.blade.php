<!DOCTYPE html> 
<html> 
<head> 
    <title>Detail Item</title> 
</head> 
<body> 
    <h1>Detail Item</h1> 

    <!-- Menampilkan informasi nama item -->
    <p><strong>Name:</strong> {{ $item->name }}</p> 

    <!-- Menampilkan informasi deskripsi item -->
    <p><strong>Description:</strong> {{ $item->description }}</p> 

    <!-- Tautan untuk mengedit item ini -->
    <a href="{{ route('items.edit', $item) }}">Edit</a> 

    <!-- Form untuk menghapus item yang sedang ditampilkan -->
    <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;"> 
        @csrf <!-- Token keamanan untuk menghindari serangan CSRF -->
        @method('DELETE') <!-- Menggunakan metode DELETE agar sesuai dengan standar RESTful -->
        <button type="submit">Delete</button> 
    </form> 

    <!-- Tautan untuk kembali ke daftar item -->
    <a href="{{ route('items.index') }}">Back to List</a> 
</body> 
</html>
