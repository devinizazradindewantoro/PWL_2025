<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        return PenjualanModel::all();
    }
    public function store(Request $request)
    {
        $penjualan = PenjualanModel::create($request->all());
        return response()->json($penjualan, 201);
    }

    public function show($penjualan)
    {
        return PenjualanModel::find($penjualan);
    }

    public function update(Request $request, $penjualan)
    {
        $data = PenjualanModel::find($penjualan);
        $data->update($request->all());
        return PenjualanModel::find($penjualan);
    }

    public function destroy($penjualan)
    {
        $data = PenjualanModel::find($penjualan);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}