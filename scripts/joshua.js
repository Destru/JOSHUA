// joshua (jquery operating system)
// http://binaerpilot.no/alexander/
// alexander@binaerpilot.no
var header = '<strong>Joshua</strong> <span id="version"/> <span class="dark">Diesel Edition</span>',
title = ' > JOSHUA (jQuery Operating System)',
terminalPrompt = '<strong>Guest</strong>@Joshua/>',
hist = [], // history (arrow up/down)
position = 0, // position in history
expires = 1095, // cookie dies in 3 years
fade = 500, // ui fade delay
muted = false, // sound
drawing = false, // drawing?
terminal = false, // terminal style layout
nextGen = ['carolla', 'contra', 'penguin', 'white'],
windows = ['config', 'music', 'alexander'],
custom = ['gallery', 'superplastic', 'desktop'],
allWindows = $.merge(windows, custom);

// helpers
function systemReady() {
	$('title').text('Ready'+title);
	$('#joshua').css('cursor', 'auto');
}
function stealFocus() {
	$('#joshua').click(function() {
		$('#prompt').focus();
	});
	$('#prompt').focus();
}
function loseFocus() {
	$('#prompt').blur();
	setTimeout(function() {
		$('#prompt').blur();
	}, 50);
}
function clearInput() {
	$('#prompt').blur().val('');
	setTimeout(function() {
		$('#prompt').focus();
	}, 50);
}
function scrollCheck() {
	if(terminal) {
		$('body').attr({ scrollTop: $("body").attr("scrollHeight") });
		$('.output:last .prompt').prepend(terminalPrompt);
	}
	else {
		var autoScroll = $('#output').data('jScrollPanePosition') == $('#output').data('jScrollPaneMaxScroll');
		$('#output').jScrollPane({
			scrollbarWidth:10,
			scrollbarMargin:10,
			enableKeyboardNavigation:false,
			animateTo: true,
			animateInterval:25
		});
		if (autoScroll) {
			$('#output')[0].scrollTo($('#output').data('jScrollPaneMaxScroll'));
		}
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
function timer(time) {
	if(time > 0) {
		var remaining = time / 60000;
		if(remaining > 1) {
			$('#output').append('<div class="output"><p><strong>Timer:</strong> There are '+remaining+' minutes remaining.</p></div>');								
		}
		else {
			$('#output').append('<div class="output"><p><strong>Timer:</strong> There is '+remaining+' minute remaining.</p></div>');
		}
		time = time - 60000;
	    setTimeout('timer('+time+')', 60000);
	}
	else {
		alert('This is an alarm. You are supposed to do something now.');
	}
	scrollCheck();
	systemReady();
}

// effects
function fxStop() {
	var cookie = readCookie('fx');
	if(cookie) {
		$('.spark, #malkovich, .brush').remove();
		if(cookie != "none") {
			eraseCookie('fx');
		}
		if(cookie == "pulsar" || cookie == "spin") {
			location.reload();
		}
		if(cookie == "draw") {
			$(document).unbind('mousedown');
			$(document).unbind('mouseup');
			$(document).unbind('mousemove');
		}
	}
}
function fxInit(fx, temp) {
	if(!temp) {
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
	else if(fx == "spin") {
		$('#joshua, .window').spin(10);
	}
	else if(fx == "pulsar") {
		pulsar();
		setInterval(pulsar,30000);
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
		$('body').append('<div id="ultraviolence" class="overlay"/>');
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
}

// themes
function loadTheme(theme, boot) {
	if(!boot) {
		createCookie('theme', theme, expires);
		location.reload();
	}
	else {
		// nextgen
		if($.inArray(theme, nextGen) > -1) {
			$("head").append("<link>");
				css = $("head").children(":last");
				css.attr({
				  rel:  'stylesheet',
				  type: 'text/css',
				  href: 'themes/nextgen.css'
			});
		}
		// load theme
		$("head").append("<link>");
		    css = $("head").children(":last");
		    css.attr({
		      rel:  'stylesheet',
		      type: 'text/css',
		      href: 'themes/'+theme+'.css'
		});
	}
}

// presets
function loadPreset(preset) {
	if(preset == "gamer") {
		createCookie('theme', 'carolla', expires);
		createCookie('background', 'atari', expires);
		createCookie('fx', 'sparks', expires);
		eraseCookie('desktop');
	}
	else if(preset == "rachael") {
		createCookie('theme', 'penguin', expires);
		createCookie('background', 'rachael', expires);
		eraseCookie('fx');
		eraseCookie('desktop');
	}
	else if(preset == "pulsar") {
		createCookie('theme', 'white', expires);
		eraseCookie('background');
		createCookie('fx', preset, expires);
		eraseCookie('desktop');
	}
	else if(preset == "identity") {
		createCookie('theme', 'tron', expires);
		eraseCookie('background');
		eraseCookie('fx');
		eraseCookie('desktop');	
	}
	else if(preset == "reset") {
		eraseCookie('theme');
		eraseCookie('background');
		eraseCookie('fx');
		eraseCookie('desktop');
		eraseCookie('release');
		eraseCookie('tron.team');
		$.each(windows,function() {
			eraseCookie(this);
			eraseCookie('window.'+this);
		});
	}
	location.reload();
}

// config window
function loadConfig() {
	// themes
	$('div#themes div').click(function() {
		var theme = this.getAttribute('class');
		createCookie('theme', theme, expires);
		loadTheme(theme);
	});
	// backgrounds
	$('div#backgrounds div').click(function() {
		var background = this.getAttribute('class');
		$('#joshua').removeClass().addClass(background);
		createCookie('background', background, expires);
	});
	// effects
	$('div#fx div').click(function() {
		$('#fx div').removeClass('selected');
		var fx = this.getAttribute('class');
		var cookie = readCookie('fx');
		if(fx == "none") {
			fxStop();
		}
		else if(fx != cookie) {
			fxInit(fx);
		}
	});
	// miscellaneous
	$('div#presets div').click(function() {
		var preset = this.getAttribute('class');
		loadPreset(preset);
	});
}

// application loaders
function loadSuperplastic() {
	$('#superplastic').append('<iframe class="gameFrame" src="superplastic/index.html" width="580" height="340" frameborder="0" scrolling="no"/>')
	$('#superplastic:hidden').fadeIn(fade);
	loseFocus();
	systemReady();
}

// chrome magic
function chromeMagic() {
	var theme = readCookie('theme');
	// nextgen
	if($.inArray(theme, nextGen) > -1) {
		var background = readCookie('background'),
		opacity = readCookie('opacity');
		if(background) {
			$('#joshua').addClass(background);
		}
		if(!opacity) {
			opacity = 1;
		}
		
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
	}
	else if(theme == "tron") {
		var team = readCookie('tron.team');
		if(!team) {
			createCookie('tron.team', 'blue', expires);
		}
		else if(team && team != "blue") {
			var colors = ['f570f5','e9000f','f0e53a','a4e750','9a65ff', 'eb7129'], color = ''; 
			if(team == "pink") { color = colors[0]; }
			else if(team == "red") { color = colors[1]; }
			else if(team == "yellow") { color = colors[2]; }
			else if(team == "green") { color = colors[3]; }
			else if(team == "purple") { color = colors[4]; }
			else if(team == "orange") { color = colors[5]; }
			team = team.charAt(0).toUpperCase() + team.slice(1);
			var css = 'body {background-image: url("images/backgroundTron'+team+'.jpg")}'+
				'h1 .dark, #input #prompt, .error, .joshua, .window p a, .window table a, .output a, .command, .tiny div:hover, .close:hover, #desktop ul li a:hover, #input {color:#'+color+'; border-color:#'+color+'}'+
				'.tracks li a.playing, .tracks li a.playing:hover {background-color:#'+color+'}'+
				'.light {color:#'+color+'; opacity:0.5;}';
			$('body').append('<div id="custom"/>');
			$('#custom').html('<style type="text/css">'+css+'</style>');
		}
		$('div.tron div.tiny div').click(function() {
			var team = this.getAttribute('class');
			createCookie('tron.team', team, expires);
			location.reload();
		});
		$('#joshua h1 strong').html('<img src="images/logoTron.png" height="8" width="71" alt="" />');
	}
	else if(theme == "contra") {
		$('#joshua h1').html('<em>Joshua</em> Konami Edition <span class="dark">30 lives!</span>');
		$('body').animate({delay: 1}, 750).animate({backgroundColor:"#fff"}, 500).animate({backgroundColor:"#152521"}, 3500);
	}
	else if(theme == "diesel") {
		$('#joshua h1').html('<div id="header"><img src="images/logoDiesel.png" alt=""/></div>');
		var dieselChrome = 271;
		$('#output').css("height", $(window).height()-dieselChrome);
		$(window).resize(function() {
			$('#output, .jScrollPaneContainer').css("height", $(window).height()-dieselChrome);
			scrollCheck();
		});
	}
	else if(theme == "helvetica" || theme == "pirate") {
		terminal = true;
		if(theme == "helvetica") {
			terminalPrompt = "<strong>Guest@Joshua:</strong>&nbsp;";
		}
		else {
			$('#joshua h1').remove();
		}
		$('#presets').prev('h2').remove();
		$('#input').prepend('<div class="prefix">'+terminalPrompt+'</div>');
		$('#desktop #links').remove();
		$('#desktop').remove();
	}
	else if(theme == "lcars") {
		$('#joshua h1').html('Joshua <span class="light">LCARS</span>');
		$('h1, h2').wrap('<p class="st"/>').wrap('<p class="tng"/>');
		var lcarsChrome = 242;
		$('#output').css("height", $(window).height()-lcarsChrome);
		$(window).resize(function() {
			$('#output, .jScrollPaneContainer').css("height", $(window).height()-lcarsChrome);
			scrollCheck();
		});
	}
	// miscellaneous cosmetic fixes
	$('div.tiny, #desktop').append('<br class="clear"/>');
}

// init chrome
function chromeInit() {
	// drag windows
	$('.window').draggable({
		distance:10,
		stop: function(event) {
			var window = 'window.'+$(this).attr('id'),
			left = $(this).css('left'),
			right = $(this).css('right'),
			top = $(this).css('top');
			createCookie(window, left+','+right+','+top, expires);
		}
	});
	// x marks the spot
	$('.window h1:not(:has(.close))').append('<a class="close">x</a>');
	// close windows
	$('.close').click(function() {
		var id = $(this).closest("div").attr("id");
		eraseCookie(id);
		$('#'+id+':visible').fadeOut(fade);
		if(id == "superplastic") {
			$('#'+id+' iframe').attr('src','');
			var fx = readCookie('fx');
			if(fx) { fxInit(fx); }
		}
		else if(id == "music") {
			if(!muted) { mute(); }
		}
		$('#'+id+'Open').removeClass('active');
		stealFocus();
	});
	// open windows
	$('.open').click(function() {
		var id = this.getAttribute('id').replace(/Open/,'');
		createCookie(id,'true',expires);
		if(id == "superplastic") {
			loadSuperplastic();
		}
		else if(id == "music") {
			if(muted) {
				mute();
			}
		}
		$('#'+id+':hidden').fadeIn(fade);
		$(this).addClass('active');
	});
	// fancybox
	 $('a.view').fancybox({
		'overlayShow': false,
		'hideOnContentClick': true,
		'showNavArrows':false,
		'showCloseButton':false,
		'padding': 10,
		'zoomSpeedIn': 300,
		'zoomSpeedOut': 300
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
	// konami
	if(readCookie('konami')) {
		$('div.contra').css({display:'block'});
	}
	// config window
	loadConfig();
	// miscellaneous
	$('.version tr.major').show(); // version log
	$('.version .toggle').click(function() {
		$(this).remove();
		$('.version tr').show();
		scrollCheck();
	});
	$("a[href^='http']").attr('target','_blank'); // ext. links in new window
	chromeMagic();
}

// initializer
function init() {
	chromeInit();
	scrollCheck();
	stealFocus();
	systemReady();
}
function clearScreen() {
	$('#output').html('<div class="clearFix"/>');
	init();
}

// booting up joshua
function boot() {
	$('#joshua').html('<h1>'+header+'</h1><div id="output"/>').append('<div id="input"/>');
	// upgrading users to latest edition
	$('#version').load('joshua.php', {command: "version", option: "clean"}, function(version) {
		var versionCheck = readCookie('release');
		if(version > versionCheck) {
			$('title').html('Upgrading'+title);
			eraseCookie('background');
			eraseCookie('config');
			eraseCookie('superplastic');
			eraseCookie('music');
			eraseCookie('gallery');
			eraseCookie('fx');
			eraseCookie('alexander');
			$.each(allWindows,function() {eraseCookie('window.'+this);});
			createCookie('theme', 'diesel', expires);
			createCookie('desktop', 'true', expires);
			createCookie('release', version, expires);
			location.reload();
		}
	});
	// load base settings
	var theme = readCookie('theme'),
	fx = readCookie('fx');
	if(theme) { loadTheme(theme, true); }
	if(fx) { fxInit(fx, true); }
	// load quote
	var pearl = $('<p class="pearl"/>').load('joshua.php', {command: "pearl", option: "clean"}, function() {
		pearl.appendTo('#pearls');
	});
	// window positions
	$.each(allWindows,function() {
		var cookie = readCookie('window.'+this);
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
	var motd = $('<div class="output"/>').load('joshua.php', {command: "motd"}, function() {
		motd.appendTo('#output');
		init(); // boot sequence finished, initialize JOSHUA
	});
}

// let's go
$(function() {
	boot();
	$('#prompt').keydown(function(e) { // key pressed
		$('title').html('Listening'+title); // listening to input
		if(e.which == 13) { // command issued with enter
			$('title').html('Running'+title); // running command
			$('#joshua').css('cursor', 'wait'); // loading
			var dump = $(this).val(), // grab the input
			input = dump.split(' '), // split the input
			command = input[0],	option = input[1]; // command (option)
			// store history
			if(command) {
				hist.push(dump);
				hist.unique();
				position = hist.length;
			}
			// js commands
			if(command == "clear") { clearScreen(); }
			else if(command == "exit" || input == "quit" || input == "logout") { window.location = "http://binaerpilot.no"; }
			// rachael
			else if(command == "rachael") {
				var current = new Date();
				var currentYear = current.getFullYear();
				var birthday = new Date(currentYear, 6-1, 29);
				var married = new Date(2009, 10-1, 7);
				if(current > birthday) {
					birthday = new Date(currentYear+1, 6-1, 29);
				}
				$('#output').append('<div class="output"><div class="prompt">rachael</div><p>Rachael is the most beautiful girl in the world. It\'s a scientific fact. Yes, I am a scientist. We\'ve been happily married for <span class="countdown married pink"/>, her birthday is in <span class="countdown birthday pink"/> and I am still madly in love. You can <a href="http://rachaelivy.com">visit her homepage</a> if you\'d like to know more. (Potential stalkers be warned: I carry an axe.)</p></div>');
				$('.birthday').countdown({until: birthday, compact: true, format: 'OWDHMS'});
				$('.married').countdown({since: married, compact: true, format: 'OWDHMS'});
				scrollCheck();
				systemReady();
			}
			// quit smoking
			else if(command == "smoking") {
				var quit = new Date(2010, 10-1, 1, 13, 37);
				$('#output').append('<div class="output"><div class="prompt">smoking</div><p>After having this nasty habit for 13 years, I\'ve been smoke free for <span class="countdown smoking light"/>. Huzzah!</p></div>');
				$('.smoking').countdown({since: quit, compact: true, format: 'OWDHMS'});
				scrollCheck();
				systemReady();
			}
			// timer
			else if(command == "timer") {
				if(option && parseInt(option, 0)) {
					timer(option*60000);
				}
				else {
					$('#output').append('<div class="output"><div class="prompt">timer</div><p>You need to specify length (in minutes).</p></div>');
				}
				scrollCheck();
				systemReady();
			}
			// windows
			else if(command == "music" || command == "config" || command == "alexander" || command == "gallery") {
				createCookie(command,'true',expires);
				$('#'+command+':hidden').fadeIn(fade);
				systemReady();
			}
			// superplastic
			else if(command == "superplastic") { loadSuperplastic(); }
			// desktop
			else if(command == "desktop") {
				var cookie = readCookie('desktop');
				if(cookie) {
					$('#desktop').fadeOut(fade);
					eraseCookie('desktop');
				}
				else {
					$('#desktop:hidden').fadeIn(fade);
					createCookie('desktop', 'true', expires);
				}
				systemReady();
			}
			else if(command == "mute") { mute(); }
			else if(command == "reset")	{ loadPreset('reset'); }
			// come on my droogs
			else if(command == "ultraviolence") { fxInit('ultraviolence', true) }
			// engine
			else {
				if(command) {
					var content = $('<div class="output"/>').load('joshua.php', {command: command, option: option, dump: dump}, function() {
						$('#output').append(content);
						init();
					});
				}
				else {
					systemReady();
				}
			}
			// clear input data
			$("#prompt").val('');
		}
		// access history
		else if(e.which == 38) {
			if(position > 0) { position = position-1; }
			$(this).val(hist[position]);
		}
		else if(e.which == 40) {
			if(position < hist.length) { position = position+1; }
			if(position == hist.length) {
				$(this).val('');
			}
			else {
				$(this).val(hist[position]);
			}
		}
	});
});