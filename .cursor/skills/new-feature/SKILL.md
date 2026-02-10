---
name: new-feature
description: Develop new features for the application. Reach for this skill when you need to develop a new feature.
---

# New Feature Workflow

When working on a new feature, follow this workflow.

## Getting Context

Read the feature request or user story carefully to:
- Understand the full context.
- Identify affected files and components if any.
- Identify any related context.

## Branching

Create a branch using the naming convention `develop/feature-<feature title>`.

```bash
git checkout -b develop/feature-<feature title>
```

## Before Implementing The Feature

These are the steps you must complete before you start working on the feature:

1. Move to `main` branch and pull the latest changes.
2. Create a new branch using the naming convention `develop/feature-<feature title>`.
3. Generate a file called `FEATURE_IMPLEMENTATION.md` in the root of the project that outlines the implementation plan.
4. Check for the existenance of a package that can help implement the feature with less code, if one exists, tell me about it and wait for my response, if none exists, implement the feature from scratch.
5. Make sure to follow the application guidelines.

## After Implementing The Feature

These are the steps you must complete after you finshed working on the feature:

1. Write tests that match the style of similar tests.
2. Ensure existing tests still pass.
3. Run `php artisan test` before considering the work complete.
4. Run `/laravel-boost:laravel-code-simplifier`.
5. Run `/finalize` command to ensure code style consistency.

## Committing

Use this commit message format:

```
Feature: <feature title>
```

## Create the PR

Push and create the PR using the `gh` CLI:

```bash
gh pr create --title "Feature: <feature title>" --body "$(cat << 'EOF'

## Summary

Brief summary of what was implemented.

## Changes

- List key changes made

EOF
)"
```

The PR should:

- Summarize what was implemented
- Flag any concerns or areas needing review

## Output

When complete, provide me with a brief summary:

- Feature title
- What was implemented
- Link to the PR