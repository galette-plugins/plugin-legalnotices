---
title: Galette pravne obavjesti
description: Dodatak za upravljanje pravnim obavijestima
---

Dodatak omogućuje:

* do **3 stranice** za pravne obavijesti:
  - *Pravne informacije* (za opće pravne obavijesti)
  - *Uvjeti korištenja* (ako ih trebate objaviti)
  - *Pravila privatnosti* (za obavijesti o obradi osobnih podataka i uporabi
    kolačića)
* **platformu za upravljanje privolama** za napredne korisnike

## Instalacija

Najprije preuzmite dodatak:

* [Preuzmite najnoviji dodatak Legal
  Notices!](https://github.com/galette-plugins/plugin-legalnotices/releases/latest)
* [Preuzmite noćnu razvojnu verziju dodatka Legal
  Notices!](https://github.com/galette-plugins/plugin-legalnotices/releases/tag/nightly)

Raspakirajte preuzetu arhivu u Galetteovu mapu `plugins`. Primjerice, na Linuxu
(zamijenite *{url}* i *{version}* odgovarajućim vrijednostima):

```
$ cd /var/www/html/galette/plugins
$ wget {url}
$ tar xjvf galette-plugin-legal-notices-{version}.tar.bz2
```

## Inicijalizacija baze podataka

Dodatak zahtijeva nekoliko tablica u bazi. Pogledajte [sučelje za upravljanje
dodacima
Galettea](https://doc.galette.eu/en/master/plugins/index.html#plugins-managment).

Gotovo; dodatak *Legal Notices* je instaliran. :)

## Uporaba dodatka

Nakon instalacije prijavljenom se korisniku u izborniku pojavljuje odjeljak
*Pravne obavijesti*. Administratori i uprava mogu podesiti dodatak i uređivati
sadržaj stranica.

![Izbornik dodatka](images/menu.jpg)

### Postavke

![Stranica postavki](images/settings.jpg)

Ponašanje dodatka možete prilagoditi sljedećim postavkama:

* **Omogući stranicu „Pravne informacije”**
* **Omogući stranicu „Uvjeti korištenja”**
* **Omogući stranicu „Pravila privatnosti”**
* **Premjesti poveznice u izbornik „Javne stranice”**: poveznice se zadano
  dodaju u podnožje. Ovom ih opcijom premještate u izbornik „Javne stranice”.
* **Zamjenski jezik za neprevedene stranice**
* **Uključi upravljanje privolama**
* **Sakrij gumb „Prihvati sve”**: nemojte uključiti ako morate poštovati
  europske propise (GDPR i ePrivacy).
* **Sakrij gumb „Odbijam”**: nemojte uključiti ako morate poštovati europske
  propise (GDPR i ePrivacy).
* **Trajanje kolačića**: najdulje trajanje kolačića koji čuva privolu u
  pregledniku, u danima. Nakon toga korisnika se ponovno pita za privolu.
* **Domena kolačića**: koristite ako želite jednom prikupiti privolu za više
  odgovarajućih domena. Klaro! mora biti prisutan i na ostalim domenama. Zadano
  se koristi trenutačna domena.
* **Uključi „localStorage”**: privola se zadano čuva u kolačiću. Uključite
  opciju ako želite koristiti lokalnu pohranu „localStorage”.

### Sadržaj stranica

![Stranica sadržaja](images/content.jpg)

Sadržaj svake **stranice** može se uređivati na svakom dostupnom **jeziku**.

**Tijelo stranice** može se uređivati vizualnim uređivačem. Dostupne su sljedeće
zamjenske vrijednosti; pojedinosti potražite u pomoći unutar sučelja:

* `{ASSO_NAME}`
* `{ASSO_SLOGAN}`
* `{ASSO_ADDRESS}`
* `{ASSO_ADDRESS_MULTI}`
* `{ASSO_PHONE}`
* `{ASSO_EMAIL}`
* `{ASSO_WEBSITE}`
* `{ASSO_PHONE_LINK}`
* `{ASSO_EMAIL_LINK}`

Ako stranica već postoji, primjerice na web-stranici udruge, možete zadati
**vanjski URL**. Tada se uređivanje tijela stranice isključuje i korisnici se
preusmjeravaju na tu adresu.

Ako oba polja ostanu prazna, prikazuje se sadržaj na *zamjenskom jeziku*
odabranom u postavkama.

### O platformi za upravljanje privolama

> **Napomena** — Uporaba platforme za upravljanje privolama (CMP) zahtijeva
> razumijevanje i pisanje JavaScript koda.

Samo uključivanje CMP-a u postavkama nema posebnu svrhu. Zadano prikazuje samo
obavijest o funkcionalnim kolačićima koje Galette pohranjuje.

![Prozor upravljanja privolama](images/cmp-modal.jpg)

U podnožje se dodaje i poveznica za otvaranje upravljanja privolama nakon
davanja privole.

![Poveznica CMP-a u podnožju](images/cmp-footer.jpg)

CMP je stoga koristan kad dodate vanjske usluge za čije uključivanje treba
privola korisnika, primjerice analitičku uslugu.

![Poruka CMP-a](images/cmp-message.jpg)

### Dodavanje vanjske usluge

> **Napomena** — Dodatak koristi [Klaro!](https://github.com/klaro-org/klaro-js)
> kao platformu za upravljanje privolama (CMP). Sljedeći primjeri pokazuju
> dodavanje jednostavne usluge. Za pojedinosti i bolje razumijevanje pročitajte
> [dokumentaciju Klaro!](https://klaro.org/docs).

1. Najprije u mapi dodatka `templates/default` izradite vlastiti predložak
   `local_klaro_config.html.twig`.

2. Zatim dodajte uslugu u CMP. Sljedeći kod unesite u svoj predložak i
   prilagodite potrebama. Pojedinosti su u dokumentaciji CMP-a, posebice u
   [konfiguracijskoj datoteci s
   komentarima](https://klaro.org/docs/integration/annotated-configuration).

   Primjer:

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

   Dodatak nudi nekoliko unaprijed definiranih vrijednosti `purposes` za
   razvrstavanje usluga u upravljanju privolama:

   * `functional` (usluge ove kategorije obvezne su i korisnik ih ne može
     odbiti)
   * `performance`
   * `analytics`
   * `marketing`
   * `advertising`

3. Na kraju u svoj predložak dodajte kod pružatelja usluge, *uz manje izmjene*
   atributa oznake `script`. Objašnjenje je u drugom dijelu poglavlja [Getting
   Started](https://klaro.org/docs/getting-started) u dokumentaciji CMP-a.

   Primjer:

   ```
   <script type="text/plain" data-type="application/javascript" data-src="https://YOUR_MATOMO_URL" data-name="matomo"></script>
   ```
