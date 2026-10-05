<?php

/**
 * This file is part of Galette Legal Notices plugin (https://galette-plugins.github.io/plugin-legalnotices).
 * SPDX-FileCopyrightText: Copyright © 2025-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

namespace GaletteLegalNotices\tests\units;

use Galette\Tests\GaletteTestCase;
use GaletteLegalNotices\Entity\Settings;

/**
 * Legal Notices plugin tests
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 * @author Guillaume AGNIERAY <dev@agnieray.net>
 */
class PluginGaletteLegalnotices extends GaletteTestCase
{
    protected int $seed = 20261005101244;

    /**
     * Cleanup after each test method
     */
    public function tearDown(): void
    {
        $this->login->logout();
        parent::tearDown();
    }

    /**
     * Set a plugin setting
     *
     * @param string $name  Setting name
     * @param string $value Setting value
     */
    private function setSetting(string $name, string $value): void
    {
        //missing settings are created with their default values
        new Settings($this->zdb);
        $update = $this->zdb->update(LEGALNOTICES_PREFIX . Settings::TABLE);
        $update->set(['value' => $value])->where(['name' => $name]);
        $this->zdb->execute($update);
    }

    /**
     * Get menu items routes
     *
     * @return array<string>
     */
    private function getMenuRoutes(): array
    {
        $plugin = $this->container->get(\GaletteLegalNotices\PluginGaletteLegalnotices::class);
        $menus = $plugin->getMenus();
        return array_map(
            fn($item) => $item['route']['name'],
            $menus['plugin_legalnotices']['items'] ?? []
        );
    }

    /**
     * Test menus by profile
     */
    public function testGetMenus(): void
    {
        $this->logSuperAdmin();
        $this->assertSame(
            [
                'legalnotices_pages',
                'legalnotices_settings',
            ],
            $this->getMenuRoutes()
        );
        $this->login->logout();

        $member = $this->getMemberOne();
        $mdata = $this->dataAdherentOne();
        $this->assertTrue($this->login->login($mdata['login_adh'], $mdata['mdp_adh']));
        $this->assertSame($mdata['login_adh'], $member->login);
        $this->assertSame([], $this->getMenuRoutes());
    }

    /**
     * The public pages are declared to the core, with a visibility of their own
     */
    public function testPublicPages(): void
    {
        $name = 'pref_legalnotices_publicpages_visibility_notices';
        $plugin = $this->container->get(\GaletteLegalNotices\PluginGaletteLegalnotices::class);

        $this->assertSame(['notices' => ['routes' => ['legalnotices_page']]], $plugin->getPublicPages());
        $this->assertSame('Legal Notices', $plugin->getPublicPageLabel('legalnotices_page'));
        $this->assertTrue(\Galette\Core\PreferencesSchema::isPublicPage($name));
        $this->assertSame($name, \Galette\Core\PreferencesSchema::getPublicPageRight('legalnotices_page'));
        $this->assertSame(
            \Galette\Enums\PublicPageVisibility::Inherit->value,
            \Galette\Core\PreferencesSchema::get($name)['default']
        );

        //the public menu entry follows it, not the default visibility
        $this->setRawPreference('pref_bool_publicpages', true);
        $this->setRawPreference(
            'pref_publicpages_visibility_generic',
            \Galette\Enums\PublicPageVisibility::Hidden->value
        );
        $this->setRawPreference($name, \Galette\Enums\PublicPageVisibility::Everyone->value);
        $this->setSetting('publicpage_links', '1');
        $this->setSetting('enable_legal_information', '1');
        $this->assertSame(['legalnotices_page'], array_map(
            fn($item) => $item['route']['name'],
            $plugin->getPublicMenuItems()
        ));

        $this->setRawPreference($name, \Galette\Enums\PublicPageVisibility::Inherit->value);
        $this->assertSame([], $plugin->getPublicMenuItems());
    }
}
