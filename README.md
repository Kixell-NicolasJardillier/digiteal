<p align="center"><a href="https://www.digiteal.eu" target="_blank"><img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/digiteal-doc-header-logo.png"></a></p>

<h1 align="center">Digiteal for PrestaShop</h1>

<p align="center">
  <em>Reinventing invoicing and payment</em><br>
  CB, Visa, Mastercard, Ideal, Bancontact, SDD &amp; SCT
</p>

<p align="center">
  <a href="https://github.com/Kixell-NicolasJardillier/digiteal/releases"><img alt="Latest release" src="https://img.shields.io/github/v/release/Kixell-NicolasJardillier/digiteal?label=release&color=0db5c0"></a>
  <img alt="PrestaShop compatibility" src="https://img.shields.io/badge/PrestaShop-1.6%20%E2%86%92%209-0db5c0">
  <img alt="PHP compatibility" src="https://img.shields.io/badge/PHP-5.6%20%E2%86%92%208.5-777bb4">
  <a href="LICENSE.txt"><img alt="License" src="https://img.shields.io/github/license/Kixell-NicolasJardillier/digiteal?color=0db5c0"></a>
  <img alt="Last commit" src="https://img.shields.io/github/last-commit/Kixell-NicolasJardillier/digiteal?color=0db5c0">
</p>

<p align="center">
  <a href="https://github.com/Kixell-NicolasJardillier/digiteal/raw/main/digiteal.zip"><strong>⬇ Download the module</strong></a>
  &nbsp;·&nbsp;
  <a href="https://www.digiteal.eu/">digiteal.eu</a>
</p>

---

## About Digiteal

#### https://www.digiteal.eu/

| Features | Advantages | Benefits |
|--------------------|----------------|---------------------|
| Multiple payment methods available (Visa*, MC*, Carte Bleue*, Bancontact, Ideal, etc.).<br /><br />* following approval of the compliance team | Smooth user experience thanks to the interface that seamlessly adapts to different screen sizes | Better satisfaction |
| No payment fees deducted during payout | Easier for the accounting department | Better treasury |
| Attractive pricing (transaction fees) | Better margin | More benefits |
| Module compatible with Prestashop 1.6 to 9 | Continuity in case of update | Flexibility |
| EEA coverage (European Economic Area ; from any IBAN = €) | Accept payments from international customers | More potential turnover |
| Easy plug & play module | Quick and easy integration | Reactivity |
| Sandbox to test the different scenarios | Making it easy for the test team | Saving time |
| Go2thepoint documentation | No more dealing with support for hours on end. | Efficiency |
| SCT/PIS support | Low transaction fees and higher amounts supported | Additional benefits |
| Payment process automation | The order/PO is triggered as soon as the payment is confirmed. | Improved stock management and time saving |

## Compatibility

| PrestaShop | Module version | PHP |
|---|---|---|
| 9.0 – 9.1 | 1.0.5 and later | 8.1 – 8.5 |
| 8.0 – 8.2 | 1.0.x | 8.1 – 8.4 |
| 1.7.x | 1.0.x | 7.1 – 8.1 |
| 1.6.x | 1.0.x | 5.6 – 7.4 |

The PHP column follows what each PrestaShop version itself supports. The module's own code is syntax-checked from **PHP 8.0 to 8.5** and uses no type declarations, so it produces no PHP 8.4 deprecation notice.

## Requirements

**Platform**

- **PrestaShop** 1.6 to 9
- **PHP** 5.6 to 8.5, matching what your PrestaShop version requires
- **MariaDB 10.2+** or **MySQL 5.7+** — the module creates no table and adds no database requirement of its own
- **Web server** Apache 2.4+ or Nginx

**PHP configuration**

- **cURL** extension — mandatory, the module will not install without it
- **JSON** extension
- `allow_url_fopen` enabled
- `memory_limit` of at least 256M, 512M on PrestaShop 9

**Network**

- **HTTPS** with a valid certificate — the payment pages are served over SSL
- The shop must be **reachable from the internet**, so Digiteal can send payment notifications. A staging site behind a VPN, an IP filter or an HTTP authentication will not receive them.
- Outgoing HTTPS to `api.digiteal.eu` (production) and `test.digiteal.eu` (sandbox)

**Shop setup**

- A Digiteal account, created and validated from the module configuration
- The currencies you enable for the module in PrestaShop
- No cron job and no manual maintenance task required

## Module available in the following languages :

| EN | FR | ES | IT | NL | DE |
|----|----|----|----|----|----|
| <img width="25" src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/1f49d492f716d64c79f49d0fd3160186f53fa6de/digiteal/en.svg"> | <img width="25" src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/1f49d492f716d64c79f49d0fd3160186f53fa6de/digiteal/fr.svg"> | <img width="25" src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/1f49d492f716d64c79f49d0fd3160186f53fa6de/digiteal/es.svg"> | <img width="25" src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/1f49d492f716d64c79f49d0fd3160186f53fa6de/digiteal/it.svg"> | <img width="25" src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/1f49d492f716d64c79f49d0fd3160186f53fa6de/digiteal/nl.svg"> | <img width="25" src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/1f49d492f716d64c79f49d0fd3160186f53fa6de/digiteal/de.svg"> |

## Installation

Download [`digiteal.zip`](https://github.com/Kixell-NicolasJardillier/digiteal/raw/main/digiteal.zip), then in your back office go to **Modules → Module Manager → Upload a module**. Once installed, open the module configuration and follow the steps below.

### Step 1

Fill your VAT number :

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-1.jpg">

* * *

### Step 2

Fill the name of your company and the email address by which you would like to create an account at Digiteal or the email address of your account at Digiteal if you already have one.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-2.jpg">

* * *

### Step 3 : If you do not have yet an account with Digiteal

You can access this step if you do not yet have an account with Digiteal. This will take you directly to the registration form with the information you previously provided.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-3.jpg">

* * *

### Step 4 : If your account on Digiteal is still not activated

Your account is active but awaiting validation by the Digiteal team.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-5.jpg">

* * *

### Step 5 : Finalize the configuration

> **Your password is never stored on your website or anywhere.**

Credentials are used to finalize the configuration with Digiteal.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-6.jpg">

* * *

### End

You can now accept payments with Digiteal and see payment methods available. If you update your information on the Digiteal side, just click on the update button and the module will retrieve the information.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-7.jpg">

## Support

- Digiteal — [digiteal.eu](https://www.digiteal.eu/)
- Module — [open an issue](https://github.com/Kixell-NicolasJardillier/digiteal/issues) or contact [Kixell](https://kixell.fr)

## License

Released under the [Academic Free License 3.0](LICENSE.txt). © SARL Kixell.
