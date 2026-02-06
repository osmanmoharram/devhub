# Application Guidelines

These are application-wide guidelines.

## Eloquent Models
- Never use `$fillable` or `$guarded`, we use `Model::unguard()` globally.

## Writing Code 
- Follow Laravel best practices and conventions.
- Use latest code standards and practices.
- **Type safety**: Use strict typing and avoid `mixed` types when possible.
- **Performance**: Optimize queries and avoid N+1 problems.
- **Security**: Follow security best practices and avoid common vulnerabilities.
- **Code quality**: Write clean, readable, and maintainable code. 
- **Documentation**: Document complex logic and business rules.

Note: the terms "task" and "feature" are used interchangeably in this project.