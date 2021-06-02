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
 * @version   1.0.0
 */
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealLogger.php';
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealTools.php';

/**
 * Class DigitealValidationModuleFrontController.
 */
class DigitealConfirmationModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function __construct()
    {
        $this->ajax = Tools::getIsset('ajax') && Tools::getValue('ajax') ? true : false;
        $this->display_column_left = false;
        $this->display_column_right = version_compare(_PS_VERSION_, '1.6', '<');
        parent::__construct();
    }

    public function initHeader()
    {
        parent::initHeader();
        session_cache_limiter('private_no_expire');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        $digiteal_elapsed_time = 10;
        if ($this->ajax && Tools::getIsset('elapsed')) {
            $digiteal_elapsed_time = (int) Tools::getValue('elapsed') - 1;
        }

        DigitealLogger::logInfo('[confirmation] postProcess called');
        if ($digiteal_elapsed_time > 0 && Tools::getIsset('cart_id')) {
            $cart_id = Tools::getValue('cart_id');
            $cart = new Cart((int) $cart_id);
            DigitealLogger::logInfo('[confirmation] $cart_id = '.$cart_id);
            if (Validate::isLoadedObject($cart)) {
                $order_id = Order::getOrderByCartId($cart_id);
                $order = new Order((int) $order_id);
                DigitealLogger::logInfo('[confirmation] $order_id = '.$order_id);
                if (Validate::isLoadedObject($order)) {
                    DigitealLogger::logInfo('[confirmation] redirect order confirmation');
                    // Redirect to order confirmation
                    $query = [
                        'id_cart'   => (int) $cart->id,
                        'id_module' => (int) $this->module->id,
                        'id_order'  => (int) $order->id,
                        'key'       => $order->secure_key,
                    ];
                    $page = DigitealTools::digitealPageLink('order-confirmation', $query);
                    DigitealTools::digitealRedirect($page, $this->ajax);
                } else { // Webhook still not called or in progress
                    DigitealLogger::logInfo('[confirmation] Order not loaded, cart_id : '.$cart_id).', start waiting ... ';

                    if ($this->ajax) {
                        ob_end_clean();
                        header('Content-Type: application/json');
                        exit(json_encode([
                            'elapsed' => $digiteal_elapsed_time,
                        ]));
                    } else {
                        $ajax_call = Context::getContext()->link->getModuleLink(
                            'digiteal',
                            'confirmation',
                            ['ajax' => true, 'cart_id' => $cart->id, 'elapsed' => $digiteal_elapsed_time],
                            true
                        );
                        $digiteal_default_url_redirect = DigitealTools::digitealPageLink('history');
                        $smarty_vars = [
                            'digiteal_elapsed_time_url'     => base64_encode($ajax_call),
                            'digiteal_default_url_redirect' => base64_encode($digiteal_default_url_redirect),
                            'digiteal_elapsed_time'         => $digiteal_elapsed_time,
                        ];
                        $this->context->smarty->assign($smarty_vars);
                        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                            $this->setTemplate('module:digiteal/views/templates/front/confirmation-1.7.tpl');
                        } else {
                            $this->setTemplate('confirmation-1.6.tpl');
                        }
                    }
                }
            } else {
                DigitealLogger::logError('[confirmation] Cart not loaded properly, cart id : '.$cart_id);
                $page = DigitealTools::digitealPageLink('history');
                DigitealTools::digitealRedirect($page);
            }
        } else {
            DigitealLogger::logError('[confirmation] cart_id does not exist');
            $page = DigitealTools::digitealPageLink('history');
            DigitealTools::digitealRedirect($page);
        }
    }
}
