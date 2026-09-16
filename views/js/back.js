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
 * @version   1.0.5
 */
(function(){"use strict";(function(){var t=document,e=!1,l=function(){var i=!1;try{$("#digiteal_modal_reinit").modal({backdrop:i?!0:"static",keyboard:i,closable:i,show:!1}),e=!0}catch(a){e=!1}},n=function(i){if(i.preventDefault(),e)$("#digiteal_modal_reinit").modal("show");else{var a=confirm("Reinitialization ?");a&&($("#digiteal_form_reinit_btn").off("click",n),$("#digiteal_form_reinit_btn").click())}},o=function(){$("#digiteal_form_reinit_btn").on("click",n),$("#digiteal-modal-submit").on("click",function(i){i.preventDefault(),$("#digiteal_modal_reinit").modal("hide"),$("#digiteal_form_reinit_btn").off("click",n),$("#digiteal_form_reinit_btn").click()})};_init=function(i){l(),o()},t.readyState!=="loading"?_init():t.addEventListener?t.addEventListener("DOMContentLoaded",_init,!1):t.attachEvent("onreadystatechange",_init)})()})();
