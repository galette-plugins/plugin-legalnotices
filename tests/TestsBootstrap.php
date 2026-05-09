<?php

/**
 * This file is part of Galette Legal Notices plugin (https://galette-community.github.io/plugin-legalnotices).
 * SPDX-FileCopyrightText: Copyright © 2025-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

/**
 * Bootstrap tests file for Galette Legal Notices
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 * @author Guillaume AGNIERAY <dev@agnieray.net>
 */

define('GALETTE_PLUGINS_PATH', __DIR__ . '/../../');
$basepath = '../../../galette/';

include_once '../../../tests/TestsBootstrap.php';
require_once __DIR__ . '/../_config.inc.php';
