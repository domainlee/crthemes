if(typeof Object.create!=='function'){Object.create=function(obj){function F(){}
F.prototype=obj;return new F();};}
(function($,window,document,undefined){var EZP={init:function(options,elem){var self=this;var $galleries;self.elem=elem;self.$elem=$(elem);self.options=$.extend({},$.fn.ezPlus.options,self.responsiveConfig(options||{}));self.imageSrc=self.$elem.data(self.options.attrImageZoomSrc)?self.$elem.data(self.options.attrImageZoomSrc):self.$elem.attr('src');if(!self.options.enabled){return;}
if(self.options.tint){self.options.lensColour='none';self.options.lensOpacity='1';}
if(self.options.zoomType==='inner'){self.options.showLens=false;}
if(self.options.zoomId===-1){self.options.zoomId=generateUUID();}
self.$elem.parent().removeAttr('title').removeAttr('alt');self.zoomImage=self.imageSrc;self.refresh(1);$galleries=$(self.options.gallery?('#'+self.options.gallery):self.options.gallerySelector);$galleries.on('click.zoom',self.options.galleryItem,function(e){if(self.options.galleryActiveClass){$(self.options.galleryItem,$galleries).removeClass(self.options.galleryActiveClass);$(this).addClass(self.options.galleryActiveClass);}
if(this.tagName==='A'){e.preventDefault();}
if($(this).data(self.options.attrImageZoomSrc)){self.zoomImagePre=$(this).data(self.options.attrImageZoomSrc);}
else{self.zoomImagePre=$(this).data('image');}
self.swaptheimage($(this).data('image'),self.zoomImagePre);if(this.tagName==='A'){return false;}});function generateUUID(){var d=new Date().getTime();var uuid='xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,function(c){var r=(d+Math.random()*16)%16|0;d=Math.floor(d/16);return(c=='x'?r:(r&0x3|0x8)).toString(16);});return uuid;}},refresh:function(length){var self=this;setTimeout(function(){self.fetch(self.imageSrc);},length||self.options.refresh);},fetch:function(imgsrc){var self=this;var newImg=new Image();newImg.onload=function(){self.largeWidth=newImg.width;self.largeHeight=newImg.height;self.startZoom();self.currentImage=self.imageSrc;self.options.onZoomedImageLoaded(self.$elem);};self.setImageSource(newImg,imgsrc);return;},setImageSource:function(image,src){image.src=src;},startZoom:function(){var self=this;self.nzWidth=self.$elem.width();self.nzHeight=self.$elem.height();self.isWindowActive=false;self.isLensActive=false;self.isTintActive=false;self.overWindow=false;if(self.options.imageCrossfade){self.zoomWrap=self.$elem.wrap('<div style="height:'+self.nzHeight+'px;width:'+self.nzWidth+'px;" class="zoomWrapper" />');self.$elem.css('position','absolute');}
self.zoomLock=1;self.scrollingLock=false;self.changeBgSize=false;self.currentZoomLevel=self.options.zoomLevel;self.nzOffset=self.$elem.offset();self.widthRatio=(self.largeWidth/self.currentZoomLevel)/self.nzWidth;self.heightRatio=(self.largeHeight/self.currentZoomLevel)/self.nzHeight;function getWindowZoomStyle(){return'overflow: hidden;'+'background-position: 0px 0px;text-align:center;'+'background-color: '+String(self.options.zoomWindowBgColour)+';'+'width: '+String(self.options.zoomWindowWidth)+'px;'+'height: '+String(self.options.zoomWindowHeight)+'px;'+'float: left;'+'background-size: '+self.largeWidth/self.currentZoomLevel+'px '+self.largeHeight/self.currentZoomLevel+'px;'+'display: none;z-index:100;'+'border: '+String(self.options.borderSize)+'px solid '+self.options.borderColour+';'+'background-repeat: no-repeat;'+'position: absolute;';}
if(self.options.zoomType==='window'){self.zoomWindowStyle=getWindowZoomStyle();}
function getInnerZoomStyle(){var borderWidth=self.$elem.css('border-left-width');return'overflow: hidden;'+'margin-left: '+String(borderWidth)+';'+'margin-top: '+String(borderWidth)+';'+'background-position: 0px 0px;'+'width: '+String(self.nzWidth)+'px;'+'height: '+String(self.nzHeight)+'px;'+'float: left;'+'display: none;'+'cursor:'+(self.options.cursor)+';'+'border: '+String(self.options.borderSize)+'px solid '+self.options.borderColour+';'+'background-repeat: no-repeat;'+'position: absolute;';}
if(self.options.zoomType==='inner'){self.zoomWindowStyle=getInnerZoomStyle();}
function getWindowLensStyle(){if(self.nzHeight<self.options.zoomWindowHeight/self.heightRatio){self.lensHeight=self.nzHeight;}
else{self.lensHeight=String(self.options.zoomWindowHeight/self.heightRatio);}
if(self.largeWidth<self.options.zoomWindowWidth){self.lensWidth=self.nzWidth;}
else{self.lensWidth=String(self.options.zoomWindowWidth/self.widthRatio);}
return'background-position: 0px 0px;width: '+String((self.options.zoomWindowWidth)/self.widthRatio)+'px;'+'height: '+String((self.options.zoomWindowHeight)/self.heightRatio)+'px;float: right;display: none;'+'overflow: hidden;'+'z-index: 999;'+'opacity:'+(self.options.lensOpacity)+';filter: alpha(opacity = '+(self.options.lensOpacity*100)+'); zoom:1;'+'width:'+self.lensWidth+'px;'+'height:'+self.lensHeight+'px;'+'background-color:'+(self.options.lensColour)+';'+'cursor:'+(self.options.cursor)+';'+'border: '+(self.options.lensBorderSize)+'px'+' solid '+(self.options.lensBorderColour)+';background-repeat: no-repeat;position: absolute;';}
if(self.options.zoomType==='window'){self.lensStyle=getWindowLensStyle();}
self.tintStyle='display: block;'+'position: absolute;'+'background-color: '+self.options.tintColour+';'+'filter:alpha(opacity=0);'+'opacity: 0;'+'width: '+self.nzWidth+'px;'+'height: '+self.nzHeight+'px;';self.lensRound='';if(self.options.zoomType==='lens'){self.lensStyle='background-position: 0px 0px;'+'float: left;display: none;'+'border: '+String(self.options.borderSize)+'px solid '+self.options.borderColour+';'+'width:'+String(self.options.lensSize)+'px;'+'height:'+String(self.options.lensSize)+'px;'+'background-repeat: no-repeat;position: absolute;';}
if(self.options.lensShape==='round'){self.lensRound='border-top-left-radius: '+String(self.options.lensSize/2+self.options.borderSize)+'px;'+'border-top-right-radius: '+String(self.options.lensSize/2+self.options.borderSize)+'px;'+'border-bottom-left-radius: '+String(self.options.lensSize/2+self.options.borderSize)+'px;'+'border-bottom-right-radius: '+String(self.options.lensSize/2+self.options.borderSize)+'px;';}
self.zoomContainer=$('<div class="zoomContainer" '+'uuid="'+self.options.zoomId+'"'+'style="'+'position:absolute;'+'left:'+self.nzOffset.left+'px;'+'top:'+self.nzOffset.top+'px;'+'height:'+self.nzHeight+'px;'+''+'width:'+self.nzWidth+'px;'+'z-index:'+self.options.zIndex+'"></div>');if(self.$elem.attr("id")){self.zoomContainer.attr("id",self.$elem.attr("id")+"-zoomContainer");}
$(self.options.zoomContainerAppendTo).append(self.zoomContainer);if(self.options.containLensZoom&&self.options.zoomType==='lens'){self.zoomContainer.css('overflow','hidden');}
if(self.options.zoomType!=='inner'){self.zoomLens=$('<div class="zoomLens" style="'+self.lensStyle+self.lensRound+'">&nbsp;</div>').appendTo(self.zoomContainer).click(function(){self.$elem.trigger('click');});if(self.options.tint){self.tintContainer=$('<div/>').addClass('tintContainer');self.zoomTint=$('<div class="zoomTint" style="'+self.tintStyle+'"></div>');self.zoomLens.wrap(self.tintContainer);self.zoomTintcss=self.zoomLens.after(self.zoomTint);self.zoomTintImage=$('<img style="'+'position: absolute; left: 0px; top: 0px; max-width: none; '+'width: '+self.nzWidth+'px; '+'height: '+self.nzHeight+'px;" '+'src="'+self.imageSrc+'">').appendTo(self.zoomLens).click(function(){self.$elem.trigger('click');});}}
var targetZoomContainer=isNaN(self.options.zoomWindowPosition)?'body':self.zoomContainer;self.zoomWindow=$('<div style="z-index:999;'+'left:'+(self.windowOffsetLeft)+'px;'+'top:'+(self.windowOffsetTop)+'px;'+self.zoomWindowStyle+'" class="zoomWindow">&nbsp;</div>').appendTo(targetZoomContainer).click(function(){self.$elem.trigger('click');});self.zoomWindowContainer=$('<div/>').addClass('zoomWindowContainer').css('width',self.options.zoomWindowWidth);self.zoomWindow.wrap(self.zoomWindowContainer);if(self.options.zoomType==='lens'){self.zoomLens.css('background-image','url("'+self.imageSrc+'")');}
if(self.options.zoomType==='window'){self.zoomWindow.css('background-image','url("'+self.imageSrc+'")');}
if(self.options.zoomType==='inner'){self.zoomWindow.css('background-image','url("'+self.imageSrc+'")');}
if(self.options.touchEnabled){self.$elem.bind('touchmove.ezpspace',function(e){e.preventDefault();var touch=e.originalEvent.touches[0]||e.originalEvent.changedTouches[0];self.setPosition(touch);});self.zoomContainer.bind('touchmove.ezpspace',function(e){if(self.options.zoomType==='inner'){self.showHideWindow('show');}
e.preventDefault();var touch=e.originalEvent.touches[0]||e.originalEvent.changedTouches[0];self.setPosition(touch);});self.zoomContainer.bind('touchend.ezpspace',function(e){self.showHideWindow('hide');if(self.options.showLens){self.showHideLens('hide');}
if(self.options.tint&&self.options.zoomType!=='inner'){self.showHideTint('hide');}});self.$elem.bind('touchend.ezpspace',function(e){self.showHideWindow('hide');if(self.options.showLens){self.showHideLens('hide');}
if(self.options.tint&&self.options.zoomType!=='inner'){self.showHideTint('hide');}});if(self.options.showLens){self.zoomLens.bind('touchmove.ezpspace',function(e){e.preventDefault();var touch=e.originalEvent.touches[0]||e.originalEvent.changedTouches[0];self.setPosition(touch);});self.zoomLens.bind('touchend.ezpspace',function(e){self.showHideWindow('hide');if(self.options.showLens){self.showHideLens('hide');}
if(self.options.tint&&self.options.zoomType!=='inner'){self.showHideTint('hide');}});}}
self.$elem.bind('mousemove.ezpspace',function(e){if(self.overWindow===false){self.setElements('show');}
if(self.lastX!==e.clientX||self.lastY!==e.clientY){self.setPosition(e);self.currentLoc=e;}
self.lastX=e.clientX;self.lastY=e.clientY;});self.zoomContainer.bind('click.ezpspace',self.options.onImageClick);self.zoomContainer.bind('mousemove.ezpspace',function(e){if(self.overWindow===false){self.setElements('show');}
mouseMoveZoomHandler(e);});function mouseMoveZoomHandler(e){if(self.lastX!==e.clientX||self.lastY!==e.clientY){self.setPosition(e);self.currentLoc=e;}
self.lastX=e.clientX;self.lastY=e.clientY;}
var elementToTrack=null;if(self.options.zoomType!=='inner'){elementToTrack=self.zoomLens;}
if(self.options.tint&&self.options.zoomType!=='inner'){elementToTrack=self.zoomTint;}
if(self.options.zoomType==='inner'){elementToTrack=self.zoomWindow;}
if(elementToTrack){elementToTrack.bind('mousemove.ezpspace',mouseMoveZoomHandler);}
self.zoomContainer.add(self.$elem).mouseenter(function(){if(self.overWindow===false){self.setElements('show');}}).mouseleave(function(){if(!self.scrollLock){self.setElements('hide');self.options.onDestroy(self.$elem);}});if(self.options.zoomType!=='inner'){self.zoomWindow.mouseenter(function(){self.overWindow=true;self.setElements('hide');}).mouseleave(function(){self.overWindow=false;});}
if(self.options.minZoomLevel){self.minZoomLevel=self.options.minZoomLevel;}
else{self.minZoomLevel=self.options.scrollZoomIncrement*2;}
if(self.options.scrollZoom){self.zoomContainer.add(self.$elem).bind('wheel DOMMouseScroll MozMousePixelScroll',function(e){self.scrollLock=true;clearTimeout($.data(this,'timer'));$.data(this,'timer',setTimeout(function(){self.scrollLock=false;},250));var theEvent=e.originalEvent.deltaY||e.originalEvent.detail*-1;e.stopImmediatePropagation();e.stopPropagation();e.preventDefault();if(theEvent/120>0){if(self.currentZoomLevel>=self.minZoomLevel){self.changeZoomLevel(self.currentZoomLevel-self.options.scrollZoomIncrement);}}
else{if((!self.fullheight&&!self.fullwidth)||!self.options.mantainZoomAspectRatio){if(self.options.maxZoomLevel){if(self.currentZoomLevel<=self.options.maxZoomLevel){self.changeZoomLevel(parseFloat(self.currentZoomLevel)+self.options.scrollZoomIncrement);}}
else{self.changeZoomLevel(parseFloat(self.currentZoomLevel)+self.options.scrollZoomIncrement);}}}
return false;});}},destroy:function(){var self=this;self.$elem.unbind('ezpspace');$(self.zoomContainer).remove();if(self.options.loadingIcon&&!!self.spinner&&!!self.spinner.length){self.spinner.remove();delete self.spinner;}},getIdentifier:function(){var self=this;return self.options.zoomId;},setElements:function(type){var self=this;if(!self.options.zoomEnabled){return false;}
if(type==='show'){if(self.isWindowSet){if(self.options.zoomType==='inner'){self.showHideWindow('show');}
if(self.options.zoomType==='window'){self.showHideWindow('show');}
if(self.options.showLens){self.showHideLens('show');}
if(self.options.tint&&self.options.zoomType!=='inner'){self.showHideTint('show');}}}
if(type==='hide'){if(self.options.zoomType==='window'){self.showHideWindow('hide');}
if(!self.options.tint){self.showHideWindow('hide');}
if(self.options.showLens){self.showHideLens('hide');}
if(self.options.tint){self.showHideTint('hide');}}},setPosition:function(e){var self=this;if(!self.options.zoomEnabled){return false;}
self.nzHeight=self.$elem.height();self.nzWidth=self.$elem.width();self.nzOffset=self.$elem.offset();if(self.options.tint&&self.options.zoomType!=='inner'){self.zoomTint.css({top:0,left:0});}
if(self.options.responsive&&!self.options.scrollZoom){if(self.options.showLens){var lensHeight,lensWidth;if(self.nzHeight<self.options.zoomWindowWidth/self.widthRatio){self.lensHeight=self.nzHeight;}
else{self.lensHeight=String((self.options.zoomWindowHeight/self.heightRatio));}
if(self.largeWidth<self.options.zoomWindowWidth){self.lensWidth=self.nzWidth;}
else{self.lensWidth=(self.options.zoomWindowWidth/self.widthRatio);}
self.widthRatio=self.largeWidth/self.nzWidth;self.heightRatio=self.largeHeight/self.nzHeight;if(self.options.zoomType!=='lens'){if(self.nzHeight<self.options.zoomWindowWidth/self.widthRatio){self.lensHeight=self.nzHeight;}
else{self.lensHeight=String((self.options.zoomWindowHeight/self.heightRatio));}
if(self.nzWidth<self.options.zoomWindowHeight/self.heightRatio){self.lensWidth=self.nzWidth;}
else{self.lensWidth=String((self.options.zoomWindowWidth/self.widthRatio));}
self.zoomLens.css({'width':self.lensWidth,'height':self.lensHeight});if(self.options.tint){self.zoomTintImage.css({'width':self.nzWidth,'height':self.nzHeight});}}
if(self.options.zoomType==='lens'){self.zoomLens.css({width:String(self.options.lensSize)+'px',height:String(self.options.lensSize)+'px'});}}}
self.zoomContainer.css({top:self.nzOffset.top,left:self.nzOffset.left,width:self.nzWidth,height:self.nzHeight});self.mouseLeft=parseInt(e.pageX-self.nzOffset.left);self.mouseTop=parseInt(e.pageY-self.nzOffset.top);if(self.options.zoomType==='window'){var zoomLensHeight=self.zoomLens.height()/2;var zoomLensWidth=self.zoomLens.width()/2;self.Etoppos=(self.mouseTop<0+zoomLensHeight);self.Eboppos=(self.mouseTop>self.nzHeight-zoomLensHeight-(self.options.lensBorderSize*2));self.Eloppos=(self.mouseLeft<0+zoomLensWidth);self.Eroppos=(self.mouseLeft>(self.nzWidth-zoomLensWidth-(self.options.lensBorderSize*2)));}
if(self.options.zoomType==='inner'){self.Etoppos=(self.mouseTop<((self.nzHeight/2)/self.heightRatio));self.Eboppos=(self.mouseTop>(self.nzHeight-((self.nzHeight/2)/self.heightRatio)));self.Eloppos=(self.mouseLeft<0+(((self.nzWidth/2)/self.widthRatio)));self.Eroppos=(self.mouseLeft>(self.nzWidth-(self.nzWidth/2)/self.widthRatio-(self.options.lensBorderSize*2)));}
if(self.mouseLeft<0||self.mouseTop<0||self.mouseLeft>self.nzWidth||self.mouseTop>self.nzHeight){self.setElements('hide');return;}
else{if(self.options.showLens){self.lensLeftPos=String(Math.floor(self.mouseLeft-self.zoomLens.width()/2));self.lensTopPos=String(Math.floor(self.mouseTop-self.zoomLens.height()/2));}
if(self.Etoppos){self.lensTopPos=0;}
if(self.Eloppos){self.windowLeftPos=0;self.lensLeftPos=0;self.tintpos=0;}
if(self.options.zoomType==='window'){if(self.Eboppos){self.lensTopPos=Math.max((self.nzHeight)-self.zoomLens.height()-(self.options.lensBorderSize*2),0);}
if(self.Eroppos){self.lensLeftPos=(self.nzWidth-(self.zoomLens.width())-(self.options.lensBorderSize*2));}}
if(self.options.zoomType==='inner'){if(self.Eboppos){self.lensTopPos=Math.max(((self.nzHeight)-(self.options.lensBorderSize*2)),0);}
if(self.Eroppos){self.lensLeftPos=(self.nzWidth-(self.nzWidth)-(self.options.lensBorderSize*2));}}
if(self.options.zoomType==='lens'){self.windowLeftPos=String(((e.pageX-self.nzOffset.left)*self.widthRatio-self.zoomLens.width()/2)*(-1));self.windowTopPos=String(((e.pageY-self.nzOffset.top)*self.heightRatio-self.zoomLens.height()/2)*(-1));self.zoomLens.css('background-position',self.windowLeftPos+'px '+self.windowTopPos+'px');if(self.changeBgSize){if(self.nzHeight>self.nzWidth){if(self.options.zoomType==='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
self.zoomWindow.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
else{if(self.options.zoomType==='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvaluewidth+'px');}
self.zoomWindow.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvaluewidth+'px');}
self.changeBgSize=false;}
self.setWindowPosition(e);}
if(self.options.tint&&self.options.zoomType!=='inner'){self.setTintPosition(e);}
if(self.options.zoomType==='window'){self.setWindowPosition(e);}
if(self.options.zoomType==='inner'){self.setWindowPosition(e);}
if(self.options.showLens){if(self.fullwidth&&self.options.zoomType!=='lens'){self.lensLeftPos=0;}
self.zoomLens.css({left:self.lensLeftPos+'px',top:self.lensTopPos+'px'});}}},showHideZoomContainer:function(change){var self=this;if(change==='show'){if(self.zoomContainer){self.zoomContainer.show();}}
if(change==='hide'){if(self.zoomContainer){self.zoomContainer.hide();}}},showHideWindow:function(change){var self=this;if(change==='show'){if(!self.isWindowActive&&self.zoomWindow){self.options.onShow(self);if(self.options.zoomWindowFadeIn){self.zoomWindow.stop(true,true,false).fadeIn(self.options.zoomWindowFadeIn);}
else{self.zoomWindow.show();}
self.isWindowActive=true;}}
if(change==='hide'){if(self.isWindowActive){if(self.options.zoomWindowFadeOut){self.zoomWindow.stop(true,true).fadeOut(self.options.zoomWindowFadeOut,function(){if(self.loop){clearInterval(self.loop);self.loop=false;}});}
else{self.zoomWindow.hide();}
self.isWindowActive=false;}}},showHideLens:function(change){var self=this;if(change==='show'){if(!self.isLensActive){if(self.options.lensFadeIn&&self.zoomLens){self.zoomLens.stop(true,true,false).fadeIn(self.options.lensFadeIn);}
else{self.zoomLens.show();}
self.isLensActive=true;}}
if(change==='hide'){if(self.isLensActive){if(self.options.lensFadeOut){self.zoomLens.stop(true,true).fadeOut(self.options.lensFadeOut);}
else{self.zoomLens.hide();}
self.isLensActive=false;}}},showHideTint:function(change){var self=this;if(change==='show'){if(!self.isTintActive&&self.zoomTint){if(self.options.zoomTintFadeIn){self.zoomTint.css('opacity',self.options.tintOpacity).animate().stop(true,true).fadeIn('slow');}
else{self.zoomTint.css('opacity',self.options.tintOpacity).animate();self.zoomTint.show();}
self.isTintActive=true;}}
if(change==='hide'){if(self.isTintActive){if(self.options.zoomTintFadeOut){self.zoomTint.stop(true,true).fadeOut(self.options.zoomTintFadeOut);}
else{self.zoomTint.hide();}
self.isTintActive=false;}}},setLensPosition:function(e){},setWindowPosition:function(e){var self=this;if(!isNaN(self.options.zoomWindowPosition)){switch(self.options.zoomWindowPosition){case 1:self.windowOffsetTop=(self.options.zoomWindowOffsetY);self.windowOffsetLeft=(+self.nzWidth);break;case 2:if(self.options.zoomWindowHeight>self.nzHeight){self.windowOffsetTop=((self.options.zoomWindowHeight/2)-(self.nzHeight/2))*(-1);self.windowOffsetLeft=(self.nzWidth);}
else{$.noop();}
break;case 3:self.windowOffsetTop=(self.nzHeight-self.zoomWindow.height()-(self.options.borderSize*2));self.windowOffsetLeft=(self.nzWidth);break;case 4:self.windowOffsetTop=(self.nzHeight);self.windowOffsetLeft=(self.nzWidth);break;case 5:self.windowOffsetTop=(self.nzHeight);self.windowOffsetLeft=(self.nzWidth-self.zoomWindow.width()-(self.options.borderSize*2));break;case 6:if(self.options.zoomWindowHeight>self.nzHeight){self.windowOffsetTop=(self.nzHeight);self.windowOffsetLeft=((self.options.zoomWindowWidth/2)-(self.nzWidth/2)+(self.options.borderSize*2))*(-1);}
else{$.noop();}
break;case 7:self.windowOffsetTop=(self.nzHeight);self.windowOffsetLeft=0;break;case 8:self.windowOffsetTop=(self.nzHeight);self.windowOffsetLeft=(self.zoomWindow.width()+(self.options.borderSize*2))*(-1);break;case 9:self.windowOffsetTop=(self.nzHeight-self.zoomWindow.height()-(self.options.borderSize*2));self.windowOffsetLeft=(self.zoomWindow.width()+(self.options.borderSize*2))*(-1);break;case 10:if(self.options.zoomWindowHeight>self.nzHeight){self.windowOffsetTop=((self.options.zoomWindowHeight/2)-(self.nzHeight/2))*(-1);self.windowOffsetLeft=(self.zoomWindow.width()+(self.options.borderSize*2))*(-1);}
else{$.noop();}
break;case 11:self.windowOffsetTop=(self.options.zoomWindowOffsetY);self.windowOffsetLeft=(self.zoomWindow.width()+(self.options.borderSize*2))*(-1);break;case 12:self.windowOffsetTop=(self.zoomWindow.height()+(self.options.borderSize*2))*(-1);self.windowOffsetLeft=(self.zoomWindow.width()+(self.options.borderSize*2))*(-1);break;case 13:self.windowOffsetTop=(self.zoomWindow.height()+(self.options.borderSize*2))*(-1);self.windowOffsetLeft=(0);break;case 14:if(self.options.zoomWindowHeight>self.nzHeight){self.windowOffsetTop=(self.zoomWindow.height()+(self.options.borderSize*2))*(-1);self.windowOffsetLeft=((self.options.zoomWindowWidth/2)-(self.nzWidth/2)+(self.options.borderSize*2))*(-1);}
else{$.noop();}
break;case 15:self.windowOffsetTop=(self.zoomWindow.height()+(self.options.borderSize*2))*(-1);self.windowOffsetLeft=(self.nzWidth-self.zoomWindow.width()-(self.options.borderSize*2));break;case 16:self.windowOffsetTop=(self.zoomWindow.height()+(self.options.borderSize*2))*(-1);self.windowOffsetLeft=(self.nzWidth);break;default:self.windowOffsetTop=(self.options.zoomWindowOffsetY);self.windowOffsetLeft=(self.nzWidth);}}
else{self.externalContainer=$(self.options.zoomWindowPosition);if(!self.externalContainer.length){self.externalContainer=$('#'+self.options.zoomWindowPosition);}
self.externalContainerWidth=self.externalContainer.width();self.externalContainerHeight=self.externalContainer.height();self.externalContainerOffset=self.externalContainer.offset();self.windowOffsetTop=self.externalContainerOffset.top;self.windowOffsetLeft=self.externalContainerOffset.left;}
self.isWindowSet=true;self.windowOffsetTop=self.windowOffsetTop+self.options.zoomWindowOffsetY;self.windowOffsetLeft=self.windowOffsetLeft+self.options.zoomWindowOffsetX;self.zoomWindow.css({top:self.windowOffsetTop,left:self.windowOffsetLeft});if(self.options.zoomType==='inner'){self.zoomWindow.css({top:0,left:0});}
self.windowLeftPos=String(((e.pageX-self.nzOffset.left)*self.widthRatio-self.zoomWindow.width()/2)*(-1));self.windowTopPos=String(((e.pageY-self.nzOffset.top)*self.heightRatio-self.zoomWindow.height()/2)*(-1));if(self.Etoppos){self.windowTopPos=0;}
if(self.Eloppos){self.windowLeftPos=0;}
if(self.Eboppos){self.windowTopPos=(self.largeHeight/self.currentZoomLevel-self.zoomWindow.height())*(-1);}
if(self.Eroppos){self.windowLeftPos=((self.largeWidth/self.currentZoomLevel-self.zoomWindow.width())*(-1));}
if(self.fullheight){self.windowTopPos=0;}
if(self.fullwidth){self.windowLeftPos=0;}
if(self.options.zoomType==='window'||self.options.zoomType==='inner'){if(self.zoomLock===1){if(self.widthRatio<=1){self.windowLeftPos=0;}
if(self.heightRatio<=1){self.windowTopPos=0;}}
if(self.options.zoomType==='window'){if(self.largeHeight<self.options.zoomWindowHeight){self.windowTopPos=0;}
if(self.largeWidth<self.options.zoomWindowWidth){self.windowLeftPos=0;}}
if(self.options.easing){if(!self.xp){self.xp=0;}
if(!self.yp){self.yp=0;}
var interval=16;if(Number.isInteger(parseInt(self.options.easing))){interval=parseInt(self.options.easing);}
if(!self.loop){self.loop=setInterval(function(){self.xp+=(self.windowLeftPos-self.xp)/self.options.easingAmount;self.yp+=(self.windowTopPos-self.yp)/self.options.easingAmount;if(self.scrollingLock){clearInterval(self.loop);self.xp=self.windowLeftPos;self.yp=self.windowTopPos;self.xp=((e.pageX-self.nzOffset.left)*self.widthRatio-self.zoomWindow.width()/2)*(-1);self.yp=(((e.pageY-self.nzOffset.top)*self.heightRatio-self.zoomWindow.height()/2)*(-1));if(self.changeBgSize){if(self.nzHeight>self.nzWidth){if(self.options.zoomType==='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
self.zoomWindow.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
else{if(self.options.zoomType!=='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvalueheight+'px');}
self.zoomWindow.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvaluewidth+'px');}
self.changeBgSize=false;}
self.zoomWindow.css('background-position',self.windowLeftPos+'px '+self.windowTopPos+'px');self.scrollingLock=false;self.loop=false;}
else if(Math.round(Math.abs(self.xp-self.windowLeftPos)+Math.abs(self.yp-self.windowTopPos))<1){clearInterval(self.loop);self.zoomWindow.css('background-position',self.windowLeftPos+'px '+self.windowTopPos+'px');self.loop=false;}
else{if(self.changeBgSize){if(self.nzHeight>self.nzWidth){if(self.options.zoomType==='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
self.zoomWindow.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
else{if(self.options.zoomType!=='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvaluewidth+'px');}
self.zoomWindow.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvaluewidth+'px');}
self.changeBgSize=false;}
self.zoomWindow.css('background-position',self.xp+'px '+self.yp+'px');}},interval);}}
else{if(self.changeBgSize){if(self.nzHeight>self.nzWidth){if(self.options.zoomType==='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
self.zoomWindow.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}
else{if(self.options.zoomType==='lens'){self.zoomLens.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvaluewidth+'px');}
if((self.largeHeight/self.newvaluewidth)<self.options.zoomWindowHeight){self.zoomWindow.css('background-size',self.largeWidth/self.newvaluewidth+'px '+
self.largeHeight/self.newvaluewidth+'px');}
else{self.zoomWindow.css('background-size',self.largeWidth/self.newvalueheight+'px '+
self.largeHeight/self.newvalueheight+'px');}}
self.changeBgSize=false;}
self.zoomWindow.css('background-position',self.windowLeftPos+'px '+
self.windowTopPos+'px');}}},setTintPosition:function(e){var self=this;var zoomLensWidth=self.zoomLens.width();var zoomLensHeight=self.zoomLens.height();self.nzOffset=self.$elem.offset();self.tintpos=String(((e.pageX-self.nzOffset.left)-(zoomLensWidth/2))*(-1));self.tintposy=String(((e.pageY-self.nzOffset.top)-zoomLensHeight/2)*(-1));if(self.Etoppos){self.tintposy=0;}
if(self.Eloppos){self.tintpos=0;}
if(self.Eboppos){self.tintposy=(self.nzHeight-zoomLensHeight-(self.options.lensBorderSize*2))*(-1);}
if(self.Eroppos){self.tintpos=((self.nzWidth-zoomLensWidth-(self.options.lensBorderSize*2))*(-1));}
if(self.options.tint){if(self.fullheight){self.tintposy=0;}
if(self.fullwidth){self.tintpos=0;}
self.zoomTintImage.css({'left':self.tintpos+'px','top':self.tintposy+'px'});}},swaptheimage:function(smallimage,largeimage){var self=this;var newImg=new Image();if(self.options.loadingIcon&&!self.spinner){var styleAttr='background: url(\''+self.options.loadingIcon+'\') no-repeat center;'+'height:'+self.nzHeight+'px;'+'width:'+self.nzWidth+'px;'+'z-index: 2000;'+'position: absolute; '+'background-position: center center;';if(self.options.zoomType==='inner'){styleAttr+='top: 0px;';}
self.spinner=$('<div class="ezp-spinner" style="'+styleAttr+'"></div>');self.$elem.after(self.spinner);}else if(self.spinner){self.spinner.show();}
self.options.onImageSwap(self.$elem);newImg.onload=function(){self.largeWidth=newImg.width;self.largeHeight=newImg.height;self.zoomImage=largeimage;self.zoomWindow.css('background-size',self.largeWidth+'px '+self.largeHeight+'px');self.swapAction(smallimage,largeimage);return;};self.setImageSource(newImg,largeimage);},swapAction:function(smallimage,largeimage){var self=this;var elemWidth=self.$elem.width();var elemHeight=self.$elem.height();var newImg2=new Image();newImg2.onload=function(){self.nzHeight=newImg2.height;self.nzWidth=newImg2.width;self.options.onImageSwapComplete(self.$elem);self.doneCallback();return;};self.setImageSource(newImg2,smallimage);self.currentZoomLevel=self.options.zoomLevel;self.options.maxZoomLevel=false;if(self.options.zoomType==='lens'){self.zoomLens.css('background-image','url("'+largeimage+'")');}
if(self.options.zoomType==='window'){self.zoomWindow.css('background-image','url("'+largeimage+'")');}
if(self.options.zoomType==='inner'){self.zoomWindow.css('background-image','url("'+largeimage+'")');}
self.currentImage=largeimage;if(self.options.imageCrossfade){var oldImg=self.$elem;var newImg=oldImg.clone();self.$elem.attr('src',smallimage);self.$elem.after(newImg);newImg.stop(true).fadeOut(self.options.imageCrossfade,function(){$(this).remove();});self.$elem.width('auto').removeAttr('width');self.$elem.height('auto').removeAttr('height');oldImg.fadeIn(self.options.imageCrossfade);if(self.options.tint&&self.options.zoomType!=='inner'){var oldImgTint=self.zoomTintImage;var newImgTint=oldImgTint.clone();self.zoomTintImage.attr('src',largeimage);self.zoomTintImage.after(newImgTint);newImgTint.stop(true).fadeOut(self.options.imageCrossfade,function(){$(this).remove();});oldImgTint.fadeIn(self.options.imageCrossfade);self.zoomTint.css({height:elemHeight,width:elemWidth});}
self.zoomContainer.css({'height':elemHeight,'width':elemWidth});if(self.options.zoomType==='inner'){if(!self.options.constrainType){self.zoomWrap.parent().css({'height':elemHeight,'width':elemWidth});self.zoomWindow.css({'height':elemHeight,'width':elemWidth});}}
if(self.options.imageCrossfade){self.zoomWrap.css({'height':elemHeight,'width':elemWidth});}}
else{self.$elem.attr('src',smallimage);if(self.options.tint){self.zoomTintImage.attr('src',largeimage);self.zoomTintImage.attr('height',elemHeight);self.zoomTintImage.css('height',elemHeight);self.zoomTint.css('height',elemHeight);}
self.zoomContainer.css({'height':elemHeight,'width':elemWidth});if(self.options.imageCrossfade){self.zoomWrap.css({'height':elemHeight,'width':elemWidth});}}
if(self.options.constrainType){if(self.options.constrainType==='height'){var autoWDimension={'height':self.options.constrainSize,'width':'auto'};self.zoomContainer.css(autoWDimension);if(self.options.imageCrossfade){self.zoomWrap.css(autoWDimension);self.constwidth=self.zoomWrap.width();}
else{self.$elem.css(autoWDimension);self.constwidth=elemWidth;}
var constWDim={'height':self.options.constrainSize,'width':self.constwidth};if(self.options.zoomType==='inner'){self.zoomWrap.parent().css(constWDim);self.zoomWindow.css(constWDim);}
if(self.options.tint){self.tintContainer.css(constWDim);self.zoomTint.css(constWDim);self.zoomTintImage.css(constWDim);}}
if(self.options.constrainType==='width'){var autoHDimension={'height':'auto','width':self.options.constrainSize};self.zoomContainer.css(autoHDimension);if(self.options.imageCrossfade){self.zoomWrap.css(autoHDimension);self.constheight=self.zoomWrap.height();}
else{self.$elem.css(autoHDimension);self.constheight=elemHeight;}
var constHDim={'height':self.constheight,'width':self.options.constrainSize};if(self.options.zoomType==='inner'){self.zoomWrap.parent().css(constHDim);self.zoomWindow.css(constHDim);}
if(self.options.tint){self.tintContainer.css(constHDim);self.zoomTint.css(constHDim);self.zoomTintImage.css(constHDim);}}}},doneCallback:function(){var self=this;if(self.options.loadingIcon&&!!self.spinner&&!!self.spinner.length){self.spinner.hide();}
self.nzOffset=self.$elem.offset();self.nzWidth=self.$elem.width();self.nzHeight=self.$elem.height();self.currentZoomLevel=self.options.zoomLevel;self.widthRatio=self.largeWidth/self.nzWidth;self.heightRatio=self.largeHeight/self.nzHeight;if(self.options.zoomType==='window'){if(self.nzHeight<self.options.zoomWindowHeight/self.heightRatio){self.lensHeight=self.nzHeight;}
else{self.lensHeight=String((self.options.zoomWindowHeight/self.heightRatio));}
if(self.nzWidth<self.options.zoomWindowWidth){self.lensWidth=self.nzWidth;}
else{self.lensWidth=(self.options.zoomWindowWidth/self.widthRatio);}
if(self.zoomLens){self.zoomLens.css({'width':self.lensWidth,'height':self.lensHeight});}}},getCurrentImage:function(){var self=this;return self.zoomImage;},getGalleryList:function(){var self=this;self.gallerylist=[];if(self.options.gallery){$('#'+self.options.gallery+' a').each(function(){var imgSrc='';if($(this).data(self.options.attrImageZoomSrc)){imgSrc=$(this).data(self.options.attrImageZoomSrc);}
else if($(this).data('image')){imgSrc=$(this).data('image');}
if(imgSrc===self.zoomImage){self.gallerylist.unshift({href:''+imgSrc+'',title:$(this).find('img').attr('title')});}
else{self.gallerylist.push({href:''+imgSrc+'',title:$(this).find('img').attr('title')});}});}
else{self.gallerylist.push({href:''+self.zoomImage+'',title:$(this).find('img').attr('title')});}
return self.gallerylist;},getGalleryListFancyboxThree:function(){var self=this;self.gallerylist=[];if(self.options.gallery){$('#'+self.options.gallery+' .woocommerce-product-gallery__image').each(function(){var img_src='',the_img=$(this).find('img');if(the_img.data("large_image")){img_src=the_img.data("large_image");}
else if(the_img.data("image")){img_src=the_img.data("image");}
if(img_src==self.zoomImage){self.gallerylist.unshift({src:''+img_src+'',opts:{caption:the_img.attr("title"),thumb:''+img_src+'',}});}
else{self.gallerylist.push({src:''+img_src+'',opts:{caption:the_img.attr("title"),thumb:''+img_src+'',}});}});}
else{self.gallerylist.push({src:''+self.zoomImage+'',opts:{caption:the_img.attr("title"),thumb:''+self.zoomImage+'',}});}
return self.gallerylist;},changeZoomLevel:function(value){var self=this;self.scrollingLock=true;self.newvalue=parseFloat(value).toFixed(2);var newvalue=self.newvalue;var maxheightnewvalue=self.largeHeight/((self.options.zoomWindowHeight/self.nzHeight)*self.nzHeight);var maxwidthtnewvalue=self.largeWidth/((self.options.zoomWindowWidth/self.nzWidth)*self.nzWidth);if(self.options.zoomType!=='inner'){if(maxheightnewvalue<=newvalue){self.heightRatio=(self.largeHeight/maxheightnewvalue)/self.nzHeight;self.newvalueheight=maxheightnewvalue;self.fullheight=true;}
else{self.heightRatio=(self.largeHeight/newvalue)/self.nzHeight;self.newvalueheight=newvalue;self.fullheight=false;}
if(maxwidthtnewvalue<=newvalue){self.widthRatio=(self.largeWidth/maxwidthtnewvalue)/self.nzWidth;self.newvaluewidth=maxwidthtnewvalue;self.fullwidth=true;}
else{self.widthRatio=(self.largeWidth/newvalue)/self.nzWidth;self.newvaluewidth=newvalue;self.fullwidth=false;}
if(self.options.zoomType==='lens'){if(maxheightnewvalue<=newvalue){self.fullwidth=true;self.newvaluewidth=maxheightnewvalue;}else{self.widthRatio=(self.largeWidth/newvalue)/self.nzWidth;self.newvaluewidth=newvalue;self.fullwidth=false;}}}
if(self.options.zoomType==='inner'){maxheightnewvalue=parseFloat(self.largeHeight/self.nzHeight).toFixed(2);maxwidthtnewvalue=parseFloat(self.largeWidth/self.nzWidth).toFixed(2);if(newvalue>maxheightnewvalue){newvalue=maxheightnewvalue;}
if(newvalue>maxwidthtnewvalue){newvalue=maxwidthtnewvalue;}
if(maxheightnewvalue<=newvalue){self.heightRatio=(self.largeHeight/newvalue)/self.nzHeight;if(newvalue>maxheightnewvalue){self.newvalueheight=maxheightnewvalue;}else{self.newvalueheight=newvalue;}
self.fullheight=true;}
else{self.heightRatio=(self.largeHeight/newvalue)/self.nzHeight;if(newvalue>maxheightnewvalue){self.newvalueheight=maxheightnewvalue;}else{self.newvalueheight=newvalue;}
self.fullheight=false;}
if(maxwidthtnewvalue<=newvalue){self.widthRatio=(self.largeWidth/newvalue)/self.nzWidth;if(newvalue>maxwidthtnewvalue){self.newvaluewidth=maxwidthtnewvalue;}else{self.newvaluewidth=newvalue;}
self.fullwidth=true;}
else{self.widthRatio=(self.largeWidth/newvalue)/self.nzWidth;self.newvaluewidth=newvalue;self.fullwidth=false;}}
var scrcontinue=false;if(self.options.zoomType==='inner'){if(self.nzWidth>=self.nzHeight){if(self.newvaluewidth<=maxwidthtnewvalue){scrcontinue=true;}
else{scrcontinue=false;self.fullheight=true;self.fullwidth=true;}}
if(self.nzHeight>self.nzWidth){if(self.newvaluewidth<=maxwidthtnewvalue){scrcontinue=true;}
else{scrcontinue=false;self.fullheight=true;self.fullwidth=true;}}}
if(self.options.zoomType!=='inner'){scrcontinue=true;}
if(scrcontinue){self.zoomLock=0;self.changeZoom=true;if(((self.options.zoomWindowHeight)/self.heightRatio)<=self.nzHeight){self.currentZoomLevel=self.newvalueheight;if(self.options.zoomType!=='lens'&&self.options.zoomType!=='inner'){self.changeBgSize=true;self.zoomLens.css('height',String(self.options.zoomWindowHeight/self.heightRatio)+'px');}
if(self.options.zoomType==='lens'||self.options.zoomType==='inner'){self.changeBgSize=true;}}
if((self.options.zoomWindowWidth/self.widthRatio)<=self.nzWidth){if(self.options.zoomType!=='inner'){if(self.newvaluewidth>self.newvalueheight){self.currentZoomLevel=self.newvaluewidth;}}
if(self.options.zoomType!=='lens'&&self.options.zoomType!=='inner'){self.changeBgSize=true;self.zoomLens.css('width',String(self.options.zoomWindowWidth/self.widthRatio)+'px');}
if(self.options.zoomType==='lens'||self.options.zoomType==='inner'){self.changeBgSize=true;}}
if(self.options.zoomType==='inner'){self.changeBgSize=true;if(self.nzWidth>self.nzHeight){self.currentZoomLevel=self.newvaluewidth;}
else if(self.nzHeight>=self.nzWidth){self.currentZoomLevel=self.newvaluewidth;}}}
self.setPosition(self.currentLoc);},closeAll:function(){var self=this;if(self.zoomWindow){self.zoomWindow.hide();}
if(self.zoomLens){self.zoomLens.hide();}
if(self.zoomTint){self.zoomTint.hide();}},changeState:function(value){var self=this;if(value==='enable'){self.options.zoomEnabled=true;}
if(value==='disable'){self.options.zoomEnabled=false;}},responsiveConfig:function(options){if(options.respond&&options.respond.length>0){return $.extend({},options,this.configByScreenWidth(options));}
return options;},configByScreenWidth:function(options){var screenWidth=$(window).width();var config=$.grep(options.respond,function(item){var range=item.range.split('-');return(screenWidth>=range[0])&&(screenWidth<=range[1]);});if(config.length>0){return config[0];}else{return options;}}};$.fn.ezPlus=function(options){return this.each(function(){var elevate=Object.create(EZP);elevate.init(options,this);$.data(this,'ezPlus',elevate);});};$.fn.ezPlus.options={attrImageZoomSrc:'large_image',borderColour:'#888',borderSize:4,constrainSize:false,constrainType:false,containLensZoom:false,cursor:'inherit',debug:false,easing:false,easingAmount:12,enabled:true,gallery:false,galleryActiveClass:'zoomGalleryActive',gallerySelector:false,galleryItem:'a',imageCrossfade:false,lensBorderColour:'#000',lensBorderSize:1,lensColour:'white',lensFadeIn:false,lensFadeOut:false,lensOpacity:0.4,lensShape:'square',lensSize:200,lenszoom:false,loadingIcon:false,mantainZoomAspectRatio:false,maxZoomLevel:false,minZoomLevel:false,onComplete:$.noop,onDestroy:$.noop,onImageClick:$.noop,onImageSwap:$.noop,onImageSwapComplete:$.noop,onShow:$.noop,onZoomedImageLoaded:$.noop,preloading:1,respond:[],responsive:true,scrollZoom:false,scrollZoomIncrement:0.1,showLens:true,tint:false,tintColour:'#333',tintOpacity:0.4,touchEnabled:true,zoomActivation:'hover',zoomContainerAppendTo:'body',zoomId:-1,zoomLevel:1,zoomTintFadeIn:false,zoomTintFadeOut:false,zoomType:'window',zoomWindowAlwaysShow:false,zoomWindowBgColour:'#fff',zoomWindowFadeIn:false,zoomWindowFadeOut:false,zoomWindowHeight:400,zoomWindowOffsetX:0,zoomWindowOffsetY:0,zoomWindowPosition:1,zoomWindowWidth:400,zoomEnabled:true,zIndex:999};})(jQuery,window,document);