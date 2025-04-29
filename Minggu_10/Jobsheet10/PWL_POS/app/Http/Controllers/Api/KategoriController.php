<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KategoriController extends Controller
{
    public function index()
    {
        return KategoriModel::all();
    }
    public function store(Request $request)
    {
        $kategori = KategoriModel::create($request->all());
        return response()->json($kategori, 201);
    }

    public function show($kategori)
    {
        return KategoriModel::find($kategori);
    }

    public function update(Request $request, $kategori)
    {
        $data = KategoriModel::find($kategori);
        $data->update($request->all());
        return KategoriModel::find($kategori);
    }

    public function destroy($kategori)
    {
        $data = KategoriModel::find($kategori);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}