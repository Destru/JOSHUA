// konami code
if(!readCookie('konami')) {
	var keySequence = [],
		konamiCode = [38,38,40,40,37,39,37,39,66,65];

	$(document).on('keydown.konami', function(e) {
    keySequence.push(e.keyCode);
    if (keySequence.toString() == konamiCode.toString()) {
			$(document).off('keydown.konami');
			clearCustomizations();
			createCookie('theme', 'contra', cookieExpires);
			createCookie('konami', ' true', cookieExpires);
			location.reload();
    }
    else if (keySequence.length >= konamiCode.length) {
	    keySequence.shift()
    }
	});
}

// cylon
function cylon() {
	var cylonize = $(document).width()-$('#cylon').width();
	$('#cylon').animate({
		opacity: 1,
		marginLeft: '+='+cylonize+'px'
	}, 1500, 'swing', function() {
		$('#cylon').animate({
			marginLeft: '0'
		}, 1500, 'swing', function() {
			cylon();
		});
	});
}

// matrix
function matrix() {
	var matrixOverlayW = window.innerWidth - 20,
		matrixOverlayH = window.innerHeight - 20,
		threadId 	= 1,
		threadCount	= 0,
		threadRemoveId = 1,
		threadMaxCount = 42,
		threadAddSpeed = 500,
		threadAnimateSpeed = 75,
		threadRemoveSpeed = 5000,
		matrixLetters = 'abcdefghijklmnopqrstuvwxyz$+-*/=%\"\'#&(),.;:?!\\|{}<>[]^~';

	function enterMatrix() {
		if(threadCount <= threadMaxCount){
			threadAdd();
			if(threadId == threadMaxCount-1){
				threadRemove();
			}
			setTimeout(enterMatrix, threadAddSpeed);
		}
	}
	function threadAdd() {
		var curFontSize = Math.floor((Math.random()*10)+10),
			startChar	= matrixLetters.charAt(Math.floor(Math.random() * matrixLetters.length)),
			opacity = curFontSize/10,
			opacity	= (opacity > 1) ? opacity : opacity * 0.3,
			left= Math.floor((Math.random()*matrixOverlayW)+1),
			threadHeight= Math.floor((Math.random()*matrixOverlayH));

		$('#matrix').append('<div id="thread'+threadId+'" class="thread" style="height:'+threadHeight+'px;left:'+left+'px;font-size:'+curFontSize+'px;z-index:'+curFontSize+';opacity:'+opacity+';">'+startChar+'</div>');
		threadAnimate(threadId, startChar, curFontSize);
		threadId++;
		threadCount++;
	}
	function threadAnimate(threadId, startChar, curFontSize) {
		var nextChar = matrixLetters.charAt(Math.floor(Math.random() * matrixLetters.length));
		$('#thread'+threadId).prepend(nextChar + '<br>');
		setTimeout(function() {
				threadAnimate(threadId, startChar, curFontSize);
			}, threadAnimateSpeed);
	}
	function threadRemove() {
		threadMoveDown(threadRemoveId);
		threadCount--;
		threadRemoveId++;
		setTimeout(threadRemove, threadRemoveSpeed);
	}
	function threadMoveDown(threadId) {
		$('#thread'+threadId).animate({
			'top': matrixOverlayH+50
		}, threadRemoveSpeed, function() {
			$('#thread'+threadId).remove();
			setTimeout(enterMatrix, threadAddSpeed);
		});
	}

	$('#matrix').css('width', matrixOverlayW);
	$('#matrix').css('height', matrixOverlayH);
	enterMatrix();
}