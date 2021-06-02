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

require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealLogger.php';
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealTools.php';
/**
 * Class DigitealErrorModuleFrontController
 */
class DigitealErrorModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function postProcess()
    {
        $this->context->cookie->digitealErrors = $this->module->l('Your payment was not accepted, you can try ordering again or use another payment method.');
        DigitealLogger::logInfo('[error] postProcess called');
        $cart_id = Tools::getValue('cart_id');
        $cart = new Cart((int)$cart_id);
        DigitealLogger::logInfo('[error] $cart_id = ' . $cart_id);
        if (Validate::isLoadedObject($cart)) {
            $order_page = DigitealTools::getOrderPageFromCart($cart);
            DigitealTools::digitealRedirect($order_page);
        } else {
            $order_page = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order';
            DigitealLogger::logError('[error] Cart not loaded properly, cart id : ' . $cart_id);
            DigitealTools::digitealRedirect('index.php?controller='.$order_page);
        }
    }
}
