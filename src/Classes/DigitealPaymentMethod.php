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
 * @version   1.0.2
 */
if (!defined('_PS_VERSION_')) {
    exit();
}

if (!class_exists('DigitealPaymentMethod', false)) {
    class DigitealPaymentMethod
    {
        /**
         * @var string[]
         */
        private static $methods = [
            'BANCONTACT',
            'CARTE_BLEUE',
            'DIGITEAL',
            'DIGITEAL_DIRECT', // DIRECT/PIS = SEPA Credit Transfer
            'DIGITEAL_STANDARD', // SEPA Direct Debit
            'IDEAL',
            'MASTERCARD',
            'VISA',
        ];

        private static $logos = [
            'bancontact'        => ['f' => 'bancontact.svg', 'w' => 57, 'h' => 40],
            'carte_bleue'       => ['f' => 'carte_bleue.svg', 'w' => 57, 'h' => 40],
            'digiteal'          => ['f' => 'digiteal.svg', 'w' => 36, 'h' => 40],
            'digiteal_direct'   => ['f' => 'digiteal_direct.svg', 'w' => 57, 'h' => 40],
            'digiteal_standard' => ['f' => 'digiteal_standard.svg', 'w' => 57, 'h' => 40],
            'ideal'             => ['f' => 'ideal.svg', 'w' => 55, 'h' => 40],
            'mastercard'        => ['f' => 'mastercard.svg', 'w' => 58, 'h' => 40],
            'visa'              => ['f' => 'visa.svg', 'w' => 58, 'h' => 40],
        ];

        /**
         * @return string[]
         */
        public static function getMethods()
        {
            return self::$methods;
        }

        /**
         * @return array
         */
        public static function getImagesPath()
        {
            $result = [];
            foreach (self::$methods as $method) {
                $result[] = [
                    'color' => 'views/img/'.strtolower($method).'.svg',
                ];
            }

            return $result;
        }

        /**
         * @param $payment_methods
         *
         * @return string[]
         */
        public static function cleanPaymentMethods($payment_methods)
        {
            $result = array_intersect(self::$methods, $payment_methods);
            sort($result);

            return $result;
        }

        /**
         * @param $file
         * @param $paymentMethods
         */
        public static function generateLogosFile($file, $paymentMethods)
        {
            $payment_method_files_dir = _PS_MODULE_DIR_.'digiteal/views/img/logos/';
            $x = 0;
            $y = 0;
            $svg = [];
            $iter = 0;
            foreach ($paymentMethods as $payment_method) {
                $svgFile = $payment_method_files_dir.self::$logos[strtolower($payment_method)]['f'];
                $svgFile = @file_get_contents($svgFile);
                if (strlen(trim($svgFile)) > 0) {
                    $svgFile = str_replace('KIX_XX', $x, $svgFile);
                    $svgFile = str_replace('KIX_YY', $y, $svgFile);
                    $svg[$iter] = $svgFile;
                    $iter++;
                    $x += self::$logos[strtolower($payment_method)]['w'] + 2;
                }
            }

            foreach ($paymentMethods as $payment_method) {
                $svg[$iter] = '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="'.strtolower($payment_method).'"/>';
                $iter++;
            }

            $svgFile = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="'.($x - 2).'px" height="40px" viewBox="0 0 '.($x - 2).' 40">';
            $svgFile .= implode('', $svg);
            $svgFile .= '</svg>';

            @file_put_contents($file, $svgFile);
        }
    }
}
