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

* **Activer la page "Informations légales"**
* **Activer la page "Conditions d'utilisation"**
* **Activer la page "Politique de confidentialité"**
* **Déplace les lien dans le menu "Pages publiques"** : les liens sont ajoutés
  par défaut dans le pied de page. Activez cette option si vous souhaitez les
  déplacer dans le menu "Pages publiques".
* **Langue de secours pour les pages non traduites**
* **Activer le gestionnaire de consentement**
* **Cacher le bouton "Accepter tout"** : ne pas activer cette option si vous
  voulez respecter la législation européenne (GDPR & ePrivacy).
* **Cache le bouton "Je décline"** : n'activez pas cette option si vous voulez
  respecter la législation européenne (GDPR & ePrivacy).
* **Durée de vie du cookie** : spécifiez la durée de vie maximale du cookie
  utilisé pour stocker les informations de consentement dans le navigateur (en
  jours). Après cette période, le consentement de l'utilisateur sera demandé de
  nouveau.
* **Domaine du cookie** : utilisez ceci si vous souhaitez demander le
  consentement une fois pour plusieurs domaines qui correspondent. Cela suppose
  que vous utilisiez Klaro! également sur les autres domaines. Par défaut, le
  domaine courant est utilisé.
* ** Activer "localStorage"** : par défaut, les informations de consentement
  sont stockées dans le navigateur avec un cookie. Activez cette option si vous
  voulez utiliser "locaStorage" à la place. Si elle est activée, le réglage des
  options ci-dessus relatives au cookie devient indifférent.

### Contenu des pages

![Contenu des pages](images/content.jpg)

Le contenu de chaque **page** peut être modifié dans chaque **langue**
disponible.

Il est possible de modifier le **contenu de la page** avec l’éditeur WYSIWYG.
Les valeurs de remplacement suivantes peuvent être utilisées (consultez l’aide
contextuelle de l’interface pour plus de détails) :

* `{ASSO_NAME}`
* `{ASSO_SLOGAN}`
* `{ASSO_ADDRESS}`
* `{ASSO_ADDRESS_MULTI}`
* `{ASSO_PHONE}`
* `{ASSO_EMAIL}`
* `{ASSO_WEBSITE}`
* `{ASSO_PHONE_LINK}`
* `{ASSO_EMAIL_LINK}`

Il est également possible de définir une **URL externe** lorsque cette page
existe déjà (sur le site Web de l'association, par exemple). Si elle est
définie, l'édition du corps de page sera désactivée, et les utilisateurs seront
redirigés vers cette URL.

Si les deux champs restent vides, le contenu de la *langue de remplacement*
choisie dans les paramètres sera affiché aux utilisateurs.

### À propos de la plateforme de gestion du consentement

> **Note** — L'utilisation du Consent Management Plateform (CMP) exige que vous
> compreniez et sachiez comment écrire un code JavaScript.

L'activation de la CMP dans les paramètres ne fait rien d'utile à elle seule.
Par défaut, il affichera seulement un message sur les cookies fonctionnels
stockés par Galette.

![Consent Manager modal](images/cmp-modal.jpg)

Un lien sera également ajouté dans le pied de page pour ouvrir le gestionnaire
de consentement une fois que le consentement a déjà été donné.

![CMP link in footer](images/cmp-footer.jpg)

Ainsi, le CMP est uniquement utile lorsque vous ajoutez des services externes
supplémentaires pour lesquels le consentement de l’utilisateur est requis afin
de les activer dans Galette, comme un service d’analyse d’audience.

![CMP message](images/cmp-message.jpg)

### Comment ajouter un service externe ?

> **Note** — Ce plugin utilise [Klaro!](https://github.com/klaro-org/klaro-js)
> comme plate-forme de gestion des consentements (CMP). Les exemples de code
> suivants décrivent comment ajouter un simple service supplémentaire. Veuillez
> lire [Klaro! documentation](https://klaro.org/docs) pour plus de détails et
> une meilleure compréhension.

1. D'abord, vous devez créer un fichier de template personnalisé nommé
   `local_klaro_config.html.twig` dans le dossier `templates/default` du plugin.

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
