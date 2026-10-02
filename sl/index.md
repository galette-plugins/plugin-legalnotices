---
title: Pravna obvestila družbe Galette
description: Vtičnik za upravljanje pravnih obvestil
---

Ta vtičnik omogoča:

* do **3 strani** za zapis pravnih obvestil :
  - *Pravne informacije* (za vsa splošna pravna obvestila)
  - *Pogoji storitve* (če morate zagotoviti takšne pogoje)
  - *Politika zasebnosti* (za vsa obvestila v zvezi z obdelavo osebnih podatkov
    in uporabo piškotkov)
* **platforma za upravljanje privolitev** (za napredne uporabnike)

## Namestitev

Najprej prenesite vtičnik:

[![Prenesite najnovejši vtičnik Legal
Notices!](https://img.shields.io/badge/1.0.0-LegalNotices-ffb619?style=for-the-badge&logo=php&logoColor=white&label=1.0.0&color=ffb619)](https://github.com/galette-plugins/plugin-legalnotices/releases/tag/1.0.0)
[![Prenesite nočno gradnjo vtičnika Legal
Notices!](https://img.shields.io/badge/Nightly-LegalNotices-ffb619?style=for-the-badge&logo=php&logoColor=white&label=Nightly&color=ffb619)](https://galette.eu/download/plugins/galette-plugin-legal-notices-dev.tar.bz2)

Razširite preneseni arhiv v mapo `plugins` programa Galette. Na primer v sistemu
Linux (pri čemer *{url}* in *{version}* nadomestite z ustreznimi vrednostmi):

```
$ cd /var/www/html/galette/plugins
$ wget {url}
$ tar xjvf galette-plugin-legal-notices-{version}.tar.bz2
```

## Inicializacija podatkovne baze

Za delovanje ta vtičnik potrebuje več tabel v podatkovni bazi. Glejte [vmesnik
za upravljanje vtičnikov
Galette](https://doc.galette.eu/en/master/plugins/index.html#plugins-managment).

In to je to; vtičnik *Legal Notices* je nameščen. :)

## Uporaba vtičnika

Po namestitvi vtičnika se v meniju programa Galette – ko je uporabnik prijavljen
– prikaže skupina *Pravna obvestila*, ki skrbnikom in osebju omogoča določanje
nastavitev vtičnika ter urejanje vsebine strani.

![Meni vtičnika](images/menu.jpg)

### Nastavitve

![Zaslon z nastavitvami](images/settings.jpg)

Več nastavitev omogoča spreminjanje delovanja vtičnika:

* **Enable the "Legal Information" page**
* **Enable the "Terms of Service" page**
* **Enable the "Privacy Policy" page**
* **Premik povezav v meni »Javne strani«** : povezave so privzeto dodane v nogo.
  Omogočite to možnost, če jih želite premakniti v meni "Javne strani".
* **Nadomestni jezik za neprevedene strani**
* **Omogoči upravitelja privolitev**
* **Skrij gumb »Sprejmi vse«** : te možnosti ne omogočite, če morate biti
  skladni z evropsko zakonodajo (GDPR in e-zasebnost).
* **Skrij gumb »Zavrni«** : te možnosti ne omogočite, če morate biti skladni z
  evropsko zakonodajo (GDPR in e-zasebnost).
* **Trajanje piškotka**: določite najdaljše trajanje piškotka, ki se uporablja
  za shranjevanje podatkov o privolitvi v brskalniku (v dneh). Po tem obdobju bo
  od uporabnika ponovno zahtevana privolitev.
* **Domena piškotka**: to možnost uporabite, če želite pridobiti soglasje enkrat
  za več ustreznih domen. To predpostavlja, da na drugih domenah prav tako
  uporabljate Klaro!. Privzeto se uporabi trenutna domena.
* **Omogoči "localStorage"**: privzeto se podatki o privolitvi v brskalniku
  shranijo v piškotek. To možnost omogočite, če želite namesto tega uporabiti
  "localStorage". Če je ta možnost omogočena, nastavitve zgoraj, ki se nanašajo
  na piškotek, niso več pomembne.

### Vsebina strani

![Zaslon z vsebino strani](images/content.jpg)

Vsebino vsake **strani** je mogoče urejati v vseh razpoložljivih **jezikih**.

Vsebino strani lahko urejate z urejevalnikom WYSIWYG. Uporabite lahko naslednje
nadomestne vrednosti (za podrobnosti glejte vgrajeno pomoč v uporabniškem
vmesniku):

* `{ASSO_NAME}`
* `{ASSO_SLOGAN}`
* `{ASSO_ADDRESS}`
* `{ASSO_ADDRESS_MULTI}`
* `{ASSO_PHONE}`
* `{ASSO_EMAIL}`
* `{ASSO_WEBSITE}`
* `{ASSO_PHONE_LINK}`
* `{ASSO_EMAIL_LINK}`

Prav tako je mogoče določiti **zunanji URL**, če takšna stran že obstaja (na
primer na spletnem mestu združenja). Če je ta določen, bo urejanje vsebine
strani onemogočeno, uporabniki pa bodo preusmerjeni na navedeni URL.

Če obe polji ostaneta prazni, se uporabnikom prikaže vsebina v *nadomestnem
jeziku*, izbranem v nastavitvah.

### O platformi za upravljanje privolitev

> **Opomba** — Uporaba platforme za upravljanje privolitev (CMP) zahteva
> razumevanje in znanje pisanja kode JavaScript.

Omogočanje CMP-ja v nastavitvah samo po sebi ne prinaša nobene koristne
funkcije. Privzeto bo prikazano le obvestilo o funkcionalnih piškotkih, ki jih
shranjuje Galette.

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
