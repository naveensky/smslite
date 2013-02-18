/* jQuery UI Widget 1.10.0+amd
 * https://github.com/blueimp/jQuery-File-Upload
 * Copyright 2013 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/jQuery.widget/
 */

(function(factory){if(typeof define==="function"&&define.amd){define(["jquery"],factory);}else{factory(jQuery);}}(function($,undefined){var uuid=0,slice=Array.prototype.slice,_cleanData=$.cleanData;$.cleanData=function(elems){for(var i=0,elem;(elem=elems[i])!=null;i++){try{$(elem).triggerHandler("remove");}catch(e){}}
    _cleanData(elems);};$.widget=function(name,base,prototype){var fullName,existingConstructor,constructor,basePrototype,proxiedPrototype={},namespace=name.split(".")[0];name=name.split(".")[1];fullName=namespace+"-"+name;if(!prototype){prototype=base;base=$.Widget;}
    $.expr[":"][fullName.toLowerCase()]=function(elem){return!!$.data(elem,fullName);};$[namespace]=$[namespace]||{};existingConstructor=$[namespace][name];constructor=$[namespace][name]=function(options,element){if(!this._createWidget){return new constructor(options,element);}
        if(arguments.length){this._createWidget(options,element);}};$.extend(constructor,existingConstructor,{version:prototype.version,_proto:$.extend({},prototype),_childConstructors:[]});basePrototype=new base();basePrototype.options=$.widget.extend({},basePrototype.options);$.each(prototype,function(prop,value){if(!$.isFunction(value)){proxiedPrototype[prop]=value;return;}
        proxiedPrototype[prop]=(function(){var _super=function(){return base.prototype[prop].apply(this,arguments);},_superApply=function(args){return base.prototype[prop].apply(this,args);};return function(){var __super=this._super,__superApply=this._superApply,returnValue;this._super=_super;this._superApply=_superApply;returnValue=value.apply(this,arguments);this._super=__super;this._superApply=__superApply;return returnValue;};})();});constructor.prototype=$.widget.extend(basePrototype,{widgetEventPrefix:existingConstructor?basePrototype.widgetEventPrefix:name},proxiedPrototype,{constructor:constructor,namespace:namespace,widgetName:name,widgetFullName:fullName});if(existingConstructor){$.each(existingConstructor._childConstructors,function(i,child){var childPrototype=child.prototype;$.widget(childPrototype.namespace+"."+childPrototype.widgetName,constructor,child._proto);});delete existingConstructor._childConstructors;}else{base._childConstructors.push(constructor);}
    $.widget.bridge(name,constructor);};$.widget.extend=function(target){var input=slice.call(arguments,1),inputIndex=0,inputLength=input.length,key,value;for(;inputIndex<inputLength;inputIndex++){for(key in input[inputIndex]){value=input[inputIndex][key];if(input[inputIndex].hasOwnProperty(key)&&value!==undefined){if($.isPlainObject(value)){target[key]=$.isPlainObject(target[key])?$.widget.extend({},target[key],value):$.widget.extend({},value);}else{target[key]=value;}}}}
    return target;};$.widget.bridge=function(name,object){var fullName=object.prototype.widgetFullName||name;$.fn[name]=function(options){var isMethodCall=typeof options==="string",args=slice.call(arguments,1),returnValue=this;options=!isMethodCall&&args.length?$.widget.extend.apply(null,[options].concat(args)):options;if(isMethodCall){this.each(function(){var methodValue,instance=$.data(this,fullName);if(!instance){return $.error("cannot call methods on "+name+" prior to initialization; "+"attempted to call method '"+options+"'");}
    if(!$.isFunction(instance[options])||options.charAt(0)==="_"){return $.error("no such method '"+options+"' for "+name+" widget instance");}
    methodValue=instance[options].apply(instance,args);if(methodValue!==instance&&methodValue!==undefined){returnValue=methodValue&&methodValue.jquery?returnValue.pushStack(methodValue.get()):methodValue;return false;}});}else{this.each(function(){var instance=$.data(this,fullName);if(instance){instance.option(options||{})._init();}else{$.data(this,fullName,new object(options,this));}});}
    return returnValue;};};$.Widget=function(){};$.Widget._childConstructors=[];$.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:false,create:null},_createWidget:function(options,element){element=$(element||this.defaultElement||this)[0];this.element=$(element);this.uuid=uuid++;this.eventNamespace="."+this.widgetName+this.uuid;this.options=$.widget.extend({},this.options,this._getCreateOptions(),options);this.bindings=$();this.hoverable=$();this.focusable=$();if(element!==this){$.data(element,this.widgetFullName,this);this._on(true,this.element,{remove:function(event){if(event.target===element){this.destroy();}}});this.document=$(element.style?element.ownerDocument:element.document||element);this.window=$(this.document[0].defaultView||this.document[0].parentWindow);}
    this._create();this._trigger("create",null,this._getCreateEventData());this._init();},_getCreateOptions:$.noop,_getCreateEventData:$.noop,_create:$.noop,_init:$.noop,destroy:function(){this._destroy();this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData($.camelCase(this.widgetFullName));this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled");this.bindings.unbind(this.eventNamespace);this.hoverable.removeClass("ui-state-hover");this.focusable.removeClass("ui-state-focus");},_destroy:$.noop,widget:function(){return this.element;},option:function(key,value){var options=key,parts,curOption,i;if(arguments.length===0){return $.widget.extend({},this.options);}
    if(typeof key==="string"){options={};parts=key.split(".");key=parts.shift();if(parts.length){curOption=options[key]=$.widget.extend({},this.options[key]);for(i=0;i<parts.length-1;i++){curOption[parts[i]]=curOption[parts[i]]||{};curOption=curOption[parts[i]];}
        key=parts.pop();if(value===undefined){return curOption[key]===undefined?null:curOption[key];}
        curOption[key]=value;}else{if(value===undefined){return this.options[key]===undefined?null:this.options[key];}
        options[key]=value;}}
    this._setOptions(options);return this;},_setOptions:function(options){var key;for(key in options){this._setOption(key,options[key]);}
    return this;},_setOption:function(key,value){this.options[key]=value;if(key==="disabled"){this.widget().toggleClass(this.widgetFullName+"-disabled ui-state-disabled",!!value).attr("aria-disabled",value);this.hoverable.removeClass("ui-state-hover");this.focusable.removeClass("ui-state-focus");}
    return this;},enable:function(){return this._setOption("disabled",false);},disable:function(){return this._setOption("disabled",true);},_on:function(suppressDisabledCheck,element,handlers){var delegateElement,instance=this;if(typeof suppressDisabledCheck!=="boolean"){handlers=element;element=suppressDisabledCheck;suppressDisabledCheck=false;}
    if(!handlers){handlers=element;element=this.element;delegateElement=this.widget();}else{element=delegateElement=$(element);this.bindings=this.bindings.add(element);}
    $.each(handlers,function(event,handler){function handlerProxy(){if(!suppressDisabledCheck&&(instance.options.disabled===true||$(this).hasClass("ui-state-disabled"))){return;}
        return(typeof handler==="string"?instance[handler]:handler).apply(instance,arguments);}
        if(typeof handler!=="string"){handlerProxy.guid=handler.guid=handler.guid||handlerProxy.guid||$.guid++;}
        var match=event.match(/^(\w+)\s*(.*)$/),eventName=match[1]+instance.eventNamespace,selector=match[2];if(selector){delegateElement.delegate(selector,eventName,handlerProxy);}else{element.bind(eventName,handlerProxy);}});},_off:function(element,eventName){eventName=(eventName||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace;element.unbind(eventName).undelegate(eventName);},_delay:function(handler,delay){function handlerProxy(){return(typeof handler==="string"?instance[handler]:handler).apply(instance,arguments);}
    var instance=this;return setTimeout(handlerProxy,delay||0);},_hoverable:function(element){this.hoverable=this.hoverable.add(element);this._on(element,{mouseenter:function(event){$(event.currentTarget).addClass("ui-state-hover");},mouseleave:function(event){$(event.currentTarget).removeClass("ui-state-hover");}});},_focusable:function(element){this.focusable=this.focusable.add(element);this._on(element,{focusin:function(event){$(event.currentTarget).addClass("ui-state-focus");},focusout:function(event){$(event.currentTarget).removeClass("ui-state-focus");}});},_trigger:function(type,event,data){var prop,orig,callback=this.options[type];data=data||{};event=$.Event(event);event.type=(type===this.widgetEventPrefix?type:this.widgetEventPrefix+type).toLowerCase();event.target=this.element[0];orig=event.originalEvent;if(orig){for(prop in orig){if(!(prop in event)){event[prop]=orig[prop];}}}
    this.element.trigger(event,data);return!($.isFunction(callback)&&callback.apply(this.element[0],[event].concat(data))===false||event.isDefaultPrevented());}};$.each({show:"fadeIn",hide:"fadeOut"},function(method,defaultEffect){$.Widget.prototype["_"+method]=function(element,options,callback){if(typeof options==="string"){options={effect:options};}
    var hasOptions,effectName=!options?method:options===true||typeof options==="number"?defaultEffect:options.effect||defaultEffect;options=options||{};if(typeof options==="number"){options={duration:options};}
    hasOptions=!$.isEmptyObject(options);options.complete=callback;if(options.delay){element.delay(options.delay);}
    if(hasOptions&&$.effects&&$.effects.effect[effectName]){element[method](options);}else if(effectName!==method&&element[effectName]){element[effectName](options.duration,options.easing,callback);}else{element.queue(function(next){$(this)[method]();if(callback){callback.call(element[0]);}
        next();});}};});}));

/*
 * jQuery Iframe Transport Plugin 1.6.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

(function(factory){'use strict';if(typeof define==='function'&&define.amd){define(['jquery'],factory);}else{factory(window.jQuery);}}(function($){'use strict';var counter=0;$.ajaxTransport('iframe',function(options){if(options.async){var form,iframe,addParamChar;return{send:function(_,completeCallback){form=$('<form style="display:none;"></form>');form.attr('accept-charset',options.formAcceptCharset);addParamChar=/\?/.test(options.url)?'&':'?';if(options.type==='DELETE'){options.url=options.url+addParamChar+'_method=DELETE';options.type='POST';}else if(options.type==='PUT'){options.url=options.url+addParamChar+'_method=PUT';options.type='POST';}else if(options.type==='PATCH'){options.url=options.url+addParamChar+'_method=PATCH';options.type='POST';}
    iframe=$('<iframe src="javascript:false;" name="iframe-transport-'+
        (counter+=1)+'"></iframe>').bind('load',function(){var fileInputClones,paramNames=$.isArray(options.paramName)?options.paramName:[options.paramName];iframe.unbind('load').bind('load',function(){var response;try{response=iframe.contents();if(!response.length||!response[0].firstChild){throw new Error();}}catch(e){response=undefined;}
            completeCallback(200,'success',{'iframe':response});$('<iframe src="javascript:false;"></iframe>').appendTo(form);form.remove();});form.prop('target',iframe.prop('name')).prop('action',options.url).prop('method',options.type);if(options.formData){$.each(options.formData,function(index,field){$('<input type="hidden"/>').prop('name',field.name).val(field.value).appendTo(form);});}
            if(options.fileInput&&options.fileInput.length&&options.type==='POST'){fileInputClones=options.fileInput.clone();options.fileInput.after(function(index){return fileInputClones[index];});if(options.paramName){options.fileInput.each(function(index){$(this).prop('name',paramNames[index]||options.paramName);});}
                form.append(options.fileInput).prop('enctype','multipart/form-data').prop('encoding','multipart/form-data');}
            form.submit();if(fileInputClones&&fileInputClones.length){options.fileInput.each(function(index,input){var clone=$(fileInputClones[index]);$(input).prop('name',clone.prop('name'));clone.replaceWith(input);});}});form.append(iframe).appendTo(document.body);},abort:function(){if(iframe){iframe.unbind('load').prop('src','javascript'.concat(':false;'));}
    if(form){form.remove();}}};}});$.ajaxSetup({converters:{'iframe text':function(iframe){return iframe&&$(iframe[0].body).text();},'iframe json':function(iframe){return iframe&&$.parseJSON($(iframe[0].body).text());},'iframe html':function(iframe){return iframe&&$(iframe[0].body).html();},'iframe script':function(iframe){return iframe&&$.globalEval($(iframe[0].body).text());}}});}));


/*
 * jQuery File Upload Plugin 5.21.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

(function(factory){'use strict';if(typeof define==='function'&&define.amd){define(['jquery','jquery.ui.widget'],factory);}else{factory(window.jQuery);}}(function($){'use strict';$.support.xhrFileUpload=!!(window.XMLHttpRequestUpload&&window.FileReader);$.support.xhrFormDataFileUpload=!!window.FormData;$.propHooks.elements={get:function(form){if($.nodeName(form,'form')){return $.grep(form.elements,function(elem){return!$.nodeName(elem,'input')||elem.type!=='file';});}
    return null;}};$.widget('blueimp.fileupload',{options:{dropZone:$(document),pasteZone:$(document),fileInput:undefined,replaceFileInput:true,paramName:undefined,singleFileUploads:true,limitMultiFileUploads:undefined,sequentialUploads:false,limitConcurrentUploads:undefined,forceIframeTransport:false,redirect:undefined,redirectParamName:undefined,postMessage:undefined,multipart:true,maxChunkSize:undefined,uploadedBytes:undefined,recalculateProgress:true,progressInterval:100,bitrateInterval:500,formData:function(form){return form.serializeArray();},add:function(e,data){data.submit();},processData:false,contentType:false,cache:false},_refreshOptionsList:['fileInput','dropZone','pasteZone','multipart','forceIframeTransport'],_BitrateTimer:function(){this.timestamp=+(new Date());this.loaded=0;this.bitrate=0;this.getBitrate=function(now,loaded,interval){var timeDiff=now-this.timestamp;if(!this.bitrate||!interval||timeDiff>interval){this.bitrate=(loaded-this.loaded)*(1000/timeDiff)*8;this.loaded=loaded;this.timestamp=now;}
    return this.bitrate;};},_isXHRUpload:function(options){return!options.forceIframeTransport&&((!options.multipart&&$.support.xhrFileUpload)||$.support.xhrFormDataFileUpload);},_getFormData:function(options){var formData;if(typeof options.formData==='function'){return options.formData(options.form);}
    if($.isArray(options.formData)){return options.formData;}
    if(options.formData){formData=[];$.each(options.formData,function(name,value){formData.push({name:name,value:value});});return formData;}
    return[];},_getTotal:function(files){var total=0;$.each(files,function(index,file){total+=file.size||1;});return total;},_onProgress:function(e,data){if(e.lengthComputable){var now=+(new Date()),total,loaded;if(data._time&&data.progressInterval&&(now-data._time<data.progressInterval)&&e.loaded!==e.total){return;}
    data._time=now;total=data.total||this._getTotal(data.files);loaded=parseInt(e.loaded/e.total*(data.chunkSize||total),10)+(data.uploadedBytes||0);this._loaded+=loaded-(data.loaded||data.uploadedBytes||0);data.lengthComputable=true;data.loaded=loaded;data.total=total;data.bitrate=data._bitrateTimer.getBitrate(now,loaded,data.bitrateInterval);this._trigger('progress',e,data);this._trigger('progressall',e,{lengthComputable:true,loaded:this._loaded,total:this._total,bitrate:this._bitrateTimer.getBitrate(now,this._loaded,data.bitrateInterval)});}},_initProgressListener:function(options){var that=this,xhr=options.xhr?options.xhr():$.ajaxSettings.xhr();if(xhr.upload){$(xhr.upload).bind('progress',function(e){var oe=e.originalEvent;e.lengthComputable=oe.lengthComputable;e.loaded=oe.loaded;e.total=oe.total;that._onProgress(e,options);});options.xhr=function(){return xhr;};}},_initXHRData:function(options){var formData,file=options.files[0],multipart=options.multipart||!$.support.xhrFileUpload,paramName=options.paramName[0];options.headers=options.headers||{};if(options.contentRange){options.headers['Content-Range']=options.contentRange;}
    if(!multipart){options.headers['Content-Disposition']='attachment; filename="'+
        encodeURI(file.name)+'"';options.contentType=file.type;options.data=options.blob||file;}else if($.support.xhrFormDataFileUpload){if(options.postMessage){formData=this._getFormData(options);if(options.blob){formData.push({name:paramName,value:options.blob});}else{$.each(options.files,function(index,file){formData.push({name:options.paramName[index]||paramName,value:file});});}}else{if(options.formData instanceof FormData){formData=options.formData;}else{formData=new FormData();$.each(this._getFormData(options),function(index,field){formData.append(field.name,field.value);});}
        if(options.blob){options.headers['Content-Disposition']='attachment; filename="'+
            encodeURI(file.name)+'"';formData.append(paramName,options.blob,file.name);}else{$.each(options.files,function(index,file){if((window.Blob&&file instanceof Blob)||(window.File&&file instanceof File)){formData.append(options.paramName[index]||paramName,file,file.name);}});}}
        options.data=formData;}
    options.blob=null;},_initIframeSettings:function(options){options.dataType='iframe '+(options.dataType||'');options.formData=this._getFormData(options);if(options.redirect&&$('<a></a>').prop('href',options.url).prop('host')!==location.host){options.formData.push({name:options.redirectParamName||'redirect',value:options.redirect});}},_initDataSettings:function(options){if(this._isXHRUpload(options)){if(!this._chunkedUpload(options,true)){if(!options.data){this._initXHRData(options);}
    this._initProgressListener(options);}
    if(options.postMessage){options.dataType='postmessage '+(options.dataType||'');}}else{this._initIframeSettings(options,'iframe');}},_getParamName:function(options){var fileInput=$(options.fileInput),paramName=options.paramName;if(!paramName){paramName=[];fileInput.each(function(){var input=$(this),name=input.prop('name')||'files[]',i=(input.prop('files')||[1]).length;while(i){paramName.push(name);i-=1;}});if(!paramName.length){paramName=[fileInput.prop('name')||'files[]'];}}else if(!$.isArray(paramName)){paramName=[paramName];}
    return paramName;},_initFormSettings:function(options){if(!options.form||!options.form.length){options.form=$(options.fileInput.prop('form'));if(!options.form.length){options.form=$(this.options.fileInput.prop('form'));}}
    options.paramName=this._getParamName(options);if(!options.url){options.url=options.form.prop('action')||location.href;}
    options.type=(options.type||options.form.prop('method')||'').toUpperCase();if(options.type!=='POST'&&options.type!=='PUT'&&options.type!=='PATCH'){options.type='POST';}
    if(!options.formAcceptCharset){options.formAcceptCharset=options.form.attr('accept-charset');}},_getAJAXSettings:function(data){var options=$.extend({},this.options,data);this._initFormSettings(options);this._initDataSettings(options);return options;},_enhancePromise:function(promise){promise.success=promise.done;promise.error=promise.fail;promise.complete=promise.always;return promise;},_getXHRPromise:function(resolveOrReject,context,args){var dfd=$.Deferred(),promise=dfd.promise();context=context||this.options.context||promise;if(resolveOrReject===true){dfd.resolveWith(context,args);}else if(resolveOrReject===false){dfd.rejectWith(context,args);}
    promise.abort=dfd.promise;return this._enhancePromise(promise);},_getUploadedBytes:function(jqXHR){var range=jqXHR.getResponseHeader('Range'),parts=range&&range.split('-'),upperBytesPos=parts&&parts.length>1&&parseInt(parts[1],10);return upperBytesPos&&upperBytesPos+1;},_chunkedUpload:function(options,testOnly){var that=this,file=options.files[0],fs=file.size,ub=options.uploadedBytes=options.uploadedBytes||0,mcs=options.maxChunkSize||fs,slice=file.slice||file.webkitSlice||file.mozSlice,dfd=$.Deferred(),promise=dfd.promise(),jqXHR,upload;if(!(this._isXHRUpload(options)&&slice&&(ub||mcs<fs))||options.data){return false;}
    if(testOnly){return true;}
    if(ub>=fs){file.error='Uploaded bytes exceed file size';return this._getXHRPromise(false,options.context,[null,'error',file.error]);}
    upload=function(){var o=$.extend({},options);o.blob=slice.call(file,ub,ub+mcs,file.type);o.chunkSize=o.blob.size;o.contentRange='bytes '+ub+'-'+
        (ub+o.chunkSize-1)+'/'+fs;that._initXHRData(o);that._initProgressListener(o);jqXHR=((that._trigger('chunksend',null,o)!==false&&$.ajax(o))||that._getXHRPromise(false,o.context)).done(function(result,textStatus,jqXHR){ub=that._getUploadedBytes(jqXHR)||(ub+o.chunkSize);if(!o.loaded||o.loaded<o.total){that._onProgress($.Event('progress',{lengthComputable:true,loaded:ub-o.uploadedBytes,total:ub-o.uploadedBytes}),o);}
        options.uploadedBytes=o.uploadedBytes=ub;o.result=result;o.textStatus=textStatus;o.jqXHR=jqXHR;that._trigger('chunkdone',null,o);that._trigger('chunkalways',null,o);if(ub<fs){upload();}else{dfd.resolveWith(o.context,[result,textStatus,jqXHR]);}}).fail(function(jqXHR,textStatus,errorThrown){o.jqXHR=jqXHR;o.textStatus=textStatus;o.errorThrown=errorThrown;that._trigger('chunkfail',null,o);that._trigger('chunkalways',null,o);dfd.rejectWith(o.context,[jqXHR,textStatus,errorThrown]);});};this._enhancePromise(promise);promise.abort=function(){return jqXHR.abort();};upload();return promise;},_beforeSend:function(e,data){if(this._active===0){this._trigger('start');this._bitrateTimer=new this._BitrateTimer();}
    this._active+=1;this._loaded+=data.uploadedBytes||0;this._total+=this._getTotal(data.files);},_onDone:function(result,textStatus,jqXHR,options){if(!this._isXHRUpload(options)||!options.loaded||options.loaded<options.total){var total=this._getTotal(options.files)||1;this._onProgress($.Event('progress',{lengthComputable:true,loaded:total,total:total}),options);}
    options.result=result;options.textStatus=textStatus;options.jqXHR=jqXHR;this._trigger('done',null,options);},_onFail:function(jqXHR,textStatus,errorThrown,options){options.jqXHR=jqXHR;options.textStatus=textStatus;options.errorThrown=errorThrown;this._trigger('fail',null,options);if(options.recalculateProgress){this._loaded-=options.loaded||options.uploadedBytes||0;this._total-=options.total||this._getTotal(options.files);}},_onAlways:function(jqXHRorResult,textStatus,jqXHRorError,options){this._active-=1;this._trigger('always',null,options);if(this._active===0){this._trigger('stop');this._loaded=this._total=0;this._bitrateTimer=null;}},_onSend:function(e,data){var that=this,jqXHR,aborted,slot,pipe,options=that._getAJAXSettings(data),send=function(){that._sending+=1;options._bitrateTimer=new that._BitrateTimer();jqXHR=jqXHR||(((aborted||that._trigger('send',e,options)===false)&&that._getXHRPromise(false,options.context,aborted))||that._chunkedUpload(options)||$.ajax(options)).done(function(result,textStatus,jqXHR){that._onDone(result,textStatus,jqXHR,options);}).fail(function(jqXHR,textStatus,errorThrown){that._onFail(jqXHR,textStatus,errorThrown,options);}).always(function(jqXHRorResult,textStatus,jqXHRorError){that._sending-=1;that._onAlways(jqXHRorResult,textStatus,jqXHRorError,options);if(options.limitConcurrentUploads&&options.limitConcurrentUploads>that._sending){var nextSlot=that._slots.shift(),isPending;while(nextSlot){isPending=nextSlot.state?nextSlot.state()==='pending':!nextSlot.isRejected();if(isPending){nextSlot.resolve();break;}
    nextSlot=that._slots.shift();}}});return jqXHR;};this._beforeSend(e,options);if(this.options.sequentialUploads||(this.options.limitConcurrentUploads&&this.options.limitConcurrentUploads<=this._sending)){if(this.options.limitConcurrentUploads>1){slot=$.Deferred();this._slots.push(slot);pipe=slot.pipe(send);}else{pipe=(this._sequence=this._sequence.pipe(send,send));}
    pipe.abort=function(){aborted=[undefined,'abort','abort'];if(!jqXHR){if(slot){slot.rejectWith(options.context,aborted);}
        return send();}
        return jqXHR.abort();};return this._enhancePromise(pipe);}
    return send();},_onAdd:function(e,data){var that=this,result=true,options=$.extend({},this.options,data),limit=options.limitMultiFileUploads,paramName=this._getParamName(options),paramNameSet,paramNameSlice,fileSet,i;if(!(options.singleFileUploads||limit)||!this._isXHRUpload(options)){fileSet=[data.files];paramNameSet=[paramName];}else if(!options.singleFileUploads&&limit){fileSet=[];paramNameSet=[];for(i=0;i<data.files.length;i+=limit){fileSet.push(data.files.slice(i,i+limit));paramNameSlice=paramName.slice(i,i+limit);if(!paramNameSlice.length){paramNameSlice=paramName;}
    paramNameSet.push(paramNameSlice);}}else{paramNameSet=paramName;}
    data.originalFiles=data.files;$.each(fileSet||data.files,function(index,element){var newData=$.extend({},data);newData.files=fileSet?element:[element];newData.paramName=paramNameSet[index];newData.submit=function(){newData.jqXHR=this.jqXHR=(that._trigger('submit',e,this)!==false)&&that._onSend(e,this);return this.jqXHR;};result=that._trigger('add',e,newData);return result;});return result;},_replaceFileInput:function(input){var inputClone=input.clone(true);$('<form></form>').append(inputClone)[0].reset();input.after(inputClone).detach();$.cleanData(input.unbind('remove'));this.options.fileInput=this.options.fileInput.map(function(i,el){if(el===input[0]){return inputClone[0];}
    return el;});if(input[0]===this.element[0]){this.element=inputClone;}},_handleFileTreeEntry:function(entry,path){var that=this,dfd=$.Deferred(),errorHandler=function(e){if(e&&!e.entry){e.entry=entry;}
    dfd.resolve([e]);},dirReader;path=path||'';if(entry.isFile){if(entry._file){entry._file.relativePath=path;dfd.resolve(entry._file);}else{entry.file(function(file){file.relativePath=path;dfd.resolve(file);},errorHandler);}}else if(entry.isDirectory){dirReader=entry.createReader();dirReader.readEntries(function(entries){that._handleFileTreeEntries(entries,path+entry.name+'/').done(function(files){dfd.resolve(files);}).fail(errorHandler);},errorHandler);}else{dfd.resolve([]);}
    return dfd.promise();},_handleFileTreeEntries:function(entries,path){var that=this;return $.when.apply($,$.map(entries,function(entry){return that._handleFileTreeEntry(entry,path);})).pipe(function(){return Array.prototype.concat.apply([],arguments);});},_getDroppedFiles:function(dataTransfer){dataTransfer=dataTransfer||{};var items=dataTransfer.items;if(items&&items.length&&(items[0].webkitGetAsEntry||items[0].getAsEntry)){return this._handleFileTreeEntries($.map(items,function(item){var entry;if(item.webkitGetAsEntry){entry=item.webkitGetAsEntry();if(entry){entry._file=item.getAsFile();}
    return entry;}
    return item.getAsEntry();}));}
    return $.Deferred().resolve($.makeArray(dataTransfer.files)).promise();},_getSingleFileInputFiles:function(fileInput){fileInput=$(fileInput);var entries=fileInput.prop('webkitEntries')||fileInput.prop('entries'),files,value;if(entries&&entries.length){return this._handleFileTreeEntries(entries);}
    files=$.makeArray(fileInput.prop('files'));if(!files.length){value=fileInput.prop('value');if(!value){return $.Deferred().resolve([]).promise();}
        files=[{name:value.replace(/^.*\\/,'')}];}else if(files[0].name===undefined&&files[0].fileName){$.each(files,function(index,file){file.name=file.fileName;file.size=file.fileSize;});}
    return $.Deferred().resolve(files).promise();},_getFileInputFiles:function(fileInput){if(!(fileInput instanceof $)||fileInput.length===1){return this._getSingleFileInputFiles(fileInput);}
    return $.when.apply($,$.map(fileInput,this._getSingleFileInputFiles)).pipe(function(){return Array.prototype.concat.apply([],arguments);});},_onChange:function(e){var that=this,data={fileInput:$(e.target),form:$(e.target.form)};this._getFileInputFiles(data.fileInput).always(function(files){data.files=files;if(that.options.replaceFileInput){that._replaceFileInput(data.fileInput);}
    if(that._trigger('change',e,data)!==false){that._onAdd(e,data);}});},_onPaste:function(e){var cbd=e.originalEvent.clipboardData,items=(cbd&&cbd.items)||[],data={files:[]};$.each(items,function(index,item){var file=item.getAsFile&&item.getAsFile();if(file){data.files.push(file);}});if(this._trigger('paste',e,data)===false||this._onAdd(e,data)===false){return false;}},_onDrop:function(e){var that=this,dataTransfer=e.dataTransfer=e.originalEvent.dataTransfer,data={};if(dataTransfer&&dataTransfer.files&&dataTransfer.files.length){e.preventDefault();}
    this._getDroppedFiles(dataTransfer).always(function(files){data.files=files;if(that._trigger('drop',e,data)!==false){that._onAdd(e,data);}});},_onDragOver:function(e){var dataTransfer=e.dataTransfer=e.originalEvent.dataTransfer;if(this._trigger('dragover',e)===false){return false;}
    if(dataTransfer&&$.inArray('Files',dataTransfer.types)!==-1){dataTransfer.dropEffect='copy';e.preventDefault();}},_initEventHandlers:function(){if(this._isXHRUpload(this.options)){this._on(this.options.dropZone,{dragover:this._onDragOver,drop:this._onDrop});this._on(this.options.pasteZone,{paste:this._onPaste});}
    this._on(this.options.fileInput,{change:this._onChange});},_destroyEventHandlers:function(){this._off(this.options.dropZone,'dragover drop');this._off(this.options.pasteZone,'paste');this._off(this.options.fileInput,'change');},_setOption:function(key,value){var refresh=$.inArray(key,this._refreshOptionsList)!==-1;if(refresh){this._destroyEventHandlers();}
    this._super(key,value);if(refresh){this._initSpecialOptions();this._initEventHandlers();}},_initSpecialOptions:function(){var options=this.options;if(options.fileInput===undefined){options.fileInput=this.element.is('input[type="file"]')?this.element:this.element.find('input[type="file"]');}else if(!(options.fileInput instanceof $)){options.fileInput=$(options.fileInput);}
    if(!(options.dropZone instanceof $)){options.dropZone=$(options.dropZone);}
    if(!(options.pasteZone instanceof $)){options.pasteZone=$(options.pasteZone);}},_create:function(){var options=this.options;$.extend(options,$(this.element[0].cloneNode(false)).data());this._initSpecialOptions();this._slots=[];this._sequence=this._getXHRPromise(true);this._sending=this._active=this._loaded=this._total=0;this._initEventHandlers();},_destroy:function(){this._destroyEventHandlers();},add:function(data){var that=this;if(!data||this.options.disabled){return;}
    if(data.fileInput&&!data.files){this._getFileInputFiles(data.fileInput).always(function(files){data.files=files;that._onAdd(null,data);});}else{data.files=$.makeArray(data.files);this._onAdd(null,data);}},send:function(data){if(data&&!this.options.disabled){if(data.fileInput&&!data.files){var that=this,dfd=$.Deferred(),promise=dfd.promise(),jqXHR,aborted;promise.abort=function(){aborted=true;if(jqXHR){return jqXHR.abort();}
    dfd.reject(null,'abort','abort');return promise;};this._getFileInputFiles(data.fileInput).always(function(files){if(aborted){return;}
    data.files=files;jqXHR=that._onSend(null,data).then(function(result,textStatus,jqXHR){dfd.resolve(result,textStatus,jqXHR);},function(jqXHR,textStatus,errorThrown){dfd.reject(jqXHR,textStatus,errorThrown);});});return this._enhancePromise(promise);}
    data.files=$.makeArray(data.files);if(data.files.length){return this._onSend(null,data);}}
    return this._getXHRPromise(false,data&&data.context);}});}));