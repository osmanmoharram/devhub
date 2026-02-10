# Feature Implementation Plan: Discussion Model

## Overview
Create Discussion model with title, content, category_id, user_id for the DevHub forum.

## Implementation Details

### 1. Database Migration
- Create `discussions` table with:
  - `id` (primary key)
  - `title` (string, required)
  - `slug` (string, unique)
  - `content` (text, required)
  - `category_id` (foreign key to categories)
  - `user_id` (foreign key to users)
  - `is_pinned` (boolean, default false)
  - `is_locked` (boolean, default false)
  - `views_count` (integer, default 0)
  - `replies_count` (integer, default 0)
  - `last_reply_at` (timestamp, nullable)
  - `created_at`, `updated_at`

### 2. Discussion Model
- Use Model::unguard() (no $fillable)
- Add proper PHPDoc annotations
- Implement relationships:
  - `category()` - belongsTo Category
  - `user()` - belongsTo User
  - `replies()` - hasMany Reply (future feature)
- Add scopes:
  - `pinned()` - filter pinned discussions
  - `popular()` - order by replies_count
  - `recent()` - order by created_at
- Add casts for boolean and integer fields

### 3. Discussion Resource
- API Resource for mobile app compatibility
- Transform data for JSON responses
- Include relationships when loaded

### 4. Factory
- DiscussionFactory for testing
- Generate realistic discussion titles and content
- Set proper relationships

### 5. Tests
- Unit/Feature tests for Discussion model
- Test relationships, scopes, and resource transformation
- Test slug generation and uniqueness

## Files to Create/Modify
- `database/migrations/xxxx_create_discussions_table.php`
- `app/Models/Discussion.php`
- `app/Http/Resources/DiscussionResource.php`
- `database/factories/DiscussionFactory.php`
- `tests/Feature/DiscussionTest.php`
- Update `app/Models/Category.php` to add discussions relationship

## API-First Considerations
- Resource transformation for mobile consumption
- Proper JSON structure
- Include necessary metadata (timestamps, counts)
