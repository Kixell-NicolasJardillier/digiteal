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
* @version   1.0.3
*}

<div class="bootstrap">
    <div class="panel text-center">
        <img width="30" src="{$module_dir|escape:'htmlall':'UTF-8'}/views/img/digiteal.svg" >
        <h1>
            Digiteal
            <br /><small>{$digiteal_description|escape:'htmlall':'UTF-8'}</small>
            {if isset($kdmode) && $kdmode == 1}
                <br /><div style="color: white;margin-top: 10px;background: #27b5c0;padding: 5px 0;">MODE TEST</div>
            {/if}
        </h1>
    </div>
    {if isset($messageSuccess)}
        <div class="panel digiteal-success">
            <p>{$messageSuccess|escape:'htmlall':'UTF-8'}</p>
        </div>
    {/if}
    {if isset($messageError)}
        <div class="panel digiteal-alert">
            <p>{$messageError|escape:'htmlall':'UTF-8'}</p>
        </div>
    {/if}

    <div class="panel">
        <form id="configuration_form" class="defaultForm form-horizontal digiteal" method="post" action="{$form_action|escape:'htmlall':'UTF-8'}">
            {if $settings_step == 1}
                <h3 class="digiteal-color panel-heading">{l s='Configuration' mod='digiteal'}</h3>
                <p class="digiteal-intro">{l s='To start the configuration, please enter your VAT number. This step allows us to verify your company\'s status with Digiteal and to assist you in configuring the module.' mod='digiteal'}</p>

            {elseif $settings_step == 2}
                <h3 class="digiteal-color panel-heading">{l s='Pre-registration' mod='digiteal'}</h3>
                <p class="digiteal-intro">{l s='Please fill in the information below to generate your registration link with Digiteal.' mod='digiteal'}</p>


            {elseif $settings_step == 3}
                <h3 class="digiteal-color panel-heading">{l s='Pre-registration' mod='digiteal'}</h3>
                <p class="digiteal-intro">
                    {l s='Access the Digiteal registration form by clicking on the button below (when you create your account with Digiteal, you will receive an email to finalize your registration).' mod='digiteal'}
                    <br />{l s='Once you have completed your registration with Digiteal, you will need to wait for the Digiteal team to validate your account. You will be able to return to the module configuration or click on this link to finalize your configuration:' mod='digiteal'}
                    <a href="#" onClick="window.location.reload();return false;">{l s='Reload the page' mod='digiteal'}</a>
                    <br />{l s='Note: Completing this configuration requires that you take at least one START pack.' mod='digiteal'}
                </p>

            {elseif $settings_step == 4}
                <h3 class="digiteal-color panel-heading">{l s='Active account pending validation by Digiteal' mod='digiteal'}</h3>
                <p class="digiteal-intro">{l s='Your account is active but awaiting validation by the Digiteal team.' mod='digiteal'}
                <br />{l s='Once your account has been validated, you can return to the module configuration or click on this link to finalize your configuration:' mod='digiteal'}
                    <a href="#" onClick="window.location.reload();return false;">{l s='Reload the page' mod='digiteal'}</a></p>

            {elseif $settings_step == 5}
                <h3 class="digiteal-colorpanel-heading">{l s='Finalization of the configuration' mod='digiteal'}</h3>
                <p class="digiteal-intro">{l s='To finalize, we need to configure the synchronization between the Digiteal platform and your store. To do this, please enter your Digiteal platform connection information:' mod='digiteal'}</p>


            {elseif $settings_step == 6}
                <h3 class="digiteal-color panel-heading">{l s='Configuration completed' mod='digiteal'}</h3>
                <p class="digiteal-intro">{l s='You can update the settings of this payment module below, and also retrieve your Digiteal account information if it has changed, by clicking the "Update" button below (for example, if you have added a new IBAN and would like it to appear in the list below, or if you have changed your payment method settings).' mod='digiteal'}</p>
            {/if}


            {foreach $inputs as $input}
                <div class="form-group">
                    {if $input.type == 'text'}
                        <label class="control-label col-lg-3">{$input.label}</label>
                        <div class="margin-form col-lg-3">
                            {if isset($input.disabled) && $input.disabled}
                                <input type="text" value="{if isset($input.value)}{$input.value}{/if}" disabled>
                                <input type="hidden" name="{$input.name}" id="{$input.name}" value="{if isset($input.value)}{$input.value}{/if}">
                            {else}
                                <input type="text" name="{$input.name}" id="{$input.name}" value="{if isset($input.value)}{$input.value}{/if}"
                                       {if isset($input.required) && $input.required}required="required"{else}{/if}>
                            {/if}
                        </div>
                        <div class="col-lg-6">
                            {if isset($input.desc)}<p class="digiteal-info">{$input.desc}</p>{/if}
                        </div>
                    {elseif $input.type == 'radio'}
                        <label class="control-label col-lg-3">{$input.label}</label>
                        <div class="margin-form col-lg-3">
                            {foreach $input.values as $value}
                                <div class="radio {if isset($input.class)}{$input.class}{/if}">
                                    {strip}
                                        <label>
                                            <input type="radio"	name="{$input.name}" id="{$value.id}" value="{$value.value|escape:'html':'UTF-8'}"{if $input.value == $value.value} checked="checked"{/if}{if (isset($input.disabled) && $input.disabled) or (isset($value.disabled) && $value.disabled)} disabled="disabled"{/if}/>
                                            {$value.label}
                                        </label>
                                    {/strip}
                                </div>
                                {if isset($value.p) && $value.p}<p class="help-block">{$value.p}</p>{/if}
                            {/foreach}
                        </div>
                        <div class="col-lg-6">
                            {if isset($input.desc)}<p class="digiteal-info">{$input.desc}</p>{/if}
                        </div>
                    {elseif $input.type == 'switch'}
                    <label class="control-label col-lg-3">{$input.label}</label>
                    <div class="margin-form col-lg-3">
                        <span class="switch prestashop-switch fixed-width-lg">
                            {foreach $input.values as $value}
                                <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $input.value == $value.value} checked="checked"{/if}{if (isset($input.disabled) && $input.disabled) or (isset($value.disabled) && $value.disabled)} disabled="disabled"{/if}/>
                            {strip}
                                <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                                {if $value.value == 1}
                                    {l s='Yes'}
                                {else}
                                    {l s='No'}
                                {/if}
                            </label>
                            {/strip}
                            {/foreach}
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="col-lg-6">
                        {if isset($input.desc)}<p class="digiteal-info">{$input.desc}</p>{/if}
                    </div>
                    {elseif $input.type == 'password'}
                        <label class="control-label col-lg-3">{$input.label}</label>
                        <div class="margin-form col-lg-3">
                            <input type="password" name="{$input.name}" id="{$input.name}" value="{if isset($input.value)}{$input.value}{/if}"
                                   {if isset($input.required) && $input.required}required="required"{else}{/if}>
                        </div>
                        <div class="col-lg-6">
                            {if isset($input.desc)}<p class="digiteal-info">{$input.desc}</p>{/if}
                        </div>
                    {elseif $input.type == 'hidden'}
                        <input type="hidden" name="{$input.name}" id="{$input.name}" value="{if isset($input.value)}{$input.value}{/if}">
                    {elseif $input.type == 'link'}
                        <label class="control-label col-lg-3">{$input.label}</label>
                        <div class="margin-form col-lg-3">
                            <a class="btn btn-lg btn-primary btn-block" {if isset($input.value)} href="{$input.value}"{/if} target="_blank">{$input.name}</a>
                        </div>
                        <div class="col-lg-6">
                            {if isset($input.desc)}<p class="digiteal-info">{$input.desc}</p>{/if}
                        </div>
                    {elseif $input.type == 'alert'}
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            {if isset($input.desc)}<p class="digiteal-alert">{$input.desc}</p>{/if}
                        </div>
                        <div class="col-lg-2"></div>
                    {elseif $input.type == 'reinsurance'}
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            {if isset($input.desc)}<p class="digiteal-reinsurance">{$input.desc}</p>{/if}
                        </div>
                        <div class="col-lg-2"></div>
                    {elseif $input.type == 'paymentMethod'}
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <h4 class="text-left">{$input.label}</h4>
                            {foreach $input.values as $value}
                                <div style="display: inline-block; margin: 0 10px" class="text-center">
                                    <label for="{$input.name}_{$value|strtolower}">
                                        <img style="cursor:pointer;" width="100" src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/{$value|strtolower}.svg">
                                    </label><br />
                                    {if isset($input.disabled) && $input.disabled}
                                    {else}
                                        <input type="checkbox" name="{$input.name}_{$value|strtolower}" id="{$input.name}_{$value|strtolower}" value="{$value|strtolower}">
                                    {/if}
                                </div>
                            {/foreach}
                        </div>
                        <div class="col-lg-2"></div>
                    {elseif $input.type == 'ibans'}
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <h4 class="text-left">{$input.label}</h4>
                            {foreach $input.values as $value}
                                <div style="text-align:left; margin: 0 10px;border: 1px solid #eeeeee;padding: 5px 15px;font-size:1.1em" class="text-center">
                                    <input type="radio" name="{$input.name}" id="{$input.name}_{$value}" value="{$value}"
                                    {if $input.selected === $value}checked{/if}>
                                    <label for="{$input.name}_{$value}" id="{$input.name}_{$value}">
                                        {$value}
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                        <div class="col-lg-2"></div>
                    {/if}
                </div>
            {/foreach}


            <div class="panel-footer text-right">
                {*<a href="{$reinit_module}" class="btn btn-default pull-left">
                    <i class="process-icon-delete"></i> {l s='Reinitialize the configuration' mod='digiteal'}
                </a>*}

                {if $settings_step != 1}
                    <button formnovalidate type="submit" value="0" id="digiteal_form_reinit_btn" name="{$reinit_submit}" class="btn btn-default pull-left">
                        <i class="process-icon-delete"></i> {l s='Reinitialize the configuration' mod='digiteal'}
                    </button>
                {/if}
                {if isset($submit_label) &&  isset($submit_name)}
                    <button type="submit" value="1" id="configuration_form_submit_btn" name="{$submit_name}" class="btn btn-primary pull-right">
                        <i class="process-icon-next"></i> {$submit_label}
                    </button>
                {/if}
            </div>
        </form>
        {* if isset($submit_label) &&  isset($submit_name)}
        </form>
        {else}
        </div>
        {/if *}
    </div>

</div>
<div class="modal fade" id="digiteal_modal_reinit" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{l s='Attention' mod='digiteal'}</h4>
            </div>

            <div class="modal-body">{l s='Resetting the configuration will disable the payment on your store. You will need to re-configure the module with your company information. Would you like to reset the module?' mod='digiteal'}</div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-lg" id="digiteal-modal-cancel" data-dismiss="modal">
                    {l s='Cancel' mod='digiteal'}
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="digiteal-modal-submit">
                    {l s='Yes, reset the module' mod='digiteal'}
                </button>
            </div>
        </div>
    </div>
</div>
