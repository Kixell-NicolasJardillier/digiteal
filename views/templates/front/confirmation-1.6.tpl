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
* @version   1.0.0
*}

{capture name=path}digiteal{/capture}
{if version_compare($smarty.const._PS_VERSION_, '1.6', '<')}
    {include file="$tpl_dir./breadcrumb.tpl"}
{/if}

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<div id="digiteal_content">
    <section id="content">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-xs-12 col-lg-6" style="-webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px;border: 1px solid #ccc;margin: 20px 0 40px 0;">
                <section id="digiteal_content" class="checkout-step -current" style="text-align: center; padding: 20px">
                    <span style="display:inline-block; width: 100px">
                        <svg enable-background="new 0 0 130.3 150" viewBox="0 0 130.3 150" xmlns="http://www.w3.org/2000/svg"><g fill="#0db5c0"><path d="m64.7 150 41.2-23.4h-82.5z"/><path d="m130.3 67.5h-55.3l39.4-39.4 15.9 9.4z"/><path d="m130.3 94.7h-83.4l24.3-23.5h59.1z"/><path d="m130.3 112.5-17.8 10.3h-92.8l24.4-24.4h86.2z"/><path d="m15.9 121.9 95.7-94.7-46.9-27.2-64.7 37.5v75z"/></g></svg>
                    </span>
                    <h1 class="step-title h3" style="margin: 20px 0 0; font-size:1.25rem">
                        {l s='Redirecting ...' mod='digiteal'}
                    </h1>

                    <div class="content">
                        <p style="text-align: center; font-size:1.15rem">
                            <br />
                            {l s='Please wait, you will be redirected to the order confirmation page.' mod='digiteal'}
                            <br /> <br />
                            <strong style="font-size:1.2rem">{l s='In' mod='digiteal'}&nbsp;<span id="digiteal_elapsed_time" data-url="{$digiteal_elapsed_time_url}" data-default="{$digiteal_default_url_redirect}">{$digiteal_elapsed_time}</span>&nbsp;{l s='seconds' mod='digiteal'}</strong>
                            <br /><br />
                        </p>
                    </div>
                </section>
            </div>

            <div class="col-lg-3"></div>
        </div>
    </section>
</div>
