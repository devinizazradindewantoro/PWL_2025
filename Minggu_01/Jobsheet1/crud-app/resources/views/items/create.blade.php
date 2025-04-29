<!DOCTYPE html> 
<html> 
<head> 
    <title>Add Item</title> 
</head> 
<body> 
    <h1>Add Item</h1> 

    <!-- Form untuk menambah item baru -->
    <form action="{{ route('items.store') }}" method="POST"> 
        @csrf <!-- Token keamanan untuk mencegah CSRF -->
        
        <!-- Input untuk nama item -->
        <label for="name">Name: </label> 
        <input type="text" name="name" required> 
        <br> 

        <!-- Input untuk deskripsi item -->
        <label for="description">Description: </label> 
        <textarea name="description" required></textarea> 
        <br> 

        <!-- Tombol submit untuk menambah item -->
        <button type="submit">Add Item</button> 
    </form> 

    <!-- Link kembali ke halaman list item -->
    <a href="{{ route('items.index') }}">Back to List</a> 
</body> 
</html>