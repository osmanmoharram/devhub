<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a profile', function (): void {
    $profile = Profile::factory()->create([
        'bio' => 'Senior Laravel developer with 5+ years experience.',
        'location' => 'Dubai, UAE',
        'avatar' => 'avatars/user1.jpg',
    ]);

    expect($profile->bio)->toBe('Senior Laravel developer with 5+ years experience.');
    expect($profile->location)->toBe('Dubai, UAE');
    expect($profile->avatar)->toBe('avatars/user1.jpg');
});

test('profile belongs to user', function (): void {
    $user = User::factory()->create();
    $profile = Profile::factory()->for($user)->create();

    expect($profile->user_id)->toBe($user->id);
    expect($profile->user->id)->toBe($user->id);
});

test('user has one profile', function (): void {
    $user = User::factory()->create();
    $profile = Profile::factory()->for($user)->create();

    expect($user->profile)->not->toBeNull();
    expect($user->profile->id)->toBe($profile->id);
});

test('profile fields are nullable', function (): void {
    $profile = Profile::factory()->create([
        'bio' => null,
        'location' => null,
        'avatar' => null,
    ]);

    expect($profile->bio)->toBeNull();
    expect($profile->location)->toBeNull();
    expect($profile->avatar)->toBeNull();
});

test('profile user_id is required', function (): void {
    expect(fn () => Profile::factory()->create(['user_id' => null]))
        ->toThrow('Integrity constraint violation');
});

test('profile user_id is unique', function (): void {
    $user = User::factory()->create();

    Profile::factory()->for($user)->create();

    expect(fn () => Profile::factory()->for($user)->create())
        ->toThrow('Integrity constraint violation');
});

test('profile factory generates valid middle east locations', function (): void {
    $profile = Profile::factory()->create();

    expect($profile->location)->toBeIn([
        'Cairo, Egypt',
        'Riyadh, Saudi Arabia',
        'Dubai, UAE',
        'Doha, Qatar',
        'Kuwait City, Kuwait',
        'Manama, Bahrain',
        'Muscat, Oman',
        'Amman, Jordan',
        'Beirut, Lebanon',
        'Istanbul, Turkey',
    ]);
});
