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

    /**
     * Display a specific category with its discussions.
     */
    public function show(Category $category): \Inertia\Response
    {
        $discussions = $category->discussions()
            ->with('user:id,name')
            ->withCount('replies')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_reply_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('categories/show', [
            'category' => $category,
            'discussions' => $discussions,
        ]);
    }
}
