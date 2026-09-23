<!-- Provides a quick, repeatable release checklist for maintainers of this module. -->
<!-- Copyright (c) 2026 die.internauten.ch GmbH -->
<!-- License: MIT -->

# Release Checklist

Use this checklist for every new release.

## 1) Prepare the Version

- [ ] Choose the next Semantic Version (`MAJOR.MINOR.PATCH`).
- [ ] Update module version in `internautenb2binfo/internautenb2binfo.php`.
- [ ] Update metadata version in `internautenb2binfo/config.xml`.

## 2) Document the Changes

- [ ] Add a new section in `CHANGELOG.md`:

```md
## [1.0.9] - 2026-09-23

### Added

- ...

### Changed

- ...

### Fixed

- ...
```

- [ ] Ensure user-facing behavior changes are reflected in `README.md`.

## 3) Add Upgrade Logic (if needed)

- [ ] If config/schema/data changes are included, add `internautenb2binfo/upgrade/upgrade-X.Y.Z.php`.
- [ ] Implement `upgrade_module_X_Y_Z(...)` with idempotent and safe operations.

## 4) Validate Locally

- [ ] Run PHP syntax checks:

```bash
php -l internautenb2binfo/internautenb2binfo.php
php -l internautenb2binfo/upgrade/upgrade-X.Y.Z.php
```

- [ ] Install/upgrade module in a local PrestaShop instance and verify expected behavior.

## 5) Tag and Release

- [ ] Commit all release-related changes.
- [ ] Create and push tag using the module version:

```bash
./scripts/tag-from-module-version.sh
```

- [ ] Confirm GitHub Actions created and attached the module ZIP.

## 6) Post-Release Verification

- [ ] Check release notes content on GitHub.
- [ ] Verify module version shown in PrestaShop back office.
- [ ] Smoke test key storefront and back-office flows affected by this release.
