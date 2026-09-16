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
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealLogger.php';
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealTools.php';
require_once _PS_MODULE_DIR_.'/digiteal/src/Classes/DigitealWebhook.php';

/**
 * Class DigitealNotifyModuleFrontController.
 *
 * Receives the PAYMENT_INITIATED webhook from Digiteal and creates the order.
 *
 * Replaces the legacy entry point modules/digiteal/validation.php : as from Prestashop 9 the
 * modules/.htaccess shipped by the core denies direct access to any .php file, which would turn
 * that webhook into a 403 and silently prevent orders from being created.
 */
class DigitealNotifyModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function __construct()
    {
        $this->ajax = true;
        $this->content_only = true;
        parent::__construct();
    }

    /**
     * A payment has been made : the order must be recorded even when the shop is closed.
     */
    protected function displayMaintenancePage()
    {
    }

    /**
     * Same reasoning : the geolocation restrictions target customers, not Digiteal's servers.
     */
    protected function displayRestrictedCountryPage()
    {
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        DigitealLogger::logInfo('[notify] postProcess called');

        $outcome = DigitealWebhook::handlePaymentInitiated(false);

        DigitealLogger::logInfo('[notify] '.$outcome);

        header('Content-Type: text/plain; charset=utf-8');
        exit($outcome);
    }
}
