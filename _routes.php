<?php

/**
 * This file is part of Galette Legal Notices plugin (https://galette-community.github.io/plugin-legalnotices).
 * SPDX-FileCopyrightText: Copyright © 2025-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

use Galette\Middleware\Authenticate;
use GaletteLegalNotices\Controllers\MainController;

//Constants and classes from plugin
require_once $module['root'] . '/_config.inc.php';

$app->get(
    '/settings',
    [MainController::class, 'settings']
)->setName('legalnotices_settings')->add(Authenticate::class);

$app->post(
    '/settings',
    [MainController::class, 'storeSettings']
)->setName('legalnotices_store_settings')->add(Authenticate::class);

$app->get(
    '/pages[/{lang}/{name}]',
    [MainController::class, 'listPages']
)->setName('legalnotices_pages')->add(Authenticate::class);

$app->post(
    '/pages/change',
    [MainController::class, 'changePage']
)->setName('legalnotices_page_change')->add(Authenticate::class);

$app->post(
    '/pages',
    [MainController::class, 'editPage']
)->setName('legalnotices_page_edit')->add(Authenticate::class);

$app->get(
    '/{name:legal-information|terms-of-service|privacy-policy}',
    [MainController::class, 'viewPage']
)->setName('legalnotices_page');
