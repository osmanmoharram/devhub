# Feature Implementation: Profile Model

## Feature Overview

Implement Profile Model as Feature #4 from MVP roadmap. This feature enables users to have extended profile information including bio, location, and avatar, supporting the community aspects of the DevHub forum.

## Requirements from Roadmap

- **Profile Model** - Create Profile model with bio, location, avatar, user_id fields
- Must follow existing code patterns and conventions
- Include proper database relationships
- Include comprehensive testing
- Support Middle East regional context (location field)

## Implementation Plan

### 1. Database Schema

**Table**: `profiles`

- `id` (primary key, auto-increment)
- `bio` (TEXT, nullable) - User biography/description
- `location` (STRING, nullable) - User location (Middle East countries focus)
- `avatar` (STRING, nullable) - Path to user avatar image
- `user_id` (BIGINT, foreign key) - References users.id with cascade delete
- `created_at`, `updated_at` (timestamps)
- **Indexes**:
    - `user_id` - Unique index for one-to-one relationship
    - `location` - For location-based searches

### 2. Model Implementation

**File**: `app/Models/Profile.php`

- Extends Laravel Eloquent Model
- Uses HasFactory trait
- Proper PHPDoc properties for IDE support
- **Relationships**:
    - `user()` - BelongsTo relationship to User
- **Casts**:
    - Consider JSON casts for future profile fields

### 3. Factory Implementation

**File**: `database/factories/ProfileFactory.php`

- Follows existing factory patterns
- Generate realistic bio content using faker
- Generate Middle East location names
- Handle avatar path generation (placeholder initially)
- Create related User models

### 4. Model Relationships Update

**Files to update**:

- `app/Models/User.php` - Add `profile()` HasOne relationship

### 5. Testing Strategy

**File**: `tests/Feature/ProfileTest.php`

- Test Profile model creation
- Test database constraints (user_id unique, nullable fields)
- Test belongsTo relationship (User)
- Test hasOne relationship (User.profile)
- Test one-to-one relationship behavior
- Follow existing test patterns and structure

## Acceptance Criteria

- ✅ Migration creates table with proper schema
- ✅ Profile model can be created and retrieved
- ✅ Profile belongs to User (one-to-one)
- ✅ User has one Profile
- ✅ Database constraints prevent duplicate user_id
- ✅ Bio, location, avatar fields are nullable
- ✅ All tests pass
- ✅ Code follows project conventions
- ✅ Code formatted with Pint

## Technical Considerations

- Use Laravel 12 conventions (no `$fillable` due to `Model::unguard()`)
- Follow established PHPDoc patterns from other models
- Use proper foreign key constraints with cascade delete
- Add unique constraint on user_id for one-to-one relationship
- Include comprehensive test coverage
- Consider avatar storage (placeholder for now, real storage later)

## Dependencies

- No additional packages required for basic profile functionality
- Uses existing Laravel features and patterns
- Builds on existing User model
- Future: May need image storage package for avatar uploads

## Timeline Estimate

- Database Migration: 15 minutes
- Model Implementation: 20 minutes
- Factory Implementation: 10 minutes
- Relationship Updates: 10 minutes
- Testing: 30 minutes
- Code Review & Formatting: 20 minutes
- **Total**: ~1.5 hours

## Package Check

Based on Laravel ecosystem and current requirements:

- No existing packages needed for basic profile functionality
- Laravel Eloquent relationships handle all required functionality
- For avatar uploads in future: Consider `spatie/laravel-medialibrary` or similar
- For now, use simple string path for avatar field
