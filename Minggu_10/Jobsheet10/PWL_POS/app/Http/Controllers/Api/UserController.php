<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return UserModel::all();
    }
    public function store(Request $request)
    {
        $request['password'] = Hash::make($request['password']);
        $level = UserModel::create($request->all());
        return response()->json($level, 201);
    }

    public function show($user)
    {
        return UserModel::find($user);
    }

    public function update(Request $request, $level)
    {
        $data = UserModel::find($level);
        $data->update($request->all());
        return UserModel::find($level);
    }

    public function destroy($user)
    {
        $data = UserModel::find($user);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}