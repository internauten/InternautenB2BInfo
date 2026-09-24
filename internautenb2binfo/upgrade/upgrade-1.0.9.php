<?php
declare(strict_types=1);

// Defines the module upgrade step to version 1.0.9 (no data migration required).
// Copyright (c) 2026 die.internauten.ch GmbH
// License: MIT

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_9(InternautenB2BInfo $module): bool
{
    return true;
}
