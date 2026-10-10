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

* [Pridobite najnovejši vtičnik za pravna
  obvestila!](https://github.com/galette-plugins/plugin-legalnotices/releases/latest)
* [Pridobite vtičnik za pravna obvestila vsako
  noč!](https://github.com/galette-plugins/plugin-legalnotices/releases/tag/nightly)

Ekstrahirajte preneseni arhiv v imenik Galette `plugins`. Na primer v sistemu
Linux (zamenjava *{url}* in *{version}* z ustreznima vrednostma):

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

Ko je vtičnik nameščen, se v meni Galette, ko je uporabnik prijavljen, doda
skupina *Pravna obvestila*, kar administratorjem in članom osebja omogoča, da
določijo nastavitve vtičnika in urejajo vsebino strani.

![Meni vtičnika](images/menu.jpg)

### Nastavitve

![Zaslon z nastavitvami](images/settings.jpg)

Več nastavitev omogoča spreminjanje delovanja vtičnika:

* **Omogoči stran »Pravne informacije«**
* **Omogoči stran »Pogoji storitve«**
* **Omogoči stran »Politika zasebnosti«**
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
* **Omogoči "localStorage"** : privzeto se informacije o soglasju shranijo v
  brskalnik s piškotkom. Omogočite to možnost, če želite namesto tega uporabiti
  »locaStorage«.

### Vsebina strani

![Zaslon z vsebino strani](images/content.jpg)

Vsebino vsake **strani** je mogoče urejati v vseh razpoložljivih **jezikih**.

Možno je urejati **telo strani** z urejevalnikom WYSIWYG. Uporabite lahko
naslednje nadomestne vrednosti (za podrobnosti si oglejte vgrajeno pomoč v
uporabniškem vmesniku):

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

> **Note** — Uporaba platforme za upravljanje privolitev (CMP) zahteva
> razumevanje in znanje pisanja kode JavaScript.

Omogočanje CMP-ja v nastavitvah samo po sebi ne prinaša nobene koristne
funkcije. Privzeto bo prikazano le obvestilo o funkcionalnih piškotkih, ki jih
shranjuje Galette.

![Modal upravitelja soglasja](images/cmp-modal.jpg)

Dodal bo tudi povezavo v nogo za odpiranje upravitelja soglasja, potem ko je
soglasje že prejeto.

![povezava CMP v nogi](images/cmp-footer.jpg)

Tako je CMP uporaben le, ko dodate dodatne zunanje storitve, za katere je
potrebno soglasje uporabnika, da jih omogočite v Galette, kot je storitev
analitike.

![Sporočilo CMP](images/cmp-message.jpg)

### Kako dodati zunanjo storitev?

> **Note** – Ta vtičnik uporablja
> [Klaro!](https://github.com/klaro-org/klaro-js) kot svojo platformo za
> upravljanje privolitve (CMP). Naslednji primeri kode opisujejo, kako dodati
> preprosto dodatno storitev. Prosim, preberite [Klaro!
> dokumentacija](https://klaro.org/docs) za nadaljnje podrobnosti in boljše
> razumevanje.

1. Najprej morate ustvariti datoteko predloge po meri z imenom
   `local_klaro_config.html.twig` v mapi `templates/default` vtičnika.

2. Nato morate storitev dodati v CMP. Uporabite naslednjo kodo v svoji datoteki
   s predlogo po meri (prilagodite jo svojim potrebam; več podrobnosti najdete v
   dokumentaciji CMP; preberite [Anotated Config
   File](https://klaro.org/docs/integration/annotated-configuration)).

   Primer:

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

   Vtičnik ponuja več vnaprej določenih `purposes` za organiziranje dodatnih
   storitev v Consent Managerju:

   * `functional` (storitve v tej kategoriji so obvezne in jih uporabnik ne more
     zavrniti)
   * `performance`
   * `analytics`
   * `marketing`
   * `advertising`

3. Na koncu morate v svojo datoteko predloge po meri dodati kodo, ki jo ponuja
   storitev, *vendar z manjšimi spremembami* atributov oznake `script` (za več
   razlag preberite drugi del poglavja [Kako
   začeti](https://klaro.org/docs/getting-started) v dokumentaciji CMP).

   Primer:

   ```
   <script type="text/plain" data-type="application/javascript" data-src="https://YOUR_MATOMO_URL" data-name="matomo"></script>
   ```
