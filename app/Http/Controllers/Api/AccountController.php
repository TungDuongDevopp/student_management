<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('role')->get();
        return response()->json($accounts);
    }

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

    public function show($id)
    {
        $account = Account::with('role')->findOrFail($id);
        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $validated = $request->validate([
            'role_id' => 'sometimes|required|integer|exists:roles,id',
            'username' => 'sometimes|required|string|max:50|unique:accounts,username,' . $id,
            'password' => 'sometimes|required|string|max:255',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $account->update($validated);
        return response()->json($account);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }
}
