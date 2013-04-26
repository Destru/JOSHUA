// joshua (jquery operating system)
// http://binaerpilot.no/alexander/
// alexander@binaerpilot.no
var hist = [], // history (arrow up/down)
	position = 0, // position in history
	expires = 1095, // cookie dies in 3 years
	fade = 350, // ui fade delay
	muted = false, // sound
	drawing = false, // drawing?
	focus = true, // steal focus
	terminal = false, // terminal style layout
	terminals = ['pirate', 'helvetica', 'mono', 'c64', 'eve'], // terminal themes
	windows = ['customize', 'music', 'gallery', 'superplastic', 'videos']; // common windows
if(theme == "nextgen" || $.inArray(theme, nextgenThemes) > -1) var nextgen = true; // nextgen themes
if(nextgen) windows.push('joshua');

// helpers
function reset() {
	eraseCookie('joshua');
	eraseCookie('release');
	eraseCookie('theme');
	eraseCookie('background');
	eraseCookie('fx');
	eraseCookie('opacity');
	$.each(windows, function() {
		eraseCookie(this);
		eraseCookie('window.'+this);
	});
	location.reload();
}
function stealFocus(off) {
	if(off) {
		$('#prompt').off('blur');
	}
	else {
		$('#prompt').off('blur').on('blur', function() {
			$('#prompt').focus();
		});
		$('#prompt').focus();
	}
}
function systemReady() {
	$('title').text(title+'Ready');
	$('#joshua').css('cursor', 'auto');
}
function clearInput() {
	$('#prompt').blur().val('');
	setTimeout(function() {
		$('#prompt').focus();
	}, 50);
}
function scrollCheck() {
	if(terminal) {
		$('html, body').animate({scrollTop: $(document).height()}, 250);
		$('.output:last .prompt').prepend('<span class="prefix">'+termPrompt+'</span>');
	}
	else {
		$('#output').animate({scrollTop: $('#output').prop('scrollHeight')}, 250);
	}
}
function mute() {
	if(!muted) {
		soundManager.mute();
		muted = true;
	}
	else {
		soundManager.unmute();
		muted = false;
	}
	systemReady();
}

// effects
function fxStop() {
	var cookie = readCookie('fx');
	if(cookie) {
		$('.spark, #malkovich, .brush, #cylon').remove();
		$('body').removeClass('pulsar');
		if(cookie != "none") {
			eraseCookie('fx');
		}
		if(cookie == "draw") {
			$(document).unbind('mousedown');
			$(document).unbind('mouseup');
			$(document).unbind('mousemove');
		}
	}
}
function fxInit(fx, runOnce) {
	if(!runOnce) {
		fxStop();
		createCookie('fx', fx, expires);
	}
	if(fx == "sparks") {
		var totalSparks = 42;
		var sparks = [];
		for (i = 0; i < totalSparks; i++) {
			sparks[i] = new Spark(50);
		}
	}
	else if(fx == "malkovich") {
		$('body').append('<div id="malkovich"/>');
		$('body').mousemove(function(event) {
			$('#malkovich').css({
				top: (event.pageY+10)+'px',
				left: (event.pageX+15)+'px'
			});
			$('#malkovich:hidden').fadeIn(fade);
		});
	}
	else if(fx == "pulsar") {
		$('body').addClass('pulsar');
	}
	else if(fx == "draw") {
		var brush;
		$('body').css('-webkit-user-select', 'none');
		$(document).mousedown(function() {
			drawing = true;
		});
		$(document).mouseup(function() {
			drawing = false;
		});
		$(document).mousemove(function(e) {
			if(drawing) {
				brush = $('<div/>').addClass('brush').hide();
				$(document.body).append(brush);
				brush.css({
					top: e.pageY-12,
					left: e.pageX-12
				}).show();
			}
		});
	}
	else if(fx == "ultraviolence") {
		$('body').append('<div id="ultraviolence" class="overlay">');
		$('#ultraviolence').css({
			'background-image': 'url("images/ultraviolence.jpg")',
			'background-repeat': 'no-repeat',
			'background-position': '50% 100%',
			'height': $(document).height(),
			'width': $(document).width()
		});
		$('#ultraviolence').fadeIn(2000);
		setTimeout(function() {
			$('#ultraviolence').fadeOut(2000);
		}, 5000);
	}
	else if(fx == "cylon") {
		$('body').append('<div id="cylon"/>');
		cylon();
	}
}

// application loaders
function loadSuperplastic() {
	createCookie('superplastic', true, expires);
	if($('#superplastic').has('iframe').length == 0) {
		$('#superplastic').append('<iframe class="gameFrame" src="superplastic/index.html" width="580" height="340" frameborder="0" scrolling="no">')		
	}
	else {
		 $('#superplastic iframe').attr("src", $('#superplastic iframe').attr("src"));
	}
	$('#superplastic:hidden').fadeIn(fade);
	systemReady();
	stealFocus(true);
}
function loadVideos() {
	createCookie('videos', true, expires);
	$('#videos:hidden').fadeIn(fade);
	systemReady();
	stealFocus(true);
}

// init chrome
function chromeInit() {
	// drag windows
	$.each(windows, function() {
		$('#'+this).draggable({
			distance:10,
			handle:"h1",
			stop: function(event) {
				var window = 'window.'+$(this).attr('id'),
				left = $(this).css('left'),
				right = $(this).css('right'),
				top = $(this).css('top');
				createCookie(window, left+','+right+','+top, expires);
			}
		});
	});
	// x marks the spot
	$('.window h1:not(:has(.close))').append('<a class="close">x</a>');
	// close windows
	$('.close').click(function() {
		var id = $(this).closest("div").attr("id");
		eraseCookie(id);
		$('#'+id+':visible').fadeOut(fade);
		if(id == "superplastic") {
			$('#'+id+' iframe').remove();
			var fx = readCookie('fx');
			if(fx) fxInit(fx);
		}
		else if(id == "music") {
		 if(!muted) mute();	
		}
		$('#'+id+'Open').removeClass('active');
		focus = true;
		stealFocus();
	});
	// open windows
	$('.open').click(function() {
		var id = $(this).attr('id').replace(/Open/,'');
		if(id == "superplastic") loadSuperplastic();
		else if(id == "videos") loadVideos();
		else {
			createCookie(id, true, expires);
			if(id == "music") if(muted) mute();
		}
		$('#'+id).fadeIn(fade);
		$(this).addClass('active');
	});
	// view images
	$('a.view').click(function(event) {
		event.preventDefault();
		var imageSource = $(this).attr('href');
		$("<img/>").attr("src", imageSource).load(function() {
			$('<div class="modalClose"><img src="'+imageSource+'" width="'+this.width+'" height="'+ this.height+'"/></div>').modal({
				overlayId: 'modalOverlay',
				containerId : 'modalContainer',
				dataId: 'modalData',
				closeClass: 'modalClose',
				overlayClose: true,
				onOpen: function(dialog) {
					dialog.overlay.fadeIn(fade, function() {
						dialog.container.fadeIn(fade);
						dialog.data.fadeIn(fade);
					});
				},
				onClose: function(dialog) {
					dialog.data.fadeOut(fade, function () {
							dialog.container.fadeOut(fade);
							dialog.overlay.fadeOut(fade, function() {
								$.modal.close();
							});
					});
				}
			});
		});
	});
	// window events
	$.each(windows,function(index, window) {
		if(readCookie(window)) {
			$('#'+window+'Open').addClass('active');
			$('#'+window+':hidden').show();
		}
	});
	if(readCookie('superplastic')) {
		loadSuperplastic();
	}
	if(readCookie('videos')) {
		loadVideos();
	}
	// customizations
	$('#fx li').click(function() {
		$('#fx li').removeClass('selected');
		var fx = this.getAttribute('class');
		var cookie = readCookie('fx');
		if(fx == "none") {
			fxStop();
		}
		else if(fx != cookie) {
			fxInit(fx);
		}
	});
	// backgrounds
	$('#backgrounds li').click(function() {
		var background = this.getAttribute('class');
		$('#joshua').removeClass().addClass(background);
		createCookie('background', background, expires);
	});
	// miscellaneous
	$('#version tr.major').show(); // version log
	$('#version .toggle').click(function() {
		$(this).remove();
		$('#version tr').show();
		scrollCheck();
	});
	$("a[href^='http']").attr('target','_blank'); // ext. links in new window
	$('#desktop, .tiny').addClass('clearfix'); // floats
	// mouse helpers
	$('.command').off('click');
	$('.command').on('click', function(e){
		var command = $(this).text(),
			e = $.Event('keydown', { which: $.ui.keyCode.ENTER });
		$('#prompt').val(command).trigger(e);
	});
}

// chrome magic
function chromeMagic() {
	// nextgen themes
	if(nextgen) {
		var background = readCookie('background'),
		opacity = readCookie('opacity');
		if(background) $('#joshua').addClass(background);
		if(!opacity) opacity = 1;
		$('#opacity').slider({
			max: 20,
			min: 3,
			value: opacity*20,
			slide: function(event, ui) {
				opacity = ui.value/20;
				$('#joshua, .window').css('opacity', opacity);
			},
			change: function(event, ui) {
				opacity = ui.value/20;
				$('#joshua, .window').css('opacity', opacity);
				createCookie('opacity', opacity, expires);
			}
		});
		$('#joshua, .window').css('opacity', opacity);
		// contra
		if(theme == "contra") {
			$('#joshua h1').html('<b>JOSHUA</b> Konami Edition <span class="dark">30 lives!</span>');
			$('body').animate({backgroundColor:"#fff"}, 250).animate({backgroundColor:"#152521"}, 1000);
		}
	}
	else if(theme == "tron") {
		var team = readCookie('tron.team');
		if(!team) {
			createCookie('tron.team', 'blue', expires);
		}
		else if(team && team != "blue") {
			var colors = ['f570f5','e9000f','f0e53a','a4e750','9a65ff','eb7129'], color = '';
			if(team == "pink") color = colors[0];
			else if(team == "red") color = colors[1];
			else if(team == "yellow") color = colors[2];
			else if(team == "green") color = colors[3];
			else if(team == "purple") color = colors[4];
			else if(team == "orange") color = colors[5];
			team = team.charAt(0).toUpperCase() + team.slice(1);
			var css = 'body {background-image: url("images/backgroundTron'+team+'.jpg")}'+
				'#desktop li a:hover, h1 .dark, h1 a, #input #prompt, .error, .joshua, .window p a, .window table a, .output a, .command, .tiny li:hover, #input, .example {color:#'+color+'; border-color:#'+color+'}'+
				'#desktop li a.active {color:#'+color+'}'+
				'.menu li a.playing, .menu li a.playing:hover {background-color:#'+color+'}'+
				'.close:hover {border-color:#'+color+'}'+
				'.light {color:#'+color+'; opacity:0.5;}';
			$('body').append('<div id="custom">');
			$('#custom').html('<style type="text/css">'+css+'</style>');
		}
		$('.tron .tiny li').click(function() {
			var team = this.getAttribute('class');
			createCookie('tron.team', team, expires);
			location.reload();
		});
		$('#joshua h1 b').html('<img src="images/logoTron.png" height="8" width="71" alt="JOSHUA">');
	}
	else if(theme == "diesel") {
		var dieselChrome = $('#desktop').outerHeight()+120;
		$('#output').css("height", $(window).height()-dieselChrome);
		$(window).resize(function() {
			$('#output').css("height", $(window).height()-dieselChrome);
			scrollCheck();
		});
	}
	else if($.inArray(theme, terminals) > -1) {
		terminal = true;
		if(theme == "pirate") {
			$('#joshua h1').remove();	
		}
		else if(theme == "c64") {
			termPrompt = "Ready.";
			$('#joshua h1').html('**** JOSHUA 64 BASIC V'+version+' ****');			
		}
		$('#presets').prev('h2').remove();
		$('#input').prepend('<span class="prefix">'+termPrompt+'</span>');
		$('#desktop').remove();
	}
	else if(theme == "lcars") {
		$('#joshua h1').html('Joshua <span class="light">LCARS</span>');
		$('#presets').prev('h2').remove();
		$('h1, h2').wrap('<p class="st"/>').wrap('<p class="tng"/>');
		var lcarsChrome = $('#desktop').outerHeight()+145;
		$('#output').css("height", $(window).height()-lcarsChrome);
		$(window).resize(function() {
			$('#output').css("height", $(window).height()-lcarsChrome);
			scrollCheck();
		});
	}
}

// initializer
function init(option) {
	chromeInit();
	if(option && option == "boot") chromeMagic();
	scrollCheck();
	systemReady();
}
function clearScreen() {
	$('#output').html('');
	init();
}

// booting up joshua
function boot() {
	$('#joshua').html('<h1>'+header+'</h1><div id="output"/>').append('<div id="input"/>');
	// upgrading
	var versionCheck = readCookie('release');
	if(parseInt(version) > versionCheck) { // upgrade to latest version
		$('title').html(title+'Upgrading...');
		$.each(windows, function() {
			eraseCookie(this);
			eraseCookie('window.'+this);
		});
		createCookie('theme', defaultTheme, expires);
		createCookie('release', version, expires);
		location.reload();
	}
	// load effects
	var fx = readCookie('fx'); if(fx) fxInit(fx, true);
	// window positions
	$.each(windows,function() {
		var cookie = readCookie('window.'+this),
		theme = readCookie('theme');
		if(cookie) {
			var pos = cookie.split(',');
			$('#'+this).css({
				position: 'absolute',
				left: pos[0],
				right: pos[1],
				top: pos[2]
			});
		}
	});
	// ready prompt
	$('#input').html('<input type="text" id="prompt" autocomplete="off"/>');
	var motd = $('<div class="output"/>').load('joshua.php', {command: "motd", option: "clean"}, function() {
		motd.appendTo('#output');
		init('boot'); // initialize
	});
	stealFocus();
}

// let's go
$(function() {
	boot();
	$('#prompt').on('keydown', function(e) { // key pressed
		$('title').html(title+'Listening...'); // listening to input
		if(e.which == 13) { // enter
			$('title').html(title+'Running...'); // running command
			$('#joshua').css('cursor', 'wait');
			var dump = $(this).val(), // grab the input
			input = dump.split(' '), // split the input
			command = input[0],	option = input[1]; // command (option)
			_gaq.push(['_trackPageview', '/'+command]); // track as a page view in analytics
			if(command) {
				// store history
				hist.push(dump);
				hist.unique();
				position = hist.length;
				$('#loader').fadeIn(100); // loader
				// perform command
				var content = $('<div class="output"/>').load('joshua.php', {command: command, option: option, dump: dump}, function() {
					$('#output').append(content);
					init();
					$('#loader').fadeOut(100);
				});
			}
			else {
				systemReady();
			}
			// clear input
			$("#prompt").val('');
		}
		// access history
		else if(e.which == 38) {
			if(position > 0) { position = position-1; }
			$(this).val(hist[position]);
		}
		else if(e.which == 40) {
			if(position < hist.length) { position = position+1; }
			if(position == hist.length) $(this).val('');
			else $(this).val(hist[position]);
		}
	});
});