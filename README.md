# PrestaShop Internauten B2B Info Module

A PrestaShop module that displays custom text on product pages for customers belonging to specific groups.

## Features

- Display custom messages on product pages based on customer group membership
- Displays Text only if there is no reduction for this group
- Displays the original catalog prise to if there is a reduction
- Easy configuration via module settings
- Enable/disable functionality
- Responsive Bootstrap styling
- Compatible with PrestaShop 1.7+

## Installation

1. Get latest release ZIP File or complete source
2. Copy the `internautenb2binfo` folder to your PrestaShop installation
3. Go to Back Office → Modules → Module Manager
4. Find "Internauten B2B Info" and click Install
5. Configure the module settings

## Configuration

- **Enable module**: Toggle the module on/off
- **Customer Group**: Select which customer group will see the message
- **Message**: Enter the text to display to the selected group

## How It Works

The module uses the `displayProductPriceBlock` hook to display text after the product price. It checks if the logged-in customer belongs to the configured group and displays the custom message accordingly.

## Release via GitHub Actions

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

Academic Free License (AFL 3.0)
