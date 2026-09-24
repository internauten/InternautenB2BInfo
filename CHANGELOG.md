<!-- Documents the release history and notable module changes for maintainers and merchants. -->
<!-- Copyright (c) 2026 die.internauten.ch GmbH -->
<!-- License: MIT -->

# Changelog

All notable changes to this project are documented in this file.

## [1.0.9] - 2026-09-24

### Fixed

- The price block now shows the original selling price (default customer group, without group-specific prices or reductions) instead of the group's special price.
- Removed leftover debug output from the price block template.

### Changed

- Renamed the price label from "Catalog price" to "Regular price".

## [1.0.8] - 2026-09-23

### Added

- Added upgrade script [internautenb2binfo/upgrade/upgrade-1.0.8.php](internautenb2binfo/upgrade/upgrade-1.0.8.php) to provide a clear migration step for module updates.

### Changed

- Aligned version metadata across module files to `1.0.8`.
- Standardized release-history structure for future entries.

## [1.0.7] - 2026-09-22

### Changed

- Improved multilingual configuration handling for group messages.

## [1.0.2] - 2026-09-16

### Changed

- Updated naming consistency.

## [1.0.1] - 2026-09-16

### Changed

- Moved all user-facing texts to translation files.

## [1.0.0] - 2026-09-16

### Added

- Initial release of the PrestaShop Group Price Text module.

## [0.1.0] - 2026-09-16

### Added

- Initial tagged release without release build.
