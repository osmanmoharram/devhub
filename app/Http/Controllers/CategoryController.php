<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of all active categories.
     */
    public function index(): \Inertia\Response
    {
        $activeCategories = Category::query()
            ->active()
            ->withCount('discussions')
            ->ordered()
            ->get(['id', 'name', 'slug', 'description', 'discussions_count']);

        return Inertia::render('categories/index', [
            'categories' => $activeCategories,
        ]);
    }
}
