<?php

use App\Models\Category;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('create discussion page loads for authenticated users', function (): void {
    $user = User::factory()->create();
    $categories = Category::factory()->count(3)->create(['is_active' => true]);

    $this->actingAs($user)
        ->get('/discussions/create')
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('discussions/create')
            ->has('categories', 3)
            ->has('categories.0', fn (Assert $category) => $category
                ->has('id')
                ->has('name')
                ->has('slug')
            )
        );
});

test('unauthenticated users are redirected to login', function (): void {
    $this->get('/discussions/create')
        ->assertRedirect('/login');
});

test('create discussion page includes categories', function (): void {
    $user = User::factory()->create();
    Category::factory()->create([
        'name' => 'Laravel',
        'sort_order' => 1,
        'is_active' => true,
    ]);
    Category::factory()->create([
        'name' => 'React',
        'sort_order' => 2,
        'is_active' => true,
    ]);
    Category::factory()->create([
        'name' => 'Inactive',
        'is_active' => false, // Should not be included
    ]);

    $this->actingAs($user)
        ->get('/discussions/create')
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('discussions/create')
            ->has('categories', 2) // Only active categories
            ->where('categories.0.name', 'Laravel')
            ->where('categories.1.name', 'React')
        );
});

test('create discussion page preselects category from query parameter', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->get('/discussions/create?category='.$category->id)
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('discussions/create')
            ->where('preselectedCategory', fn ($value) => (int) $value === $category->id)
        );
});

test('form validation requires title and content', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => '',
            'content' => '',
            'category_id' => $category->id,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['title', 'content']);
});

test('form validation requires category', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'Valid Title',
            'content' => 'Valid content with at least 10 characters.',
            'category_id' => '',
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['category_id']);
});

test('form validation rejects inactive category', function (): void {
    $user = User::factory()->create();
    $activeCategory = Category::factory()->create(['is_active' => true]);
    $inactiveCategory = Category::factory()->create(['is_active' => false]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'Valid Title',
            'content' => 'Valid content with at least 10 characters.',
            'category_id' => $inactiveCategory->id,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['category_id']);
});

test('form validation requires minimum title length', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'ab', // Too short (min 3)
            'content' => 'Valid content with at least 10 characters.',
            'category_id' => $category->id,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['title']);
});

test('form validation requires minimum content length', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'Valid Title',
            'content' => 'short', // Too short (min 10)
            'category_id' => $category->id,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['content']);
});

test('form validation rejects title too long', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => str_repeat('a', 256), // Too long (max 255)
            'content' => 'Valid content with at least 10 characters.',
            'category_id' => $category->id,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['title']);
});

test('successful discussion creation', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'Test Discussion Title',
            'content' => 'This is a valid discussion content with more than 10 characters.',
            'category_id' => $category->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('discussions', [
        'title' => 'Test Discussion Title',
        'content' => 'This is a valid discussion content with more than 10 characters.',
        'category_id' => $category->id,
        'user_id' => $user->id,
        'slug' => 'test-discussion-title',
    ]);
});

test('slug is generated correctly', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'Hello World! How Are You?',
            'content' => 'This is a valid discussion content with more than 10 characters.',
            'category_id' => $category->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('discussions', [
        'slug' => 'hello-world-how-are-you',
    ]);
});

test('slug is unique when duplicate titles exist', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    // Create existing discussion
    Discussion::factory()->create([
        'title' => 'Test Discussion',
        'slug' => 'test-discussion',
    ]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'Test Discussion', // Same title
            'content' => 'This is a valid discussion content with more than 10 characters.',
            'category_id' => $category->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('discussions', [
        'title' => 'Test Discussion',
        'slug' => 'test-discussion-1', // Should have suffix
    ]);
});

test('discussion created with correct timestamps', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post('/discussions', [
            'title' => 'Test Discussion Title',
            'content' => 'This is a valid discussion content with more than 10 characters.',
            'category_id' => $category->id,
        ])
        ->assertRedirect();

    $discussion = Discussion::first();
    $this->assertNotNull($discussion->created_at);
    $this->assertNotNull($discussion->updated_at);
    $this->assertNotNull($discussion->last_reply_at);
});
