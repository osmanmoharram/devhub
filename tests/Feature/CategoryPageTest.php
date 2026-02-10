<?php

use App\Models\Category;
use App\Models\Discussion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('categories page loads correctly', function (): void {
    Category::factory()->create(['name' => 'Laravel', 'is_active' => true]);

    $this->get('/categories')
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/index')
            ->has('categories', 1)
        );
});

test('categories are ordered correctly', function (): void {
    Category::factory()->create([
        'name' => 'Laravel',
        'sort_order' => 2,
        'is_active' => true,
    ]);
    Category::factory()->create([
        'name' => 'React',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $response = $this->get('/categories');

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/index')
            ->has('categories', 2)
            ->where('categories.0.name', 'React')
            ->where('categories.1.name', 'Laravel')
        );
});

test('categories include discussions count', function (): void {
    $category = Category::factory()->create(['is_active' => true]);

    Discussion::factory()->count(3)->create(['category_id' => $category->id]);

    $this->get('/categories')
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/index')
            ->has('categories', 1)
            ->where('categories.0.discussions_count', 3)
        );
});

test('inactive categories are not shown', function (): void {
    Category::factory()->create(['name' => 'Laravel', 'is_active' => true]);
    Category::factory()->create(['name' => 'React', 'is_active' => false]);

    $this->get('/categories')
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('categories/index')
            ->has('categories', 1)
            ->where('categories.0.name', 'Laravel')
        );
});
