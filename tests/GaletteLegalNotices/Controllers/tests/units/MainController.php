<?php

/**
 * This file is part of Galette Legal Notices plugin (https://galette-plugins.github.io/plugin-legalnotices).
 * SPDX-FileCopyrightText: Copyright © 2025-2026 The Galette Team
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

declare(strict_types=1);

namespace GaletteLegalNotices\Controllers\tests\units;

use Galette\Tests\GaletteRoutingTestCase;
use GaletteLegalNotices\Entity\Pages;
use GaletteLegalNotices\Entity\Settings;

/**
 * Legal Notices controller tests
 *
 * @author Johan Cwiklinski <johan@x-tnd.be>
 */
class MainController extends GaletteRoutingTestCase
{
    protected int $seed = 20260928071512;
    protected bool $load_plugins = true;

    private const string UNSAFE_BODY = '<p onclick="alert(1)">Who we are, write to <a href="mailto:{ASSO_EMAIL}">us</a></p>'
        . '<script>alert(2)</script><img src="x" onerror="alert(3)">';

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
     * Get stored body of a page
     *
     * @param string $name Page name
     */
    private function getStoredBody(string $name): string
    {
        $select = $this->zdb->select(LEGALNOTICES_PREFIX . Pages::TABLE);
        $select->where(['name' => $name, 'lang' => \Galette\Core\I18n::DEFAULT_LANG]);
        return $this->zdb->execute($select)->current()->body;
    }

    /**
     * Assert body is safe, and its patterns and markup kept
     *
     * @param string $body Body
     */
    private function assertSafeBody(string $body): void
    {
        //payloads are unique: a page layout has its own scripts
        foreach (['alert(1)', 'alert(2)', 'alert(3)'] as $payload) {
            $this->assertStringNotContainsString($payload, $body);
        }
        $this->assertStringContainsString('Who we are, write to', $body);
    }

    /**
     * Content of pages is cleaned when stored
     */
    public function testStoredContentIsCleaned(): void
    {
        $pages = new Pages($this->preferences, $this->routeparser);
        $pages->getPages('legal-information', \Galette\Core\I18n::DEFAULT_LANG);
        $this->assertTrue(
            $pages->storePageContent('legal-information', \Galette\Core\I18n::DEFAULT_LANG, self::UNSAFE_BODY, '')
        );

        $stored = $this->getStoredBody('legal-information');
        $this->assertSafeBody($stored);
        //patterns in attributes are still replaced
        $this->assertStringContainsString('<a href="mailto:{ASSO_EMAIL}">us</a>', $stored);
    }

    /**
     * Pages are displayed without dangerous content, even stored before it was cleaned
     */
    public function testViewPageIsCleaned(): void
    {
        $this->preferences->pref_email_nom = 'Galette';
        $this->preferences->pref_org_email = 'contact@example.org';
        $this->setSetting('enable_legal_information', '1');
        $pages = new Pages($this->preferences, $this->routeparser);
        $pages->getPages('legal-information', \Galette\Core\I18n::DEFAULT_LANG);
        $update = $this->zdb->update(LEGALNOTICES_PREFIX . Pages::TABLE);
        $update
            ->set(['body' => self::UNSAFE_BODY . '<p>{ASSO_EMAIL_LINK}</p>'])
            ->where(['name' => 'legal-information', 'lang' => \Galette\Core\I18n::DEFAULT_LANG]);
        $this->zdb->execute($update);

        $test_response = $this->app->handle(
            $this->createRequest('legalnotices_page', ['name' => 'legal-information'])
        );
        $this->assertSame(200, $test_response->getStatusCode());
        $this->expectNoLogEntry();

        $body = (string)$test_response->getBody();
        $this->assertSafeBody($body);
        //replacements are done after cleaning
        $this->assertStringContainsString('href="mailto:contact@example.org"', $body);
        $this->assertStringContainsString(
            '<span class="obfuscate"><span class="u">contact</span> [at] <span class="d">example<span class="p"> [dot] </span>org</span></span>',
            $body
        );
    }
}
