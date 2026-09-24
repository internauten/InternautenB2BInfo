<!-- Explains module usage, installation, and release workflow for Internauten B2B Info. -->
<!-- Copyright (c) 2026 die.internauten.ch GmbH -->
<!-- License: MIT -->

# PrestaShop Internauten B2B Info Module

[![Sponsor](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa?logo=githubsponsors&style=for-the-badge)](https://github.com/sponsors/internauten)
[![Stars](https://img.shields.io/github/stars/internauten/InternautenB2BInfo?style=for-the-badge)](https://github.com/internauten/InternautenB2BInfo/stargazers)
[![Release](https://img.shields.io/github/v/release/internauten/InternautenB2BInfo?style=for-the-badge)](https://github.com/internauten/InternautenB2BInfo/releases)
[![License](https://img.shields.io/github/license/internauten/InternautenB2BInfo?style=for-the-badge)](LICENSE)

A PrestaShop module that displays custom text on product pages for customers belonging to specific groups.

## Features

- Display custom messages on product pages based on customer group membership
- Displays Text only if there is no reduction for this group
- Displays the original selling price (default customer group, without the group's specific price) if there is a reduction
- Easy configuration via module settings
- Enable/disable functionality
- Responsive Bootstrap styling
- Translated into German (`de`), English (`en`), French (`fr`) and Italian (`it`)
- Tested with PrestaShop 9.1.4

## Installation

1. Get latest release ZIP File or complete source
2. Copy the `internautenb2binfo` folder to your PrestaShop installation
3. Go to Back Office → Modules → Module Manager
4. Find "Internauten B2B Info" and click Install
5. Configure the module settings

## Configuration

- **Enable module**: Toggle the module on/off
- **Reseller group**: Select which customer group will see the message
- **Message**: Enter the text to display to the selected group

## How It Works

The module uses the `displayProductPriceBlock` hook (type `after_price`) to render content after the product price.

Output is only rendered when all of the following conditions are met:

- The module is enabled.
- The visitor is logged in (guests never see any output).
- The customer's **default group** (`id_default_group`) matches the configured customer group. Additional group memberships are intentionally ignored.

If these conditions are met, the output depends on the product's price:

- **No reduction or specific price for this group:** the configured message is displayed.
- **Reduction or specific price for this group:** instead of the message, the original selling price is displayed ("Regular price: …"). It is calculated for the default customer group (`PS_CUSTOMER_GROUP`), ignoring group- or customer-specific prices and reductions, and is always shown including VAT (if taxes are enabled in the shop).

In all other cases (module disabled, guest, other default group) the module renders nothing.

## Release via GitHub Actions

For a fast step-by-step release routine, use [RELEASE_CHECKLIST.md](RELEASE_CHECKLIST.md).

How to create a new release that builds the ZIP and attaches it to the GitHub Release:

1. Ensure `CHANGELOG.md` contains a section for the tag, for example:

   ```md
   ## v1.2.3

   - Short description of the changes.
   ```

2. Commit your changes and push them to `main`.

3. Create a tag and push it to GitHub:

   ```bash
   git tag v1.2.3
   git push origin v1.2.3
   ```

4. GitHub Actions runs the workflow, creates `internautenb2binfo.zip`, and attaches it to the release.

Notes:

- The release text is taken from the matching section in `CHANGELOG.md`.
- If no section is present, commit messages are used as release notes automatically.

## Version History and Upgrades

For each new release, keep these three artifacts in sync:

1. Update the module version in `internautenb2binfo/internautenb2binfo.php`.
2. Update the metadata version in `internautenb2binfo/config.xml`.
3. Add a new section in `CHANGELOG.md` using this format:

   ```md
   ## [1.0.9] - 2026-09-23

   ### Added

   - ...

   ### Changed

   - ...

   ### Fixed

   - ...
   ```

If an update changes configuration, schema, or stored values, also create an upgrade script in `internautenb2binfo/upgrade/` named `upgrade-X.Y.Z.php` with the function `upgrade_module_X_Y_Z(...)`.

### Create and push tag from module version

You can create and push a release tag directly from the version in `internautenb2binfo/internautenb2binfo.php` (`$this->version`) with:

```bash
./scripts/tag-from-module-version.sh
```

On Windows (PowerShell), use one of the following commands:

```powershell
./scripts/tag-from-module-version.ps1
# or
bash ./scripts/tag-from-module-version.sh
```

The script reads the module version, creates an annotated tag in the format `v<version>`, and pushes it to `origin`.

Use a dry-run to preview the tag command without creating or pushing anything:

```bash
./scripts/tag-from-module-version.sh --dry-run
```

```powershell
./scripts/tag-from-module-version.ps1 -DryRun
```

## Get Module and install it

1. git clone yor fork of this repo
   ```bash
   cd ~
   git clone https://github.com/yourgithub/InternautenB2BInfo.git
   ```
2. set owner, goup and rights
   ```bash
   sudo chown -R www-data:www-data ~/InternautenB2BInfo/internautenb2binfo
   ```
3. Create symlink and set group:owner
   ```bash
   sudo ln -s ~/InternautenB2BInfo/internautenb2binfo /var/www/prestashop/modules/internautenb2binfo
   sudo chown -h www-data:www-data ~/InternautenB2BInfo/internautenb2binfo
   sudo chown -h www-data:www-data /var/www/prestashop/modules/internautenb2binfo
   ```
4. Activate and configure Module in Prestashop  
   In Prestashop backend go to Module Manager / not installed Modules and install the module.

## License

This project is licensed under the MIT License. See details [`LICENSE`](LICENSE).

Copyright (c) 2026 die.internauten.ch GmbH
