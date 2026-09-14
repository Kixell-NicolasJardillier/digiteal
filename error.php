<?php

/**
 * NOTICE OF LICENSE.
 *
 * Digiteal for PrestaShop is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/afl-3.0.php
 *
 * @author    SARL KIXELL (https://kixell.fr)
 * @copyright Copyright © 2021 - SARL Kixell
 * @license   https://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * @version   1.0.5
 */

/*
 * LEGACY ENTRY POINT — PAYMENT_INITIATION_ERROR webhook.
 *
 * Kept for the shops that registered this URL with Digiteal before module version 1.0.5, on
 * Prestashop 1.5 to 8. Since 1.0.5 the webhook is served by the front controller
 * digiteal/notifyerror, which is the only route that works on Prestashop 9.
 *
 * Do not add logic here : everything lives in DigitealWebhook so that both routes behave the same.
 */
require_once dirname(dirname(dirname(__FILE__))).'/config/config.inc.php';
require_once dirname(__FILE__).'/digiteal.php';

$outcome = DigitealWebhook::handlePaymentInitiationError();

exit('<p style="display: none">'.$outcome.'</p>');
