{*
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
* @version   1.0.1
*}

{if $status == 'ok'}
<p class="alert alert-success">{l s='Your order on %s is complete.' sprintf=$shop_name mod='digiteal'}</p>
<div class="box">
    <p>
        {if !isset($reference)}
            {l s='Your order number #%d on' sprintf=$id_order mod='digiteal'}&nbsp;<span class="bold">{$shop_name|escape:'html':'UTF-8'}</span> {l s='is complete.' mod='digiteal'}
        {else}
            {l s='Your order reference %s on' sprintf=$reference mod='digiteal'}&nbsp;<span class="bold">{$shop_name|escape:'html':'UTF-8'}</span> {l s='is complete.' mod='digiteal'}
        {/if}
        <br />
        {l s='We registered your payment of ' mod='digiteal'}&nbsp;<span class="price">{$total_to_pay|escape:'html':'UTF-8'}</span>
        <br />
        {l s='For any questions or for further information, please contact our' mod='digiteal'}&nbsp;<a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='customer support' mod='digiteal'}</a>.
    </p>
</div>
{else}
    <p class="alert alert-warning">
        {l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='digiteal'}
        <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team' mod='digiteal'}</a>.
    </p>
{/if}
