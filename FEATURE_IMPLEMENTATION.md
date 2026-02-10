# Feature Implementation: Reply Model

## Feature Overview

Implement Reply Model as Feature #3 from MVP roadmap. This feature enables users to reply to discussion threads, forming the core interaction mechanism for the forum.

## Requirements from Roadmap

- **Reply Model** - Create Reply model with content, discussion_id, user_id fields
- Must follow existing code patterns and conventions
- Include proper database relationships
- Include comprehensive testing

## Implementation Plan

### 1. Database Schema

**Table**: `replies`

- `id` (primary key, auto-increment)
- `content` (TEXT) - The reply content text
- `discussion_id` (BIGINT, foreign key) - References discussions.id with cascade delete
- `user_id` (BIGINT, foreign key) - References users.id with cascade delete
- `created_at`, `updated_at` (timestamps)
- **Indexes**:
    - `discussion_id, created_at` - For retrieving replies in order
    - `user_id, created_at` - For user's reply history

### 2. Model Implementation

**File**: `app/Models/Reply.php`

- Extends Laravel Eloquent Model
- Uses HasFactory trait
- Proper PHPDoc properties for IDE support
- **Relationships**:
    - `discussion()` - BelongsTo relationship to Discussion
    - `user()` - BelongsTo relationship to User

### 3. Factory Implementation

**File**: `database/factories/ReplyFactory.php`

- Follows existing factory patterns
- Default content using faker paragraphs
- Creates related Discussion and User models

### 4. Model Relationships Update

**Files to update**:

- `app/Models/Discussion.php` - Add `replies()` HasMany relationship
- `app/Models/User.php` - Add `replies()` HasMany relationship

### 5. Testing Strategy

**File**: `tests/Feature/ReplyTest.php`

- Test Reply model creation
- Test database constraints (content, discussion_id, user_id required)
- Test belongsTo relationships (Discussion, User)
- Test hasMany relationships (Discussion.replies, User.replies)
- Follow existing test patterns and structure

## Acceptance Criteria

- ✅ Migration creates table with proper schema
- ✅ Reply model can be created and retrieved
- ✅ Reply belongs to Discussion and User
- ✅ Discussion has many Replies
- ✅ User has many Replies
- ✅ Database constraints prevent null values
- ✅ All tests pass
- ✅ Code follows project conventions
- ✅ Code formatted with Pint

## Technical Considerations

- Use Laravel 12 conventions (no `$fillable` due to `Model::unguard()`)
- Follow established PHPDoc patterns from other models
- Use proper foreign key constraints with cascade delete
- Add database indexes for performance
- Include comprehensive test coverage

## Dependencies

- No additional packages required
- Uses existing Laravel features and patterns
- Builds on existing Category and Discussion models

## Timeline Estimate

- Database Migration: 15 minutes
- Model Implementation: 20 minutes
- Factory Implementation: 10 minutes
- Relationship Updates: 15 minutes
- Testing: 30 minutes
- Code Review & Formatting: 20 minutes
- **Total**: ~2 hours

## Package Check

Based on Laravel ecosystem and current requirements:

- No existing packages needed for basic reply functionality
- Laravel Eloquent relationships handle all required functionality
- No need for additional packages at this time
