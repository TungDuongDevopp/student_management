<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    //Xem danh sách tất cả các tài khoản

    //GET /api/accounts
    public function index()
    {
        $accounts = Account::with(['role', 'student', 'teacher'])->get();
        return response()->json($accounts);
    }
    // Tạo tài khoản
    //POST /api/accounts
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|integer|exists:roles,id',
            'username' => 'required|string|max:50|unique:accounts,username',
            'password' => 'required|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $account = Account::create($validated);
        return response()->json($account, 201);
    }
    //Xem chi tiết một tài khoản
    //GET /api/accounts/{id}
    public function show($id)
    {
        $account = Account::with('role')->findOrFail($id);
        return response()->json($account);
    }
    //Cập nhật tài khoản (chỉ cập nhật quyền - role_id, không đổi username/password)
    //PUT /api/accounts/{id}
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $validated = $request->validate([
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $account->update($validated);
        return response()->json($account->load('role'));
    }
    //Xóa tài khoản
    //DELETE /api/accounts/{id} 
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }
}
