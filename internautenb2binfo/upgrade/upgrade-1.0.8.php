<?php
declare(strict_types=1);

// Defines the module upgrade step to version 1.0.8 and normalizes default config values.
// Copyright (c) 2026 die.internauten.ch GmbH
// License: MIT

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_8(InternautenB2BInfo $module): bool
{
    if (Configuration::get('INTERNAUTENB2BINFO_ENABLED') === false) {
        return Configuration::updateValue('INTERNAUTENB2BINFO_ENABLED', 1);
    }

    return true;
}
