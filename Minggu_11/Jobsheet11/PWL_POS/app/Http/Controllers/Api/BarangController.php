<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }
    public function store(Request $request)
    {
        $barang = BarangModel::create($request->all());
        return response()->json($barang, 201);
    }

    public function show($barang)
    {
        return BarangModel::find($barang);
    }

    public function update(Request $request, $barang)
    {
        $data = BarangModel::find($barang);
        $data->update($request->all());
        return BarangModel::find($barang);
    }

    public function destroy($barang)
    {
        $data = BarangModel::find($barang);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}