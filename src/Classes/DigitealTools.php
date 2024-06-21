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
if (!defined('_PS_VERSION_')) {
    exit;
}

if (!class_exists('DigitealTools', false)) {
    class DigitealTools
    {
        /**
         * @param      $key
         * @param      $array
         * @param null $default
         *
         * @return int|mixed|null
         */
        public static function findInArray($key, $array, $default = null)
        {
            if (is_array($array) && key_exists($key, $array)) {
                if (is_bool($array[$key])) {
                    return $array[$key] ? 1 : 0;
                } else {
                    return $array[$key];
                }
            }

            return $default;
        }

        /**
         * @param $data
         *
         * @return bool
         */
        public static function checkWebhookPaymentInitiated($data)
        {
            $error = false;
            $keys = ['paymentRequestInformation', 'paymentStatus', 'paymentMethod', 'bankTransactionID'];
            $priKeys = ['paymentID', 'remittanceInfo', 'currency'];
            if (!is_array($data)) {
                $error = true;
            } else {
                foreach ($keys as $key) {
                    if (!array_key_exists($key, $data)) {
                        $error = true;
                        break;
                    } else {
                        if ($key === 'paymentStatus' && $data[$key] !== 'INITIATED') {
                            $error = true;
                            break;
                        }
                    }
                }

                if (!$error) {
                    if (!is_array($data['paymentRequestInformation'])) {
                        $error = true;
                    } else {
                        foreach ($priKeys as $priKey) {
                            if (!array_key_exists($priKey, $data['paymentRequestInformation'])) {
                                $error = true;
                                break;
                            }
                        }
                    }
                }
            }

            return !$error;
        }

        /**
         * @param $data
         *
         * @return bool
         */
        public static function checkWebhookPaymentInitiationError($data)
        {
            $error = false;
            $keys = ['paymentRequestInformation', 'paymentStatus', 'paymentMethod', 'bankTransactionID', 'errorMessage'];
            $priKeys = ['paymentID', 'remittanceInfo', 'currency'];
            if (!is_array($data)) {
                $error = true;
            } else {
                foreach ($keys as $key) {
                    if (!array_key_exists($key, $data)) {
                        $error = true;
                        break;
                    } else {
                        if ($key === 'paymentStatus' && $data[$key] !== 'INITIATION_ERROR') {
                            $error = true;
                            break;
                        }
                    }
                }
                if (!$error) {
                    if (!is_array($data['paymentRequestInformation'])) {
                        $error = true;
                    } else {
                        foreach ($priKeys as $priKey) {
                            if (!array_key_exists($priKey, $data['paymentRequestInformation'])) {
                                $error = true;
                                break;
                            }
                        }
                    }
                }
            }

            return !$error;
        }

        /**
         * Due to problems in some version of Prestashop, let's try to rebuild the context depending on the cart object.
         *
         * @param CartCore $cart
         *
         * @throws PrestaShopDatabaseException
         * @throws PrestaShopException
         */
        public static function buildPrestashopContext($cart)
        {
            if (isset($cart->id_shop)) {
                $_GET['id_shop'] = $cart->id_shop;
                Context::getContext()->shop = Shop::initialize();
            }
            $controller = new FrontController();
            $controller->init();
            Context::getContext()->controller = $controller;
            Context::getContext()->customer = new Customer((int) $cart->id_customer);
            Context::getContext()->customer->logged = 1;
            Context::getContext()->cart = $cart = new Cart((int) $cart->id);
            $address = new Address((int) $cart->id_address_invoice);
            Context::getContext()->country = new Country((int) $address->id_country);
            Context::getContext()->language = new Language((int) $cart->id_lang);
            Context::getContext()->currency = new Currency((int) $cart->id_currency);
            Context::getContext()->link = new Link();
        }

        /**
         * @param $cart
         *
         * @return string
         */
        public static function getOrderPageFromCart($cart)
        {
            $order_page = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order';
            $order_query = [];
            if (!Validate::isLoadedObject($cart) || $cart->nbProducts() <= 0) {
                $order_page = self::digitealPageLink($order_page);
            } elseif (!$cart->id_customer || !$cart->id_address_delivery || !$cart->id_address_invoice) {
                if (version_compare(_PS_VERSION_, '1.7', '<') && !Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                    $order_query['step'] = 3;
                    if (version_compare(_PS_VERSION_, '1.5.1', '<')) {
                        $order_query['cgv'] = 1;
                        $order_query['id_carrier'] = $cart->id_carrier;
                    }
                    $order_page = self::digitealPageLink($order_page, $order_query);
                }
            }

            return self::digitealPageLink($order_page);
        }

        /**
         * @param       $controller
         * @param array $query
         *
         * @return string
         */
        public static function digitealPageLink($controller, $query = [])
        {
            $url = Context::getContext()->link->getPageLink($controller, true);
            if (count($query) > 0) {
                $url .= '?'.http_build_query($query);
            }

            return $url;
        }

        /**
         * @param       $url
         * @param false $ajax
         */
        public static function digitealRedirect($url, $ajax = false)
        {
            if ($ajax) {
                $link = Context::getContext()->link;
                $base_uri = __PS_BASE_URI__;

                if (strpos($url, 'http://') === false && strpos($url, 'https://') === false && $link) {
                    if (strpos($url, $base_uri) === 0) {
                        $url = substr($url, strlen($base_uri));
                    }
                    if (strpos($url, 'index.php?controller=') !== false && strpos($url, 'index.php/') == 0) {
                        $url = substr($url, strlen('index.php?controller='));
                        if (Configuration::get('PS_REWRITING_SETTINGS')) {
                            $url = Tools::strReplaceFirst('&', '?', $url);
                        }
                    }

                    $explode = explode('?', $url);
                    // don't use ssl if url is home page
                    // used when logout for example
                    $use_ssl = !empty($url);
                    $url = $link->getPageLink($explode[0], $use_ssl);
                    if (isset($explode[1])) {
                        $url .= '?'.$explode[1];
                    }
                }

                ob_end_clean();
                header('Content-Type: application/json');
                exit(json_encode([
                    'redirect' => $url,
                ]));
            } else {
                Tools::redirect($url);
            }
        }
    }
}
