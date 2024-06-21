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

if (!class_exists('DigitealLogger', false)) {
    class DigitealLogger
    {
        /**
         * @var FileLogger
         */
        private static $_logger = null;

        /**
         * @var bool
         */
        private static $_enable = true;

        /**
         * @return FileLogger
         */
        private static function getInstance()
        {
            if (is_null(self::$_enable)) {
                self::$_enable = (bool) Configuration::get('KD_ENABLE_LOGGER');
            }
            if (self::$_enable) {
                if (is_null(self::$_logger)) {
                    self::$_logger = new FileLogger();
                    $logs_dir = _PS_ROOT_DIR_.'/var/logs/';
                    if (!file_exists($logs_dir)) {
                        $logs_dir = _PS_ROOT_DIR_.'/app/logs/';
                        if (!file_exists($logs_dir)) {
                            $logs_dir = _PS_ROOT_DIR_.'/log/';
                        }
                    }
                    self::$_logger->setFilename($logs_dir.date('Y_m_d').'_digiteal.log');
                }
            }

            return self::$_logger;
        }

        /**
         * @param     $message
         * @param int $level
         */
        public static function log($message, $level = FileLogger::DEBUG)
        {
            $logger = self::getInstance();
            if (self::$_enable) {
                $logger->log($message, $level);
            }
        }

        /**
         * @param $message
         */
        public static function logDebug($message)
        {
            $logger = self::getInstance();
            if (self::$_enable) {
                $logger->logDebug($message);
            }
        }

        /**
         * @param $message
         */
        public static function logInfo($message)
        {
            $logger = self::getInstance();
            if (self::$_enable) {
                $logger->logInfo($message);
            }
        }

        /**
         * @param $message
         */
        public static function logWarning($message)
        {
            $logger = self::getInstance();
            if (self::$_enable) {
                $logger->logWarning($message);
            }
        }

        /**
         * @param $message
         */
        public static function logError($message)
        {
            $logger = self::getInstance();
            if (self::$_enable) {
                $logger->logError($message);
            }
        }
    }
}
