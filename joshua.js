var fadeDelay = 250,
	cookieExpires = 365,
	history =  [],
	historyPosition = 0,
	muted = false,
	drawing = false,
	nextgen = false,
	terminal = false,
	terminals = ['pirate', 'helvetica', 'mono', 'c64'],
	windows = ['customize', 'music', 'superplastic', 'videos', 'gallery'];
if (theme == "nextgen" || $.inArray(theme, nextgenThemes) > -1) nextgen = true;
if (nextgen) windows.push('joshua');

// helpers
function reset() {
	eraseCookie('joshua');
	eraseCookie('theme');
	clearCustomizations();
	$.each(windows, function() {
		eraseCookie(this);
		eraseCookie('window.'+this);
	});
	location.reload();
}

function stealFocus() {
	$('#prompt').focus();
}

function systemReady() {
	$('title').text(title+'Ready');
	$('body').css('cursor', 'auto');
}

function overflowHelper() {
	var output = $('#output');
	if (output.height() < output.get(0).scrollHeight) {
		output.addClass('overflow');
	}
	else {
		output.removeClass('overflow');
	}
}

function resizeHelper(chrome) {
	$('#output').css("height", $(window).height()-chrome);
	$(window).resize(function() {
		$('#output').css("height", $(window).height()-chrome);
		scrollCheck();
	});
}

function historyHelper() {
	var historyCookie = readCookie('history');
	if (historyCookie) {
		history = historyCookie.split(',');
		historyPosition = history.length;
	}
}

function scrollCheck() {
	if (terminal) {
		$('html, body').stop();
		$('html, body').animate({scrollTop: $(document).height()}, 250);
		$('.output:last .prompt').prepend('<span class="prefix">'+termPrompt+'</span>');
	}
	else {
		$('#output').stop();
		$('#output').animate({scrollTop: $('#output').prop('scrollHeight')}, 250);
	}
	overflowHelper();
}

function keyboardHelpers() {
	$(window).on('keypress', function(){
		if (!$(document.activeElement).is(':input')) stealFocus();
	});
}

function mouseHelpers() {
	$(document).on('click', '.command, .example', function(e){
		var command = $(this).text(),
			e = $.Event('keydown', { which: $.ui.keyCode.ENTER });
		$('#prompt').val(command).trigger(e);
		stealFocus();
	});
}

function mute() {
	if (!muted) {
		soundManager.mute();
		muted = true;
	}
	else {
		soundManager.unmute();
		muted = false;
	}
	systemReady();
}

function clearCustomizations() {
	eraseCookie('background');
	eraseCookie('fx');
	eraseCookie('opacity');
	eraseCookie('hue');
	eraseCookie('saturation');
	eraseCookie('history');
}

// effects
function fxStop() {
	var cookie = readCookie('fx');
	if (cookie) {
		$('[data-custom="effects"] li').removeClass('active');
		$('.spark, #malkovich, .brush, #cylon, #matrix').remove();
		$('html').removeClass();
		if (cookie == "draw") {
			$(document).off('mousedown');
			$(document).off('mouseup');
			$(document).off('mousemove');
		}
		else if (cookie == "malkovich") {
			$(document).off('mousemove');
		}
		eraseCookie('fx');
	}
}

function fxInit(fx, runOnce) {
	if (!runOnce) {
		fxStop();
		createCookie('fx', fx, cookieExpires);
	}
	if (fx == "sparks") {
		var totalSparks = 42;
		var sparks = [];
		for (i = 0; i < totalSparks; i++) {
			sparks[i] = new Spark(Math.floor(Math.random()*10));
		}
	}
	else if (fx == "malkovich") {
		$('#wrapper').append('<div id="malkovich"/>');
		$(document).on('mousemove', function(e) {
			$('#malkovich').css({
				top: (e.pageY+10)+'px',
				left: (e.pageX+15)+'px'
			});
			$('#malkovich:hidden').fadeIn(fadeDelay);
		});
	}
	else if (fx == "pulsar" || fx == "drunk" || fx == "hipster" || fx == "invert") {
		$('html').addClass(fx);
	}
	else if (fx == "draw") {
		var brush;
		$('body').css('-webkit-user-select', 'none');
		$(document).mousedown(function() {
			drawing = true;
		});
		$(document).mouseup(function() {
			drawing = false;
		});
		$(document).mousemove(function(e) {
			if (drawing) {
				brush = $('<div/>').addClass('brush').hide();
				$(document.body).append(brush);
				brush.css({
					top: e.pageY-12,
					left: e.pageX-12
				}).show();
			}
		});
	}
	else if (fx == "ultraviolence") {
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
	else if (fx == "cylon") {
		$('#wrapper').prepend('<div id="cylon"/>');
		cylon();
	}
	else if (fx == "matrix") {
		$('#wrapper').prepend('<div id="matrix"/>');
		matrix();
	}
	$('[data-effect='+fx+']').addClass('active');
}

// applications
function loadSuperplastic() {
	createCookie('superplastic', true, cookieExpires);
	if ($('#superplastic').has('iframe').length == 0) {
		$('#superplastic').append('<iframe class="gameFrame" src="superplastic/index.html" width="580" height="340" frameborder="0" scrolling="no">')
	}
	else $('#superplastic iframe').attr("src", $('#superplastic iframe').attr("src"));
	$('#superplastic:hidden').fadeIn(fadeDelay);
	systemReady();
}

function loadVideos() {
	createCookie('videos', true, cookieExpires);
	$('#videos:hidden').fadeIn(fadeDelay);
	systemReady();
}

// chrome
function chromeInit() {
	$.each(windows, function() {
		var $window = $('#'+this),
			windowName = this;
		$window.draggable({
			distance: 10,
			handle: 'h1',
			stop: function(event) {
				var left = $(this).css('left'),
					right = $(this).css('right'),
					top = $(this).css('top');
				createCookie('window.'+windowName, left+','+right+','+top, cookieExpires);
			}
		}).find('h1').append('<a class="close">x</a>');
		if (readCookie(windowName)) {
			$('[data-window="'+windowName+'"]').addClass('active');
			$window.show();
		}
	});
	$(document).on('click', '.close', function() {
		var id = $(this).closest('div').attr('id');
		eraseCookie(id);
		$('#'+id+':visible').fadeOut(fadeDelay);
		if (id == "superplastic") {
			$('#'+id+' iframe').remove();
		}
		else if (id == "music") if (!muted) mute();
		$('[data-window="'+id+'"]').removeClass('active');
		stealFocus();
	});
	$(document).on('click', '[data-window]', function() {
		var button = $(this);
			id = button.data('window');
		if (button.hasClass('active')) {
			button.removeClass('active');
			eraseCookie(id);
			$('#'+id).fadeOut(fadeDelay);
			if (!muted) mute();
		}
		else {
			if (id == "superplastic") loadSuperplastic();
			else if (id == "videos") loadVideos();
			else {
				createCookie(id, true, cookieExpires);
				if (id == "music") if (muted) mute();
			}
			$('#'+id).fadeIn(fadeDelay);
			button.addClass('active');
		}
	});
	$(document).on('click', '.view', function(e) {
		$('#loader').fadeIn(fadeDelay);
		e.preventDefault();
		var imageSource = $(this).attr('href');
		$("<img/>").attr("src", imageSource).load(function() {
			$('#loader').fadeOut(fadeDelay);
			$('<div class="modalClose"><img src="'+imageSource+'" width="'+this.width+'" height="'+ this.height+'"/></div>').modal({
				overlayId: 'modalOverlay',
				containerId : 'modalContainer',
				dataId: 'modalData',
				closeClass: 'modalClose',
				overlayClose: true,
				onOpen: function(dialog) {
					dialog.overlay.fadeIn(fade, function() {
						dialog.container.fadeIn(fadeDelay);
						dialog.data.fadeIn(fadeDelay);
					});
				},
				onClose: function(dialog) {
					dialog.data.fadeOut(fade, function () {
							dialog.container.fadeOut(fadeDelay);
							dialog.overlay.fadeOut(fade, function() {
								$.modal.close();
							});
					});
				}
			});
		});
	});
	$(document).on('click', '[data-effect]', function() {
		if ($(this).hasClass('active')) fxStop();
		else fxInit($(this).data('effect'));
	});
	if (readCookie('superplastic')) loadSuperplastic();
	if (readCookie('videos')) loadVideos();
}

function initSliders() {
	var opacity = readCookie('opacity') || 1,
		hue = readCookie('hue') || 360,
		saturation = readCookie('saturation') || 100;
	$('[data-slider="opacity"]').slider({
		max: 20,
		min: 3,
		value: opacity*20,
		slide: function(event, ui) {
			opacity = ui.value/20;
			$('#joshua, .window').css('opacity', opacity);
			createCookie('opacity', opacity, cookieExpires);
		}
	});
	$('#joshua, .window').css('opacity', opacity);
	$('[data-slider="hue"]').slider({
		max: 360,
		min: 0,
		value: hue,
		slide: function(event, ui) {
			$('html').css('-webkit-filter',
				'hue-rotate('+ui.value+'deg) saturate('+saturation+'%)');
			createCookie('hue', ui.value, cookieExpires);
		}
	});
	$('[data-slider="saturation"]').slider({
		max: 200,
		min: 0,
		value: saturation,
		slide: function(event, ui) {
			$('html').css('-webkit-filter',
				'hue-rotate('+hue+'deg) saturate('+ui.value+'%)');
			createCookie('saturation', ui.value, cookieExpires);
		}
	});
	$('html').css('-webkit-filter',
		'hue-rotate('+hue+'deg) saturate('+saturation+'%)');
}

function chromeMagic() {
	if (nextgen || theme == "tron") {
		initSliders();
	}
	if (nextgen) {
		var b = readCookie('background');
		if (b) $('#joshua').addClass(b);
		$('[data-background="'+b+'"]').addClass('active');
		$(document).on('click', '[data-background]', function() {
			var b = $(this).data('background');
			if ($(this).hasClass('active')) {
				$('#joshua').removeClass(b);
				$(this).removeClass('active');
				eraseCookie('background');
			}
			else {
				$('#joshua').removeClass().addClass(b);
				$('[data-background]').removeClass('active');
				$('[data-background="'+b+'"]').addClass('active');
				createCookie('background', b, cookieExpires);
			}
		});
		if (theme == "contra") {
			$('html').addClass('contra');
			$('#joshua h1').html('<b>JOSHUA</b> Konami Edition <span class="dark">30 lives!</span>');
		}
	}
	else if (theme == "tron") {
		$('#joshua h1 b').html('<img src="images/logoTron.png" height="8" width="71" alt="JOSHUA">');
	}
	else if (theme == "diesel") {
		resizeHelper(245);
	}
	else if ($.inArray(theme, terminals) > -1) {
		terminal = true;
		if (theme == "pirate") {
			$('#joshua h1').remove();
		}
		else if (theme == "c64") {
			termPrompt = "Ready.";
			$('#joshua h1').html('**** JOSHUA 64 BASIC V'+version+' ****');
		}
		$('#input').prepend('<span class="prefix">'+termPrompt+'</span>');
		$('#desktop').remove();
	}
	else if (theme == "lcars") {
		$('#joshua h1').html('Joshua <span class="light">LCARS</span>');
		$('h1, h2').wrap('<p class="st"/>').wrap('<p class="tng"/>');
		resizeHelper(205);
	}
	else if (theme == "neocom") {
		$('#wrapper').prepend('<div id="nebula"><img src="images/backgroundNeocom.jpg"></div>');
		$('#desktop').prepend('<a href="/"><div id="neocom"><img src="images/logoNeocom.png" width="20" height="20" alt="JOSHUA"></div></a>');
		resizeHelper(88);
	}
}

// boot
function boot() {
	var fx = readCookie('fx');
	$('#loader').fadeIn(fadeDelay);
	$.each(windows,function() {
		var cookie = readCookie('window.'+this);
		if (cookie) {
			var pos = cookie.split(',');
			$('#'+this).css({
				position: 'absolute',
				left: pos[0],
				right: pos[1],
				top: pos[2]
			});
		}
	});
	var motd = $('<div class="output"/>').load('joshua.php', {command: "motd", option: "inline"}, function() {
		motd.appendTo('#output');
		chromeInit();
		chromeMagic();
		if (fx) fxInit(fx, true);
	});
	historyHelper();
	keyboardHelpers();
	mouseHelpers();
	stealFocus();
	$('#loader').fadeOut(fadeDelay);
}

$(function() {
	boot();
	$('#prompt').on('keydown', function(e) {
		$('title').html(title+'Listening...');
		if (e.which == 13) {
			$('title').html(title+'Running...');
			$('body').css('cursor', 'wait');
			var dump = $(this).val(),
			input = dump.split(' '),
			command = input[0],	option = input[1];
			if (command) {
				$('#loader').fadeIn(fadeDelay);
				var content = $('<div class="output"/>').load('joshua.php', {command: command, option: option, dump: dump}, function() {
					$('#output').append(content);
					if (typeof ga == 'function') ga('send', 'pageview', '/'+command);
					scrollCheck();
					systemReady();
					$('#loader').fadeOut(fadeDelay);
				});
				history.push(dump);
				history = history.filter(function(e,i,a){if(i==a.indexOf(e))return 1;return 0});
				historyPosition = history.length;
				createCookie('history', history, cookieExpires);
			}
			else {
				systemReady();
			}
			$("#prompt").val('');
		}
		else if (e.which == 38) {
			e.preventDefault();
			if (historyPosition > 0) historyPosition = historyPosition - 1;
			$(this).val(history[historyPosition]);
		}
		else if (e.which == 40) {
			if (historyPosition < history.length) historyPosition = historyPosition + 1;
			if (historyPosition == history.length) $(this).val('');
			else $(this).val(history[historyPosition]);
		}
	});
});
