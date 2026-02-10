---
name: finalize
description: Finalize the code by fixing code style, statically analysing and refactoring the code.
---

## Command

```bash
composer run finalize
```

## Purpose

This command finalizes the code by:

- Fixing code style using `laravel/pint`
- Statically analysing the code using `phpstan/phpstan`
- Refactoring the code using `rector/rector`
