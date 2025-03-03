<!DOCTYPE html> 
<html> 
<head> 
    <title>Edit Item</title> 
</head> 
<body> 
    <h1>Edit Item</h1> 

    <!-- Form untuk mengedit item yang sudah ada -->
    <form action="{{ route('items.update', $item) }}" method="POST"> 
        @csrf <!-- Token keamanan untuk mencegah CSRF -->
        @method('PUT') <!-- Method PUT untuk update data -->

        <!-- Input untuk nama item, menggunakan value dari data yang ada -->
        <label for="name">Name:</label> 
        <input type="text" name="name" value="{{ $item->name }}" required> 
        <br> 

        <!-- Input untuk deskripsi item, menggunakan value dari data yang ada -->
        <label for="description">Description: </label> 
        <textarea name="description" required>{{ $item->description }}</textarea> 
        <br> 

        <!-- Tombol submit untuk memperbarui item -->
        <button type="submit">Update Item</button> 
    </form> 

    <!-- Link kembali ke halaman list item -->
    <a href="{{ route('items.index') }}">Back to List</a> 
</body> 
</html>