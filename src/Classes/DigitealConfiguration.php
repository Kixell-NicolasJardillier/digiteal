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

if (! defined('_PS_VERSION_')) {
    exit();
}

if (!class_exists('DigitealConfiguration', false)) {
    class DigitealConfiguration
    {
        const PREFIX = "KD_";

        public function get($key)
        {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $key = substr($key, 0, 32);
            }
            return Configuration::get(self::PREFIX . strtoupper($key));
        }

        public function updateValue($key, $value)
        {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $key = substr($key, 0, 32);
            }
            return Configuration::updateValue(self::PREFIX . strtoupper($key), $value);
        }
    }
}
