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
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * 1.0.5 adds the digiteal/notify and digiteal/notifyerror front controllers, which replace the
 * modules/digiteal/validation.php and modules/digiteal/error.php entry points forbidden by
 * Prestashop 9.
 *
 * @param Digiteal $module
 *
 * @return bool
 */
function upgrade_module_1_0_5($module)
{
    foreach (['clearSf2Cache', 'clearSmartyCache', 'clearXMLCache'] as $method) {
        if (method_exists('Tools', $method)) {
            try {
                Tools::$method();
            } catch (Exception $e) {
            }
        }
    }

    if (defined('_PS_CACHE_DIR_')) {
        $classIndex = _PS_CACHE_DIR_.'class_index.php';
        if (file_exists($classIndex) && is_writable($classIndex)) {
            @unlink($classIndex);
        }
    }

    if (method_exists($module, 'syncWebhookUrls')) {
        $module->syncWebhookUrls();
    }

    return true;
}
