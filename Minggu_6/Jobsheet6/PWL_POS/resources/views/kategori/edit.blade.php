@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit Kategori</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/kategori/' . $kategori->kategori_id) }}" method="POST">
                @csrf
                @method('PUT')
            
                <div class="form-group">
                    <label for="kategori_kode">Kode Kategori</label>
                    <input type="text" class="form-control" id="kategori_kode" name="kategori_kode" value="{{ $kategori->kategori_kode }}" required>
                </div>
            
                <div class="form-group">
                    <label for="kategori_nama">Nama Kategori</label>
                    <input type="text" class="form-control" id="kategori_nama" name="kategori_nama" value="{{ $kategori->kategori_nama }}" required>
                </div>
            
                <button type="submit" class="btn btn-sm btn-success">Update</button>
                <a href="{{ url('/kategori') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </form>            
        </div>
    </div>
</div>
@endsection