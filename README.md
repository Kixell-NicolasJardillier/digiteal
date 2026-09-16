<p align="center"><a href="https://www.digiteal.eu" target="_blank"><img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/digiteal-doc-header-logo.png"></a></p>

<h1 align="center">Digiteal for PrestaShop</h1>

<p align="center">
  <em>Reinventing invoicing and payment</em><br>
  Accept CB, Visa, Mastercard, Bancontact, iDEAL, SDD and SCT payments in your shop.
</p>

<p align="center">
  <a href="https://github.com/Kixell-NicolasJardillier/digiteal/releases"><img alt="Latest release" src="https://img.shields.io/github/v/release/Kixell-NicolasJardillier/digiteal?label=release&color=0db5c0"></a>
  <img alt="PrestaShop compatibility" src="https://img.shields.io/badge/PrestaShop-1.6%20%E2%86%92%209.1-0db5c0">
  <img alt="PHP compatibility" src="https://img.shields.io/badge/PHP-8.0%20%E2%86%92%208.5-777bb4">
  <a href="LICENSE.txt"><img alt="License" src="https://img.shields.io/github/license/Kixell-NicolasJardillier/digiteal?color=0db5c0"></a>
  <img alt="Last commit" src="https://img.shields.io/github/last-commit/Kixell-NicolasJardillier/digiteal?color=0db5c0">
</p>

<p align="center">
  <a href="https://github.com/Kixell-NicolasJardillier/digiteal/raw/main/digiteal.zip"><strong>⬇ Download the module</strong></a>
  &nbsp;·&nbsp;
  <a href="https://www.digiteal.eu/">digiteal.eu</a>
  &nbsp;·&nbsp;
  <a href="https://doc.digiteal.eu/">API documentation</a>
</p>

---

## Why Digiteal

| | |
|---|---|
| **Many payment methods** | Visa, Mastercard, Carte Bleue, Bancontact, iDEAL and more.<sup>*</sup> One checkout, adapted to every screen size. |
| **No fees deducted at payout** | Transaction fees are billed separately, which keeps your accounting simple. |
| **Attractive transaction fees** | Better margins on every order. |
| **Full EEA coverage** | Get paid from any euro IBAN across the European Economic Area. |
| **SCT / PIS support** | Low fees and support for high amounts. |
| **Automated order flow** | The order is created as soon as the payment is confirmed, so your stock stays accurate. |
| **Sandbox included** | Run through every scenario before going live. |

<sup>*</sup> Subject to approval by the Digiteal compliance team.

## Compatibility

| PrestaShop | Module version | PHP |
|---|---|---|
| 9.0 – 9.1 | 1.0.5 and later | 8.1 – 8.5 |
| 8.0 – 8.2 | 1.0.x | 8.1 – 8.4 |
| 1.7.x | 1.0.x | 7.1 – 8.1 |
| 1.6.x | 1.0.x | 5.6 – 7.4 |

The PHP column follows what each PrestaShop version itself supports. The module's own code is syntax-checked from **PHP 8.0 to 8.5** and uses no type declarations, so it produces no PHP 8.4 deprecation notice.

## Requirements

- **PrestaShop** 1.6 to 9.1.
- **PHP** as required by your PrestaShop version.
- **MariaDB 10.2+** or **MySQL 5.7+** — the module creates no table and adds no database requirement of its own.
- **cURL** PHP extension — mandatory. The module will not install without it.
- **HTTPS**, and a shop reachable from the internet so Digiteal can send payment notifications.

## Available languages

| 🇬🇧 EN | 🇫🇷 FR | 🇪🇸 ES | 🇮🇹 IT | 🇳🇱 NL | 🇩🇪 DE |
|----|----|----|----|----|----|

## Installation

Download [`digiteal.zip`](https://github.com/Kixell-NicolasJardillier/digiteal/raw/main/digiteal.zip), then in your back office go to **Modules → Module Manager → Upload a module**. Once installed, open the module configuration and follow the steps below.

### 1. Enter your VAT number

We use it to check your company status with Digiteal and guide you through the rest of the setup.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-1.jpg">

### 2. Company name and email

Give the name of your company and the email address you want to use for your Digiteal account — or the one of your existing account.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-2.jpg">

### 3. Create your Digiteal account

Only if you do not have one yet. The registration form opens pre-filled with the information you just provided.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-3.jpg">

### 4. Wait for validation

Your account is active and awaiting validation by the Digiteal team.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-5.jpg">

### 5. Finalize the configuration

Your credentials are used once to complete the setup with Digiteal. **Your password is never stored, neither on your website nor anywhere else.**

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-6.jpg">

### Done

You can now accept payments and see which methods are available. If you change your details on the Digiteal side, click the update button and the module fetches them again.

<img src="https://raw.githubusercontent.com/Kixell-NicolasJardillier/art/main/digiteal/step-7.jpg">

## Support

- Digiteal — [digiteal.eu](https://www.digiteal.eu/) · [API documentation](https://doc.digiteal.eu/)
- Module — [open an issue](https://github.com/Kixell-NicolasJardillier/digiteal/issues) or contact [Kixell](https://kixell.fr)

## License

Released under the [Academic Free License 3.0](LICENSE.txt). © SARL Kixell.
