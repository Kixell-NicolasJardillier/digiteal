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
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealRest.php';

class DigitealRedirectModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function postProcess()
    {
        $cart = $this->context->cart;

        $order_page = Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order';

        // Is the cart object could be used ? If no, redirect to the corresponding page.
        $order_query = array();
        if (!Validate::isLoadedObject($cart) || $cart->nbProducts() <= 0) {
            $this->digitealRedirect($this->digitealPageLink($order_page));
        } elseif (! $cart->id_customer || ! $cart->id_address_delivery || ! $cart->id_address_invoice || ! $this->module->active) {
            if (version_compare(_PS_VERSION_, '1.7', '<') && ! Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                $order_query['step'] = 3;
                if (version_compare(_PS_VERSION_, '1.5.1', '<')) {
                    $order_query['cgv'] = 1;
                    $order_query['id_carrier'] = $cart->id_carrier;
                }
                $this->digitealRedirect($this->digitealPageLink($order_page, $order_query));
            }
            $this->digitealRedirect($this->digitealPageLink($order_page));
        }

        $endpoint = DigitealRest::END_POINT . '/api/v1/payment-request/pay-button/execute';
        $orderTotal = $cart->getOrderTotal(true, Cart::BOTH);
        $digitealAmountInCents = (float)$orderTotal * 100;

        $cancelUrl = Context::getContext()->link->getPageLink($order_page);
        $confirmationURL = Context::getContext()->link->getModuleLink('digiteal', 'confirmation', array('cart_id' => $cart->id), true);
        $errorUrl = Context::getContext()->link->getModuleLink('digiteal', 'error', array('cart_id' => $cart->id), true);

        $iso_code = $this->context->language->iso_code;
        $params = array(
            'multiple' => 'true',
            'requesterVAT' => Configuration::get('KD_VATNUMBER'),
            'amountInCents' => (int)$digitealAmountInCents,
            'iban' => Configuration::get('KD_SELECTEDIBAN'),
            'language' => strtoupper($iso_code),
            'remittanceInfo' => 'cart-' . $cart->id,
            'cancelURL' => $cancelUrl,
            'confirmationURL' => $confirmationURL,
            'errorURL' => $errorUrl,
        );

        $endpoint .= '?' . http_build_query($params);

        // If redirect to payment gateway failed, then redirect to order page
        if (!$this->digitealRedirect($endpoint, true)) {
            $this->digitealRedirect($this->digitealPageLink($order_page));
        }
    }

    /**
     * Redirect to corresponding url or follow redirections and then redirect.
     * @param $url
     * @param bool $followLocation
     * @return false
     */
    private function digitealRedirect($url, $followLocation = false)
    {
        if ($followLocation) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; SM-G892A Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Mobile Safari/537.36');
            $response = curl_exec($ch);
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $headers = $this->getHeadersFromCurl($header);
            curl_close($ch);
            if (isset($headers['Location'])) {
                $redirectedUrl =  $headers['Location'];
                DigitealLogger::logError('[redirect] ' . $redirectedUrl);
                Tools::redirect((string)$redirectedUrl);
            } else {
                Tools::redirect($url);
            }
        } else {
            Tools::redirect($url);
        }
        return false;
    }

    /**
     * @param $respHeaders
     * @return array
     */
    private function getHeadersFromCurl($respHeaders)
    {
        $headers = array();
        $headerText = substr($respHeaders, 0, strpos($respHeaders, "\r\n\r\n"));
        foreach (explode("\r\n", $headerText) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    /**
     * Generate the URL depending on the controller and query.
     * @param string $controller
     * @param array $query
     * @return string
     */
    private function digitealPageLink($controller, $query = array())
    {
        $url = Context::getContext()->link->getPageLink($controller, true);
        if (count($query) > 0) {
            $url .= '?' . http_build_query($query);
        }
        return $url;
    }
}
