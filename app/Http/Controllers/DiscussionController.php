<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscussionRequest;
use App\Models\Category;
use App\Models\Discussion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DiscussionController extends Controller
{
    /**
     * Show the form for creating a new discussion.
     */
    public function create(Request $request): Response
    {
        $categories = Category::query()
            ->active()
            ->ordered()
            ->get(['id', 'name', 'slug']);

        return Inertia::render('discussions/create', [
            'categories' => $categories,
            'preselectedCategory' => (string) $request->query('category'),
        ]);
    }

    /**
     * Store a newly created discussion in storage.
     */
    public function store(StoreDiscussionRequest $request): RedirectResponse
    {
        /** @var array{title: string, content: string, category_id: int} $validated */
        $validated = $request->validated();

        // Generate unique slug from title
        $title = $validated['title'];
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Discussion::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $discussion = Discussion::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'last_reply_at' => now(),
        ]);

        // For now, redirect to categories since discussion detail page doesn't exist yet
        // TODO: Change to redirect to discussion detail page when implemented
        return redirect()
            ->route('categories.show', ['category' => $discussion->category->slug])
            ->with('success', 'Discussion created successfully!');
    }
}
