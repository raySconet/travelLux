<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\User;
use App\Models\Insurance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InsuranceCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $insurances = Insurance::get();
        $categories = Categorie::getActiveCategories(); // You already have this

        return response()->json([
            'insurances' => $insurances,
            'categories' => $categories,
        ]);
    }
}
