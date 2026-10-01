---
title: Galette Legal Notices
description: Plugin pour gérer des pages de mentions légales
---

Ce plugin fournit :

* jusqu'à **3 pages** pour écrire des mentions légales :
  - *Informations légales* (pour toutes les mentions légales)
  - *Conditions d'utilisation* (dans le cas où vous fournissez des telles
    conditions)
  - *Politique de confidentialité* (pour tous les avis concernant le traitement
    des données personnelles et des cookies)
* une **plate forme de gestion de consentement** (pour les utilisateurs avancés)

## Installation

Tout d'abord, téléchargez le plugin :

[![Obtenir le dernier plugin Legal Notices
!](https://img.shields.io/badge/1.0.0-LegalNotices-ffb619?style=for-the-badge&logo=php&logoColor=white&label=1.0.0&color=ffb619)](https://github.com/galette-plugins/plugin-legalnotices/releases/tag/1.0.0)
[![Obtenir la nightly du plugin Legal Notices
!](https://img.shields.io/badge/Nightly-LegalNotices-ffb619?style=for-the-badge&logo=php&logoColor=white&label=Nightly&color=ffb619)](https://galette.eu/download/plugins/galette-plugin-legal-notices-dev.tar.bz2)

Décompressez l'archive téléchargée dans le répertoire `plugins` de Galette. Par
exemple, sous linux (en remplaçant *{url}* et *{version}* par les valeurs
correspondantes) :

```
$ cd /var/www/html/galette/plugins
$ wget {url}
$ tar xjvf galette-plugin-legal-notices-{version}.tar.bz2
```

## Initialisation de la base de données

Pour fonctionner, ce plugin requiert des tables dans la base de données.
Référez-vous [à l'interface de gestion des plugins de
Galette](https://doc.galette.eu/en/master/plugins/index.html#plugins-managment).

Et c'est tout ; le plugin *Legal Notices* est installé. :)

## Utilisation

Lorsque le plugin est installé, un groupe *Legal Notices* est ajouté au menu de
Galette lorsqu'un utilisateur est connecté, permettant aux administrateurs et
membres du bureau de définir les préférences du plugin et modifier le contenu
des pages.

![Menu du plugin](images/menu.jpg)

### Paramètres

![Écran des préférences](images/settings.jpg)

Plusieurs paramètres permettent de modifier le comportement du plugin :

* **Enable the "Legal Information" page**
* **Enable the "Terms of Service" page**
* **Enable the "Privacy Policy" page**
* **Move links in the "Public pages" menu** : links are added by default in the
  footer. Enable this option if you want to move them in the "Public pages"
  menu.
* **Fallback language for untranslated pages**
* **Enable the consent manager**
* **Hide the "Accept all" button** : do not enable this option if you need to
  comply with the european legislation (GDPR & ePrivacy).
* **Hide the "I decline" button** : do not enable this option if you need to
  comply with the european legislation (GDPR & ePrivacy).
* **Cookie lifetime** : specify the maximum lifetime of the cookie used to store
  consent information in the browser (in days). After this period, the user's
  consent will be requested again.
* **Cookie domain** : use this if you want to get consent once for multiple
  matching domains. This supposes you are using Klaro! too on the other domains.
  By default, the current domain is used.
* **Enable "localStorage"** : by default, consent information is stored in the
  browser with a cookie. Enable this option if you want to use "locaStorage"
  instead. If enabled, setting the options above related to the cookie becomes
  irrelevant.

### Contenu des pages

![Pages content screen](images/content.jpg)

The content of each **page** can be edited in every available **language**.

It is possible to edit the **page body** with the WYSIWYG editor. The following
replacements values can be used (refer to the inline help from the user
interface to get details) :

* `{ASSO_NAME}`
* `{ASSO_SLOGAN}`
* `{ASSO_ADDRESS}`
* `{ASSO_ADDRESS_MULTI}`
* `{ASSO_PHONE}`
* `{ASSO_EMAIL}`
* `{ASSO_WEBSITE}`
* `{ASSO_PHONE_LINK}`
* `{ASSO_EMAIL_LINK}`

It is also possible to define an **external URL** when such a page already
exists (on the association's website, for example). If it is defined, editing of
the page body will be disabled, and users will be redirected to this URL.

If both fields remain empty, the content of the *fallback language* chosen in
the settings will be displayed to the users.

### About the Consent Manager Platform

> **Note** — Using the Consent Management Plateform (CMP) requires that you
> understand and know how to write JavaScript code.

Enabling the CMP in the settings does nothing useful on its own. By default, it
will only display a message about the functional cookies stored by Galette.

![Consent Manager modal](images/cmp-modal.jpg)

It will also add a link in the footer to open the Consent Manager after consent
has already been received.

![CMP link in footer](images/cmp-footer.jpg)

Thus, the CMP is only useful when you add additional external services for which
user consent is required to enable them in Galette, such as an analytics
service.

![CMP message](images/cmp-message.jpg)

### How to add an external service ?

> **Note** — This plugin uses [Klaro!](https://github.com/klaro-org/klaro-js) as
> its Consent Management Platform (CMP). The following code examples describe
> how to add a simple additional service. Please, read [Klaro!
> documentation](https://klaro.org/docs) for further details and a better
> understanding.

1. First, you have to create a custom template file named
   `local_klaro_config.html.twig` in the `templates/default` folder of the
   plugin.

2. Then, you need to add the service in the CMP. Use the following code in your
   custom template file (adjust it to your needs ; you can find more details in
   the documentation of the CMP ; please read the [Annotated Config
   File](https://klaro.org/docs/integration/annotated-configuration)).

   Example :

   ```
   <script type="text/javascript">
       let matomo = {
           name: 'matomo',
           purposes: ['analytics'],
           translations: {
               zz: {
                   title: 'Matomo'
               }
           }
       };
       klaroConfig.services.push(matomo);
   </script>
   ```

   The plugin provides several predefined `purposes` to organize addtional
   services in the Consent Manager :

   * `functional` (services in this category are mandatory and cannot be
     declined by the user)
   * `performance`
   * `analytics`
   * `marketing`
   * `advertising`

3. Finally, you need to add in your custom template file the actual code
   provided by the service, *but with minor changes* to the attributes of the
   `script` tag (for more explanations, read the second part of the [Getting
   Started](https://klaro.org/docs/getting-started) chapter in the documentation
   of the CMP).

   Example :

   ```
   <script type="text/plain" data-type="application/javascript" data-src="https://YOUR_MATOMO_URL" data-name="matomo"></script>
   ```
