# Feature Implementation: Create Discussion Form

## Feature Overview

Implement Create Discussion Form as Feature #7 from MVP roadmap. This feature enables users to create new discussion threads within categories, serving as the primary content creation interface for the DevHub forum.

## Requirements from Roadmap

- **Create Discussion Form** - Form to create new discussion threads
- Must follow existing code patterns and conventions
- Use existing Discussion model
- Support Arabic/English language context
- Be responsive and accessible
- Include proper validation and error handling
- Support rich text content with code snippets

## Implementation Plan

### 1. Backend Implementation

#### Controller

**File**: `app/Http/Controllers/DiscussionController.php`

- Create new DiscussionController with `create()` and `store()` methods
- `create()` method returns form page with category selection
- `store()` method handles form submission with validation
- Validation rules for title (required, max 255) and content (required)
- Assign authenticated user and selected category
- Generate unique slug from title
- Redirect to newly created discussion

#### Route Addition

**File**: `routes/web.php`

- `GET /discussions/create` - Show create discussion form
- `POST /discussions` - Store new discussion
- Use middleware for authentication
- Use Wayfinder for type-safe routing

#### Form Request Validation

**File**: `app/Http/Requests/StoreDiscussionRequest.php`

- Form request class for validation
- Custom error messages
- Proper validation rules

### 2. Frontend Implementation

#### React Components

**File**: `resources/js/pages/discussions/create.tsx`

- Discussion creation form with title and content fields
- Category selection dropdown populated from active categories
- Rich text editor with basic formatting (or simple textarea initially)
- Form validation and error display
- Success feedback and redirection
- Responsive design with Tailwind CSS

**File**: `resources/js/pages/discussions/edit.tsx` (Future, not in this feature)

#### Navigation Updates

- Add "Create Discussion" button to category detail page
- Add link to create form from categories listing
- Update navigation breadcrumbs

### 3. Data Flow

1. User clicks "Create Discussion" button
2. Navigate to `/discussions/create` with category pre-selected if applicable
3. User fills title, content, and selects category
4. Form submission validates input on client and server
5. Discussion created with user and category assignment
6. Generate slug and redirect to discussion detail

### 4. Testing Strategy

**File**: `tests/Feature/CreateDiscussionTest.php`

- Test create discussion page loads for authenticated users
- Test unauthenticated users are redirected to login
- Test form validation for missing title/content
- Test successful discussion creation
- Test category selection and assignment
- Test slug generation and uniqueness
- Test redirection behavior (temporarily to categories since detail page doesn't exist yet)
- Test form displays properly on mobile

## Acceptance Criteria

- ✅ Create discussion page loads for authenticated users
- ✅ Unauthenticated users redirected to login
- ✅ Form includes title, content, and category fields
- ✅ Validation shows appropriate error messages
- ✅ Successful creation redirects appropriately
- ✅ Discussion is properly associated with user and category
- ✅ Unique slug generated from title
- ✅ Form is responsive on mobile/desktop
- ✅ Arabic/English text supported
- ✅ All tests pass
- ✅ Code follows project conventions
- ✅ Code formatted with Pint

## Technical Considerations

- Use Form Request validation for clean controller code
- Implement proper CSRF protection
- Use route model binding for clean URLs
- Consider rich text editor for future enhancement
- Handle concurrent slug generation properly
- Support Middle East context in form design
- Ensure proper error handling and user feedback

## Dependencies

- Uses existing Discussion and Category models
- Inertia.js for SPA navigation
- React for component rendering
- Tailwind CSS for styling
- Wayfinder for type-safe routes
- Laravel validation for form handling

## Package Check

Based on Laravel ecosystem and current requirements:

- **Rich Text Editor**: Consider `tiptap` or `quill` for future
- **Current Implementation**: Simple textarea with basic formatting
- No additional packages needed for basic implementation
- All core dependencies already available in the project

## Timeline Estimate

- Backend Controller: 45 minutes
- Route Configuration: 15 minutes
- Frontend Component: 75 minutes
- Form Request Validation: 20 minutes
- Navigation Updates: 20 minutes
- Testing: 60 minutes
- Code Review & Formatting: 20 minutes
- **Total**: ~3.5 hours

## Implementation Order

1. Backend DiscussionController and routes
2. Form Request validation class
3. Frontend create discussion component
4. Navigation buttons and links
5. Testing and validation
6. Code finalization
