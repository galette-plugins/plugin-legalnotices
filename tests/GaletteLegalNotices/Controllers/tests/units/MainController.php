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

    /**
     * Get a plugin setting, as stored
     *
     * @param string $name Setting name
     */
    private function getSetting(string $name): string
    {
        $select = $this->zdb->select(LEGALNOTICES_PREFIX . Settings::TABLE);
        $select->where(['name' => $name]);
        return (string)$this->zdb->execute($select)->current()->value;
    }

    /**
     * Settings written in the scripts of every page are checked, and escaped
     */
    public function testConsentManagerSettings(): void
    {
        $this->setSetting('cookie_domain', 'example.org');
        $this->logSuperAdmin();

        $request = $this->createRequest('legalnotices_store_settings', [], 'POST')->withParsedBody([
            'enable_cmp' => '1',
            'cookie_expiration' => '30; alert(4)//',
            'cookie_domain' => "x'; alert(5)//",
            'fallback_language' => '<b>'
        ]);
        $test_response = $this->app->handle($request);
        $this->assertSame(301, $test_response->getStatusCode());
        $this->expectFlashData(['success_detected' => [_T('Legal Notices settings have been saved.', 'legalnotices')]]);
        $this->expectLogEntry(\Analog\Analog::WARNING, 'Invalid value for Legal Notices setting');
        $this->expectNoLogEntry();

        $this->assertSame('1', $this->getSetting('enable_cmp'));
        $this->assertSame('90', $this->getSetting('cookie_expiration'));
        $this->assertSame('example.org', $this->getSetting('cookie_domain'));
        $this->assertSame(\Galette\Core\I18n::DEFAULT_LANG, $this->getSetting('fallback_language'));

        $request = $this->createRequest('legalnotices_store_settings', [], 'POST')->withParsedBody([
            'enable_cmp' => '1',
            'cookie_expiration' => '30',
            'cookie_domain' => '.example.org',
            'fallback_language' => 'fr_FR'
        ]);
        $this->app->handle($request);
        $this->expectFlashData(['success_detected' => [_T('Legal Notices settings have been saved.', 'legalnotices')]]);
        $this->assertSame('30', $this->getSetting('cookie_expiration'));
        $this->assertSame('.example.org', $this->getSetting('cookie_domain'));
        $this->assertSame('fr_FR', $this->getSetting('fallback_language'));

        //values stored before they were checked are escaped
        $this->setSetting('cookie_expiration', '30; alert(4)//');
        $this->setSetting('cookie_domain', "x'; alert(5)//");
        $this->setSetting('enable_legal_information', '1');
        $body = (string)$this->app->handle(
            $this->createRequest('legalnotices_page', ['name' => 'legal-information'])
        )->getBody();
        $this->assertStringContainsString('klaroConfig', $body);
        $this->assertStringNotContainsString('alert(4)', $body);
        $this->assertStringNotContainsString("x'; alert(5)", $body);
        $this->expectNoLogEntry();
    }

    /**
     * Pages set to an external URL redirect to it, not permanently
     */
    public function testExternalUrl(): void
    {
        $this->setSetting('enable_legal_information', '1');
        $pages = new Pages($this->preferences, $this->routeparser);
        $pages->getPages('legal-information', \Galette\Core\I18n::DEFAULT_LANG);
        $pages->storePageContent('legal-information', \Galette\Core\I18n::DEFAULT_LANG, '', 'https://example.org/legal');

        $test_response = $this->app->handle(
            $this->createRequest('legalnotices_page', ['name' => 'legal-information'])
        );
        $this->assertSame(302, $test_response->getStatusCode());
        $this->assertSame(['https://example.org/legal'], $test_response->getHeader('Location'));
        $this->expectNoLogEntry();
    }

    /**
     * Post a page content
     *
     * @param array<string, string> $data Posted data
     */
    private function postPage(array $data): \Psr\Http\Message\ResponseInterface
    {
        $request = $this->createRequest('legalnotices_page_edit', [], 'POST')->withParsedBody($data);
        return $this->app->handle($request);
    }

    /**
     * Only known pages, in known languages, can be edited; missing ones are added
     */
    public function testEditPage(): void
    {
        $this->logSuperAdmin();
        $delete = $this->zdb->delete(LEGALNOTICES_PREFIX . Pages::TABLE);
        $this->zdb->execute($delete);

        foreach (
            [
                ['cur_name' => 'unknown', 'cur_lang' => 'fr_FR'],
                ['cur_name' => 'legal-information', 'cur_lang' => 'xx_XX'],
                ['page_body' => '<p>Hi</p>']
            ] as $data
        ) {
            $this->assertSame(404, $this->postPage($data + ['page_body' => '<p>Hi</p>', 'external_url' => ''])->getStatusCode());
            $this->expectNoLogEntry();
        }
        $select = $this->zdb->select(LEGALNOTICES_PREFIX . Pages::TABLE);
        $select->where(['body' => '<p>Hi</p>']);
        $this->assertSame(0, $this->zdb->execute($select)->count());

        //page does not exist yet in database
        $test_response = $this->postPage([
            'cur_name' => 'privacy-policy',
            'cur_lang' => 'fr_FR',
            'cur_label' => 'Privacy Policy',
            'page_body' => '<p>Nos données</p>',
            'external_url' => ''
        ]);
        $this->assertSame(301, $test_response->getStatusCode());
        $this->expectNoLogEntry();
        $select = $this->zdb->select(LEGALNOTICES_PREFIX . Pages::TABLE);
        $select->where(['name' => 'privacy-policy', 'lang' => 'fr_FR']);
        $this->assertSame('<p>Nos données</p>', $this->zdb->execute($select)->current()->body);
    }

    /**
     * Displaying a page does not load every language
     */
    public function testViewPageLoadsOneLanguage(): void
    {
        global $galette_log_var;

        $this->setSetting('enable_legal_information', '1');
        (new Pages($this->preferences, $this->routeparser))->installInit();

        $galette_log_var = null;
        $test_response = $this->app->handle(
            $this->createRequest('legalnotices_page', ['name' => 'legal-information'])
        );
        $this->assertSame(200, $test_response->getStatusCode());
        $this->assertLessThanOrEqual(2, substr_count((string)$galette_log_var, 'Trying to set locale'));
        $this->expectNoLogEntry();
    }

    /**
     * Consent manager texts are escaped for scripts
     */
    public function testConsentManagerScript(): void
    {
        $this->setSetting('enable_cmp', '1');
        $this->setSetting('enable_legal_information', '1');

        $test_response = $this->app->handle(
            $this->createRequest('legalnotices_page', ['name' => 'legal-information'])
        );
        $this->assertSame(200, $test_response->getStatusCode());
        $this->expectNoLogEntry();
        $body = (string)$test_response->getBody();
        $this->assertStringContainsString('ok: "That\\u0027s\\u0020ok"', $body);
        $this->assertStringNotContainsString('footer_links.insertAdjacentHTML', $body);
    }
}
