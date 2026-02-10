<?php

use App\Models\Discussion;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a reply', function (): void {
    $reply = Reply::factory()->create([
        'content' => 'This is a helpful reply to the discussion.',
    ]);

    expect($reply->content)->toBe('This is a helpful reply to the discussion.');
});

test('reply belongs to discussion', function (): void {
    $discussion = Discussion::factory()->create();
    $reply = Reply::factory()->for($discussion)->create();

    expect($reply->discussion_id)->toBe($discussion->id);
    expect($reply->discussion->id)->toBe($discussion->id);
});

test('reply belongs to user', function (): void {
    $user = User::factory()->create();
    $reply = Reply::factory()->for($user)->create();

    expect($reply->user_id)->toBe($user->id);
    expect($reply->user->id)->toBe($user->id);
});

test('discussion has many replies', function (): void {
    $discussion = Discussion::factory()->create();
    $reply1 = Reply::factory()->for($discussion)->create();
    $reply2 = Reply::factory()->for($discussion)->create();

    expect($discussion->replies)->toHaveCount(2);
    expect($discussion->replies->pluck('id')->toArray())->toContain($reply1->id, $reply2->id);
});

test('user has many replies', function (): void {
    $user = User::factory()->create();
    $reply1 = Reply::factory()->for($user)->create();
    $reply2 = Reply::factory()->for($user)->create();

    expect($user->replies)->toHaveCount(2);
    expect($user->replies->pluck('id')->toArray())->toContain($reply1->id, $reply2->id);
});

test('reply content is required', function (): void {
    expect(fn () => Reply::factory()->create(['content' => null]))
        ->toThrow('Integrity constraint violation');
});

test('reply discussion_id is required', function (): void {
    expect(fn () => Reply::factory()->create(['discussion_id' => null]))
        ->toThrow('Integrity constraint violation');
});

test('reply user_id is required', function (): void {
    expect(fn () => Reply::factory()->create(['user_id' => null]))
        ->toThrow('Integrity constraint violation');
});
