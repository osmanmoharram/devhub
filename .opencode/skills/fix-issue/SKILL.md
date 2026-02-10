---
name: fix-issue
description: Review a Github issue, solve issue, and submit a PR fix. Reach for this skill when you need to work on a GitHub issue.
---

# Fixing GitHub Issues

When working on a GitHub issue in this project, follow this approach.

## Getting Context

Always fetch the issue details first to understand the full context:

```bash
gh issue view <number> --json title,body,labels
```

Read the title, description, and labels to identify:
- Affected files and components.
- Type of fix needed.
- Any related context.

## Branching

Create a branch using the naming convention `fix/issue-<number>`.

```bash
git checkout -b fix/issue-<number>
```

## Before Implementing the Fix

These are the steps you must complete before you start working on the fix:

1. Move to `main` branch and pull the latest changes.
2. Create a new branch using the naming convention `fix/issue-<issue number>`.

## After Implementing The Fix

1. Write tests that match the style of similar tests.
2. Ensure existing tests still pass.
3. Run `/test` command before considering the work complete.
4. Run `/laravel-boost:laravel-code-simplifier` command.
5. Run `/finalize` command to ensure code style consistency

## Committing

Use this commit message format:

```
Fix: <issue title> (#<number>)
```

## Create the PR

Push and create the PR using the `gh` CLI:

```bash
gh pr create --title "Fix: <issue title> (#<number>)" --body "$(cat << 'EOF'

## Summary

Brief summary of what was fixed.

## Changes

- List key changes made

EOF
)"
```

The PR should:

- Reference `Closes #<number>` in the body
- Summarize what was fixed
- Flag any concerns or areas needing review

## Output

When complete, provide me with a brief summary:

- Issue number and title
- What was changed
- Link to the PR