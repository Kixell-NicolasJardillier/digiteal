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
 * @version   1.0.1
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'/digiteal/src/includeClasses.php';

/**
 * Class Digiteal.
 */
class Digiteal extends PaymentModule
{
    const MODULE_MIN_VERSION = '1.5.0';
    const MODULE_MAX_VERSION = '1.7.99';

    const REINITIALIZE_MODULE = 'fds0frk34kv';
    const SUBMIT_STEP_1 = 'g1fds56g4s3dh1';
    const SUBMIT_STEP_2 = '4768j7d4j324jj';
    const SUBMIT_STEP_5 = 'j4f6y7k1d3r9j7';
    const SUBMIT_STEP_6 = 'j44yj36rt43s54';

    /**
     * @var DigitealCompanyStatus
     */
    private $companyStatus;

    /**
     * @var string Name of the current controller
     */
    private $currentController;

    /**
     * Digiteal module constructor.
     */
    public function __construct()
    {
        $this->name = 'digiteal';
        $this->version = '1.0.1';
        $this->tab = 'payments_gateways';
        $this->author = 'Kixell';
        $this->controllers = ['redirect', 'confirmation', 'error'];
        $this->is_eu_compatible = 1;
        $this->bootstrap = true;
        $this->need_instance = true;
        //$this->module_key = '';
        $this->ps_versions_compliancy = ['min' => self::MODULE_MIN_VERSION, 'max' => self::MODULE_MAX_VERSION];
        $this->currencies = true;
        if (is_callable('curl_init') === false) {
            $this->_errors[] = $this->l('To be able to use this module, please activate cURL (PHP extension).');
        }
        parent::__construct();

        $this->description = $this->l('CB, Visa, Mastercard, Ideal, Bancontact, SDD & Digiteal payment module.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module ?');
        $this->currentController = Tools::strtolower(Tools::getValue('controller'));

        $this->displayName = 'Digiteal';

        try { // Mainly used for P1.5
            $id_order = Tools::getValue('id_order');
            if (false !== $id_order) {
                $order = new Order((int) $id_order);
                if (($order->module == $this->name) && ($this->context->controller instanceof OrderConfirmationController)) {
                    $this->displayName = $order->payment;
                }
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Install the module.
     *
     * @return bool
     */
    public function install()
    {
        if (version_compare(_PS_VERSION_, self::MODULE_MIN_VERSION, '<')) {
            return false;
        }

        $installed = parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('paymentReturn');

        if ($installed) {
            $installed = Configuration::updateValue('KD_ENABLE_LOGGER', 0) &&
                Configuration::updateValue('KD_ROADMAP', 'digiteal.kixell.fr') &&
                Configuration::updateValue('KD_ENABLE_ROADMAP', true) &&
                Configuration::updateValue('KD_MODE', 0);
        }
        if ($installed) {
            ((false !== ($dc = DigitealRest::getConf())) ?
                $installed = Configuration::updateValue('KD_KPIID', $dc) : $installed = false);
        }
        if ($installed) {
            ((false !== ($dc = DigitealRest::getConf(true))) ?
                $installed = Configuration::updateValue('KD_KPIIDT', $dc) : $installed = false);
        }

        if ($installed) {
            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                if (!$this->registerHook('payment') || !$this->registerHook('displayPaymentEU')) {
                    DigitealLogger::logError('Hook payment or displayPaymentEU could not be saved.');
                    $this->_errors[] = $this->l('One or more hooks required for the module could not be saved.');
                    $installed = false;
                }
            } else {
                if (!$this->registerHook('paymentOptions')) {
                    DigitealLogger::logError('Hook paymentOptions could not be saved.');
                    $this->_errors[] = $this->l('One or more hooks required for the module could not be saved.');
                    $installed = false;
                }
            }
        }

        if ($installed) {
            $installed = $this->installTab();
        }

        if (false === $installed) {
            $this->uninstall();

            return false;
        }

        return (bool) $installed;
    }

    /**
     * Uninstall the module.
     *
     * @return bool
     */
    public function uninstall()
    {
        $uninstalled = parent::uninstall() &&
            $this->unregisterHook('header') &&
            $this->unregisterHook('backOfficeHeader') &&
            $this->unregisterHook('paymentReturn');

        if ($uninstalled) {
            $uninstalled &= Configuration::deleteByName('KD_ENABLE_LOGGER') &&
                Configuration::deleteByName('KD_ROADMAP') &&
                Configuration::deleteByName('KD_ENABLE_ROADMAP') &&
                Configuration::deleteByName('KD_KPIID') &&
                Configuration::deleteByName('KD_KPIIDT') &&
                Configuration::deleteByName('KD_MODE');
            $uninstalled &= Db::getInstance()->execute(
                'DELETE FROM `'._DB_PREFIX_."configuration` WHERE `name` LIKE 'KD_%'"
            );
        }

        if ($uninstalled) {
            $uninstalled = $this->uninstallTab();
        }

        return (bool) $uninstalled;
    }

    /**
     * @return mixed
     */
    private function installTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminDigitealRoadmap';
        $tab->module = 'digiteal';
        $tab->active = 0;
        $tab->id_parent = (int) Tab::getIdFromClassName('DEFAULT');
        $languages = Language::getLanguages();
        $tab->name = [];
        foreach ($languages as $lang) {
            $tab->name[$lang['id_lang']] = 'digiteal';
        }

        return $tab->add();
    }

    /**
     * @return bool
     */
    private function uninstallTab()
    {
        $idTab = (int) Tab::getIdFromClassName('AdminDigitealRoadmap');
        if ($idTab) {
            $tab = new Tab($idTab);

            return $tab->delete();
        }

        return true;
    }

    /**
     * @param array $params
     */
    public function hookHeader($params)
    {
        $controller = $this->context->controller;
        if ($this->currentController === 'order' || $this->currentController === 'order-opc') {
            // If there is errors during payment and the customer is redirected to the checkout,
            // then keep the cart and display the message to the customer.
            if (isset($this->context->cookie->digitealErrors)) {
                $controller->errors = array_merge(
                    $controller->errors,
                    explode("\n", $this->context->cookie->digitealErrors)
                );
                unset($this->context->cookie->digitealErrors);
            }
        }
        $this->context->controller->addJS($this->_path.'views/js/front.js');
    }

    /**
     * @param array $params
     */
    public function hookBackOfficeHeader($params)
    {
        $moduleName = Tools::getValue('module_name');
        $moduleName = (false === $moduleName ? Tools::getValue('configure') : $moduleName);

        if ($moduleName === $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $this->context->controller->addCSS($this->_path.'views/css/back-1.5.css');
            }
        }
    }

    /**
     * @param array $params
     */
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $this->companyStatus = new DigitealCompanyStatus();
        if (!$this->companyStatus->getModuleReady() || !$this->companyStatus->canReceiveFunds() || !$this->companyStatus->hasSelectedIban() || count($this->companyStatus->getPaymentMethodsAsArray()) <= 0) {
            return;
        }

        $digitealPaymentOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
        $digitealPaymentOption->setModuleName($this->name)
            ->setCallToActionText($this->l('Pay online'))
            ->setAction($this->context->link->getModuleLink($this->name, 'redirect', [], true));

        $logo = $this->getPaymentLogo();
        if (!is_null($logo)) {
            $digitealPaymentOption->setLogo(Media::getMediaPath($logo));
        }

        return [$digitealPaymentOption];
    }

    /**
     * @param array $params
     */
    public function hookPaymentReturn($params)
    {
        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $order = isset($params['order']) ? $params['order'] : $params['objOrder'];

            if (!$this->active || ($order->module != $this->name)) {
                return;
            }

            $state = $order->getCurrentState();
            if (in_array($state, [Configuration::get('PS_OS_PAYMENT'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID')])) {
                $smartVars = [
                    'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
                    'status'       => 'ok',
                    'id_order'     => $order->id,
                ];
                if (isset($order->reference) && !empty($order->reference)) {
                    $smartVars['reference'] = $order->reference;
                }
                $this->smarty->assign($smartVars);
            } else {
                $this->smarty->assign('status', 'failed');
            }

            return $this->display(__FILE__, 'payment_return.tpl');
        }
    }

    /**
     * @param $params
     *
     * @return array|void
     */
    public function hookDisplayPaymentEU($params)
    {
        if (!$this->active) {
            return;
        }

        // Currency support.
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $this->companyStatus = new DigitealCompanyStatus();
        if (!$this->companyStatus->getModuleReady() || !$this->companyStatus->canReceiveFunds() || !$this->companyStatus->hasSelectedIban() || count($this->companyStatus->getPaymentMethodsAsArray()) <= 0) {
            return;
        }

        $payment_options = [
            'cta_text' => $this->l('Pay online'),
            'action'   => $this->context->link->getModuleLink($this->name, 'redirect', [], true),
        ];
        $logo = $this->getPaymentLogo();

        if (!is_null($logo)) {
            $payment_options['logo'] = $logo;
        }

        return $payment_options;
    }

    public function hookPayment($params)
    {
        if (!$this->active) {
            return;
        }

        // Currency support.
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $this->companyStatus = new DigitealCompanyStatus();
        if (!$this->companyStatus->getModuleReady() || !$this->companyStatus->canReceiveFunds() || !$this->companyStatus->hasSelectedIban() || count($this->companyStatus->getPaymentMethodsAsArray()) <= 0) {
            return;
        }

        $payment_options = [
            'cta_text' => $this->l('Pay online'),
            'action'   => $this->context->link->getModuleLink($this->name, 'redirect', [], true),
        ];
        $logo = $this->getPaymentLogo();
        if (!is_null($logo)) {
            $payment_options['logo'] = $logo;
        }

        $this->smarty->assign($payment_options);

        return $this->display(__FILE__, 'payment.tpl');
    }

    /**
     * @param $cart
     *
     * @return bool
     */
    public function checkCurrency($cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return null|string payment logo URL
     */
    protected function getPaymentLogo()
    {
        if (isset($this->companyStatus)) {
            $payment_logos = $this->companyStatus->getPaymentMethodsLogos();
            if (!is_null($payment_logos)) {
                $file = _PS_MODULE_DIR_.$this->name.'/views/img/logos/'.$payment_logos.'.svg';
                if (!file_exists($file)) {
                    $payment_methods = $this->companyStatus->getPaymentMethodsAsArray();
                    if (count($payment_methods) > 0) {
                        DigitealPaymentMethod::generateLogosFile($file, $payment_methods);
                    }
                }
                if (version_compare(_PS_VERSION_, '1.6', '<')) {
                    return Tools::getProtocol().Tools::getMediaServer($file).'/modules/'.$this->name.'/views/img/logos/'.$payment_logos.'.svg';
                } else {
                    return Media::getMediaPath($file);
                }
            }
        }

        return null;
    }

    /**
     * @throws PrestaShopException
     *
     * @return false|string
     */
    public function getContent()
    {
        $this->companyStatus = new DigitealCompanyStatus();
        $digiteal_roadmap = null;
        if (Configuration::get('KD_ENABLE_ROADMAP')) {
            $digiteal_roadmap = $this->context->link->getAdminLink('AdminDigitealRoadmap');
        }

        $smartyVars = [
            'digiteal_description' => $this->description,
            'form_action'          => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'reinit_module'        => AdminController::$currentIndex.'&configure='.$this->name.'&'.self::REINITIALIZE_MODULE.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'reinit_submit'        => self::REINITIALIZE_MODULE,
            'digiteal_roadmap'     => $digiteal_roadmap,
        ];

        $nextStep = 1;

        if (Tools::isSubmit(self::REINITIALIZE_MODULE)) {
            $this->companyStatus->emptyAndReload();
            $smartyVars['messageSuccess'] = $this->l('The module has been successfully reset.');
        } elseif (Tools::isSubmit(self::SUBMIT_STEP_1)) {
            if (Tools::getIsset('vatNumber')) {
                $kdmode = (int) (Tools::getValue('kdmode') == 0 ? 0 : 1);
                $this->companyStatus->setMode($kdmode);
                $this->companyStatus->setVatNumber(Tools::getValue('vatNumber'));
                $this->companyStatus->save();
            }
        } elseif (Tools::isSubmit(self::SUBMIT_STEP_2)) {
            if (Tools::getIsset('vatNumber') && Tools::getIsset('companyName')
                && Tools::getIsset('contactPersonEmail')) {
                $vatNumber = Tools::getValue('vatNumber');
                if ($vatNumber !== $this->companyStatus->getVatNumber()) {
                    //Do nothing : this means that user force VAT in source code. This should never occur.
                } else {
                    $this->companyStatus->setCompanyName(Tools::getValue('companyName'));
                    $this->companyStatus->setContactPersonEmail(Tools::getValue('contactPersonEmail'));
                    $postPaymentMethods = [];
                    $paymentMethods = DigitealPaymentMethod::getMethods();
                    foreach ($paymentMethods as $paymentMethod) {
                        if (Tools::getIsset('paymentMethods_'.strtolower($paymentMethod))) {
                            $postPaymentMethods[] = strtoupper(Tools::getValue('paymentMethods_'.strtolower($paymentMethod)));
                        }
                    }
                    $postPaymentMethods = json_encode($postPaymentMethods);
                    $this->companyStatus->setPaymentMethods($postPaymentMethods);
                    $this->companyStatus->generateCompanyRegistrationLink();
                    $this->companyStatus->save();
                }
            }
        } elseif (Tools::isSubmit(self::SUBMIT_STEP_5)) {
            if (Tools::getIsset('contactPersonEmail') && Tools::getIsset('contactPersonPassword')) {
                $shop_url = Tools::getCurrentUrlProtocolPrefix().htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
                $webhookValidationLink = $shop_url.'modules/digiteal/validation.php';
                $webhookErrorLink = $shop_url.'modules/digiteal/error.php';
                if ($this->companyStatus->generateWebhookConfiguration(
                    $webhookValidationLink,
                    $webhookErrorLink,
                    Tools::getValue('contactPersonEmail'),
                    Tools::getValue('contactPersonPassword')
                )) {
                    $this->companyStatus->setWebhookValidationUrl($webhookValidationLink);
                    $this->companyStatus->setWebhookErrorUrl($webhookErrorLink);
                    $this->companyStatus->setModuleReady(true);
                    $this->companyStatus->save();
                }
            }
        } elseif (Tools::isSubmit(self::SUBMIT_STEP_6)) {
            if ($this->companyStatus->checkRestStatus()) {
                $smartyVars['messageSuccess'] = $this->l('The information has been updated.');

                if (Tools::getIsset('selectedIban')) {
                    $ibans = $this->companyStatus->getIbansAsArray();
                    $iban = Tools::getValue('selectedIban');
                    if (in_array($iban, $ibans)) {
                        $this->companyStatus->setSelectedIban($iban);
                        $this->companyStatus->save();
                    }
                }
            }
        }

        if ($this->companyStatus->getModuleReady()) {
            $nextStep = 6;
        } else {
            if ($this->companyStatus->hasVatNumber()) {
                if ($this->companyStatus->checkRestStatus()) {
                    if ($this->companyStatus->isCompanyFound()) {
                        if ($this->companyStatus->getCanReceiveFunds()) {
                            $nextStep = 5;
                        } else {
                            $nextStep = 4;
                        }
                    } else {
                        $nextStep = 2;
                        if ($this->companyStatus->hasCompanyRegistrationLink()) {
                            $nextStep = 3;
                        }
                    }
                } else {
                    $smartyVars['messageError'] = $this->l('Please, make sure you have filled in the right information.');
                }
            }
        }

        $smartyVars['kdmode'] = $this->companyStatus->getMode();

        if ($nextStep === 1) {
            $smartyVars['settings_step'] = 1;
            $smartyVars['submit_name'] = self::SUBMIT_STEP_1;
            $smartyVars['submit_label'] = $this->l('Start the status check');
            $smartyVars['inputs'] = $this->getInputsStep1();
        } elseif ($nextStep === 2) {
            $smartyVars['settings_step'] = 2;
            $smartyVars['submit_name'] = self::SUBMIT_STEP_2;
            $smartyVars['submit_label'] = $this->l('Generate registration link');
            $smartyVars['inputs'] = $this->getInputsStep2();
        } elseif ($nextStep === 3) {
            $smartyVars['settings_step'] = 3;
            $smartyVars['inputs'] = $this->getInputsStep3();
        } elseif ($nextStep === 4) {
            $smartyVars['settings_step'] = 4;
            $smartyVars['inputs'] = $this->getInputsStep4();
        } elseif ($nextStep === 5) {
            $smartyVars['settings_step'] = 5;
            $smartyVars['submit_name'] = self::SUBMIT_STEP_5;
            $smartyVars['submit_label'] = $this->l('Finalize the configuration');
            $smartyVars['inputs'] = $this->getInputsStep5();
        } elseif ($nextStep === 6) {
            $smartyVars['settings_step'] = 6;
            $smartyVars['submit_name'] = self::SUBMIT_STEP_6;
            $smartyVars['submit_label'] = $this->l('Update');
            $smartyVars['inputs'] = $this->getInputsStep6();
        }

        $this->smarty->assign($smartyVars);

        return $this->display(__FILE__, 'views/templates/admin/settings.tpl');
    }

    /**
     * @return array[]
     */
    protected function getInputsStep1()
    {
        return [
            [
                'type'    => ((version_compare(_PS_VERSION_, '1.6', '<')) ? 'radio' : 'switch'),
                'label'   => $this->l('Mode Test'),
                'name'    => 'kdmode',
                'is_bool' => true,
                'value'   => $this->companyStatus->getMode(),
                'values'  => [
                    [
                        'id'    => 'kdmode_on',
                        'value' => 1,
                        'label' => $this->l('Enable'),
                    ],
                    [
                        'id'    => 'kdmode_off',
                        'value' => 0,
                        'label' => $this->l('Disable'),
                    ],
                ],
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('VAT number'),
                'name'     => 'vatNumber',
                'desc'     => $this->l('The intra-community VAT number of your company.'),
                'value'    => $this->companyStatus->getVatNumber(),
                'required' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getInputsStep2()
    {
        return [
            [
                'type'  => 'hidden',
                'name'  => 'integratorID',
                'value' => $this->companyStatus->getKpiid(),
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('VAT number'),
                'name'     => 'vatNumber',
                'desc'     => $this->l('The intra-community VAT number of your company.'),
                'value'    => $this->companyStatus->getVatNumber(),
                'disabled' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Name of the company'),
                'name'     => 'companyName',
                'desc'     => $this->l('Your company name as it will appear in Digiteal.'),
                'value'    => $this->companyStatus->getCompanyName(),
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Email'),
                'name'     => 'contactPersonEmail',
                'desc'     => $this->l('The email address of the main contact.'),
                'value'    => $this->companyStatus->getContactPersonEmail(),
                'required' => true,
            ],
            [
                'type'     => 'paymentMethod',
                'label'    => $this->l('Payment methods available at Digiteal :'),
                'name'     => 'paymentMethods',
                'values'   => DigitealPaymentMethod::getMethods(),
                'required' => false,
                'disabled' => true,
            ],
        ];
    }

    /**
     * @return array[]
     */
    protected function getInputsStep3()
    {
        return [
            [
                'type'  => 'link',
                'label' => $this->l('Your registration form'),
                'name'  => $this->l('Go to the form'),
                'value' => $this->companyStatus->getCompanyRegistrationLink(),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getInputsStep4()
    {
        return [];
    }

    /**
     * @return array[]
     */
    protected function getInputsStep5()
    {
        return [
            [
                'type'     => 'text',
                'label'    => $this->l('Email'),
                'name'     => 'contactPersonEmail',
                'desc'     => $this->l('The email address of your account with Digiteal.'),
                'value'    => $this->companyStatus->getContactPersonEmail(),
                'required' => true,
            ],
            [
                'type'     => 'password',
                'label'    => $this->l('Password'),
                'name'     => 'contactPersonPassword',
                'desc'     => $this->l('Your Digiteal account password.'),
                'required' => true,
            ],
            [
                'type' => 'alert',
                'desc' => $this->l('Your password is not stored on your website. The authentication information you provide in this form is used to finalize the configuration and will not be needed again.'),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getInputsStep6()
    {
        $return = [];
        if (count($this->companyStatus->getPaymentMethodsAsArray()) <= 0) {
            $return[] = [
                'type' => 'alert',
                'desc' => $this->l('Your module is not active because you do not have any payment methods activated. Please contact the Digiteal team to activate one of them').' (support@digiteal.eu or <a href="http://support.digiteal.eu" target="_blank">http://support.digiteal.eu</a>) '.$this->l('and click "Update" button.'),
            ];
        } elseif (!$this->companyStatus->hasSelectedIban()) {
            $return[] = [
                'type' => 'alert',
                'desc' => $this->l('Your module is not active because you do not have an IBAN selected. Select one in the list below and click on the "Update" button.'),
            ];
        } else {
            $return[] = [
                'type' => 'reinsurance',
                'desc' => $this->l('Congratulations! The payment module is active on your store and you are ready to receive payments with Digiteal.'),
            ];
        }
        $return[] = [
            'type'     => 'ibans',
            'label'    => $this->l('IBAN selected:'),
            'name'     => 'selectedIban',
            'selected' => $this->companyStatus->getSelectedIban(),
            'values'   => $this->companyStatus->getIbansAsArray(),
            'required' => false,
        ];
        $return[] = [
            'type'     => 'paymentMethod',
            'label'    => $this->l('The payment methods activated on your store are :'),
            'name'     => 'paymentMethods',
            'values'   => $this->companyStatus->getPaymentMethodsAsArray(),
            'required' => false,
            'disabled' => true,
        ];

        return $return;
    }
}
