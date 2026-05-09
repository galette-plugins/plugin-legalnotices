<?php

/**
 * This file is part of Galette Legal Notices plugin (https://galette-community.github.io/plugin-legalnotices).
 * SPDX-FileCopyrightText: Copyright © 2025-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

namespace GaletteLegalNotices;

use DI\Attribute\Inject;
use Galette\Core\Db;
use Galette\Core\Login;
use Galette\Core\Plugins\MenuProviderInterface;
use Galette\Core\GalettePlugin;
use GaletteLegalNotices\Entity\Pages;
use GaletteLegalNotices\Entity\Settings;

/**
 * Plugin Galette Legal Notices
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 * @author Guillaume AGNIERAY <dev@agnieray.net>
 */

class PluginGaletteLegalnotices extends GalettePlugin implements MenuProviderInterface
{
    #[Inject]
    private readonly Db $zdb; //@phpstan-ignore-line injected from DI

    /**
     * Get plugins menus
     *
     * @return array<string, string|array<string,mixed>>
     */
    public function getMenus(): array
    {
        /** @var Login $login */
        global $login;
        $menus = [];
        $items = [];

        if ($login->isAdmin() || $login->isStaff()) {
            $items[] = [
                'label' => _T("Pages content", "legalnotices"),
                'route' => [
                    'name' => 'legalnotices_pages'
                ]
            ];
        }

        if ($login->isAdmin()) {
            $items[] = [
                'label' => _T("Settings"),
                'route' => [
                    'name' => 'legalnotices_settings'
                ]
            ];
        }

        if ($login->isAdmin() || $login->isStaff()) {
            $menus['plugin_legalnotices'] = [
                'title' => _T("Legal Notices", "legalnotices"),
                'icon' => 'balance scale',
                'items' => $items
            ];
        }

        return $menus;
    }

    /**
     * Get plugins public menus
     *
     * @return array<int, string|array<string,mixed>>
     */
    public function getPublicMenus(): array
    {
        $settings = new Settings($this->zdb);
        $items = [];
        $children = [];

        if ($settings->enable_legal_information) {
            $children[] = [
                'label' => _T("Legal Information", "legalnotices"),
                'route' => [
                    'name' => 'legalnotices_page',
                    'args' => ['name' => 'legal-information']
                ],
                'icon' => 'balance scale left'
            ];
        }
        if ($settings->enable_terms_of_service) {
            $children[] = [
                'label' => _T("Terms of Service", "legalnotices"),
                'route' => [
                    'name' => 'legalnotices_page',
                    'args' => ['name' => 'terms-of-service']
                ],
                'icon' => 'handshake outline'
            ];
        }
        if ($settings->enable_privacy_policy) {
            $children[] = [
                'label' => _T("Privacy Policy", "legalnotices"),
                'route' => [
                    'name' => 'legalnotices_page',
                    'args' => ['name' => 'privacy-policy']

                ],
                'icon' => 'lock'
            ];
        }

        if ($settings->publicpage_links) {
            if (count($children) > 1) {
                $items[] = [
                    'label' => _T("Legal Notices", "legalnotices"),
                    'icon' => 'balance scale',
                    'children' => $children
                ];
            } else {
                $items = array_merge($items, $children);
            }
        }

        return $items;
    }

    /**
     * Get plugin settings
     *
     * @return array<string>
     */
    public static function getPluginSettings(): array
    {
        /** @var Db $zdb */
        global $zdb;
        $settings = new Settings($zdb);
        return $settings->getSettings();
    }

    /**
     * Is the plugin fully installed (including database, extra configuration, etc.)?
     */
    public function isInstalled(): bool
    {
        return
            $this->zdb->tableExists(LEGALNOTICES_PREFIX . Pages::TABLE)
            && $this->zdb->tableExists(LEGALNOTICES_PREFIX . Settings::TABLE);
    }
}
