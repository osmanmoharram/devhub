---
name: Fix Issue
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
git checkout -b fix/issue-<numbe r>
```

## Implementation Standards

- Follow existing code conventions
- Write tests that match the style of similar tests
- Ensure existing tests still pass
- Run `php artisan test` before considering the work complete
- Run `/finalize` command to ensure code style consistency

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