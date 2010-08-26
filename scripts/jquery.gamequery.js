/*
 * gameQuery rev. 0.3
 *
 * Copyright (c) 2008 Selim Arsever (gamequery.onaluf.org)
 * licensed under the MIT (MIT-LICENSE.txt)
 */

/**
 * This is the Animation Object
 */
function Animation(options){
	//default values jQuery style:
	options = jQuery.extend({
		imageURL:		"",
		numberOfFrame:	1,
		delta:			32,
		rate: 			30,
		type:			0,
		distance:		0
	}, options);

	//"public" attributes:
	this.imageURL		= options.imageURL;		// The url of the image to be used as an animation or sprite 
	this.numberOfFrame	= options.numberOfFrame;// The number of frame to be displayed when playing the animation
	this.delta			= options.delta;		// The the distance in pixels between two frame
	this.rate			= options.rate;			// The rate at which the frame must be played in miliseconds
	this.type			= options.type;			// The type of the animation.This is bitwise OR of the properties.
	this.distance		= options.distance;		// The the distance in pixels between two animation
	
	//Whenever a new animation is created we add it to the ResourceManager animation list
	jQuery.gameQuery.resourceManager.addAnimation(this);
	
	return true;
};

//"Constants " for the different type of an animation:
Animation.VERTICAL 		=  1; // genertated by a verical offset of the background
Animation.HORIZONTAL	=  2; // genertated by a horizontal offset of the background
Animation.ONCE			=  4; // played only once (else looping indefinitly)
Animation.CALLBACK		=  8; // A callack is exectued at the end of a cycle 
Animation.MULTI			= 16; // The image file contains many animations

 
// this allow to used the convenient $ notation in  a plugins 
(function($) {
	/**
	* A class to manages the resources like animations
	**/
	this.ResourceManager = function() {
		this.animations = new Array(); // List of animation / images used in the game
		this.sounds		= new Array(); // List of sounds used in the game
		this.callbacks = new Array();  // List of the functions called at each refresh
		this.running = false;		   // State of the game,
		
		/**
		 * This function the covers things to load befor to start the game.
		  **/
		this.preload = function(){
			//Start loading the images
			for (var i in this.animations){
				this.animations[i].domO = new Image();
				this.animations[i].domO.src = this.animations[i].imageURL;
			}
			
			//Start loading the sounds
			jQuery.gameQuery.playground.append("<div id='sounds' style='visibility: hidden' width=0 height=0 />");
			for (var i in this.sounds){
				$("#sounds").append("<embed type='audio/wav' src='"+this.sounds[i].url+"' autostart=false id='"+this.sounds[i].name+"'enablejavascript='true' />");
				this.sounds[i].domO = $("#"+this.sounds[i].name)[0]; 
			}
			 
			jQuery.gameQuery.resourceManager.waitForResources();
		};
		
		/**
		 * This function the waits for all the resources called for in preload() to finish loading.
		  **/
		this.waitForResources =function(){
			var loadbarEnabled = (jQuery.gameQuery.loadbar != undefined);
			if(loadbarEnabled){
				$(jQuery.gameQuery.loadbar.id).width(0); 
				var loadBarIncremant = jQuery.gameQuery.loadbar.width / (jQuery.gameQuery.resourceManager.animations.length + jQuery.gameQuery.resourceManager.sounds.length);
			}
			//check the images
			var imageCount = 0; 
			for(var i=0; i < jQuery.gameQuery.resourceManager.animations.length; i++){
				if(jQuery.gameQuery.resourceManager.animations[i].domO.complete){
					imageCount++;
				}
			}
			//check the sounds 
			var soundCount = 0; 
			for(var i=0; i < jQuery.gameQuery.resourceManager.sounds.length; i++){
				try{ // Quicktime
					if(jQuery.gameQuery.resourceManager.sounds[i].domO.GetPluginStatus()=="complete"){
						soundCount++;
					}
				} catch (e){
					try{ // WMP
						if(jQuery.gameQuery.resourceManager.sounds[i].domO.player.playState==10){
							soundCount++;
						}
					} catch (e){
						try{ // RealPlayer
							if(jQuery.gameQuery.resourceManager.sounds[i].domO.CanPlay()){
								soundCount++;
							}
						} catch (e){ // VLC
							try{
								var soundState = jQuery.gameQuery.resourceManager.sounds[i].domO.input.state;
								if(soundState!=1 & soundState!=2){
									soundCount++;
								}
							} catch (e){}
						}
					}
				}	
			}
			// All the image are loaded but we haven't seen a loaded sound yet ? Maybe no plugin are present or we don't know how to check ... 
			// Anyway we stop waiting and consider all the sound are done loading.
			if(soundCount==0 & imageCount == jQuery.gameQuery.resourceManager.animations.length){
				soundCount = jQuery.gameQuery.resourceManager.sounds.length;
			}
			//update the loading bar
			if(loadbarEnabled){
				$("#"+jQuery.gameQuery.loadbar.id).width((imageCount+soundCount)*loadBarIncremant); 
			}
			
			if(imageCount < (jQuery.gameQuery.resourceManager.animations.length + jQuery.gameQuery.resourceManager.sounds.length)){
				imgWait=setTimeout('jQuery.gameQuery.resourceManager.waitForResources()', 100);
			} else {
				// all the resources are loaded!
				// We can associate the animation's images to their coresponding sprites
				$(".sceengraph").children().each(function(){
					// recursive call on the children:
					$(this).children().each(arguments.callee);
					// add the image as a background
					if(this.gameQuery && this.gameQuery.animation){
						$(this).css("background-image", "url("+this.gameQuery.animation.imageURL+")");
						// we set the correct kind of repeat
						if(this.gameQuery.animation.type & Animation.VERTICAL) {
							$(this).css("background-repeat", "repeat-x");
						} else if(this.gameQuery.animation.type & Animation.HORIZONTAL) {
							$(this).css("background-repeat", "repeat-y");
						} else {
							$(this).css("background-repeat", "no-repeat");
						}
					}
				});
				
				// And launch the refresh loop
				jQuery.gameQuery.resourceManager.running = true;
				setInterval("jQuery.gameQuery.resourceManager.refresh()",(jQuery.gameQuery.refreshRate));
				if(jQuery.gameQuery.startCallback){
					jQuery.gameQuery.startCallback();
				}
				//make the sceengraph visible
				jQuery.gameQuery.playground.children(".sceengraph").css("visibility","visible");
			}
		};
		
		/**
		* This function refresh a unique sprite
		**/
		this.refreshSprite = function(){
			//Call this function on all the children:
			$(this).children().each(jQuery.gameQuery.resourceManager.refreshSprite);
			// is 'this' a sprite ? 
			if(this.gameQuery != undefined){
				// does 'this' has an animation ?
				if(this.gameQuery.animation != null){
					//Do we have anything to do?
					if(this.gameQuery.idleCounter == this.gameQuery.animation.rate-1){
						// does 'this' loops?
						if(this.gameQuery.animation.type & Animation.ONCE){
							if(this.gameQuery.currentFrame < this.gameQuery.animation.numberOfFrame-2){
								this.gameQuery.currentFrame++;
							} else if(this.gameQuery.currentFrame == this.gameQuery.animation.numberOfFrame-2) {
								this.gameQuery.currentFrame++;
								// does 'this' has a callback ?
								if(this.gameQuery.animation.type & Animation.CALLBACK){
									if(this.gameQuery.callback != null){
										this.gameQuery.callback(this);
									}
								}
							}
						} else {
							this.gameQuery.currentFrame = (this.gameQuery.currentFrame+1)%this.gameQuery.animation.numberOfFrame;
							if(this.gameQuery.currentFrame == this.gameQuery.animation.numberOfFrame-1){
								// does 'this' has a callback ?
								if(this.gameQuery.animation.type & Animation.CALLBACK){
									if(this.gameQuery.callback != null){
										this.gameQuery.callback(this);
									}
								}
							}
						}
						// update the background:
						if(this.gameQuery.animation.type & Animation.VERTICAL){
							if($(this).data("multi")){
								$(this).css("background-position",""+$(this).data("multi")+"px "+(-this.gameQuery.animation.delta*this.gameQuery.currentFrame)+"px");
							} else {
								$(this).css("background-position","0px "+(-this.gameQuery.animation.delta*this.gameQuery.currentFrame)+"px");
							}
						} else if(this.gameQuery.animation.type & Animation.HORIZONTAL) {
							if($(this).data("multi")){
								$(this).css("background-position",""+(-this.gameQuery.animation.delta*this.gameQuery.currentFrame)+"px "+$(this).data("multi")+"px");
							} else {
								$(this).css("background-position",""+(-this.gameQuery.animation.delta*this.gameQuery.currentFrame)+"px 0px");
							}
						}
					}
					this.gameQuery.idleCounter = (this.gameQuery.idleCounter+1)%this.gameQuery.animation.rate;
				}
			}
			return true;
		};
		
		/**
		 * This function is called periodically to refresh the state of the game.
		  **/
		this.refresh = function(){	
			$(".sceengraph").children().each(this.refreshSprite);
			
			var deadCallback= new Array();
			for (var i in this.callbacks){
				if(this.callbacks[i].idleCounter == this.callbacks[i].rate-1){
					var returnedValue = this.callbacks[i].fn();
					if(typeof returnedValue == 'boolean'){
						// if we have a boolean: 'true' means 'no more execution', 'false' means 'execute once more'
						if(returnedValue){
							deadCallback.push(parseInt(i));
						}
					} else if(typeof returnedValue == 'number') {
						// if we have a number it re-defines the time to the nex call
						this.callbacks[i].rate = parseInt(returnedValue/jQuery.gameQuery.refreshRate);
						this.callbacks[i].idleCounter = 0;
					}
				}
				this.callbacks[i].idleCounter = (this.callbacks[i].idleCounter+1)%this.callbacks[i].rate;
			}
			for (i in deadCallback){
				this.callbacks.splice(deadCallback[i],1);
			}
		};
		
		this.addAnimation = function(animation){
			if(jQuery.inArray(animation,this.animations)<0){
				//normalize the animationRate:
				animation.rate = parseInt(animation.rate/jQuery.gameQuery.refreshRate);
				if(animation.rate==0){
					animation.rate = 1;
				}
				this.animations.push(animation);
			}
		};
		
		this.addSound = function(name, soundUrl){
			this.sounds.push({name: name, url: soundUrl});
		};
		
		this.registerCallback = function(fn, rate){
			rate  = parseInt(rate/jQuery.gameQuery.refreshRate);
			if(rate==0){
				rate = 1;
			}
			this.callbacks.push({fn: fn, rate: rate, idleCounter: 0});
		};
		
		return true;
	};
	
	/**
	* This is an extension of jQuery for the gameQuery variables
	*/
	var gameQuery =	{
					playground: 		null,
					refreshRate: 		30,
					resourceManager:	new ResourceManager()
					};
	jQuery.extend({	gameQuery: gameQuery});
	
	/**
	* Define the div to use for the display the game and initailize it.
	* This could be called on any node it doesn't matter.
	* The returned node is the playground node.
	* This IS a desrtuctive call
	**/
	jQuery.fn.playground = function(div, options) {
		if(div != undefined){
			options = jQuery.extend({
				height:		320,
				width:		480,
				refreshRate: 30,
				position:	"absolute",
				keyTracker:	false
			}, options);
			//We save the playground node and set some variable for this node:
			jQuery.gameQuery.playground = $(div);
			jQuery.gameQuery.refreshRate = options.refreshRate;
			jQuery.gameQuery.playground[0].height = options.height;
			jQuery.gameQuery.playground[0].width = options.width;

			// We initialize the apearance of the div
			jQuery.gameQuery.playground.css("position", options.position);
			jQuery.gameQuery.playground.css("display", "block");
			jQuery.gameQuery.playground.css("overflow","hidden");
			jQuery.gameQuery.playground.height(options.height);
			jQuery.gameQuery.playground.width(options.width);
			
			// We create the sceen graph:
			jQuery.gameQuery.playground.append("<div class='sceengraph' style='visibility: hidden'/>");
			
			//Add the keyTracker to the gameQuery object:
			jQuery.gameQuery.keyTracker = {};
			// we only enable the real tracking if the users wants it
			if(options.keyTracker){
				$(document).keydown(function(event){
					$.gameQuery.keyTracker[event.keyCode] = true;
				});
				$(document).keyup(function(event){
					$.gameQuery.keyTracker[event.keyCode] = false;
				});
			}
		}
		return jQuery.gameQuery.playground;
	};
	
	/**
	* Starts the game. The resources from the resource manager are preloaded if necesary 
	* Works only for the playgroung node.
	* This is a non-desrtuctive call
	**/
	jQuery.fn.startGame = function(callback) {
		//if the element is the playground we start the game:
		jQuery.gameQuery.startCallback = callback;
		jQuery.gameQuery.resourceManager.preload();
		return this;
	};
	
	/**
	* Add a group to the sceen graph
	* works only on the sceengraph root or on another group
	* This IS a desrtuctive call and should be terminated with end() to go back one level up in the chaining
	**/
	jQuery.fn.addGroup = function(group, options) {
		options = jQuery.extend({
			width:		32,
			height:		32,
			posx:		0,
			posy:		0,
			overflow: 	"visible"
		}, options);
		
		var newGroupElement = "<div id='"+group+"' class='group' style='position: absolute; display: block; overflow: "+options.overflow+"; top: "+options.posy+"px; left: "+options.posx+"px; height: "+options.height+"px; width: "+options.width+"px;' />";
		if(this == jQuery.gameQuery.playground){
			this.children(".sceengraph").append(newGroupElement);
		} else if ((this == jQuery.gameQuery.sceengraph)||(this.hasClass("group"))){
			this.append(newGroupElement);
		}
		var newGroup = $("#"+group);
		newGroup[0].gameQuery = options;
		newGroup[0].gameQuery.group = true;
		return this.pushStack(newGroup);
	};
	
	/**
	* Add a sprite to the current node.
	* Works only on the playground, the sceengraph root or a sceengraph group
	* This is a non-desrtuctive call
	**/
	jQuery.fn.addSprite = function(sprite, options) {
		options = jQuery.extend({
			width:			32,
			height:			32,
			posx:			0,
			posy:			0,
			idleCounter:	0,
			currentFrame:	0,
			callback:		null
		}, options);
		
		var newSpriteElem = "<div id='"+sprite+"' style='position: absolute; display: block; overflow: hidden; height: "+options.height+"px; width: "+options.width+"px; left: "+options.posx+"px; top: "+options.posy+"px; background-position: 0px 0px;' />";
		if(this == jQuery.gameQuery.playground){
			this.children(".sceengraph").append(newSpriteElem);
		} else {
			this.append(newSpriteElem);
		}

		//if the game has already started we want to add the animation's image as a background now:
		if(options.animation){
			if(jQuery.gameQuery.resourceManager.running){
				$("#"+sprite).css("background-image", "url("+options.animation.imageURL+")");
			}
			if(options.animation.type & Animation.VERTICAL) {
				$("#"+sprite).css("background-repeat", "repeat-x");
			} else if(options.animation.type & Animation.HORIZONTAL) {
				$("#"+sprite).css("background-repeat", "repeat-y");
			} else {
				$("#"+sprite).css("background-repeat", "no-repeat");
			}
		}
		
		
		var spriteDOMObject = $("#"+sprite)[0];
		if(spriteDOMObject != undefined){
			spriteDOMObject.gameQuery = options;
		}
		return this;
	};
	
	/**
	* Remove the sprite  on which it is called. This is here for backward compatibility  but it doesn't
	* do anything more than simply calling .remove()
	* This is a non-desrtuctive call
 	**/
	jQuery.fn.removeSprite = function() {
		this.remove();
		return this;
	};
	
	/**
	* Changes the animation associated with a sprite.
	* WARNING: no check are made to ensure that the object is really a sprite
	* This is a non-desrtuctive call
	**/
	jQuery.fn.setAnimation = function(animation, callback) {	
		
		if(typeof animation == "number"){
			if(this[0].gameQuery.animation.type & Animation.MULTI){
				var distance = this[0].gameQuery.distance * animation;
				this.data("multi",distance);
				if(this[0].gameQuery.animation.type & Animation.VERTICAL) {
					this[0].gameQuery.currentFrame = 0;
					this.css("background-position",""+distance+"px 0px");
				} else if(this[0].gameQuery.animation.type & Animation.HORIZONTAL) {
					this[0].gameQuery.currentFrame = 0;
					this.css("background-position","0px "+distance+"px");
				}
			}
		} else {
			if(animation){
				this[0].gameQuery.animation = animation;
				this[0].gameQuery.currentFrame = 0;
				this.css("background-image", "url("+animation.imageURL+")");
				this.css("background-position","0px 0px");
				
				if(this[0].gameQuery.animation.type & Animation.VERTICAL) {
					this.css("background-repeat", "repeat-x");
				} else if(this[0].gameQuery.animation.type & Animation.HORIZONTAL) {
					this.css("background-repeat", "repeat-y");
				} else {
					this.css("background-repeat", "no-repeat");
				}
			} else {
				this.css("background-image", "none");
			}
		}
		
		if(callback != undefined){
			this[0].gameQuery.callback = callback;	
		}
		
		return this;
	};
	
	/**
	* This function add the sound to the resourceManger for later use.
	* This is a non-desrtuctive call
	**/
	jQuery.fn.addSound = function(name, soundUrl) {
		jQuery.gameQuery.resourceManager.addSound(name, soundUrl);
		return this;
	};
	
	/**
	* This function plays the sound if it has been loaded before
	* This is a non-desrtuctive call
	**/
	jQuery.fn.playSound = function(loop) {
		if($(this).parent("#sounds").length!=0){
			try {
				$(this)[0].Play(); //Quicktime  ?
			} catch (e){
				try {
					$(this)[0].play(); //WMP ? 
				} catch (e){
					try {
						$(this)[0].DoPlay(); // RealPlayer ?
					} catch (e){
						try {
							$(this)[0].playlist.play(); //VLC ?
						} catch (e){/* Silently giving up... */}
					}
				}
			}
		}
		return this;
	};
	
	/**
	* This function stops the sound
	* This is a non-desrtuctive call
	**/
	jQuery.fn.stopSound = function(loop) {
		if($(this).parent("#sounds").length!=0){
			try {
				$(this)[0].Stop(); //Quicktime ?
			} catch (e){
				try {
					$(this)[0].stop(); //WMP ? 
				} catch (e){
					try {
						$(this)[0].DoStop(); // RealPlayer ?
					} catch (e){
						try {
							$(this)[0].playlist.stop(); //VLC ?
						} catch (e){/* Silently giving up... */}
					}
				}
			}
		}
		return this;
	};
	
	/**
	* Register a callback to be trigered every "rate"
	* This is a non-desrtuctive call
	**/
	jQuery.fn.registerCallback = function(fn, rate) {
		jQuery.gameQuery.resourceManager.registerCallback(fn, rate);
		return this;
	};
	
	/**
	* Set the id of the div to use as a loading bar while the games media are loaded during the preload
	* This is a non-desrtuctive call
	**/
	jQuery.fn.setLoadBar = function(elementId, finalwidth) {
		jQuery.gameQuery.loadbar = {id: elementId, width: finalwidth};
		return this;
	};
	
	/**
	 * This function retreive a list of object in collision with the subject:
	 * - if 'this' is a sprite or a group, the function will retrieve the list of sprites (not groups) that touch it
	 * - if 'this' is the playground, the function will return a list of all pair of collisioning elements. They are represented 
	 *    by a jQuery object containing a series of paire. Each paire represents two object colliding.
	 * For now all abject are considered to be boxes.
	 * This IS a desrtuctive call and should be terminated with end() to go back one level up in the chaining
	 **/
	jQuery.fn.collision = function(filter){
		var resultList = new Array();
		
		//retrieve 'this' offset by looking at the parents
		var itsParent = this[0].parentNode;
		var offsetX = 0;
		var offsetY = 0;
		while (itsParent != jQuery.gameQuery.playground[0]){
				if(itsParent.gameQuery){
				offsetX += itsParent.gameQuery.posx;
				offsetY += itsParent.gameQuery.posy;
			}
			itsParent = itsParent.parentNode;
		}
		
		// retrieve 'this' absolute position and size information
		var itsGeom = {top: this[0].gameQuery.posy+offsetY,left: this[0].gameQuery.posx+offsetX};
		itsGeom.right = itsGeom.left + this[0].gameQuery.width;
		itsGeom.bottom = itsGeom.top + this[0].gameQuery.height;
		// retrieve the playground's absolute position and size information
		var pgdGeom = {top: 0, left: 0, bottom: jQuery.gameQuery.playground[0].height, right: jQuery.gameQuery.playground[0].width};
		
		// Does 'this' is inside the playground ?
		if((itsGeom.bottom < pgdGeom.top)&&(itsGeom.right < pgdGeom.left)&&
		   (itsGeom.top > pgdGeom.bottom)&&(itsGeom.left > pgdGeom.right)){
			return this.pushStack(new jQuery(new Array()));
		}
		
		if(this == jQuery.gameQuery.playground){ 
			//TODO Code the "all against all" collision detection and find a nice way to return a list of pairs of elements
		} else {
			// we must find all the element that touches 'this'
			var elementsToCheck = new Array();
			elementsToCheck.push($(".sceengraph").children(filter).get());
			elementsToCheck[0].offsetX = 0;
			elementsToCheck[0].offsetY = 0;
			
			var i = 0;
			var	len = elementsToCheck.length;
			while (i < len) {
				var subLen = elementsToCheck[i].length;
				while(subLen--){
					var elementToCheck = elementsToCheck[i][subLen];
					// is it a sprite ?
				    if(elementToCheck.gameQuery){
				    	var eleGeom = {top: elementToCheck.gameQuery.posy + elementsToCheck[i].offsetY, left: elementToCheck.gameQuery.posx + elementsToCheck[i].offsetX};
				    	if(!elementToCheck.gameQuery.group){
							eleGeom.right = eleGeom.left + elementToCheck.gameQuery.width;
							eleGeom.bottom = eleGeom.top + elementToCheck.gameQuery.height;
							// does it touches the selection?
							if(!((eleGeom.bottom < itsGeom.top)||(eleGeom.right < itsGeom.left)||
							    (eleGeom.top > itsGeom.bottom)||(eleGeom.left > itsGeom.right))){
							    if(this[0]!=elementToCheck){
									// We add the element to the list
						    		resultList.push(elementsToCheck[i][subLen]);
							    }
							}
				    	}
						var eleChildren = $(elementToCheck).children(filter);
					    if(eleChildren.length){
					    	elementsToCheck.push(eleChildren.get());
					    	elementsToCheck[len].offsetX = eleGeom.left;
					    	elementsToCheck[len].offsetY = eleGeom.top;
					    	len++;
					    }
				    }
				}
				i++;
			}
			return this.pushStack($(resultList));
		}
	};
	
	/**
	 * This function rotates the selected element(s) clock-wise. The argument is a degree.
	 **/
	jQuery.fn.rotate = function(angle){
		if(angle) {
			this.data("rotate_angle", angle);
			if(this.css("MozTransform")) {
				// For firefox from 3.5
				var transform = "rotate("+angle+"deg) scale("+this.scale()+")";
				this.css("MozTransform",transform);
			} else if(this.css("WebkitTransform")!==null && this.css("WebkitTransform")!==undefined) {
				// For safari from 3.1 (and chrome)
				var transform = "rotate("+angle+"deg) scale("+this.scale()+")";
				this.css("WebkitTransform",transform);
			} else if(this.css("filter")!==undefined){
				// For ie from 5.5
				var fac = this.scale();
				var cos = Math.cos(Math.PI * 2 / 360 * angle) * fac;
				var sin = Math.sin(Math.PI * 2 / 360 * angle) * fac;
				var previousWidth = this.width();
				var previousHeight = this.height();
				this.css("filter","progid:DXImageTransform.Microsoft.Matrix(M11="+cos+",M12=-"+sin+",M21="+sin+",M22="+cos+",SizingMethod='auto expand',FilterType='nearest neighbor')");
				var newWidth = this.width();
				var newHeight = this.height();
				this.css("left", ""+(parseInt(this.css("left"))-(newWidth-previousWidth)/2)+"px");
				this.css("top", ""+(parseInt(this.css("top"))-(newHeight-previousHeight)/2)+"px");
			}
			return this;
		} else {
			var ang = this.data("rotate_angle");
			return ang ? ang : 0;
		}
	};
	
	/**
	 * This function change the scale of the selected element(s). The passed argument is a ratio: 
	 * 1.0 = original size
	 * 0.5 = half the original size
	 * 2.0 = twice the original size
	 **/
	jQuery.fn.scale = function(factor){
		if(factor) {
			this.data("scale_factor", factor);
			if(this.css("MozTransform")) {
				// For firefox from 3.5
				var transform = "rotate("+this.rotate()+"deg) scale("+factor+")";
				this.css("MozTransform",transform);
			} else if(this.css("WebkitTransform")!==null && this.css("WebkitTransform")!==undefined) {
				// For safari from 3.1 (and chrome)
				var transform = "rotate("+this.rotate()+"deg) scale("+factor+")";
				this.css("WebkitTransform",transform);
			} else if(this.css("filter")!==undefined){
				// For ie from 5.5
				var ang = this.rotate();
				var cos = Math.cos(Math.PI * 2 / 360 * ang) * factor;
				var sin = Math.sin(Math.PI * 2 / 360 * ang) * factor;
				var previousWidth = this.width();
				var previousHeight = this.height();
				this.css("filter","progid:DXImageTransform.Microsoft.Matrix(M11="+cos+", M12=-"+sin+", M21="+sin+", M22="+cos+",SizingMethod='auto expand',FilterType='nearest neighbor')");
				var newWidth = this.width();
				var newHeight = this.height();
				this.css("left", ""+(parseInt(this.css("left"))-(newWidth-previousWidth)/2)+"px");
				this.css("top", ""+(parseInt(this.css("top"))-(newHeight-previousHeight)/2)+"px");
			}
			return this;
		} else {
			var fac = this.data("scale_factor");
			return fac ? fac : 1;
		}
	};
	

	// This is an hijack to keep track of the change in the sprites, and group positions and size
	var oldCssFunction = jQuery.fn.css;
	jQuery.fn.css = function(key, value) {
		if((this.length > 0) && this[0].gameQuery && value){
			if(key == "top"){
				this[0].gameQuery.posy = parseFloat(value);
			} else if(key == "left"){
				this[0].gameQuery.posx = parseFloat(value);
			} else if(key == "width"){
				this[0].gameQuery.width = parseFloat(value);
			} else if (key == "height"){
				this[0].gameQuery.height = parseFloat(value);
			}
		}
		return oldCssFunction.apply(this, new Array(key, value));
	};
	
})(jQuery);