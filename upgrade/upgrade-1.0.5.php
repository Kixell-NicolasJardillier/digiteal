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
 * The webhooks registered with Digiteal cannot be updated from here : the Digiteal password is
 * required and is never stored. The merchant has to run step 5 ("Finalize the configuration")
 * again; the module configuration page displays a warning as long as the old URLs are in use.
 *
 * @param Digiteal $module
 *
 * @return bool
 */
function upgrade_module_1_0_5($module)
{
    // Make sure the newly added front controllers are picked up right away.
    foreach (['clearSf2Cache', 'clearSmartyCache', 'clearXMLCache'] as $method) {
        if (method_exists('Tools', $method)) {
            try {
                Tools::$method();
            } catch (Exception $e) {
                // A cache that cannot be cleared must not abort the upgrade.
            }
        }
    }

    if (defined('_PS_CACHE_DIR_')) {
        $classIndex = _PS_CACHE_DIR_.'class_index.php';
        if (file_exists($classIndex) && is_writable($classIndex)) {
            @unlink($classIndex);
        }
    }

    return true;
}
