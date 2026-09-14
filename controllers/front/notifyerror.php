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
 * Class DigitealNotifyErrorModuleFrontController.
 *
 * Receives the PAYMENT_INITIATION_ERROR webhook from Digiteal.
 *
 * Replaces the legacy entry point modules/digiteal/error.php, see DigitealNotifyModuleFrontController.
 */
class DigitealNotifyErrorModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function __construct()
    {
        // No theme, no header, no footer : this endpoint is called by Digiteal, not by a browser.
        $this->ajax = true;
        $this->content_only = true;
        parent::__construct();
    }

    protected function displayMaintenancePage()
    {
    }

    protected function displayRestrictedCountryPage()
    {
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        DigitealLogger::logInfo('[notifyerror] postProcess called');

        // false : the front dispatcher already initialized a controller for this request.
        $outcome = DigitealWebhook::handlePaymentInitiationError(false);

        DigitealLogger::logInfo('[notifyerror] '.$outcome);

        header('Content-Type: text/plain; charset=utf-8');
        exit($outcome);
    }
}
