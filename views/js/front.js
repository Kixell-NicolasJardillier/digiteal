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
 * @version   1.0.3
 */
(function(){"use strict";(function(a,s){var n=document,t=null,i=null,l=!0,r=null,d=parseInt(a("#digiteal_elapsed_time").text()),c=function(e){return e?decodeURIComponent(window.atob(e)):!1},f=function(){t&&l&&(l=!1,a.ajax({cache:!1,contentType:!1,processData:!1,type:"POST",url:t,dataType:"json",success:function(e){e.elapsed!==s?parseInt(e.elapsed)>=0&&(a("#digiteal_elapsed_time").empty().html(e.elapsed),t=t.replace("elapsed="+d,"elapsed="+e.elapsed),d=parseInt(e.elapsed),l=!0):e.redirect!==s?(clearInterval(r),window.location.href=e.redirect):(clearInterval(r),window.location.href=i)},error:function(e,_,v){l=!0,d--,d<=0&&(clearInterval(r),i&&(window.location.href=i))}}))},p=function(){try{t=c(a("#digiteal_elapsed_time").data("url")),i=c(a("#digiteal_elapsed_time").data("default"))}catch(e){}},u=function(){r=setInterval(f,1e3)},o=function(){p(),t&&u()};n.readyState!=="loading"?o():n.addEventListener?n.addEventListener("DOMContentLoaded",o,!1):n.attachEvent("onreadystatechange",o)})(jQuery)})();
