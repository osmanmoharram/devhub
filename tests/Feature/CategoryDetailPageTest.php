<?php

use App\Models\Category;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('category detail page loads correctly', function (): void {
    $category = Category::factory()->create(['is_active' => true]);

    $this->get("/categories/{$category->slug}")
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->has('category')
            ->has('discussions')
        );
});

test('category detail page shows correct category information', function (): void {
    $category = Category::factory()->create([
        'name' => 'Laravel',
        'description' => 'All about Laravel development',
        'is_active' => true,
    ]);

    $this->get("/categories/{$category->slug}")
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->where('category.name', 'Laravel')
            ->where('category.description', 'All about Laravel development')
            ->where('category.slug', $category->slug)
        );
});

test('category detail page displays discussions', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    Discussion::factory()->count(3)->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $this->get("/categories/{$category->slug}")
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->has('discussions.data', 3)
            ->where('discussions.data.0.user.name', $user->name)
        );
});

test('category detail page paginates discussions', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    Discussion::factory()->count(15)->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $response = $this->get("/categories/{$category->slug}");

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->has('discussions.data', 10) // Default pagination
            ->where('discussions.total', 15)
            ->where('discussions.per_page', 10)
            ->where('discussions.current_page', 1)
        );
});

test('category detail page handles empty discussions correctly', function (): void {
    $category = Category::factory()->create(['is_active' => true]);

    $this->get("/categories/{$category->slug}")
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->has('discussions.data', 0)
            ->where('discussions.total', 0)
        );
});

test('category detail page orders discussions correctly', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    // Create discussions with different creation times
    $oldDiscussion = Discussion::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
        'created_at' => now()->subDays(2),
        'last_reply_at' => now()->subDays(2),
    ]);

    $pinnedDiscussion = Discussion::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
        'is_pinned' => true,
        'created_at' => now()->subDay(),
        'last_reply_at' => now()->subDay(),
    ]);

    $recentDiscussion = Discussion::factory()->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
        'created_at' => now(),
        'last_reply_at' => now(),
    ]);

    $response = $this->get("/categories/{$category->slug}");

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->has('discussions.data', 3)
            ->where('discussions.data.0.id', $pinnedDiscussion->id) // Pinned first
            ->where('discussions.data.1.id', $recentDiscussion->id) // Then recent
            ->where('discussions.data.2.id', $oldDiscussion->id) // Then old
        );
});

test('category detail page includes reply counts property', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    Discussion::factory()->count(2)->create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $this->get("/categories/{$category->slug}")
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->has('discussions.data', 2)
            ->has('discussions.data.0.replies_count') // Just verify the property exists
            ->has('discussions.data.1.replies_count')
        );
});

test('category detail page returns 404 for non-existent category', function (): void {
    $this->get('/categories/non-existent-slug')
        ->assertStatus(404);
});

test('category detail page shows only discussions from that category', function (): void {
    $user = User::factory()->create();
    $category1 = Category::factory()->create(['is_active' => true]);
    $category2 = Category::factory()->create(['is_active' => true]);

    Discussion::factory()->count(2)->create([
        'category_id' => $category1->id,
        'user_id' => $user->id,
    ]);

    Discussion::factory()->count(3)->create([
        'category_id' => $category2->id,
        'user_id' => $user->id,
    ]);

    $this->get("/categories/{$category1->slug}")
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/show')
            ->has('discussions.data', 2) // Only discussions from category1
            ->where('discussions.total', 2)
        );
});
