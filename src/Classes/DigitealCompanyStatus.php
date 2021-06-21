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
    exit();
}

if (!class_exists('DigitealCompanyStatus', false)) {
    class DigitealCompanyStatus extends DigitealConfiguration
    {
        /**
         * @var string
         */
        private $mode;

        /**
         * @var string
         */
        private $kpiid;

        /**
         * @var string
         */
        private $kpiidt;

        /**
         * @var string
         */
        private $integratorId;

        /**
         * @var string
         */
        private $ibans;

        /**
         * @var string
         */
        private $selectedIban;

        /**
         * @var string
         */
        private $selectedPaymentMethods;

        /**
         * Identification number of the company.
         *
         * @var string
         */
        private $identificationNumber;

        /**
         * VAT number of the company.
         *
         * @var string
         */
        private $vatNumber;

        /**
         * @var string
         */
        private $companyName;

        /**
         * @var string
         */
        private $status;

        /**
         * @var string
         */
        private $canSendFunds;

        /**
         * @var string
         */
        private $canReceiveFunds;

        /**
         * @var string
         */
        private $contactPersonEmail;

        /**
         * @var string
         */
        private $paymentMethods;

        /**
         * @var string
         */
        private $companyRegistrationLink;

        /**
         * @var string
         */
        private $webhookValidationUrl;

        /**
         * @var string
         */
        private $webhookErrorUrl;

        /**
         * @var string
         */
        private $moduleReady = false;

        /**
         * @return string
         */
        public function getKpiid()
        {
            return (1 === (int)$this->mode ? $this->kpiidt : $this->kpiid);
        }

        /**
         * @return string
         */
        public function getMode()
        {
            return $this->mode;
        }

        /**
         * @param string $mode
         */
        public function setMode($mode)
        {
            $this->mode = $mode;
        }

        /**
         * @return string
         */
        public function getIdentificationNumber()
        {
            return $this->identificationNumber;
        }

        /**
         * @param string $identificationNumber
         */
        public function setIdentificationNumber($identificationNumber)
        {
            $this->identificationNumber = $identificationNumber;
        }

        /**
         * @return string
         */
        public function getVatNumber()
        {
            return $this->vatNumber;
        }

        /**
         * @param string $vatNumber
         */
        public function setVatNumber($vatNumber)
        {
            $this->vatNumber = $vatNumber;
        }

        /**
         * @return string
         */
        public function getCompanyName()
        {
            return $this->companyName;
        }

        /**
         * @param string $companyName
         */
        public function setCompanyName($companyName)
        {
            $this->companyName = $companyName;
        }

        /**
         * @return string
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * @param string $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }

        /**
         * @return string
         */
        public function getCanSendFunds()
        {
            return $this->canSendFunds;
        }

        /**
         * @param string $canSendFunds
         */
        public function setCanSendFunds($canSendFunds)
        {
            $this->canSendFunds = $canSendFunds;
        }

        /**
         * @return string
         */
        public function getCanReceiveFunds()
        {
            return $this->canReceiveFunds;
        }

        /**
         * @return string
         */
        public function getContactPersonEmail()
        {
            return $this->contactPersonEmail;
        }

        /**
         * @param string $contactPersonEmail
         */
        public function setContactPersonEmail($contactPersonEmail)
        {
            $this->contactPersonEmail = $contactPersonEmail;
        }

        /**
         * @return string
         */
        public function getPaymentMethods()
        {
            return $this->paymentMethods;
        }

        /**
         * @param string $paymentMethods
         */
        public function setPaymentMethods($paymentMethods)
        {
            $this->paymentMethods = $paymentMethods;
        }

        /**
         * @return string
         */
        public function getCompanyRegistrationLink()
        {
            return DigitealRest::getEndPoint() . $this->companyRegistrationLink;
        }

        /**
         * @param string $companyRegistrationLink
         */
        public function setCompanyRegistrationLink($companyRegistrationLink)
        {
            $this->companyRegistrationLink = $companyRegistrationLink;
        }

        /**
         * @return string
         */
        public function getWebhookValidationUrl()
        {
            return $this->webhookValidationUrl;
        }

        /**
         * @param string $webhookValidationUrl
         */
        public function setWebhookValidationUrl($webhookValidationUrl)
        {
            $this->webhookValidationUrl = $webhookValidationUrl;
        }

        /**
         * @return string
         */
        public function getWebhookErrorUrl()
        {
            return $this->webhookErrorUrl;
        }

        /**
         * @return string
         */
        public function getModuleReady()
        {
            return $this->moduleReady;
        }

        /**
         * @param string $moduleReady
         */
        public function setModuleReady($moduleReady)
        {
            $this->moduleReady = $moduleReady;
        }

        /**
         * @param string $webhookErrorUrl
         */
        public function setWebhookErrorUrl($webhookErrorUrl)
        {
            $this->webhookErrorUrl = $webhookErrorUrl;
        }

        /**
         * @return string
         */
        public function getIntegratorId()
        {
            return $this->integratorId;
        }

        /**
         * @param string $integratorId
         */
        public function setIntegratorId($integratorId)
        {
            $this->integratorId = $integratorId;
        }

        /**
         * @return string
         */
        public function getIbans()
        {
            return $this->ibans;
        }

        /**
         * @param string $ibans
         */
        public function setIbans($ibans)
        {
            $this->ibans = $ibans;
        }

        /**
         * @return string
         */
        public function getSelectedIban()
        {
            return $this->selectedIban;
        }

        /**
         * @param string $selectedIban
         */
        public function setSelectedIban($selectedIban)
        {
            $this->selectedIban = $selectedIban;
        }

        /**
         * @return string
         */
        public function getSelectedPaymentMethods()
        {
            return $this->selectedPaymentMethods;
        }

        /**
         * @param string $selectedPaymentMethods
         */
        public function setSelectedPaymentMethods($selectedPaymentMethods)
        {
            $this->selectedPaymentMethods = $selectedPaymentMethods;
        }

        /**
         * DigitealCompanyStatus constructor.
         */
        public function __construct()
        {
            $this->load();
        }

        /**
         * Load data from database.
         */
        protected function load()
        {
            $properties = get_object_vars($this);
            foreach ($properties as $k => $v) {
                $key = (string) $k;
                $this->$key = $this->get($k);
            }
        }

        /**
         * Erase all values and reload.
         */
        public function emptyAndReload()
        {
            $properties = get_object_vars($this);
            foreach ($properties as $k => $v) {
                $key = (string) $k;
                if (!in_array($key, ['mode', 'kpiid', 'kpiidt'])) {
                    $this->updateValue($key, null);
                }
            }
            $this->load();
        }

        /**
         * Save.
         */
        public function save()
        {
            $properties = get_object_vars($this);
            foreach ($properties as $k => $v) {
                $key = (string) $k;
                $this->updateValue($key, (string) $v);
            }
            $this->updateValue('paymentMethods', $this->paymentMethods);
        }

        /*
            public function isStatusReady()
            {
                if ($this->status === 'PROD') {
                    return true;
                }
                return false;
            }
        */

        /**
         * @return bool
         */
        public function canReceiveFunds()
        {
            if ($this->canReceiveFunds) {
                return true;
            }

            return false;
        }

        /**
         * @return bool
         */
        public function isCompanyFound()
        {
            if ($this->status === 'PROD') {
                return true;
            }

            return false;
        }

        /**
         * @return bool
         */
        public function hasVatNumber()
        {
            if (!empty($this->vatNumber)) {
                return true;
            }

            return false;
        }

        /**
         * @return bool
         */
        public function hasCompanyRegistrationLink()
        {
            if (!empty($this->companyRegistrationLink)) {
                return true;
            }

            return false;
        }

        /**
         * @return bool
         */
        public function hasSelectedIban()
        {
            if (!empty($this->selectedIban)) {
                return true;
            }

            return false;
        }

        /**
         * @return array
         */
        public function getPaymentMethodsAsArray()
        {
            if (!empty($this->paymentMethods)) {
                return json_decode($this->paymentMethods, true);
            }

            return [];
        }

        /**
         * @return string|null
         */
        public function getPaymentMethodsLogos()
        {
            if (!empty($this->paymentMethods)) {
                $payment_methods = json_decode($this->paymentMethods, true);
                sort($payment_methods);
                $str = implode('-', $payment_methods);

                return strtolower($str);
            }

            return null;
        }

        /**
         * @return array
         */
        public function getIbansAsArray()
        {
            if (!empty($this->ibans)) {
                return json_decode($this->ibans, true);
            }

            return [];
        }

        /**
         * @throws \Exception
         *
         * @return bool
         */
        public function checkRestStatus()
        {
            DigitealLogger::logInfo('[checkRestStatus]');
            if (empty($this->vatNumber)) {
                return false;
            }

            $client = new DigitealRest();
            $requestData = ['vatNumber' => $this->vatNumber];

            if (false !== ($response = $client->get('/api/v1/integrator/company-info', $requestData))) {
                DigitealLogger::logInfo(var_export($response, true));
                if (is_array($response)) {
                    $errorCode = DigitealTools::findInArray('errorCode', $response);
                    if (isset($errorCode)) {
                        $errorMessage = DigitealTools::findInArray('errorMessage', $response);
                        $errorSubjects = DigitealTools::findInArray('errorSubjects', $response);
                        $requestId = DigitealTools::findInArray('requestId', $response);
                        DigitealLogger::logError('[checkRestStatus] requestId: '.var_export($requestId, true));
                        DigitealLogger::logError('[checkRestStatus] errorSubjects: '.var_export($errorSubjects, true));
                        DigitealLogger::logError('[checkRestStatus] errorMessage: '.var_export($errorMessage, true));
                    } else {
                        $this->status = DigitealTools::findInArray('status', $response);
                        $this->canReceiveFunds = DigitealTools::findInArray('canReceiveFunds', $response);
                        $this->canSendFunds = DigitealTools::findInArray('canSendFunds', $response);
                        if ($this->status === 'PROD') {
                            $this->companyName = DigitealTools::findInArray('companyName', $response);
                            $this->identificationNumber = DigitealTools::findInArray('id', $response);
                            $this->integratorId = DigitealTools::findInArray('integratorId', $response);
                            $ibans = DigitealTools::findInArray('ibans', $response);
                            if (count($ibans) === 1) {
                                $this->selectedIban = $ibans[0];
                            } else {
                                $this->selectedIban = null;
                            }
                            $this->ibans = json_encode($ibans);
                            $paymentMethods = DigitealTools::findInArray('paymentMethods', $response);
                            $paymentMethods = DigitealPaymentMethod::cleanPaymentMethods($paymentMethods);
                            $this->paymentMethods = strtoupper(json_encode($paymentMethods));
                            $this->save();

                            return true;
                        } else {
                            $this->save();

                            return true;
                        }
                    }
                }
            }

            return false;
        }

        /**
         * @return string
         */
        public function generateCompanyRegistrationLink()
        {
            $url = '/#/register/'.$this->contactPersonEmail
                .'/?vatNumber='.$this->vatNumber
                .'&companyName='.$this->companyName
                .'&paymentMethods='.$this->paymentMethods
                .'&integratorID='.$this->getKpiid()
                .'&forBusiness=true&pack=START';
            $this->updateValue('companyRegistrationLink', $url);
            $this->load();

            return $url;
        }

        /**
         * Call API to configure PAYMENT_INITIATED and PAYMENT_INITIATION_ERROR webhook.
         *
         * @param $validationUrl
         * @param $errorUrl
         * @param $username
         * @param $password
         *
         * @throws \Exception
         *
         * @return bool
         */
        public function generateWebhookConfiguration($validationUrl, $errorUrl, $username, $password)
        {
            if (empty($username) && empty($password)) {
                return false;
            }

            // TODO = MUST BE THE SAME
            if ($this->contactPersonEmail !== $username) {
                $this->contactPersonEmail = $username;
                $this->updateValue('contactPersonEmail', $username);
            }

            if ($this->restGenerateWebhook('PAYMENT_INITIATED', $validationUrl, $username, $password)) {
                if ($this->restGenerateWebhook('PAYMENT_INITIATION_ERROR', $errorUrl, $username, $password)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Call API to configure a webhook.
         *
         * @param $type
         * @param $url
         * @param $username
         * @param $password
         *
         * @throws \Exception
         *
         * @return bool
         */
        private function restGenerateWebhook($type, $url, $username, $password)
        {
            $client = new DigitealRest();

            $requestData = [
                'type' => $type,
                'url'  => $url,
            ];
            $authData = $username.':'.$password;
            if (false !== ($response = $client->post('/api/v1/webhook', $requestData, $authData))) {
                if (is_array($response)) {
                    $errorCode = DigitealTools::findInArray('errorCode', $response);
                    if (isset($errorCode)) {
                        $errorMessage = DigitealTools::findInArray('errorMessage', $response);
                        $errorSubjects = DigitealTools::findInArray('errorSubjects', $response);
                        $requestId = DigitealTools::findInArray('requestId', $response);
                        DigitealLogger::logError('[restGenerateWebhook] requestId: '.var_export($requestId, true));
                        DigitealLogger::logError('[restGenerateWebhook] errorSubjects: '.var_export($errorSubjects, true));
                        DigitealLogger::logError('[restGenerateWebhook] errorMessage: '.var_export($errorMessage, true));

                        return false;
                    } else {
                        $success = DigitealTools::findInArray('success', $response);
                        if (isset($success) && $success == true) {
                            return true;
                        }
                    }
                }
            }

            return false;
        }
    }
}
