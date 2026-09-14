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
if (!defined('_PS_VERSION_')) {
    exit;
}

if (!class_exists('DigitealWebhook', false)) {
    /**
     * Shared handling of the Digiteal webhooks.
     *
     * The very same code is reached through two routes :
     *  - the module front controllers (digiteal/notify and digiteal/notifyerror), used since 1.0.5
     *    and mandatory as from Prestashop 9 which forbids direct access to .php files of modules ;
     *  - the legacy entry points at the root of the module (validation.php and error.php), kept so
     *    that shops registered before 1.0.5 keep working until their webhooks are registered again.
     */
    class DigitealWebhook
    {
        /**
         * Read and decode the JSON body sent by Digiteal.
         *
         * @return array|null Decoded payload, null when the body is missing or not valid JSON
         */
        private static function readPayload()
        {
            try {
                $payload = file_get_contents('php://input');
            } catch (Exception $e) {
                DigitealLogger::logError('[webhook] Cannot read body : '.$e->getMessage());

                return null;
            }

            if (!is_string($payload) || $payload === '') {
                DigitealLogger::logError('[webhook] Empty body');

                return null;
            }

            $decoded = json_decode($payload, true);
            if (!is_array($decoded)) {
                DigitealLogger::logError('[webhook] Body is not a valid JSON object : '.var_export($payload, true));

                return null;
            }

            return $decoded;
        }

        /**
         * Extract the cart id carried by the remittance info ("cart-<id>").
         *
         * @param array $payload
         *
         * @return int|false
         */
        private static function getCartId($payload)
        {
            $remittanceInfo = DigitealTools::findInArray(
                'remittanceInfo',
                DigitealTools::findInArray('paymentRequestInformation', $payload, [])
            );

            if (!is_string($remittanceInfo)) {
                return false;
            }

            $remittanceInfo = explode('-', $remittanceInfo);
            if (count($remittanceInfo) !== 2 || $remittanceInfo[0] !== 'cart') {
                return false;
            }

            return (int) $remittanceInfo[1];
        }

        /**
         * Handle a PAYMENT_INITIATED notification : create the order when it does not exist yet.
         *
         * @param bool $initController Build a FrontController while rebuilding the context. Must be
         *                             false when already running inside a front controller.
         *
         * @throws PrestaShopDatabaseException
         * @throws PrestaShopException
         *
         * @return string Human readable outcome, meant for the (hidden) response body
         */
        public static function handlePaymentInitiated($initController = true)
        {
            $payload = self::readPayload();
            if (null === $payload) {
                return 'Invalid body';
            }

            if (!DigitealTools::checkWebhookPaymentInitiated($payload)) {
                DigitealLogger::logError('[webhook] Check data from payment initiated failed : '.var_export($payload, true));

                return 'Check data from payment initiated failed';
            }

            $cart_id = self::getCartId($payload);
            if (false === $cart_id) {
                DigitealLogger::logError('[webhook] Cannot retrieve cart id from remittance info : '.var_export($payload, true));

                return 'Cannot retrieve cart id from remittance info';
            }

            $cart = new Cart($cart_id);
            if (!Validate::isLoadedObject($cart)) {
                DigitealLogger::logError('[webhook] Cart '.$cart_id.' not loaded properly');

                return 'Cart not loaded properly';
            }

            if ($cart->nbProducts() <= 0) {
                DigitealLogger::logError('[webhook] Cart '.$cart_id.' has no products');

                return 'Cart has no products';
            }

            try {
                DigitealTools::buildPrestashopContext($cart, $initController);
            } catch (Exception $e) {
                DigitealLogger::logError('[webhook] Exception to build Prestashop context : '.$e->getMessage());

                return 'Exception to build Prestashop context: '.$e->getMessage();
            }

            $order_id = DigitealTools::getOrderIdByCartId($cart_id);
            if ($order_id !== false && $order_id > 0) { // Order already exists
                DigitealLogger::logInfo('[webhook] Order '.$order_id.' already exist with cart '.$cart_id);

                $order = new Order((int) $order_id);
                $old_state = (int) $order->getCurrentState();
                DigitealLogger::logInfo('[webhook] Current state for order '.$order_id.' (cart '.$cart_id.') is : '.$old_state);

                return 'Order already exists';
            }

            DigitealLogger::logInfo('[webhook] Create order for cart id '.$cart->id);

            // Retrieve customer from cart.
            $customer = new Customer((int) $cart->id_customer);

            // Retrieve currency used
            $currency = $payload['paymentRequestInformation']['currency'];
            $currency_id = Currency::getIdByIsoCode($currency);

            // Real paid through payment gateway.
            $total_paid = (float) $payload['paymentRequestInformation']['amountInCents'] / 100;

            // Compare with only two digits the total amount of the cart and the total really paid by the customer.
            // Then re-assign the total amount of the cart to the variable $total_paid used to validate the order.
            // The aim is to avoid somme decimals problems that could occur with some versions of Prestashop.
            $cart_total_two_digits = (float) ((int) ($cart->getOrderTotal() * 100)) / 100;
            if ($cart_total_two_digits === $total_paid) {
                $total_paid = $cart->getOrderTotal();
            }

            DigitealLogger::logInfo('[webhook] Remittance info amount '.$total_paid);

            // Set the title of the order transaction that will displayed in the backoffice
            $title = $payload['paymentMethod'].' [Digiteal]';

            // Add transaction_id to the extra data to retrieve the information in the backoffice
            $extra_vars = [];
            $extra_vars['transaction_id'] = $payload['bankTransactionID'];

            // Generate an explicite message with the order
            $message_data = [
                'transaction_id' => $payload['bankTransactionID'],
                'execution_date' => DigitealTools::findInArray('executionTimestamp', $payload),
                'data'           => $payload,
            ];
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
            DigitealLogger::logInfo('[webhook] Digiteal->validateOrder called for cart id '.$cart->id);

            return 'Order created';
        }

        /**
         * Handle a PAYMENT_INITIATION_ERROR notification.
         *
         * @param bool $initController Build a FrontController while rebuilding the context. Must be
         *                             false when already running inside a front controller.
         *
         * @throws PrestaShopDatabaseException
         * @throws PrestaShopException
         *
         * @return string Human readable outcome, meant for the (hidden) response body
         */
        public static function handlePaymentInitiationError($initController = true)
        {
            DigitealLogger::logError('[webhook] [Payment initiation error]');

            $payload = self::readPayload();
            if (null === $payload) {
                return 'Invalid body';
            }

            if (!DigitealTools::checkWebhookPaymentInitiationError($payload)) {
                DigitealLogger::logError('[webhook] Check data from payment initiation error failed : '.var_export($payload, true));

                return 'Check data from payment initiation error failed';
            }

            $cart_id = self::getCartId($payload);
            if (false === $cart_id) {
                DigitealLogger::logError('[webhook] Cannot retrieve cart id from remittance info : '.var_export($payload, true));

                return 'Cannot retrieve cart id from remittance info';
            }

            $cart = new Cart($cart_id);
            if (!Validate::isLoadedObject($cart)) {
                DigitealLogger::logError('[webhook] Cart '.$cart_id.' not loaded properly');

                return 'Cart not loaded properly';
            }

            try {
                DigitealTools::buildPrestashopContext($cart, $initController);
            } catch (Exception $e) {
                DigitealLogger::logError('[webhook] Exception to build Prestashop context : '.$e->getMessage());

                return 'Exception to build Prestashop context: '.$e->getMessage();
            }

            return 'Payment initiation error handled';
        }
    }
}
