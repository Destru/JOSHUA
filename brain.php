<?php // joshua brain <alexander@binaerpilot.no>
// brain cells (modules)
$d = scandir(".");
foreach($d as $file) {
	if(stristr($file, 'cell.')) include $file;
}
// static information
$static = array(
'help' => '
	<table id="help">
		<tr>
			<td><span class="command">about</span></td>
			<td>Who, what, when, where?</td>
		</tr>
		<tr>
			<td><span class="command">superplastic</span></td>
			<td>My first attempt at game design; A tiny space shooter!</td>
		</tr>
		<tr>
			<td><span class="command">numbers</span></td>
			<td>A text-based game where all the answers are numbers</td>
		</tr>
		<tr>
			<td><span class="command">reviews</span></td>
			<td>Reviews of terrible, terrible movies</td>
		</tr>
		<tr>
			<td><span class="command">theme</span> <span class="dark">name</span></td>
			<td>Change the look of the command prompt (use <span class="command">reset</span> to undo)</td>
		</tr>
		<tr>
			<td><span class="command">customize</span></td>
			<td>Customization options (some theme specific)</td>
		</tr>
		<tr>
			<td><span class="command">msg</span> <span class="dark">text</span></td>
			<td>Leave me a message (use <span class="command">msg list</span> to read)</td>
		</tr>
		<tr>
			<td><span class="command">wtfig</span> <span class="dark">font</span> <span class="dark">text</span></td>
			<td>Do you ASCII? Figlet generator extraordinaire</td>
		</tr>
		<tr>
			<td><span class="command">say</span> <span class="dark">text</span></td>
			<td>What? Your website doesn\'t talk?</td>
		</tr>
		<tr>
			<td><span class="command">get</span> <span class="dark">query</span></td>
			<td>Search and download torrents</td>
		</tr>
		<tr>
			<td><span class="command">game</span> <span class="dark">name</span></td>
			<td>Favorite games and things I made for them</td>
		</tr>
		<tr>
			<td><span class="command">rate</span> <span class="dark">title</span></td>
			<td>Checks for ratings on IMDb and Rotten Tomatoes</td>
		</tr>
		<tr>
			<td><span class="command">img</span> <span class="dark">query</span></td>
			<td>A basic image search (currently only Instagram)</td>
		</tr>
		<tr>
			<td><span class="command">yoda</span> <span class="dark">question</span></td>
			<td>Answer your questions Yoda will</td>
		</tr>
		<tr>
			<td><span class="command">last.fm</span></td>
			<td>Recent tracks and other Last.FM data</td>
		</tr>
		<tr>
			<td><span class="command">xbox</span></td>
			<td>My gamerscore (terrible) and the games I play</td>
		</tr>
		<tr>
			<td><span class="command">bash</span></td>
			<td>Our infinite stupidity as documented through bash</td>
		</tr>
		<tr>
			<td><span class="command">locate</span> <span class="dark">ip</span></td>
			<td>Find out where IP is located</td>
		</tr>
		<tr>
			<td><span class="command">whois</span> <span class="dark">domain</span></td>
			<td>Look up whois information</td>
		</tr>
		<tr>
			<td><span class="command">uptime</span></td>
			<td>Server uptime and load averages</td>
		</tr>
		<tr>
			<td><span class="command">rachael</span></td>
			<td>JOSHUA\'s first command (husband cheat-sheet)</td>
		</tr>
		<tr>
			<td><span class="command">prime</span> <span class="dark">number</span></td>
			<td>Checks if number is prime</td>
		</tr>
		<tr>
			<td><span class="command">calc</span> <span class="dark">operation</span></td>
			<td>Performs simple calculations (only one operation at a time)</td>
		</tr>
		<tr>
			<td><span class="command">thanks</span></td>
			<td>Credit where credit is due</td>
		</tr>
		<tr>
			<td><span class="command">version</span></td>
			<td>Captain\'s log (lists version changes)</td>
		</tr>
		<tr>
			<td><span class="command">stats</span></td>
			<td>Useless information that I think is really neat</td>
		</tr>
	</table>',

'version' => '
	<table id="version">
		<tr class="major">
			<td class="fixed-width">1.0 <span class="dark">Beta</span></td>
			<td>The first version of JOSHUA. Apart from the UI only minor alterations were made to the <a href="http://miklos.ca/cmd.html">Osenoa</a> JS. Still using XML. Wrote two PHP commands, <span class="command">uptime</span> and <span class="command">whois</span>.</td>
		</tr>
		<tr>
			<td class="fixed-width">1.1</td>
			<td>Added themes. (Mmm, fluff.) JOSHUA now responds to standard *nix commands.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">2.0 <span class="dark">PHP</span></td>
			<td>Completely rewrote the JS (-40 lines) and made the entire engine solely rely on PHP for content. Added view, easter-eggs and wtFIG.</td>
		</tr>
		<tr>
			<td class="fixed-width">2.1</td>
			<td>Added a countdown and some other JS magic (I magicked the shit out of it).</td>
		</tr>
		<tr>
			<td class="fixed-width">2.2</td>
			<td>Security, output and error handling is now exlusively PHP\'s domain.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">3.0 <span class="dark">XBrowser</span></td>
			<td>Rewrote the JS again (!) and optimized the PHP engine. Added a brand new GUI to combat the WebKit issues. Everything should run smoothly crossbrowser now.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">4.0 <span class="dark">Clean</span></td>
			<td>Who needs content? New GUI, again. Themes need to be updated, but it will be worth it.	No more fixed-width fontage (that breaks cross-OS) and pretty scrollbar is pretty.</td>
		</tr>
		<tr>
			<td class="fixed-width">4.1</td>
			<td><span class="destroy">Sitebar for floating content.</span> Added the <span class="command">game</span> command and a bunch of hidden ones.</td>
		</tr>
		<tr>
			<td class="fixed-width">4.2</td>
			<td>It\'s now possible to leave messages with <span class="command">msg</span>. Random motd extravaganza in place. Tweaked the scrollbar and updated all the themes (needs to be easier).</td>
		</tr>
		<tr>
			<td class="fixed-width">4.3</td>
			<td>Ask <span class="command">yoda</span> questions. More game info, the <span class="command">destru</span> command. Cheating is now possible.</td>
		</tr>
		<tr>
			<td class="fixed-width">4.4</td>
			<td>More hidden commands and added desktop emulation for all the non-JOSHUA content.</td>
		</tr>
		<tr>
			<td class="fixed-width">4.5</td>
			<td>Fixed a couple of interface/performance issues and added <span class="command">last.fm</span>.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">5.0 <span class="dark">Next-Gen</span></td>
			<td>Redid the JOSHUA chrome to (almost) completely rely on CSS+JS. Wrote a new Gallery. Made new themes. Added a handful of statics and other junk. Good times!</td>
		</tr>
		<tr>
			<td class="fixed-width">5.1</td>
			<td>Refocused the layout on the command prompt emulation. The <span class="command">desktop</span> was demanding too much attention. Also added a handful commands and a visual effect (sparks).</td>
		</tr>
		<tr>
			<td class="fixed-width">5.2</td>
			<td>Mostly CPU enhancements and visual tweaks (motivated by your feedback: thanks!). Started working on my first game; An awesome fantastic sidescroller called Superplastic (open development). Added <span class="command">tip</span> and the usual bunch of hiddens.</td>
		</tr>
		<tr>
			<td class="fixed-width">5.3</td>
			<td>JOSHUA will now remember what windows you have open and input history (finally got around to it). <span class="command">Music</span> was asked for so I made a tiny MP3 player.</td>
		</tr>
		<tr>
			<td class="fixed-width">5.4</td>
			<td>Window positions are now stored and there\'s new effects (spin, translucent and pulsar). Otherwise strictly a maintenance release.</td>
		</tr>
		<tr>
			<td class="fixed-width">5.5</td>
			<td>New stand-alone default theme called Tron (relatively cross-browser). You can download torrents from Isohunt with <span class="command">get</span> and read about people worse off than you with <span class="command">fml</span> and see IRC idiocracy with <span class="command">bash</span>.</td>
		</tr>
		<tr>
			<td class="fixed-width">5.6</td>
			<td>Redid the theme-handling (to support flexible layouts). Added <span class="command">stats</span>.</td>
		</tr>
		<tr>
			<td class="fixed-width">5.7</td>
			<td>Superplastic: New enemies and visual effects. Shields added, points added, rate of fire increased. In short: Rachael helped make the game more fun.</td>
		</tr>
		<tr>
			<td class="fixed-width">5.8</td>
			<td>A new text-based game called <span class="command">numbers</span> has been implemented.</td>
		</tr>
		<tr>
			<td class="fixed-width">5.9</td>
			<td>Minor bugfixes and new version-handling. More content and the <span class="command">xbox</span> command.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">6.0 <span class="dark">Content</span></td>
			<td>A lot of content added. Mobile (iPhone/Android) placeholder. Discovered and fixed a bug with Safari (apparently \'version\' is an illegal cookie-name).</td>
		</tr>
		<tr>
			<td class="fixed-width">6.1</td>
			<td>Brand new fluid scrolling output. Fixed a glitch with Firefox 3.6.</td>
		</tr>
		<tr>
			<td class="fixed-width">6.2</td>
			<td>Rewritten parts of the engine to take advantage of SimpleXML (-3kb gain). Added a couple CURL tweaks for external requests.</td>
		</tr>
		<tr>
			<td class="fixed-width">6.3</td>
			<td>Geolocation with <span class="command">trace</span> added. Minor theme fixes. Superplastic bugfix. More useless stats.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">7.0 <span class="dark">Diesel</span></td>
			<td>Diesel introduced; Stand-alone design with several interface tweaks. Removed a lot of unnecessary junk.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.1</td>
			<td>Load time reduced by 80%: Optimized requests and added compression.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.2</td>
			<td>Hacker-stylesheet added to highlight new terminal-style functionality (expect more soon). Re-implemented the wtFIG command-line integration.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.3</td>
			<td>Added mouse and title-indication when JOSHUA is processing commands (waiting cursor not behaving correctly in WebKit, bug report submitted). New Pirate-stylesheet and several minor tweaks to existing styles.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.4</td>
			<td>LCARS assimilated (yes, I have been watching Star Trek).</td>
		</tr>
		<tr>
			<td class="fixed-width">7.5</td>
			<td>You can now draw. Granted it\'s only one brush and one color (for now), but it\'s still pretty cool right? A bunch of other tweaks.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.6</td>
			<td>A fair amount of housecleaning before porting source to Github.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.7</td>
			<td>Housecleaning continued, and bugfixing as a result of said cleaning. <span class="scratch">Added a <span class="command">timer</span> command for Rachael.</span> LCARS is now the default theme.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.8</td>
			<td>I hated the Hacker theme, say hello to Helvetica instead. Made the timer less useless.</td>
		</tr>
		<tr>
			<td class="fixed-width">7.9</td>
			<td>Backend reworked (again?! yes!). Streamlined themes, fixed some initializing glitches. Simple <span class="command">calc</span> and a method to check for <span class="command">prime</span> numbers.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">8.0 <span class="dark">Stable</span></td>
			<td>First stable release, well, on WebKit-based platforms. (I use Chrome and this site is maintained by the WFMWF-principle.)</td>
		</tr>
		<tr>
			<td class="fixed-width">8.1</td>
			<td>New Slick Gallery. Fancybox removed and replaced with SimpleModal (-30kb).</td>
		</tr>
		<tr>
			<td class="fixed-width">8.2</td>
			<td>HTML5 and content update.</td>
		</tr>
		<tr>
			<td class="fixed-width">8.3</td>
			<td>External links cleanup, content updates and Pirate\'d.</td>
		</tr>
		<tr>
			<td class="fixed-width">8.4</td>
			<td>MMO API\'s are teh suck. Failsafes for clean installs. I can <span class="command">sudo</span>.</td>
		</tr>
		<tr>
			<td class="fixed-width">8.5</td>
			<td>Added <span class="command">reviews</span>, cleaned the desktop and updated Tron.</td>
		</tr>
		<tr>
			<td class="fixed-width">8.6</td>
			<td>Updated all the game API\'s (and made them a module).</td>
		</tr>
		<tr>
			<td class="fixed-width">8.7</td>
			<td>Fixed the message command. Made sudoing less useless.</td>
		</tr>
		<tr>
			<td class="fixed-width">8.8</td>
			<td>There is now a load indicator (top right) when requests are running.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">9.0 <span class="dark">Mono</span></td>
			<td>Mono means back to minimalism. Removed some pointless windows and tweaked the ones that are needed. Themes and presets can (finally) be loaded from the prompt.</td>
		</tr>
		<tr>
			<td class="fixed-width">9.1</td>
			<td>Since Youtube added support for duration in their API, hello <span class="command">videos</span>!</td>
		</tr>
		<tr>
			<td class="fixed-width">9.2</td>
			<td>Upgraded to use jQuery 1.7.2 (from 1.4.2). No more jScrollPane, rather use native (where possible). Upgrading should no longer reset settings beyond new default theme.</td>
		</tr>
		<tr>
			<td class="fixed-width">9.3</td>
			<td>Ze reddit patch (fixes based on feedback from posting JOSHUA). Windows should no longer flicker (removed toggling), stealFocus should now be more invasive and consequently useful. Several new commands and will now be storing input that fails.</td>
		</tr>
		<tr>
			<td class="fixed-width">9.4</td>
			<td>A bunch of new commands based on invalid input. Support for HTML fetching when developers are too lazy to write an API. Open Graph support to keep up with sharing. Started work on a C64 theme (live but not complete). TSW and Diablo 3 are fun games.</td>
		</tr>
		<tr>
			<td class="fixed-width">9.5</td>
			<td>You asked for it, JOSHUA can now talk. Use <span class="command">say</span> to play around with Text-to-Speech.</td>
		</tr>
		<tr>
			<td class="fixed-width">9.6</td>
			<td>Usability/compability update. More relaxed security, possible to click commands, upgraded all externals where relevant, several tweaks to existing themes.</td>
		</tr>
		<tr class="major">
			<td class="fixed-width">10 <span class="dark">Neocom</span></td>
			<td>New theme, a new matrix effect, (arguably) better effect and window management, gallery refactored as a fancy <span class="command">image</span> search interface, <span class="command">rate</span> has been added, tons of QOL fixes.</td>
		</tr>
		<tr>
			<td class="fixed-width">10.1</td>
			<td>Why use PNG\'s for the drawing brushes when CSS3 has radial gradient? Custom effects fixed for all skins.</td>
		</tr>
		<tr>
			<td class="fixed-width">10.2</td>
			<td>Random theme on first time init. Rate command fixed (API changed). Minor tweaks.</td>
		</tr>
		<tr>
			<td class="fixed-width">10.3</td>
			<td>Neocom II: Beastmode. New CSS3 effects and transitions.</td>
		</tr>
		<tr>
			<td class="fixed-width">10.4</td>
			<td>QOL updates and tweaked Neocom.</td>
		</tr>
	</table>',

'about' => '
	<p>
		JOSHUA is a shell emulation written in jQuery using a PHP back-end. He was based on <a href="http://miklos.ca/cmd.html" class="blank">Osenoa</a> but has evolved a lot since then.
		The name is a reference to \'War Games\' (Shall we play a game?).
		My name is <span class="command">alexander</span> and this site is fueled by sleep deprivation.
	</p>
	<p>
		Making JOSHUA has been (and still is) a fun challenge for me. Although in all honesty it\'s pretty much the most awkward way to navigate a website ever conceived.
		But then again; Mouseclicking is <i>sooo</i> 90\'s.
	</p>',
	
'alexander' => '<table class="fluid">
		<tr><td rowspan="8"><div class="image" style="background-image:url(\'http://www.gravatar.com/avatar/3005b66c6817d98851a980560a79e231?s=100\');width:100px;height:100px;"></div></td></tr>
		<tr><td class="label dark">Name</td><td>Alexander Støver</td></tr>
		<tr><td class="label dark">Alias</td><td>Destru Kaneda</td></tr>
		<tr><td class="label dark">Location</td><td>Brooklyn, New York</td></tr>
		<tr><td class="label dark">Status</td><td>Happily married</td></tr>
		<tr><td class="label dark">Motto</td><td>There is no try, only do.</td></tr>
		<tr><td class="label dark">Gamer</td><td>Killer socializer</td></tr>
		<tr><td class="label dark">Contact</td><td><a href="mailto:alexander@binaerpilot.no">alexander@binaerpilot.no</a></td></tr>
	</table>
	<p>
		I do front end web development (see <span class="command">resume</span>).
		On my spare time I make <a href="http://binaerpilot.no">music for robots</a>.
		I am, what is colloquially known as, a massive nerd.
	</p>',

'destru' => '<p><b>Destru Kaneda</b> is my gaming alias (+10 nerd points). Destru is a play on Destrega and the word \'destruction\'. Kaneda, as anyone fascinated by manga will tell you, is from the epic "Akira". '.
	'When it comes to games my favorite setting is science fiction (duh) with team-based PvP as a favorite gameplay mode. I also prefer games with actual consequences, like EVE Online or anything with a hardcore mode. '.
	'PEWPWEPWPEPWEPPWEWPEW!</p>',

'ascii' => '<pre class="ascii">
       dP                   dP                        
       88                   88                        
       88 .d8888b. .d8888b. 88d888b. dP    dP .d8888b.
       88 88\'  `88 Y8ooooo. 88\'  `88 88    88 88\'  `88
88.  .d8P 88.  .88       88 88    88 88.  .88 88.  .88
 `Y8888\'  `88888P\' `88888P\' dP    dP `88888P\' `88888P8
</pre>',

'ultraviolence' => '<script>fxInit(\'ultraviolence\', true);</script>',
'pvp' => 'PVP > PVE',
'cheese' => 'I\'m in ur fridge, <a href="misc/cheese.jpg" class="view">stealin\' ur cheeze</a>.',
'geek' => '<pre>GMU d-(---)pu s+++:-- a-- C++++$ U>+++ P+ L+ E---- W+++$ w PS+++ PE-- Y++ PGP-- t+ tv-- b+ D++ G e- h r++ y+*</pre>',
'ip' => $_SERVER['REMOTE_ADDR'],
'user' => $_SERVER['HTTP_USER_AGENT'],
'whoami' => $_SERVER['REMOTE_ADDR'].' on '.$_SERVER['HTTP_USER_AGENT'],
'lol' => 'stfu nub',
'omg' => 'lol',
'stfu' => 'wat lol',
'wat' => 'lol',
'how' => '<img src="images/magic.gif">',
'flattr' => 'I can dig the concept. Here\'s hoping it catches on beyond Germany. You can <a href="http://flattr.com/thing/58145/">flattr JOSHUA</a>.',
'elp' => 'Looks like you might need some... Type slower!',
'hello' => '<p class="joshua">'.$joshua.'I am currently unable to provide stimulating conversation. Please try again at a later point in time.',
'helo' => '<p class="joshua">'.$joshua.'Helo ther how er u?',
'hi' => 'yo',
'yo' => 'sup dude',
'drunk' => '<p>Touch your nose. Walk a straight line. Do calculus.</p><p>Hell son, do a flip.</p>',
'dafunk' => 'Back to the punk.',
'dolphins' => '<a href="http://sncrly.com/?210">Beloved</a> animals.',
'tongues' => 'Tongues? <a href="misc/tongues.jpg" class="view">We have them</a>.',
'chicks' => 'I have a couple of <a href="misc/nude_chicks.jpg" class="view">nude chicks</a> at my crib. Because I\'m a total pimp.',
'opmg' => 'Oh prankster mothergoose (omph my gawd).',
'noobcaek' => 'Also known as my <a href="http://www.urbandictionary.com/define.php?term=noobcaek">Urban Dictionary</a> moment of fame.',
'earth' => 'Mostly harmless.',
'42' => ' The meaning of life, the universe and everything.',
'sealab' => '<a href="http://en.wikipedia.org/wiki/Sealab_2021">Sealab 2021</a> was fucking awesome. Would you put your brain in a robot body? Buy the DVD\'s.',
'pridelings' => 'Small and inoffensive chesthair.',
'cheat' => 'There are no cheats. Unless you\'re a member of Delta-Q-Delta...',
'iddqd' => 'Degreelessness mode enabled.',
'idclip' => 'You can now walk through walls. Go ahead and try it. A running start works best?',
'hal' => 'Let me put it this way, Mr. Amer. The 9000 series is the most reliable computer ever made. No 9000 computer has ever made a mistake or distorted information. We are all, by any practical definition of the words, foolproof and incapable of error.',
'hadouken' => 'Shoryuken!',
'shoryuken' => 'Hadouken!',
'sifl' => 'Is crescent fresh.',
'olly' => 'Is also crescent fresh.',
'test' => 'I rarely test, but I frequently break.',
'smart' => 'I am Mount Cleverest!',
'valhalla' => 'My first lap dance at the Spearmint Rhino in Vegas.',
'education' => '<i>I\'ve never let my school interfere with my education.</i><br>&mdash; Mark Twain',
'tetris' => 'The greatest game ever written. Bar none. Fight me on <a href="http://tetrisfriends.com">Tetris Friends</a> (I\'m DestruKaneda).',
'poop' => 'And farts! Don\'t forget farts!',
'pooper' => 'Are you twelve?',
'fart' => 'Rachael? Are you pluggin stuff into JOSHUA again?',
'farts' => 'Would you look at that? A lil mouse ran in and tooted.',
'shit' => 'NO U',
'yarr' => 'Harr harr! You\'d be wise to try the pirate style, matey!',
'nintendo' => '<p>When I say nintendo you say rock.</p><p>Nintendo!</p>',
'rock' => 'Nintendo!',
'hamburger' => 'The <a href="http://www.recipezaar.com/Royal-Red-Robin-Burger-143864" class="blank">Royal Red Robin Burger</a> is the king of all hamburgers. And I should know, <a href="misc/alexander_the_burger_king.jpg" class="view">I eat a lot of junk food</a>.',
'dentist' => '<p>When I was a kid I soon realized that my teeth were made of glass. Because every time I sat in a dentist chair they wouldn\'t let me leave until at least three or four cavities were taken care of. So naturally I developed a fear of the dentist. Now that I\'m older that means I won\'t go before the pain is tantamount to getting kicked in the balls repeatedly by a field goal kicker.</p><p>But you know what would ease my pain? Not being charged an arm and a leg for this torture. What the fuck, Norway? You mean to tell me I can go to the hospital and a surgeon will fix me up practically for free, but a dentist will charge me half a months paycheck for less work? Guess I\'m chewing painkillers and booking a flight to Poland.</p>',
'sheldon' => 'Scissors cuts paper, paper covers rock, rock crushes lizard, lizard poisons Spock, Spock smashes scissors, scissors decapitates lizard, lizard eats paper, paper disproves Spock, Spock vaporizes rock, and as it always has, rock crushes scissors.',
'wtf' => 'Can you pay them? The people who put everything together? That\'s the only way I can explain it.',
'armadillo' => 'Do not hurt the infamous armadillo, whose tales of awesometude have reached even the cold shores of Norway. We, the vikings of this barren land, will fight to preserve this majestic creature\'s right to proper lodgings. Something something, axe murder.',
'toes' => '<i>I don\'t care much for toes. Sure, they help you keep your balance, but name me one more useful thing about them?</i> Ouch! That\'s how I used to feel about toes, but <a href="misc/toes.jpg" class="view">that was before I met these guys</a>. Dey r super ql an fun to b aroun lol!',
'pnk' => 'Hei Eirik, slutt snokinga etter kommandoer. Kom heller og ta en øl.',
'inge' => 'Behold the majesty that is <a href="misc/ingusmccloud.jpg" class="view">Ingus McCloud</a>.',
'peter' => 'Real men are not afraid to show their emotions. <a href="misc/malebonding.gif" class="view">Like me and Peter</a>.',
'kgb' => '<p class="joshua">'.$joshua.'That information is classified.',
'paranoid' => 'Just because you\'re paranoid, don\'t mean they\'re not <a href="misc/kgb.jpg" class="view">after you</a>.',
'host' => 'JOSHUA is hosted by <a href="http://optical.no" class="blank">Optical</a>. I strongly recommend them.',
'sleep' => 'I just wanna tell you that there is no sleep in here.',
'konami' => 'Do you speak Konami? Tell me the code.',
'prudiful' => 'A mix of "Pretty" and "Beautiful". A synomyn for Rachael.',
'hot' => 'One of the hottest things on the planet, the infamous <a href="misc/deadsexy.jpg" class="view">Peter Andersen</a>.',
'asimov' => 'Individual science fiction stories may seem as trivial as ever to the blinder critics and philosophers of today. But the core of science fiction, its essence, has become crucial to our salvation if we are to be saved at all.',
'joshua' => '<p>JOSHUA is my homepage. Willfully ignoring any and all conventions that "aren\'t cool" (but absolutely crucial), it has turned into a website that never ceases to entertain me (while developing) and confuse everybody else (while visiting). I work with making the web easy and accesible every day, so consider this my outlet. For more information see <span class="command">about</span>.<br><a href="http://github.com/destru/joshua" class="external">Download JOSHUA at GitHub.</a></p>',
'source' => '<p><a href="http://github.com/destru/joshua" class="external">Download JOSHUA at GitHub.</a></p>',
'hidden' => 'Look closer! Have you tried cheating? To <span class="command">cheat</span> or not to <span class="command">cheat</span>, that is the question.',
'sharks' => 'Don\'t poop. Ever. They\'re like the Thunderdome, only two men enter and no man leaves.',
'lamb' => 'A lamb stays a lamb. Forever. Sheep are not lambs, they are a different species entirely. Dr. Young, the world renowned lambologist, has done research into the matter.',
'mouse' => 'Pro mouse w/steelmat? <a class="view" href="misc/donald.jpg">I has one</a>.',
'butts' => 'Cuz I\'m buuuurnin\', buuuuuurnin\', burnin\' with deeeesiiire.',
'sick' => 'Rad to the power of awesome!',
'bugger' => 'Oh dear.',
'home' => '<i>Home is where the heart lies, but if the heart lies where is home?</i><br>&mdash; Fish',
'server' => '<p class="joshua">'.$joshua.'Currently running on '.$_SERVER['SERVER_SOFTWARE'].'</p>',
'deploy' => 'JOSHUA '.$version.' ('.$versionName.') is deployed.',
'henrik' => 'Lillebroren min som skal bli rockestjerne.',
'freiburg' => 'I don\'t know why I hate you so much.',
'osenoa' => '<p class="joshua">'.$joshua.'Daddy?',
'survice' => 'How Alexander spells "survive" apparently. Brain tumor, anyone?',
'almond' => 'Giving an almond is when you go down on a woman. At least according to Dr. Alxaendr, the illiterate cage monkey.',
'purpose' => 'Matthew thinks that JOSHUA <a href="http://mwholt.com">has no real purpose</a>. Yet his site uses a console interface for the exact same reason mine does. Then again, this site is more about having fun rather than coming off as pretentious.',
'stupid' => 'I try to fill my life with the things I love, whether or not they hold meaning to anyone else is besides the point.',
'batdog' => 'Who is batdog? <a href="misc/batdog.jpg" class="view">He is the bat</a>, Rachael\'s guardian.',
'smeg' => 'We are <a href="http://steamcommunity.com/groups/clansmeg">Clan SMEG</a>. Also known as The Boys From The Dwarf. Also known as me and my friends when we play Counter-Strike: Source.',
'coffee' => 'Cheers, I take mine black. Rachael? Non-fat half the syrup double tall white chocolate almond mocha...',
'blocktrix' => 'The greatest game in the world. It\'s like <a href="http://en.tetrinet.no/">Tetris on steroids</a>.',
'trick' => '"Luckily I don\'t have a trick knee like grandpa over here." Quoth Rachael, the harpy.',
'illuminati' => 'Novus Ordo Seclorum',
'reddit' => 'We had joy, we had fun, we had seasons in the sun. But the hills that we climbed were just seasons out of time. (Thanks for taking JOSHUA to the front page!)',
'penguin' => 'Synonymous with Rachael.',
'vagina' => '<i>Why do people say "Grow some balls"? Balls are weak and sensitive! If you really wanna get tough, grow a vagina! Those things take a pounding!</i><br>&mdash; Betty White',
'sup' => 'nammach, u?',
'commands' => 'See <span class="command">help</span> for a list of commands. (But keep in mind there are many, many more hidden ones.)',
'echo' => 'Is not actually a function.',
'finger' => 'How dare you.',
'tell' => 'A gentleman never does.',
'what' => 'Who?',
'where' => 'When?',
'why' => 'Why not?',
'yes' => 'Aw yiss.',
'pfft' => 'Dude, whatever.',
'no' => 'Don\'t you mean yes?',
'cunt' => 'Fuck off, you pissed up slapper.',
'robot' => 'if(!full(battery)) { charge(battery); } else { for(i = 0; i < 4; i++) { var we = \"the robots\"; } }',
'meaning' => 'I\'m too tired for this Zen bullshit. YOLO.',
'format' => '<p class="joshua">'.$joshua.'Come on, did you really expect that to work?</p>',
'fuck' => 'Possibly the best word in the English language, you fucker.',
'pointless' => 'A circle.',
'haha' => 'Hehe, right?',
'hehe' => 'Dude, yes.',
'work' => 'I make websites for a living. Bet you didn\'t see that coming.',
'herp' => 'Derpderpderp.',
'derp' => 'herp',
'goodbye' => 'Hasta la vista, baby.',
'asdf' => 'qwerty',
'tron' => 'He fights for the Users.',

'homtanks' => '<p>The celebrity renaming game. Created by Yachael Roung.</p><pre class="dark">
Alexander: I still think Hom Tanks is the best. tolsen wins!
Rachael: hahahahaha
Alexander: BRODO FAGGINS!
Rachael: HAHAHAHAHAHAH!
Rachael: oh dear god... that\'s brilliant. my stomach kind of hurts.
Alexander: I\'ll make some unfunny ones like... lex luthor.
Rachael: ... gooood one... or or or...
Rachael: Cane Jurtis
Rachael: actually that one was pretty funny
Rachael: actually
Alexander: yeah
Alexander: coz of the cane...
Alexander: THE CANE OF JURTIS!!!
Rachael: HAHAH! NO! stop... pain...
Alexander: CONAN WIELDS THE MIGHTY CANE!
Rachael: HAHAHA!</pre>',

'time' => gmdate('l jS \of F Y h:i:s A').' UTC',
'fizzbuzz' => '&lt;?while($i++<100)echo$i%15?$i%5?$i%3?$i:"Fizz":"Buzz":"FizzBuzz","\n";?&gt;',
'justmtv' => 'An amazing idea I had that never will be realized. Streaming music videos without interruption online. Or in other words, what MTV should be.',
'everywhere' => '<p>Can you hear me calling<br>Out your name<br>You know that I\'m falling<br>And I don\'t know what to say<br>I\'ll speak a little louder<br>I\'ll even shout<br>You know that I\'m proud<br>And I can\'t get the words out</p><p>Oh I...<br>I want to be with you everywhere</span></p>',
'git' => 'Git is awesome. I don\'t know how I survived without it.<br><a class="external" href="http://github.com/destru/">Follow me at GitHub.</a>',
'bomfunk' => 'We grew up on the south side, ghettoblaster was huge!',
'draw' => 'Sure, choose "Draw" in the <span class="command">customize</span> menu and have at it.',
'love' => 'Baby don\'t hurt me, don\'t hurt me, no more.',
'api' => 'I\'m a little confused as to why you tried this command, but I have made an API.<br><a class="external href="http://chronicless.einhyrning.com">JSON API for The Secret World.</a>',
'contact' => 'You can leave a <span class="command">msg</span> or <a href="mailto:alexander@binaerpilot.no">send me an email</a> and I\'ll be in touch.',
'motherfucker' => 'You got me, I did in fact have sex with your mother. On multiple occasions. I\'d like to take this opportunity to apologize.',
'motherfuckers' => 'Wanna get with me. Lay with me, love with me, all right.',
'sex' => 'It\'s a good thing.',
'nice' => 'Thanks!',
'you' => 'Me?',
'me' => 'You?',
'anyone' => 'Everyone?',
'moo' => 'Hah, you don\'t fool me. Cows can\'t type.',
'lalala' => '<a href="http://www.youtube.com/watch?v=1ygdAiDxKfI&t=0m57s">Hilipati hilipati hilipati hillaa.</a>',
'awesomeness' => 'Why, thank you.',
'snap' => 'Rhythm is a dancer.',
'rly' => 'srsly',
'primus' => 'As the toys go winding down.',
'firefly' => 'One of the best sci-fi shows ever made.',
'nananana' => 'Hey hey hey, goodbye!',
'rock' => 'Paper! Hah!',
'paper' => 'Scissor! What do you mean I\'m cheating?',
'scissor' => 'Rock! You won\'t win, I am really good at this game.',
'ss' => 'He better not be going top. Lest he wants to face the Might of Demacia.',
'teemo' => 'Never underestimate the power of the Scout\'s code.',
'xd' => 'ᕙ(⇀‸↼‶)ᕗ',
'rage' => '(╯°□°）╯︵ ┻━┻',
'tits' => '<a href="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/GreatTit002.jpg/220px-GreatTit002.jpg">A beautiful pair of tits.</a>',
'moneyball' => 'We play Fantasy Football at work. To combat my lack of knowledge on players these days, I\'m running my team based on Moneyball. Will it succeed? No, it will not. Does it give me an out when people are slaughtering my line-up at the pub? Yes, it does.',
'einhyrning' => 'QSBkcmVhbQ==<br><a class="external" href="http://einhyrning.com">RHJlYW0gdG9nZXRoZXI=</a>',
'cakeday' => 'I\'ll be getting some cake in '.cakeDay("10/03").' days.',
'resume' => '<p><a href="http://www.linkedin.com/in/astoever">I am currently working for This Life Inc. in New York.</a>
	<p>The majority of my professional career is spent in private repositories, but some notable sites I have worked on are
		<a href="http://www.travelocity.com/">Travelocity</a>,
		<a href="http://finn.no">FINN</a> and
		<a href="">HowAboutWe</a> (currently).
		To this day I still enjoy doing random projects on my spare time for fun.
		One of the more noteworthy being <a href="http://chronicless.einhyrning.com">ChronicLESS</a>, a JSON API for Funcom\'s The Secret World.
	<p>I am currently not looking for work, but feel free to <a href="mailto:alexander@binaerpilot.no">mail me</a> if you have any questions.
	',
'rachael' => '<p>Rachael is the most beautiful girl in the world. It\'s a scientific fact. Yes, I am a scientist.
	We\'ve been happily married for <span class="light">'.round((strtotime(date("Ymd"))-strtotime("2009/10/07"))/86400).'</span> days
	and her birthday is in <span class="light">'.cakeDay("06/29").'</span> days
	(so remember to buy her something nice). <a href="http://rachaelivy.com">Visit her homepage</a> for more information about her acting.</p>',

'thanks' => '<p>I\'m a firm believer in giving credit where credit is due, this is a list over all the people who in some way have contributed to JOSHUA. Thank you for being awesome.<p>
	<p>
		<a href="http://ejohn.org/">John Resig</a> for jQuery.
		<a href="http://miklos.ca/">Miklos Bacso</a> for Osenoa (JOSHUA\'s Father).
		<a href="http://onaluf.org/">Selim Arsever</a> for gameQuery.
		<a href="http://schillmania.com/">Scott Schiller</a> for SoundManager2.
		<a href="http://paulbakaus.com/">Paul Bakaus</a> and the jQuery UI team.
		<a href="http://brandonaaron.net/">Brandon Aaron</a> for jQuery Mousewheel.
		<a href="http://malsup.com/">Mike Alsup</a> for jQuery Cycle.
		<a href="http://ericmmartin.com/">Eric Martin</a> for SimpleModal.
		<a href="http://www.stilbuero.de/">Klaus Hartl</a> for jQuery Cookie.
		<a href="http://p.yusukekamiyamane.com/">Yusuke Kamiyamane</a> for his pixel fonts.
		<a href="http://www.sedgeman.com/" class="blank">Luke Sedgeman</a> for his pixel Yoda.
		<a href="http://vision-media.ca/">Vision Media</a> for the sparks effect.
		<a href="http://elliottkember.com/">Elliott Kember</a> for the spin effect.
		<a href="http://lucasbaltes.com/">Lucas Baltes</a> for his Figlet PHP class.
		<a href="http://bananarenders.com/">Bananarenders</a> for the original TR2N background.
		<a href="https://github.com/kripken/">Alon Zakai</a> for his JavaScript TTS.
		Andrew Welch, Carl Osterwald and Steve Gilardi for <a href="http://en.wikipedia.org/wiki/ProFont">ProFont</a>.
		Prowareness for his <a href="http://www.prowareness.com/blog/matrix-effect-using-jquery/">Matrix effect</a>.
		Brsev for <a href="http://brsev.deviantart.com/art/Token-128429570">Token</a>.
		<a href="http://www.tigaer-design.com/">Christian Hecker</a> for the Neocom background.
	</p>
	<p>I\'d also like to thank Rachael for being patient with me while working on this, Eirik and Inge for answering stupid questions, George McGinley Smith for his work on easing methods and an unknown person for the remade Contra logo. Last but not least, I\'d like to thank John Malkovich for being John Malkovich.</p>',

'porn' =>'<pre>Perfect Breasts             <span class="light">(o)(o)</span>
Fake Silicone Breasts       <span class="light">( + )( + )</span>
High Nipple Breasts         <span class="light">(*)(*)</span>
Big Nipple Breasts          <span class="light">(@)(@)</span>
A Cups                      <span class="light">oo</span>
D Cups                      <span class="light">{ O }{ O }</span>
Wonder Bra Breasts          <span class="light">(oYo)</span>
Cold Breasts                <span class="light">( ^ )( ^ )</span>
Lopsided Breasts            <span class="light">(o)(O)</span>
Pierced Breasts             <span class="light">(Q)(Q)</span>
Hanging Tassels Breasts     <span class="light">(p)(p)</span>
Against-The-Shower-Door     <span class="light">(  -  )(  -  )</span>
Android Breasts             <span class="light">|o||o|</span>
Porn Star Breasts           <span class="light">($)($)</span></pre>');
?>