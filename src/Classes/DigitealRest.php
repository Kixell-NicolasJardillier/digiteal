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

if (!class_exists('DigitealRest', false)) {
    class DigitealRest
    {
        /**
         * @var int
         */
        private $connectionTimeout = 45;

        /**
         * @var int
         */
        private $timeout = 45;

        /**
         * @return string
         */
        public static function getEndPoint($mode = null)
        {
            if (Configuration::get('KD_MODE') || $mode) {
                return 'https://test.digiteal.eu';
            }
            else {
                return 'https://api.digiteal.eu';
            }
        }

        /**
         * @return string
         */
        public static function getDigitealConfig($mode = null)
        {
            if (Configuration::get('KD_MODE') || $mode) {
                return 'YToxOntzOjU6ImtwaWlkIjtpOjI3MzAxO30=';
            }
            else {
                return 'YToxOntzOjU6ImtwaWlkIjtpOjE5ODQ4MzY5O30=';
            }
        }

        /**
         * @param $target
         * @param $data
         * @param null $auth
         *
         * @return array|false|mixed|null
         */
        public function post($target, $data, $auth = null)
        {
            try {
                return $this->callCurl($target, json_encode($data), $auth);
            } catch (Exception $e) {
                DigitealLogger::logError('[Exception] DigitealRest:post : '.$e->getMessage());
            }

            return false;
        }

        /**
         * @param $target
         * @param $data
         *
         * @return array|false|mixed|null
         */
        public function get($target, $data)
        {
            try {
                return $this->callCurl($target.'?'.http_build_query($data));
            } catch (Exception $e) {
                DigitealLogger::logError('[Exception] DigitealRest:get : '.$e->getMessage());
            }

            return false;
        }

        public static function getConf($mode = null)
        {
            $conf = unserialize(base64_decode(self::getDigitealConfig($mode)));
            if (isset($conf['kpiid']) && is_int($conf['kpiid'])) {
                return (int) $conf['kpiid'];
            }

            return false;
        }

        /**
         * @param $target
         * @param null $data
         * @param null $auth
         *
         * @throws Exception
         *
         * @return array|mixed|null
         */
        private function callCurl($target, $data = null, $auth = null)
        {
            $url = self::getEndPoint().$target;

            $headers = [
                'Content-type: application/json',
            ];

            if (!empty($auth)) {
                array_push($headers, 'Authorization: Basic '.base64_encode($auth));
            }

            $curl = curl_init($url);
            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (Linux; Android 7.0; SM-G892A Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Mobile Safari/537.36',
                CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
                CURLOPT_HEADER         => false,
                CURLOPT_CONNECTTIMEOUT => $this->connectionTimeout,
                CURLOPT_TIMEOUT        => $this->timeout,
            ];
            if (!is_null($data)) {
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $data;
            }
            curl_setopt_array($curl, $options);

            $raw_response = curl_exec($curl);
            $info = curl_getinfo($curl);

            $http_code = $info['http_code'];
            if (!in_array($http_code, [200, 401, 403, 404, 500])) {
                $error = curl_error($curl);
                $errno = curl_errno($curl);
                curl_close($curl);
                $msg = "Call to URL $url failed with unexpected status: {$info['http_code']}";
                if ($raw_response) {
                    $msg .= ", raw response: $raw_response";
                }

                if ($errno) {
                    $msg .= ", cURL error: $error ($errno)";
                }

                $msg .= ', cURL info: '.print_r($info, true);

                throw new Exception($msg, '-1');
            }

            $response = json_decode($raw_response, true);
            if (!is_array($response)) {
                $response['success'] = false;
                $errno = curl_errno($curl);
                if (empty($errno) && $http_code === 200) {
                    $response['success'] = true;
                }
            }
            curl_close($curl);

            return $response;
        }
    }
}
