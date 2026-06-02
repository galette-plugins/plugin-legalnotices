<?php

/**
 * This file is part of Galette Legal Notices plugin (https://galette-community.github.io/plugin-legalnotices).
 * SPDX-FileCopyrightText: Copyright © 2025-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

/** @var \Galette\Core\Plugins $this */
$this->register(
    name: 'Galette Legal Notices',           //Name
    desc: 'Manage legal notices in Galette', //Short description
    author: 'Guillaume AGNIERAY',            //Author
    version: '1.0.0',                        //Version
    compver: '1.3.0',                        //Galette compatible version
    route: 'legalnotices',                   //Routing name and translation domain
    date: '2025-10-17',                      //Release date
    acls: [                                  //Permissions needed
        'legalnotices_settings' => 'admin',
        'legalnotices_store_settings' => 'admin',
        'legalnotices_pages' => 'staff',
        'legalnotices_page_change' => 'staff',
        'legalnotices_page_edit' => 'staff'
    ],
    priority: 9999,                          //Priority
    dbver: 1.00                              //DB version
);
