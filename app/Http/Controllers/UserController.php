<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userPermission' => 'user',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'user' => $user,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully']);
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

    public function partial()
    {
        $users = $this->getUsersForPermissionsView();

        return view('permissions.user-permission-table', compact('users'));
    }

    private function getUsersForPermissionsView()
    {
        return User::where('isDeleted', 0)
            ->orderByRaw('id = ? DESC', [auth()->id()])
            ->orderBy('name')
            ->get();
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
