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
 * @version   1.0.3
 */
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealLogger.php';

/**
 * Class DigitealValidationModuleFrontController.
 */
class DigitealValidationModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        DigitealLogger::logInfo('[validation] postProcess called');
        if (Tools::getIsset('cart_id')) {
            $cart_id = Tools::getValue('cart_id');
            DigitealLogger::logInfo('[validation] $cart_id = '.$cart_id);
            $cart = new Cart((int) $cart_id);
            if (Validate::isLoadedObject($cart)) {
                $order_id = Order::getOrderByCartId($cart_id);
                $order = new Order((int) $order_id);
                if (Validate::isLoadedObject($order)) {
                    // Redirect to order confirmation
                    Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int) $cart->id.'&id_module='.(int) $this->module->id.'&id_order='.$order->id.'&key='.$order->secure_key);
                } else {
                    DigitealLogger::logError('[validation] Order not loaded properly, cart_id : '.$cart_id.', $order_id : '.$order_id);
                }
            } else {
                DigitealLogger::logError('[validation] Cart not loaded properly, cart id : '.$cart_id);
            }
        } else {
            DigitealLogger::logError('[validation] cart_id does not exist');
        }
        Tools::redirect('index.php?controller=history');
    }
}
