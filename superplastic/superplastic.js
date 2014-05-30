// constants
var gameWidth = 580,
gameHeight = 340,
refreshRate	= 25,
gracePeriod	= 2000,
missileSpeed = 30;

// animation containers
var playerAnimation = new Array();
var missile = new Array();
var enemy = new Array();

// game states
var playerHit = false,
timeOfRespawn = 0,
hasShot = false,
gameOver = false,
gameStarted = false,
spawnTime = 1000;

// player data
var playerName = "Anonymous",
playerSpeed = 5,
playerSpeedVertical = 5,
playerScore = 0,
playerShield = 3,
playerLives = 1;

// helpers
var seconds = 0,
points = 0;

// helper functions
function explodePlayer(playerNode) {
	playerNode.children().hide();
	playerNode.addSprite("explosion",{animation: playerAnimation["explode"], width: 40, height: 23});
	playerHit = true;
}
function timer() {
	if(!gameOver) {
		seconds = seconds+1;
		if(spawnTime > 200) spawnTime = spawnTime-1; // virtual levels
	}
    setTimeout('timer()', 10);
}
function submitScore() {
	var playerName = $('#playerName').val();
	var saveScore = $('<div id="highscore"/>').load('../joshua.php', {command: "scores", name: playerName, score: playerScore}, function() {
		if(playerName != "Anonymous") createCookie("player", playerName, 1095);
		window.location.reload();
	});
}
function spawnMobs() {
	if(!gameOver) {
		if(Math.random() < 0.25) {
			var name = "enemy1_"+Math.ceil(Math.random()*1000);
			$("#actors").addSprite(name, {animation: enemy[0]["idle"], posx: gameWidth, posy: Math.random()*gameHeight,width: 38, height: 32});
			$("#"+name).addClass("enemy");
			$("#"+name)[0].enemy = new freight($("#"+name));
		}
		else if (Math.random() < 0.45) {
			var name = "enemy2_"+Math.ceil(Math.random()*1000);
			$("#actors").addSprite(name, {animation: enemy[1]["idle"], posx: gameWidth, posy: Math.random()*gameHeight,width: 61, height: 48});
			$("#"+name).addClass("enemy");
			$("#"+name)[0].enemy = new spike($("#"+name));
		}
		else if (Math.random() < 0.75) {
			var name = "enemy3_"+Math.ceil(Math.random()*1000);
			$("#actors").addSprite(name, {animation: enemy[2]["idle"], posx: gameWidth, posy: Math.random()*gameHeight,width: 47, height: 19});
			$("#"+name).addClass("enemy");
			$("#"+name)[0].enemy = new dart($("#"+name));
		}
		else if (Math.random() < 0.99) {
			var name = "enemy4_"+Math.ceil(Math.random()*1000);
			$("#actors").addSprite(name, {animation: enemy[3]["idle"], posx: gameWidth, posy: Math.random()*gameHeight,width: 43, height: 256});
			$("#"+name).addClass("enemy");
			$("#"+name)[0].enemy = new axe($("#"+name));
		}
	}
	setTimeout('spawnMobs()',spawnTime);
}

// objects
function Player(node) {

	this.node = node;
	//this.animations = animations;

	this.gracePeriod = false;
	this.replay = playerLives;
	this.shield = playerShield;
	this.respawnTime = -1;

	// damage or destroy ship
	this.damage = function() {
		if(!this.gracePeriod) {
			this.shield--;
			if (this.shield == 0) {
				return true;
			}
			return false;
		}
		return false;
	};

	// respawn or end game
	this.respawn = function() {
		this.replay--;
		if(this.replay == 0) {
			return true;
		}

		this.gracePeriod = true;
		this.shield	= 5;

		this.respawnTime = (new Date()).getTime();
		$(this.node).fadeTo(0, 0.5);
		return false;
	};
	this.update = function() {
		if((this.respawnTime > 0) && (((new Date()).getTime()-this.respawnTime) > 3000)) {
			this.gracePeriod = false;
			$(this.node).fadeTo(500, 1);
			this.respawnTime = -1;
		}
	}
	return true;
}

function Enemy(node) {
	this.shield	= 1;
	this.speedx	= -8;
	this.speedy = 1;
	this.node = $(node);

	// damage taken
	this.damage = function() {
		this.shield--;
		if(this.shield == 0) {
			return true;
		}
		return false;
	};

	// movement
	this.update = function(playerNode) {
		this.updateX(playerNode);
		this.updateY(playerNode);
	};
	this.updateX = function(playerNode) {
		var newpos = parseInt(this.node.css("left"))+this.speedx;
		this.node.css("left",""+newpos+"px");
	};
	this.updateY= function(playerNode) {
		var newpos = parseInt(this.node.css("top"))+this.speedy;
		this.node.css("top",""+newpos+"px");
	};
}

function freight(node) {
	this.node = $(node);
}
freight.prototype = new Enemy();
freight.prototype.updateY = function(playerNode) {
	if((this.node[0].gameQuery.posy+this.alignmentOffset) > $(playerNode)[0].gameQuery.posy) {
		var newpos = parseInt(this.node.css("top"))-this.speedy;
		this.node.css("top",""+newpos+"px");
	} else if((this.node[0].gameQuery.posy+this.alignmentOffset) < $(playerNode)[0].gameQuery.posy) {
		var newpos = parseInt(this.node.css("top"))+this.speedy;
		this.node.css("top",""+newpos+"px");
	}
}

function spike(node) {
	this.node = $(node);
	this.speedx = -8;
	this.alignmentOffset = 10;
	this.shield = 2;
}
spike.prototype = new freight();

function dart(node) {
	this.node = $(node);
	this.speedx	= -18;
	this.speedy = 2;
	this.alignmentOffset = 2;
}
dart.prototype = new freight();

function axe(node) {
	this.node = $(node);
	this.speedx = -2;
	this.alignmentOffset = 120;
	this.shield = 5;
}
axe.prototype = new freight();

// game framework
$(function() {
	// load highscores
	var result = $('<div id="highscore"/>').load('../joshua.php', {command: "scores"}, function() {
		result.appendTo('#welcome');
	});

	// background
	var background = new Animation({imageURL: "background.png"});
	var background2 = new Animation({imageURL: "background2.png"});
	var background3 = new Animation({imageURL: "background3.png"});
	var background4 = new Animation({imageURL: "background4.png"});

	// player
	playerAnimation["idle"]	= new Animation({imageURL: "player.png"});
	playerAnimation["explode"] = new Animation({imageURL: "player_explode.png", numberOfFrame: 4, delta: 23, rate: 100, type: Animation.VERTICAL} | Animation.CALLBACK);

	// freight
	enemy[0] = new Array();
	enemy[0]["idle"] = new Animation({imageURL: "freight.png"});
	enemy[0]["explode"] = new Animation({imageURL: "freight_explode.png", numberOfFrame: 3, delta: 32, rate: 100, type: Animation.VERTICAL | Animation.CALLBACK});

	// spike
	enemy[1] = new Array();
	enemy[1]["idle"]	= new Animation({imageURL: "spike.png"});
	enemy[1]["explode"] = new Animation({imageURL: "spike_explode.png", numberOfFrame: 3, delta: 48, rate: 100, type: Animation.VERTICAL | Animation.CALLBACK});

	// dart
	enemy[2] = new Array();
	enemy[2]["idle"]	= new Animation({imageURL: "dart.png"});
	enemy[2]["explode"] = new Animation({imageURL: "dart_explode.png", numberOfFrame: 3, delta: 19, rate: 100, type: Animation.VERTICAL | Animation.CALLBACK});

	// axe
	enemy[3] = new Array();
	enemy[3]["idle"]	= new Animation({imageURL: "axe.png"});
	enemy[3]["explode"] = new Animation({imageURL: "axe_explode.png", numberOfFrame: 3, delta: 256, rate: 100, type: Animation.VERTICAL | Animation.CALLBACK});

	// missiles
	missile["player"] = new Animation({imageURL: "player_missile.png"});

	// initialize
	$(document).playground(".gameContainer", {height: gameHeight, width: gameWidth, keyTracker: true});

	// stage
	$().playground().addGroup("background", {width: gameWidth, height: gameHeight})
						.addSprite("stars", {animation: background, width: gameWidth, height: gameHeight})
						.addSprite("stars2", {animation: background2, width: gameWidth, height: gameHeight, posx: gameWidth})
						.addSprite("stars3", {animation: background3, width: gameWidth, height: gameHeight})
						.addSprite("stars4", {animation: background4, width: gameWidth, height: gameHeight, posx: gameWidth})
					.end()
					.addGroup("actors", {width: gameWidth, height: gameHeight})
						.addGroup("player", {posx: gameWidth/2, posy: gameHeight/2, width: 40, height: 23})
							.addSprite("playerBody",{animation: playerAnimation["idle"], posx: 0, posy: 0, width: 40, height: 23})
						.end()
					.end()
					.addGroup("playerMissiles",{width: gameWidth, height: gameHeight}).end()
					.addGroup("overlay",{width: gameWidth, height: gameHeight});

	$("#player")[0].player = new Player($("#player"));

	// heads-up display
	$("#overlay").append('<div id="hud"/><div id="message"/>');

	// start the game
	window.addEventListener('keydown', function(e) {
		if(e.which == 13 && !gameStarted) {
			$().playground().startGame(function() {
				$("#welcome").fadeTo(1000,0,function() {$(this).remove();});
				gameStarted = true;
				timer();
				spawnMobs();
			});
		}
		else if(e.which == 13 && gameOver) submitScore();
	});
	$("#startGame").click(function() {
		$().playground().startGame(function() {
			$("#welcome").fadeTo(1000,0,function() {$(this).remove();});
			timer();
			spawnMobs();
		});
	})

	// game logic
	$().playground().registerCallback(function() {
		if(!gameOver) {
			$("#hud").html('Time: <b>'+seconds+'</b> Points: <b>'+points+'</b> Shield: <b>'+playerShield+'</b>');
			// movement
			if(!playerHit) {
				$("#player")[0].player.update();
				if(jQuery.gameQuery.keyTracker[65]) { // (a)
					var nextpos = parseInt($("#player").css("left"))-playerSpeed;
					if(nextpos > 0) {
						$("#player").css("left", ""+nextpos+"px");
					}
				}
				if(jQuery.gameQuery.keyTracker[68]) { // (d)
					var nextpos = parseInt($("#player").css("left"))+playerSpeed;
					if(nextpos < gameWidth - 100) {
						$("#player").css("left", ""+nextpos+"px");
					}
				}
				if(jQuery.gameQuery.keyTracker[87]) { // (w)
					var nextpos = parseInt($("#player").css("top"))-playerSpeedVertical;
					if(nextpos > 0) {
						$("#player").css("top", ""+nextpos+"px");
					}
				}
				if(jQuery.gameQuery.keyTracker[83]) { // (s)
					var nextpos = parseInt($("#player").css("top"))+playerSpeedVertical;
					if(nextpos < gameHeight - 30) {
						$("#player").css("top", ""+nextpos+"px");
					}
				}
			} else {
				var posy = parseInt($("#player").css("top"))+5;
				var posx = parseInt($("#player").css("left"))-5;
				if(posy > gameHeight) {
					// game over
					if($("#player")[0].player.respawn()) {
						playerScore = seconds+points;
						$("#hud").fadeOut(200);
						$("#actors,#playerMissiles").fadeTo(500,0);
						$("#background").fadeTo(1500,0);
						var cookie = readCookie('player'); if(cookie) playerName = cookie;
						$(".gameContainer").html('<div id="gameOver"><h1>Game over</h1><p>You survived for <b>'+seconds+'</b> miliseconds and scored <b>'+points+'</b> bonus points.<br/>Your total score is <b>'+playerScore+'</b>.</p><div id="submitForm"><label class="arrow_box">Your name</label><input id="playerName" class="text" value="'+playerName+'" maxlength="9" onFocus="this.value=\'\';"/>'+'<div class="button" onClick="submitScore();">Try Again</div></div></div>');
					} else {
						$("#explosion").remove();
						$("#player").children().show();
						$("#player").css("top", gameHeight / 2);
						$("#player").css("left", gameWidth / 2);
						playerHit = false;
					}
				} else {
					$("#player").css("top", ""+ posy +"px");
					$("#player").css("left", ""+ posx +"px");
				}
			}

			// enemy move
			$(".enemy").each(function() {
					this.enemy.update($("#player"));
					var posx = parseInt($(this).css("left"));
					if((posx + 100) < 0) {
						$(this).remove();
						return;
					}
					// collision test
					var collided = $(this).collision("#playerBody,.group");
					if(collided.length > 0) {
						if(this.enemy instanceof spike) {
							$(this).setAnimation(enemy[1]["explode"], function(node) {$(node).remove();});
							$(this).css("width", 61);
						} else if (this.enemy instanceof dart) {
							$(this).setAnimation(enemy[2]["explode"], function(node) {$(node).remove();});
							$(this).css("width", 47);
						} else if (this.enemy instanceof axe) {
							$(this).setAnimation(enemy[3]["explode"], function(node) {$(node).remove();});
							$(this).css("width", 43);
						} else {
							$(this).setAnimation(enemy[0]["explode"], function(node) {$(node).remove();});
							$(this).css("width", 38);
						}
						$(this).removeClass("enemy");
						// player was hit
						$('#message').fadeIn(50).fadeOut(150);
						if($("#player")[0].player.damage()) {
							explodePlayer($("#player"));
						}
						playerShield = playerShield-1;
					}
				});

			// missile movement
			$(".playerMissiles").each(function() {
				var posx = parseInt($(this).css("left"));
				if(posx > gameWidth) {
					$(this).remove();
					return;
				}
				$(this).css("left", ""+(posx+missileSpeed)+"px");
				// collisions
				var collided = $(this).collision(".group,.enemy");
				if(collided.length > 0) {
					// enemy was hit!
					collided.each(function() {
							if($(this)[0].enemy.damage()) {
								if(this.enemy instanceof spike) {
									$(this).setAnimation(enemy[1]["explode"], function(node) {$(node).remove();});
									$(this).css("width", 61);
									points = points+50;
								} else if (this.enemy instanceof dart) {
									$(this).setAnimation(enemy[2]["explode"], function(node) {$(node).remove();});
									$(this).css("width", 47);
									points = points+50;
								} else if (this.enemy instanceof axe) {
									$(this).setAnimation(enemy[3]["explode"], function(node) {$(node).remove();});
									$(this).css("width", 43);
									points = points+100;
								} else {
									$(this).setAnimation(enemy[0]["explode"], function(node) {$(node).remove();});
									$(this).css("width", 38);
									points = points+25;
								}
								$(this).removeClass("enemy");
							}
							$(this).fadeOut(20).fadeIn(100);
						})
					$(this).setAnimation(missile["playerexplode"], function(node) {$(node).remove();});
					$(this).css("width", 40);
					$(this).css("height", 23);
					$(this).css("top", parseInt($(this).css("top"))-7);
					$(this).removeClass("playerMissiles");
				}
			});
		}
	}, refreshRate);

	// background animation
	$().playground().registerCallback(function() {
		var newPos = (parseInt($("#stars").css("left")) - 10 - gameWidth) % (-2 * gameWidth) + gameWidth;
		$("#stars").css("left", newPos);
		var newPos = (parseInt($("#stars2").css("left")) - 10 - gameWidth) % (-2 * gameWidth) + gameWidth;
		$("#stars2").css("left", newPos);
		var newPos = (parseInt($("#stars3").css("left")) - 6 - gameWidth) % (-2 * gameWidth) + gameWidth;
		$("#stars3").css("left", newPos);
		var newPos = (parseInt($("#stars4").css("left")) - 6 - gameWidth) % (-2 * gameWidth) + gameWidth;
		$("#stars4").css("left", newPos);
	}, refreshRate);

	// keyhandling
	$(document).keydown(function(e) {
		if(!gameOver && !playerHit && !hasShot) {
			switch(e.keyCode) {
				case 75: // shoot
					hasShot = true;
					var playerposx = parseInt($("#player").css("left"));
					var playerposy = parseInt($("#player").css("top"));
					var name = "playerMissle_"+Math.ceil(Math.random()*1000);
					$("#playerMissiles").addSprite(name,{animation: missile["player"], posx: playerposx + 42, posy: playerposy + 11, width: 5, height: 1});
					$("#"+name).addClass("playerMissiles")
				break;
			}
		}
	});

	// stop shot spams
	setInterval(function() {
		hasShot = false;
	}, 450);

});