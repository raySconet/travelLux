<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::getActiveUsers();
        $authUser = auth()->user();

        $assignedUserIds = [];

        // Only fetch assigned users if not admin/super_admin
        if (!in_array($authUser->userPermission, ['super_admin'])) { // ['admin', 'super_admin']
            $assignedUserIds = UserAssignment::where('user_id', $authUser->id)
                ->where('isDeleted', false)
                ->pluck('assigned_id')
                ->toArray();
        }

        return response()->json([
            'users' => $users,
            'auth_user_id' => auth()->id(),
            'assigned_user_ids' => $assignedUserIds,
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

        $userIds = array_keys($request->input('permissions', [])); // new
        $users = User::whereIn('id', $userIds)->get()->keyBy('id'); // new

        foreach ($request->input('permissions', []) as $userId => $permission) {
            // $user = User::find($userId); // old
            $user = $users->get($userId); // new

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

    public function getUsersAndAssignedUsers(Request $request)
    {
        $userId = $request->query('user_id');

        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $users = User::where('id', '!=', $userId)
            ->select('id', 'name')
            ->where('isDeleted', 0)
            ->orderBy('name')
            ->get();

        $assigned = UserAssignment::where('user_id', $userId)
            ->where('isDeleted', 0)
            ->pluck('assigned_id')
            ->toArray();

        return response()->json([
            'users' => $users,
            'assigned_user_ids' => $assigned,
        ]);
    }

    public function assignViewAccess(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'assigned_user_ids' => 'array',
            'assigned_user_ids.*' => 'exists:users,id',
        ]);

        $userId = $request->input('user_id');
        $newAssignedIds = $request->input('assigned_user_ids') ?? [];

        $user = User::find($userId);

        if ($user && in_array($user->userPermission, ['super_admin'])) { // ['admin', 'super_admin']
            return response()->json([
                'message' => 'Admins do not need to assign view access.'
            ], 403);
        }

        // Step 1: Get existing assignments (not deleted)
        // $existingAssignments = UserAssignment::where('user_id', $userId)
        //     ->where('isDeleted', false)
        //     ->get()
        //     ->keyBy('assigned_id');
        // Step 1: Get all existing assignments (including soft-deleted)
        $existingAssignments = UserAssignment::where('user_id', $userId)
            ->get()
            ->keyBy('assigned_id');

        // Step 2: Determine which to insert and which to soft-delete
        // $toInsert = [];

        // foreach ($newAssignedIds as $assignedId) {
        //     if (! $existingAssignments->has($assignedId)) {
        //         // New assignment → insert
        //         $toInsert[] = [
        //             'user_id' => $userId,
        //             'assigned_id' => $assignedId,
        //             'isDeleted' => false,
        //         ];
        //     } else {
        //         // Already exists → ensure it's active
        //         $existing = $existingAssignments->get($assignedId);
        //         if ($existing->isDeleted) {
        //             $existing->update(['isDeleted' => false]);
        //         }
        //         // Remove from list so we don't mark it for deletion
        //         $existingAssignments->forget($assignedId);
        //     }
        // }

        // // Step 3: Mark missing ones as deleted
        // foreach ($existingAssignments as $oldAssignment) {
        //     $oldAssignment->update(['isDeleted' => true]);
        // }

        // // Step 4: Insert new ones
        // if (!empty($toInsert)) {
        //     UserAssignment::insert($toInsert);
        // }
        // old

        $existingIds = $existingAssignments->keys()->toArray();

        // --- Step 1: Determine what to insert and reactivate ---
        $toInsert = array_diff($newAssignedIds, $existingIds);
        $toReactivate = array_intersect($newAssignedIds, $existingIds);

        // --- Step 2: Reactivate soft-deleted ones in bulk ---
        if (!empty($toReactivate)) {
            UserAssignment::where('user_id', $userId)
                ->whereIn('assigned_id', $toReactivate)
                ->where('isDeleted', true)
                ->update(['isDeleted' => false]);
        }

        // --- Step 3: Mark missing ones as deleted (bulk) ---
        $toDelete = array_diff($existingIds, $newAssignedIds);
        if (!empty($toDelete)) {
            UserAssignment::where('user_id', $userId)
                ->whereIn('assigned_id', $toDelete)
                ->update(['isDeleted' => true]);
        }

        // --- Step 4: Insert new ones ---
        if (!empty($toInsert)) {
            $insertData = array_map(fn($assignedId) => [
                'user_id' => $userId,
                'assigned_id' => $assignedId,
                'isDeleted' => false,
            ], $toInsert);

            UserAssignment::insert($insertData);
        }
        // new
        return response()->json(['message' => 'View access updated successfully.']);
    }
}
