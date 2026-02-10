<?php

use App\Models\Category;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a discussion', function (): void {
    $discussion = Discussion::factory()->create([
        'title' => 'How to learn Laravel?',
        'slug' => 'how-to-learn-laravel',
        'content' => 'I want to learn Laravel, where should I start?',
    ]);

    expect($discussion->title)->toBe('How to learn Laravel?');
    expect($discussion->slug)->toBe('how-to-learn-laravel');
    expect($discussion->content)->toBe('I want to learn Laravel, where should I start?');
});

test('discussion slug is unique', function (): void {
    Discussion::factory()->create(['slug' => 'how-to-learn-laravel']);

    expect(fn () => Discussion::factory()->create(['slug' => 'how-to-learn-laravel']))
        ->toThrow('Integrity constraint violation');
});

test('discussion belongs to category', function (): void {
    $category = Category::factory()->create();
    $discussion = Discussion::factory()->for($category)->create();

    expect($discussion->category_id)->toBe($category->id);
});

test('discussion belongs to user', function (): void {
    $user = User::factory()->create();
    $discussion = Discussion::factory()->for($user)->create();

    expect($discussion->user_id)->toBe($user->id);
});

test('pinned scope returns only pinned discussions', function (): void {
    $pinnedDiscussion = Discussion::factory()->create(['is_pinned' => true]);
    $unpinnedDiscussion = Discussion::factory()->create(['is_pinned' => false]);

    $pinnedDiscussions = Discussion::pinned()->get();

    expect($pinnedDiscussions)->toHaveCount(1);
    expect($pinnedDiscussions->first()->id)->toBe($pinnedDiscussion->id);
});

test('popular scope orders by replies count', function (): void {
    $discussion3 = Discussion::factory()->create(['replies_count' => 10]);
    $discussion1 = Discussion::factory()->create(['replies_count' => 5]);
    $discussion2 = Discussion::factory()->create(['replies_count' => 15]);

    $popularDiscussions = Discussion::popular()->get();

    expect($popularDiscussions->pluck('id')->toArray())->toBe([
        $discussion2->id, // 15 replies
        $discussion3->id, // 10 replies
        $discussion1->id, // 5 replies
    ]);
});

test('recent scope orders by created_at desc', function (): void {
    $discussion3 = Discussion::factory()->create(['created_at' => now()->subDays(3)]);
    $discussion1 = Discussion::factory()->create(['created_at' => now()->subDays(1)]);
    $discussion2 = Discussion::factory()->create(['created_at' => now()->subDays(2)]);

    $recentDiscussions = Discussion::recent()->get();

    expect($recentDiscussions->pluck('id')->toArray())->toBe([
        $discussion1->id, // 1 day ago
        $discussion2->id, // 2 days ago
        $discussion3->id, // 3 days ago
    ]);
});

test('forCategory scope filters by category', function (): void {
    $category = Category::factory()->create();
    $discussionInCategory = Discussion::factory()->for($category)->create();
    $discussionInOtherCategory = Discussion::factory()->create();

    $filteredDiscussions = Discussion::forCategory($category->id)->get();

    expect($filteredDiscussions)->toHaveCount(1);
    expect($filteredDiscussions->first()->id)->toBe($discussionInCategory->id);
});

test('discussion resource transforms correctly', function (): void {
    $discussion = Discussion::factory()->create();

    $resource = new \App\Http\Resources\DiscussionResource($discussion);
    $array = $resource->toArray(request());

    expect($array)->toHaveKeys([
        'id', 'title', 'slug', 'content', 'category_id', 'user_id',
        'is_pinned', 'is_locked', 'views_count', 'replies_count',
        'last_reply_at', 'created_at', 'updated_at', 'category', 'user',
    ]);
});
