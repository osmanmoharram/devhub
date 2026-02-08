<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a category', function (): void {
    $category = Category::factory()->create([
        'name' => 'Web Development',
        'slug' => 'web-development',
        'description' => 'Discuss web development topics',
    ]);

    expect($category->name)->toBe('Web Development');
    expect($category->slug)->toBe('web-development');
    expect($category->description)->toBe('Discuss web development topics');
    expect($category->is_active)->toBeTrue();
});

test('category slug is unique', function (): void {
    Category::factory()->create(['slug' => 'web-development']);

    expect(fn () => Category::factory()->create(['slug' => 'web-development']))
        ->toThrow('Integrity constraint violation');
});

test('active scope returns only active categories', function (): void {
    $activeCategory = Category::factory()->create(['is_active' => true]);
    $inactiveCategory = Category::factory()->create(['is_active' => false]);

    $activeCategories = Category::active()->get();

    expect($activeCategories)->toHaveCount(1);
    expect($activeCategories->first()->id)->toBe($activeCategory->id);
});

test('ordered scope sorts by sort_order then name', function (): void {
    $category3 = Category::factory()->create(['name' => 'C', 'sort_order' => 3]);
    $category1 = Category::factory()->create(['name' => 'A', 'sort_order' => 1]);
    $category2 = Category::factory()->create(['name' => 'B', 'sort_order' => 2]);

    $orderedCategories = Category::ordered()->get();

    expect($orderedCategories->pluck('id')->toArray())->toBe([
        $category1->id,
        $category2->id,
        $category3->id,
    ]);
});

test('category resource transforms correctly', function (): void {
    $category = Category::factory()->create();

    $resource = new \App\Http\Resources\CategoryResource($category);
    $array = $resource->toArray(request());

    expect($array)->toHaveKeys([
        'id', 'name', 'slug', 'description', 'color',
        'is_active', 'sort_order', 'created_at', 'updated_at',
    ]);
});
