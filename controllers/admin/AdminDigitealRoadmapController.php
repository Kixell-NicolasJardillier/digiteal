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
 * @version   1.0.0
 */
class AdminDigitealRoadmapController extends ModuleAdminController
{
    public function __construct()
    {
        $this->ajax = true;
        parent::__construct();
    }

    public function postProcess()
    {
        $content = '';
        $roadmap = Configuration::get('KD_ROADMAP');
        $shopname = Configuration::get('PS_SHOP_NAME');
        $lang = $this->context->language->iso_code;
        if ($roadmap) {
            $query = ['v' => $this->module->version, 's' => $shopname, 'l' => $lang];
            $content = file_get_contents('https://'.$roadmap.'/index.php?'.http_build_query($query));
        }
        exit($content);
    }
}
