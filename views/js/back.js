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
!function(e){var t={};function n(i){if(t[i])return t[i].exports;var r=t[i]={i:i,l:!1,exports:{}};return e[i].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(i,r,function(t){return e[t]}.bind(null,r));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=1)}([function(e,t,n){"use strict";var i,r=function(){return void 0===i&&(i=Boolean(window&&document&&document.all&&!window.atob)),i},o=function(){var e={};return function(t){if(void 0===e[t]){var n=document.querySelector(t);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(e){n=null}e[t]=n}return e[t]}}(),a=[];function c(e){for(var t=-1,n=0;n<a.length;n++)if(a[n].identifier===e){t=n;break}return t}function l(e,t){for(var n={},i=[],r=0;r<e.length;r++){var o=e[r],l=t.base?o[0]+t.base:o[0],u=n[l]||0,d="".concat(l," ").concat(u);n[l]=u+1;var f=c(d),s={css:o[1],media:o[2],sourceMap:o[3]};-1!==f?(a[f].references++,a[f].updater(s)):a.push({identifier:d,updater:b(s,t),references:1}),i.push(d)}return i}function u(e){var t=document.createElement("style"),i=e.attributes||{};if(void 0===i.nonce){var r=n.nc;r&&(i.nonce=r)}if(Object.keys(i).forEach((function(e){t.setAttribute(e,i[e])})),"function"==typeof e.insert)e.insert(t);else{var a=o(e.insert||"head");if(!a)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");a.appendChild(t)}return t}var d,f=(d=[],function(e,t){return d[e]=t,d.filter(Boolean).join("\n")});function s(e,t,n,i){var r=n?"":i.media?"@media ".concat(i.media," {").concat(i.css,"}"):i.css;if(e.styleSheet)e.styleSheet.cssText=f(t,r);else{var o=document.createTextNode(r),a=e.childNodes;a[t]&&e.removeChild(a[t]),a.length?e.insertBefore(o,a[t]):e.appendChild(o)}}function p(e,t,n){var i=n.css,r=n.media,o=n.sourceMap;if(r?e.setAttribute("media",r):e.removeAttribute("media"),o&&"undefined"!=typeof btoa&&(i+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(o))))," */")),e.styleSheet)e.styleSheet.cssText=i;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(i))}}var m=null,v=0;function b(e,t){var n,i,r;if(t.singleton){var o=v++;n=m||(m=u(t)),i=s.bind(null,n,o,!1),r=s.bind(null,n,o,!0)}else n=u(t),i=p.bind(null,n,t),r=function(){!function(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e)}(n)};return i(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;i(e=t)}else r()}}e.exports=function(e,t){(t=t||{}).singleton||"boolean"==typeof t.singleton||(t.singleton=r());var n=l(e=e||[],t);return function(e){if(e=e||[],"[object Array]"===Object.prototype.toString.call(e)){for(var i=0;i<n.length;i++){var r=c(n[i]);a[r].references--}for(var o=l(e,t),u=0;u<n.length;u++){var d=c(n[u]);0===a[d].references&&(a[d].updater(),a.splice(d,1))}n=o}}}},function(e,t,n){n(2),e.exports=n(4)},function(e,t,n){"use strict";n.r(t);n(3)},function(e,t){var n,i,r;n=document,i=!1,r=function e(t){t.preventDefault(),i?$("#digiteal_modal_reinit").modal("show"):confirm("Reinitialization ?")&&($("#digiteal_form_reinit_btn").off("click",e),$("#digiteal_form_reinit_btn").click())},_init=function(e){!function(){try{$("#digiteal_modal_reinit").modal({backdrop:"static",keyboard:!1,closable:!1,show:!1}),i=!0}catch(e){i=!1}}(),$("#digiteal_form_reinit_btn").on("click",r),$("#digiteal-modal-submit").on("click",(function(e){e.preventDefault(),$("#digiteal_modal_reinit").modal("hide"),$("#digiteal_form_reinit_btn").off("click",r),$("#digiteal_form_reinit_btn").click()}))},"loading"!==n.readyState?_init():n.addEventListener?n.addEventListener("DOMContentLoaded",_init,!1):n.attachEvent("onreadystatechange",_init)},function(e,t,n){"use strict";n.r(t);n(5)},function(e,t,n){var i=n(0),r=n(6);"string"==typeof(r=r.__esModule?r.default:r)&&(r=[[e.i,r,""]]);var o={insert:"head",singleton:!1};i(r,o);e.exports=r.locals||{}},function(e,t,n){}]);