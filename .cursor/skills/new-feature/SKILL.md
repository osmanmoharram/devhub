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

## Implementation Standards

- Move to `main` branch and pull the latest changes.
- Create a new branch using the naming convention `develop/feature-<feature title>`.
- Generate a file called `FEATURE_IMPLEMENTATION.md` in the root of the project that outlines the implementation plan.
- Make sure to follow the application guidelines.
- When developing the feature, first check for the existence of a package that allows you to implement the feature with less code, if one exists, tell me about it and wait for my response, if none exists, implement the feature from scratch.
- Write tests that match the style of similar tests
- Ensure existing tests still pass
- Run `php artisan test` before considering the work complete
- Run `/finalize` command to ensure code style consistency

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