<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::getActiveUsers();
        return response()->json([
            'users' => $users,
            'auth_user_id' => auth()->id(),
        ]);
    }

    public function userPermissions()
    {
        $users = User::where('isDeleted', 0)
                ->orderByRaw('id = ? DESC', [auth()->id()])
                ->orderBy('name')
                ->get();
        return view('permissions', [
            'users' => $users,
        ]);
    }

    public function updatePermissions(Request $request)
    {

    }
}
