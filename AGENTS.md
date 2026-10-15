<!--
Shared instructions for AI coding agents (GitHub Copilot, Claude Code, Codex, etc.) working in this repository.
Copyright (c) 2026 die.internauten.ch GmbH
License: MIT
-->

# Agent Instructions for InternautenB2BInfo

This file contains the instructions for AI coding agents in this repository.
`CLAUDE.md` imports this file. `.github/copilot-instructions.md` contains an identical copy of the rules below.
When changing any rule, update both files in the same commit.

## Project context

- PrestaShop module. The module folder is `internautenb2binfo/` (lowercase); the main class file is `internautenb2binfo/internautenb2binfo.php`.
- The module shows a configurable, per-language message (or the regular price) in the product price block for a selected customer group (hook `displayProductPriceBlock`).
- Target platforms:
  - PrestaShop >= 9.1.4
  - PHP >= 8.3.31
- This repository also serves as a reference example for AI-assisted development.

## Language

- Chat answers, explanations, and suggestions: **German**.
- Code, code comments, commit messages, and all Markdown files (including README files): **English**.

## Working principles

- Keep changes small, understandable, and purposeful.
- Do not change business logic without clear justification in the PR description or commit message.
- Maintain backward compatibility unless a breaking change is explicitly required.
- Avoid destructive bulk changes such as sweeping formatting updates without a clear benefit.
- When requirements are unclear, state your assumptions before proceeding.
- For changes touching more than 3 files or altering module behavior, first present a short step-by-step plan and wait for confirmation before implementing.

## Coding standards

- Use `declare(strict_types=1);` in all new PHP files.
- Use clear naming and keep each class or function focused on a single responsibility.
- Prefer explicit types for parameters, return values, and properties.
- Keep functions short and easy to test.
- Only add inline comments where the logic would otherwise be difficult to follow.

### File headers

Every new or modified file must include a brief file header describing its purpose, a copyright notice for die.internauten.ch GmbH, and a reference to the MIT license.

- PHP files: directly below `declare(strict_types=1);`
- Other file types: at the top of the file, using the file type's comment syntax (e.g. `{* ... *}` in Smarty `.tpl`, `<!-- ... -->` in HTML/Markdown).

```php
// Provides the API endpoints for synchronizing external stock levels.
// Copyright (c) 2026 die.internauten.ch GmbH
// License: MIT
```

## PrestaShop module-specific guidance

- Keep the structure of the `internautenb2binfo/` folder consistent (`views/templates/hook`, `translations`, `upgrade`).
- Every directory inside the module must contain the standard PrestaShop `index.php` guard file.
- Only change hook names, service IDs, configuration keys, and database structures when there is a clear migration strategy.
- Database or configuration changes require an upgrade script `internautenb2binfo/upgrade/upgrade-X.Y.Z.php` matching the new module version.
- The module is loaded locally into the PrestaShop environment from `WoWGetPrestaLocal`.
- The PrestaShop core reference code is at `../WoWGetPrestaLocal/html` (relative to this repository). Use it as the primary reference for analysis, comparison, and compatibility checks.

### Translations

- Never hardcode user-facing text (back office or front end) in PHP, templates, or other source files.
- This module uses the **legacy module translation system**:
  - PHP: `$this->l('...')` in the module class
  - Smarty: `{l s='...' mod='internautenb2binfo'}`
  - Translation files: `internautenb2binfo/translations/{en,de,fr,it}.php`
- Do not switch to the Symfony `trans()` / translation domain system unless explicitly requested.
- Keep `en`, `de` (German, Switzerland), `fr`, and `it` aligned whenever strings are added or changed.
- In German texts use proper umlauts (`ä`, `ö`, `ü`, `ß`), never ASCII substitutes.

## Local development environment

- Docker Compose project: `../WoWGetPrestaLocal/docker-compose.yml`. Run `docker compose` commands from `../WoWGetPrestaLocal`.
- Services / containers: `db` (`wow-presta-db`, MariaDB), `php` (`wow-presta-php`, PHP-FPM), `nginx` (`wow-presta-nginx`).
- Front office: `http://localhost:8081`. The back office URL, DB credentials, and DB name are in `AGENTS.local.md` (not committed); read it when needed.
- The `internautenb2binfo/` folder is mounted into the shop via Docker volume at `modules/internautenb2binfo`, so file changes are live without copying.
- Clear the cache after template, hook, or config changes: `docker compose exec php sh -c 'rm -rf var/cache/prod var/cache/dev'`
- Logs: `../WoWGetPrestaLocal/html/var/logs/`
- DB table prefix: `ps_` (use `_DB_PREFIX_` in code, never a hardcoded prefix).
- Never write credentials or the back office folder name into committed files.

## Versioning and documentation

- The module version is defined in `internautenb2binfo/internautenb2binfo.php` as `$this->version = 'X.Y.Z';`. Keep this exact format; the release script parses it.
- Increment the version (semantic versioning) for every user-visible change, fix, or feature. New features trigger a minor bump (e.g. `1.1.1` -> `1.2.0`), fixes a patch bump.
- Add a matching section `## [X.Y.Z] - YYYY-MM-DD` to `CHANGELOG.md`. The release workflow extracts the release notes from this heading format.
- Update `README.md` when functionality or behavior changes.
- Follow `RELEASE_CHECKLIST.md` when preparing a release.

## Quality assurance

- Add or update suitable tests when changing code. If no automated tests exist, document concrete manual test steps in the PR or final answer.
- Before finishing, check PHP syntax of changed files:

  ```bash
  find internautenb2binfo -name '*.php' -print0 | xargs -0 -n1 php -l
  ```

- Error messages must be clear and useful to developers and must not expose sensitive data.

## Release conventions

- Release tags follow the pattern `vX.Y.Z`.
- Tags are created with `scripts/tag-from-module-version.sh` (or `scripts/tag-from-module-version.ps1`; the shell script supports `--dry-run`), which reads the version from the module file.
- `.github/workflows/zip-internautenb2binfo.yml` zips the `internautenb2binfo/` folder and, on tag push, creates a GitHub release with notes from `CHANGELOG.md`. Do not rename or move that folder, and do not break the version line format or the changelog heading format.
