<!DOCTYPE html> 
<html> 
<head> 
    <title>Edit Item</title> 
</head> 
<body> 
    <h1>Edit Item</h1> 

    <!-- Form untuk memperbarui data item yang sudah ada -->
    <form action="{{ route('items.update', $item) }}" method="POST"> 
        @csrf <!-- Token keamanan untuk melindungi dari serangan CSRF -->
        @method('PUT') <!-- Menggunakan metode PUT agar sesuai dengan standar RESTful -->

        <!-- Input field untuk nama item, nilai diambil dari data yang tersedia -->
        <label for="name">Name:</label> 
        <input type="text" name="name" value="{{ $item->name }}" required> 
        <br> 

        <!-- Input field untuk deskripsi item, dengan data yang sudah tersimpan sebelumnya -->
        <label for="description">Description: </label> 
        <textarea name="description" required>{{ $item->description }}</textarea> 
        <br> 

        <!-- Tombol untuk menyimpan perubahan item -->
        <button type="submit">Update Item</button> 
    </form> 

    <!-- Tautan untuk kembali ke daftar item -->
    <a href="{{ route('items.index') }}">Back to List</a> 
</body> 
</html>
