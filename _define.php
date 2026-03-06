<?php

/**
 * Copyright © 2003-2026 The Galette Team
 *
 * This file is part of Galette Legal Notices plugin (https://galette-community.github.io/plugin-legalnotices).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

/** @var \Galette\Core\Plugins $this */
$this->register(
    name: 'Galette Legal Notices',           //Name
    desc: 'Manage legal notices in Galette', //Short description
    author: 'Guillaume AGNIERAY',            //Author
    version: '1.0.0',                        //Version
    compver: '1.2.0',                        //Galette compatible version
    route: 'legalnotices',                   //Routing name and translation domain
    date: '2025-10-17',                      //Release date
    acls: [                                  //Permissions needed
        'legalnotices_settings' => 'admin',
        'legalnotices_store_settings' => 'admin',
        'legalnotices_pages' => 'staff',
        'legalnotices_page_change' => 'staff',
        'legalnotices_page_edit' => 'staff'
    ],
    priority: 9999                           //Priority
);
