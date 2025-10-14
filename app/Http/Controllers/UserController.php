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
        if(!auth()->user()->isSuperAdmin()) {
            return response()->json([
                'status' => 'error',
                'message', 'You do not have permission to perform this action.'
            ], 403);
        }

        $results = [];

        foreach ($request->input('permissions', []) as $userId => $permission) {
            $user = User::find($userId);

            if (!$user) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'User not found.'
                // ], 404);
                $results[$userId] = [
                    'status' => 'error',
                    'message' => 'User not found.'
                ];
                continue;
            }

            if ($user->id === auth()->id() && auth()->user()->isSuperAdmin()) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => "You cannot remove yourself from the Super Admin role."
                // ], 403);
                $results[$userId] = [
                    'status' => 'error',
                    'message' => 'You cannot change your own Super Admin role.'
                ];
                continue;
            }

            if ($user) {
                $user->userPermission = $permission;
                $updated = $user->save();
            } else {
                $updated = false;
            }
        }
        return response()->json([
            'status' => 'completed',
            'summary' => [
                'total' => count($results),
                'success' => collect($results)->where('status', 'success')->count(),
                'errors' => collect($results)->where('status', 'error')->count(),
            ],
            'results' => $results,
        ]);
    }

    public function delete(Request $request, User $user)
    {
        if(!auth()->user()->isSuperAdmin()) {
            return response()->json([
                'status' => 'error',
                'message', 'You do not have permission to perform this action.'
            ], 403);
        }

        if ($user->id === auth()->id() && auth()->user()->isSuperAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => "You cannot delete your own account."
            ], 403);
        }

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => "You do not have permission to delete a Super Admin."
            ], 403);
        }

        $user->isDeleted = 1;
        $deleted = $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.',
            'deleted' => $deleted,
        ]);
    }
}
