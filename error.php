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
 * @version   1.0.2
 */
require_once dirname(dirname(dirname(__FILE__))).'/config/config.inc.php';
require_once dirname(__FILE__).'/digiteal.php';

DigitealLogger::logError('[Payment initiation error]');
// Retrieve body content
try {
    $payment_data = file_get_contents('php://input');
} catch (Exception $e) {
    DigitealLogger::logError($e->getMessage());
    exit();
}

try {
    $payment_array = json_decode($payment_data, true);
} catch (Exception $e) {
    DigitealLogger::logError($e->getMessage());
    exit();
}

if (DigitealTools::checkWebhookPaymentInitiationError($payment_array)) {
    $remittanceInfo = $payment_array['paymentRequestInformation']['remittanceInfo'];
    $remittanceInfo = explode('-', $remittanceInfo);
    if (count($remittanceInfo) === 2 && $remittanceInfo[0] === 'cart') {
        $cart_id = (int) $remittanceInfo[1];
        $cart = new Cart($cart_id);
        if (Validate::isLoadedObject($cart)) {
            try {
                DigitealTools::buildPrestashopContext($cart);
            } catch (Exception $e) {
                DigitealLogger::logError($e->getMessage());
                exit('<p style="display: none">Exception to build Prestashop context: '.$e->getMessage().'</p>');
            }
        } else {
            DigitealLogger::logError('Cart not loaded properly : '.var_export($payment_data, true));
            exit('<p style="display: none">Cart not loaded properly</p>');
        }
    } else {
        DigitealLogger::logError('Cannot retrieve cart id from remittance info : '.var_export($payment_data, true));
        exit('<p style="display: none">Cannot retrieve cart id from remittance info</p>');
    }
} else {
    DigitealLogger::logError('Check data from payment initiation error failed : '.var_export($payment_data, true));
    exit('<p style="display: none">Check data from payment  initiation error failed</p>');
}

exit();
