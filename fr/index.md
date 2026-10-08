---
title: Galette Legal Notices
description: Plugin pour gérer des mentions légales
---

Ce plugin fournit :

* jusqu'à **3 pages** pour écrire des mentions légales :
  - *Informations légales* (pour toutes les mentions légales communes)
  - *Conditions générales d'utilisation* (dans le cas où vous avez besoin de
    fournir des telles conditions)
  - *Politique de confidentialité* (pour toutes les mentions concernant le
    traitement de données personnelles et l'utilisation de cookies)
* une **Plateforme de Gestion du Consentement** (pour les utilisateurs avancés)

## Installation

Tout d'abord, téléchargez le plugin :

* [Obtenez le dernier plugin Legal Notices
  !](https://github.com/galette-plugins/plugin-legalnotices/releases/latest)
* [Obtenez la nightly du plugin Legal Notices
  !](https://github.com/galette-plugins/plugin-legalnotices/releases/tag/nightly)

Décompressez l'archive téléchargée dans le répertoire `plugins` de Galette. Par
exemple, sous Linux (en remplaçant *{url}* et *{version}* par les valeurs
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

Une fois le plugin installé, un groupe *Mentions légales* est ajouté au menu de
Galette lorsqu’un utilisateur est connecté, permettant aux administrateurs et
membres du bureau de définir les préférences du plugin et de modifier le contenu
des pages.

![Menu du plugin](images/menu.jpg)

### Préférences

![Écran des préférences](images/settings.jpg)

Plusieurs paramètres permettent de modifier le comportement du plugin :

* **Activer la page "Informations légales"**
* **Activer la page "Conditions générales d'utilisation"**
* **Activer la page "Politique de confidentialité"**
* **Déplace les liens dans le menu "Pages publiques"** : les liens sont ajoutés
  par défaut dans le pied de page. Activez cette option si vous souhaitez les
  déplacer dans le menu "Pages publiques".
* **Langue de secours pour les pages non traduites**
* **Activer le gestionnaire de consentement**
* **Cacher le bouton "Accepter tout"** : n'activez pas cette option si vous
  voulez respecter la législation européenne (GDPR & ePrivacy).
* **Cacher le bouton "Je refuse"** : n'activez pas cette option si vous voulez
  respecter la législation européenne (GDPR & ePrivacy).
* **Durée de vie du cookie** : spécifiez la durée de vie maximale du cookie
  utilisé pour stocker les informations de consentement dans le navigateur (en
  jours). Après cette période, le consentement de l'utilisateur sera demandé de
  nouveau.
* **Domaine du cookie** : utilisez cette option si vous souhaitez demander le
  consentement une seule fois pour plusieurs domaines qui correspondent. Cela
  suppose que vous utilisiez Klaro! également sur les autres domaines. Par
  défaut, le domaine courant est utilisé.
* ** Activer "localStorage"** : par défaut, les informations de consentement
  sont stockées dans le navigateur avec un cookie. Activez cette option si vous
  voulez utiliser "locaStorage" à la place.

### Contenu des pages

![Écran du Contenu des pages](images/content.jpg)

Le contenu de chaque **page** peut être modifié dans toutes les **langues**
disponibles.

Il est possible de modifier le **corps de la page** avec l’éditeur WYSIWYG. Les
valeurs de remplacement suivantes peuvent être utilisées (consultez l’aide
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

Il est également possible de définir une **URL externe** lorsqu'une telle page
existe déjà (sur le site Web de l'association, par exemple). Si elle est
définie, l'édition du corps de la page sera désactivée, et les utilisateurs
seront redirigés vers cette URL.

Si les deux champs restent vides, le contenu de la *langue de secours* choisie
dans les paramètres sera affiché aux utilisateurs.

### À propos de la Plateforme de Gestion du Consentement

> **Note** — L'utilisation de la Plateforme de Gestion du Consentement (PGC)
> exige que vous compreniez et sachiez comment écrire du code JavaScript.

L'activation de la PGC dans les préférences ne fait rien d'utile à elle seule.
Par défaut, cela affichera seulement un message à propos des cookies
fonctionnels stockés par Galette.

![Modale du gestionnaire de consentement](images/cmp-modal.jpg)

Un lien sera également ajouté dans le pied de page pour ouvrir le Gestionnaire
de Consentement une fois que le consentement aura déjà été recueilli.

![Lien de la PGC en bas de page](images/cmp-footer.jpg)

Ainsi, la PGC est uniquement utile lorsque vous ajoutez des services externes
supplémentaires pour lesquels le consentement de l’utilisateur est requis afin
de les activer dans Galette, comme un service d’analyse d’audience.

![Message de la PGC](images/cmp-message.jpg)

### Comment ajouter un service externe ?

> **Note** — Ce plugin utilise [Klaro!](https://github.com/klaro-org/klaro-js)
> comme Plateforme de Gestion du Consentement (PGC). Les exemples de code
> suivants décrivent comment ajouter un service supplémentaire simple. Veuillez
> lire la [documentation de Klaro!](https://klaro.org/docs) pour plus de détails
> et une meilleure compréhension.

1. D'abord, vous devez créer un fichier de gabarit personnalisé nommé
   `local_klaro_config.html.twig` dans le dossier `templates/default` du plugin.

2. Ensuite, vous devez ajouter le service dans la PGC. Utilisez le code suivant
   dans votre fichier de gabarit personnalisé (ajustez-le selon vos besoins ;
   vous pourrez trouver plus de détails dans la documentation de la PGC ;
   veuillez lire le [Fichier de Configuration
   Annoté](https://klaro.org/docs/integration/annotated-configuration)).

   Exemple :

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

   Le plugin fournit plusieurs `purposes` prédéfinis pour organiser des services
   additionnels dans le Gestionnaire de Consentement :

   * `functional` (les services de cette catégorie sont obligatoires et ne
     peuvent pas être refusés par l'utilisateur)
   * `performance`
   * `analytics`
   * `marketing`
   * `advertising`

3. Enfin, vous devez ajouter dans votre fichier de gabarit personnalisé le code
   fourni par le service, *mais avec des modifications mineures* apportées aux
   attributs de la balise `script` (pour plus d'explications, lisez la deuxième
   partie du chapitre [Prise en main](https://klaro.org/docs/getting-started) de
   la documentation de la PGC.

   Exemple :

   ```
   <script type="text/plain" data-type="application/javascript" data-src="https://YOUR_MATOMO_URL" data-name="matomo"></script>
   ```
