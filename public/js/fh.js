
(function(window){var $=window.jQuery||window.angular.element;var rootElement=window.document.documentElement,$rootElement=$(rootElement);addGlobalEventListener('change',markValue);addValueChangeByJsListener(markValue);$.prototype.checkAndTriggerAutoFillEvent=jqCheckAndTriggerAutoFillEvent;addGlobalEventListener('blur',function(target){window.setTimeout(function(){findParentForm(target).find('input').checkAndTriggerAutoFillEvent();},20);});window.document.addEventListener('DOMContentLoaded',function(){window.setTimeout(function(){$rootElement.find('input').checkAndTriggerAutoFillEvent();},200);},false);return;function jqCheckAndTriggerAutoFillEvent(){var i,el;for(i=0;i<this.length;i++){el=this[i];if(!valueMarked(el)){markValue(el);triggerChangeEvent(el);}}}
function valueMarked(el){var val=el.value,$$currentValue=el.$$currentValue;if(!val&&!$$currentValue){return true;}
return val===$$currentValue;}
function markValue(el){el.$$currentValue=el.value;}
function addValueChangeByJsListener(listener){var jq=window.jQuery||window.angular.element,jqProto=jq.prototype;var _val=jqProto.val;jqProto.val=function(newValue){var res=_val.apply(this,arguments);if(arguments.length>0){forEach(this,function(el){listener(el,newValue);});}
return res;}}
function addGlobalEventListener(eventName,listener){rootElement.addEventListener(eventName,onEvent,true);function onEvent(event){var target=event.target;listener(target);}}
function findParentForm(el){while(el){if(el.nodeName==='FORM'){return $(el);}
el=el.parentNode;}
return $();}
function forEach(arr,listener){if(arr.forEach){return arr.forEach(listener);}
var i;for(i=0;i<arr.length;i++){listener(arr[i]);}}
function triggerChangeEvent(element){var doc=window.document;var event=doc.createEvent("HTMLEvents");event.initEvent("change",true,true);element.dispatchEvent(event);}})(window);;'use strict';angular.module.('fh.directives',[]);angular.module.('fh.services',[]);angular.module.('fh.controllers',['fh.services']);angular.module('fh',['ngAnimate','ui.bootstrap','fh.directive','fh.services','fh.controllers']).config(['$httpProvider','$interpolateProvider',function($httpProvider,$interpolateProvider){'use strict';$httpProvider.defaults.headers.common["X-Requested-With"]='XMLHttpRequest';$interpolateProvider.startSymbol('{[{').endSymbol('}]}');}]);;