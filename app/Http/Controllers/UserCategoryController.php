<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $users = User::getActiveUsers(); // You already have this
        $categories = Categorie::getActiveCategories(); // You already have this

        return response()->json([
            'users' => $users,
            'categories' => $categories,
            'can_create' => $user->canCreateCase(),
        ]);
    }
}
