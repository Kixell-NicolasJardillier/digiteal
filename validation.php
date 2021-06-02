<?php
/**
 * NOTICE OF LICENSE
 *
 * Digiteal for PrestaShop is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/afl-3.0.php
 *
 * @author    SARL KIXELL (https://kixell.fr)
 * @copyright Copyright © 2021 - SARL Kixell
 * @license   https://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 * @package   digiteal
 * @version   1.0.0
 */

require_once dirname(dirname(dirname(__FILE__))) . '/config/config.inc.php';
require_once dirname(__FILE__) . '/digiteal.php';

try {
    $payment_data = file_get_contents('php://input');
} catch (Exception $e) {
    DigitealLogger::logError($e->getMessage());
    die();
}

try {
    $payment_array = json_decode($payment_data, true);
} catch (Exception $e) {
    DigitealLogger::logError($e->getMessage());
    die();
}

if (DigitealTools::checkWebhookPaymentInitiated($payment_array)) {
    $remittanceInfo = $payment_array['paymentRequestInformation']['remittanceInfo'];
    $remittanceInfo = explode('-', $remittanceInfo);
    if (count($remittanceInfo) === 2 && $remittanceInfo[0] === 'cart') {
        $cart_id = (int)$remittanceInfo[1];
        $cart = new Cart($cart_id);
        if (Validate::isLoadedObject($cart)) {
            if ($cart->nbProducts() > 0) {
                try {
                    DigitealTools::buildPrestashopContext($cart);
                } catch (Exception $e) {
                    DigitealLogger::logError($e->getMessage());
                    die('<p style="display: none">Exception to build Prestashop context: '.$e->getMessage().'</p>');
                }

                $order_id = Order::getOrderByCartId($cart_id);
                if ($order_id !== false) { // Order already exist
                    DigitealLogger::logInfo('Order '. $order_id.' already exist with cart ' . $cart_id);

                    // Retrieve state of the order :
                    $order = new Order((int)$order_id);
                    $old_state = (int)$order->getCurrentState();
                    DigitealLogger::logInfo('Current state for order '. $order_id.' (cart ' . $cart_id . ') is : '.$old_state);
                    $state = (int)Configuration::get('PS_OS_PAYMENT');
                    /* TODO
                     * if (($old_state === $state)) {

                    } else {

                    }*/
                } else { // Order does not exist
                    DigitealLogger::logInfo('Create order for cart id ' . $cart->id);

                    // Retrieve customer from cart.
                    $customer = new Customer((int) $cart->id_customer);

                    // Retrieve currency used
                    $currency = $payment_array['paymentRequestInformation']['currency'];
                    $currency_id = Currency::getIdByIsoCode($currency);

                    // Real paid through payment gateway.
                    $total_paid = (float)$payment_array['paymentRequestInformation']['amountInCents'] / 100;

                    // Compare with only two digits the total amount of the cart and the total really paid by the customer.
                    // Then re-assign the total amount of the cart to the variable $total_paid used to validate the order.
                    // The aim is to avoid somme decimals problems that could occur with some versions of Prestashop.
                    $cart_total_two_digits = (float)((int)($cart->getOrderTotal() * 100))/100;
                    if ($cart_total_two_digits === $total_paid) {
                        $total_paid = $cart->getOrderTotal();
                    }

                    DigitealLogger::logInfo('Remittance info amount ' . $total_paid);

                    // Set the title of the order transaction that will displayed in the backoffice
                    $paymentMethod = $payment_array['paymentMethod'];
                    $title = $paymentMethod . ' [Digiteal]';

                    // Add transaction_id to the extra data to retrieve the information in the backoffice
                    $extra_vars = array();
                    $extra_vars['transaction_id'] = $payment_array['bankTransactionID'];

                    // Generate an explicite message with the order
                    $message_data = array(
                        'transaction_id' => $payment_array['bankTransactionID'],
                        'execution_date' => $payment_array['executionTimestamp'],
                        'data' => $payment_array,
                    );
                    $message = json_encode($message_data);

                    // Set the status for payment accepted
                    $state = Configuration::get('PS_OS_PAYMENT');

                    $digiteal = new Digiteal();
                    // Call payment module validateOrder.
                    $digiteal->validateOrder(
                        $cart->id,
                        $state,
                        $total_paid,
                        $title,
                        $message,
                        $extra_vars,
                        $currency_id,
                        true,
                        $customer->secure_key
                    );
                    DigitealLogger::logInfo('Digiteal->validateOrder called for cart id ' . $cart->id);
                }
            } else {
                DigitealLogger::logError('Shopping cart has no products : ' . var_export($payment_data, true));
                die('<p style="display: none">Cart has no products</p>');
            }
        } else {
            DigitealLogger::logError('Cart not loaded properly : ' . var_export($payment_data, true));
            die('<p style="display: none">Cart not loaded properly</p>');
        }
    } else {
        DigitealLogger::logError('Cannot retrieve cart id from remittance info : ' . var_export($payment_data, true));
        die('<p style="display: none">Cannot retrieve cart id from remittance info</p>');
    }
} else {
    DigitealLogger::logError('Check data from payment initiated failed : ' . var_export($payment_data, true));
    die('<p style="display: none">Check data from payment initiated failed</p>');
}

die();
