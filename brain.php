<?php // joshua brain <alexander@binaerpilot.no>
// brain cells
$d = scandir(".");
foreach($d as $file) {
	if(stristr($file, 'cell.')) include $file;
}
// static information
$static = array(
'help' => '<table class="help">
	<tr><td class="command">about</td>
		<td>Who, what, when, where?</td></tr>
	<tr><td class="command">config</td>
		<td>GUI configuration (themes, backgrounds, effects)</td></tr>
	<tr><td class="command">superplastic</td>
		<td>My first attempt at gamedesign; A tiny space shooter!</td></tr>
	<tr><td class="command">numbers</td>
		<td>A text-based game where all the answers are numbers</td></tr>
	<tr><td class="command">msg <span class="dark">message</span></td>
		<td>Leave me a message (use <span class="dark">msg list</span> to read)</td></tr>
	<tr><td class="command">wtfig <span class="dark">font</span> <span class="dark">caption</span></td>
		<td>Do you ASCII? Figlet generator extraordinaire</td></tr>
	<tr><td class="command">yoda <span class="dark">question</span></td>
		<td>Answer your questions Yoda will</td></tr>
	<tr><td class="command">bash / fml</td>
		<td>Our infinite stupidity as documented through bash and fml</td></tr>
	<tr><td class="command">last.fm</td>
		<td>Recent tracks and other Last.FM data</td></tr>
	<tr><td class="command">get <span class="dark">query</span></td>
		<td>Search and download torrents</td></tr>
	<tr><td class="command">game <span class="dark">name</span></td>
		<td>Favorite games and things I made for them</td></tr>
	<tr><td class="command">xbox</td>
		<td>Destru Kaneda, pillager of villages and destroyer of men!</td></tr>
	<tr><td class="command">locate <span class="dark">ip</span></td>
		<td>Find out where IP is located</td></tr>
	<tr><td class="command">whois <span class="dark">domain</span></td>
		<td>Look up whois information</td></tr>
	<tr><td class="command">uptime</td>
		<td>Server uptime and load averages</td></tr>
	<tr><td class="command">rachael</td>
		<td>Joshua\'s first command (husband cheat-sheet)</td></tr>
	<tr><td class="command">gallery</td>
		<td>Random moments in time and space</td></tr>
	<tr><td class="command">music</td>
		<td>Listen to my music with the Bleep MP3 player</td></tr>
	<tr>
		<td class="command">prime <span class="dark">number</span></td>
		<td>Checks if number is prime</td>
	</tr>
	<tr>
		<td class="command">calc <span class="dark">operation</span></td>
		<td>Performs simple calculations (only one operation at a time)</td>
	</tr>

	<tr><td class="command">thanks</td>
		<td>Credit where credit is due</td></tr>
	<tr><td class="command">version</td>
		<td>Captain\'s log (lists version changes)</td></tr>
	<tr><td class="command">stats</td>
		<td>Useless information that nerds love</td></tr>
	</table>',

'about' => '
	<p>My name is Alexander Støver. I work for <a href="http://manualdesign.no" class="blank">Manual design</a> where my title is \'Hacker\'. Now I can\'t hack,
	but I do work with web development and interface design. On my spare time I make crazy robot music as <a href="http://biaerpilot.no">Binärpilot</a> and play online <span class="dark">games</span> under the name <em>Destru Kaneda</em>.
	If you want to hang out your best bet is the <a href="http://forum.binaerpilot.no">Binaer People</a>.</p>

	<p>Joshua is a shell emulation written in jQuery using a PHP back-end. He was based on <a href="http://miklos.ca/cmd.html" class="blank">Osenoa</a> but has evolved a lot since then.
	The name Joshua is a reference to \'War Games\' (Shall we play a game?).
	Special <span class="dark">thanks</span> to <a href="http://twitter.com/ehjelle" class="blank">Eirik Hjelle</a> and <a href="http://twitter.com/elektronaut" class="blank">Inge Jørgensen</a> for answering stupid questions.
	This site is fueled by sleep deprivation and nicotine. Feel free to to <a href="http://github.com/destru/joshua">bite my code</a>.</p>

	<p>Making Joshua has been (and still is) a fun challenge for me. Although in all honesty it\'s pretty much the most
	awkward way to navigate a website ever perceived. But then again; Mouseclicking is so 90\'s.</p>',

'version' => '
	<table class="version">
		<tr class="major"><td class="command fixed-width">1.0 <span class="dark">Beta</span></td>
			<td>The first version of Joshua. Apart from the UI only minor alterations were made to the <a href="http://miklos.ca/cmd.html">Osenoa</a> JS. Still using XML. Wrote two PHP commands, <span class="dark">uptime</span> and <span class="dark">whois</span>.</td>
		</tr>
		<tr><td class="command fixed-width">1.1</td>
			<td>Added themes. (Mmm, fluff.) Joshua now responds to standard *nix commands.</td>
		</tr>
		<tr class="major"><td class="command fixed-width">2.0 <span class="dark">PHP</span></td>
			<td>Completely rewrote the JS (-40 lines) and made the entire engine solely rely on PHP for content. Added view, easter-eggs and wtFIG.</td>
		</tr>
		<tr><td class="command fixed-width">2.1</td>
			<td>Added a countdown and some other JS magic (I magicked the shit out of it).</td>
		</tr>
		<tr><td class="command fixed-width">2.2</td>
			<td>Security, output and error handling is now exlusively PHP\'s domain.</td>
		</tr>
		<tr class="major"><td class="command fixed-width">3.0 <span class="dark">XBrowser</span></td>
			<td>Rewrote the JS again (!) and optimized the PHP engine. Added a brand new GUI to combat the WebKit issues. Everything should run smoothly crossbrowser now.</td>
		</tr>
		<tr class="major"><td class="command fixed-width">4.0 <span class="dark">Clean</span></td>
			<td>Who needs content? New GUI, again. Themes need to be updated, but it will be worth it.	No more fixed-width fontage (that breaks cross-OS) and pretty scrollbar is pretty.</td>
		</tr>
		<tr><td class="command fixed-width">4.1</td>
			<td><span class="destroy">Sitebar for floating content.</span> Added the <span class="dark">game</span> command and a bunch of hidden ones.</td>
		</tr>
		<tr><td class="command fixed-width">4.2</td>
			<td>It\'s now possible to leave messages with <span class="dark">msg</span>. Random motd extravaganza in place. Tweaked the scrollbar and updated all the themes (needs to be easier).</td>
		</tr>
		<tr><td class="command fixed-width">4.3</td>
			<td>Ask <span class="dark">yoda</span> questions. More game info, the <span class="dark">destru</span> command. Cheating is now possible.</td>
		</tr>
		<tr><td class="command fixed-width">4.4</td>
			<td>More hidden commands and added desktop emulation for all the non-Joshua content.</td>
		</tr>
		<tr><td class="command fixed-width">4.5</td>
			<td>Fixed a couple of interface/performance issues and added <span class="dark">last.fm</span>.</td>
		</tr>
		<tr class="major"><td class="command fixed-width">5.0 <span class="dark">Next-Gen</span></td>
			<td>Redid the Joshua chrome to (almost) completely rely on CSS+JS. Wrote a new Gallery. Made new themes. Added a handful of statics and other junk. Good times!</td>
		</tr>
		<tr><td class="command fixed-width">5.1</td>
			<td>Refocused the layout on the command prompt emulation. The <span class="dark">desktop</span> was demanding too much attention. Also added a handful commands and a visual effect (sparks).</td>
		</tr>
		<tr><td class="command fixed-width">5.2</td>
			<td>Mostly CPU enhancements and visual tweaks (motivated by your feedback: thanks!). Started working on my first game; An awesome fantastic sidescroller called Superplastic (open development). Added <span class="dark">tip</span> and the usual bunch of hiddens.</td>
		</tr>
		<tr><td class="command fixed-width">5.3</td>
			<td>Joshua will now remember what windows you have open and input history (finally got around to it). Music was asked for, say hi to <span class="dark">bleep</span>, the tiny music player.</td>
		</tr>
		<tr><td class="command fixed-width">5.4</td>
			<td>Window positions are now stored and there\'s new effects (spin, translucent and pulsar). Otherwise strictly a maintenance release.</td>
		</tr>
		<tr><td class="command fixed-width">5.5</td>
			<td>New stand-alone default theme called Tron (relatively cross-browser). You can download torrents from Isohunt with <span class="dark">get</span> and read about people worse off than you with <span class="dark">fml</span> and see IRC idiocracy with <span class="dark">bash</span>.</td>
		</tr>
		<tr><td class="command fixed-width">5.6</td>
			<td>Redid the theme-handling (to support flexible layouts). Added <span class="dark">stats</span>.</td>
		</tr>
		<tr><td class="command fixed-width">5.7</td>
			<td>Superplastic: New enemies and visual effects. Shields added, points added, rate of fire increased. In short: Rachael helped make the game more fun.</td>
		</tr>
		<tr><td class="command fixed-width">5.8</td>
			<td>A new text-based game called <span class="dark">numbers</span> has been implemented.</td>
		</tr>
		<tr><td class="command fixed-width">5.9</td>
			<td>Minor bugfixes and new version-handling. More content and the <span class="dark">xbox</span> command.</td>
		</tr>
		<tr class="major"><td class="command fixed-width">6.0 <span class="dark">mobile</span></td>
			<td>Mobile (iPhone/Android) placeholder. Discovered and fixed a bug with Safari (apparently \'version\' is an illegal cookie-name). A lot of content added.</td>
		</tr>
		<tr><td class="command fixed-width">6.1</td>
			<td>Brand new fluid scrolling output. Fixed a glitch with Firefox 3.6.</td>
		</tr>
		<tr><td class="command fixed-width">6.2</td>
			<td>Rewritten parts of the engine to take advantage of SimpleXML (-3kb gain). Added a couple CURL tweaks for external requests.</td>
		</tr>
		<tr><td class="command fixed-width">6.3</td>
			<td>Geolocation with <span class="dark">trace</span> added. Minor theme fixes. Superplastic bugfix. More useless stats.</td>
		</tr>
		<tr class="major"><td class="command fixed-width">7.0 <span class="dark">Diesel</span></td>
			<td>Diesel introduced; Stand-alone design with several interface tweaks. Removed a lot of unnecessary junk.</td>
		</tr>
		<tr><td class="command fixed-width">7.1</td>
			<td>Load time reduced by 80%: Optimized requests and added compression.</td>
		</tr>
		<tr><td class="command fixed-width">7.2</td>
			<td>Hacker-stylesheet added to highlight new terminal-style functionality (expect more soon). Re-implemented the wtFIG command-line integration.</td>
		</tr>
		<tr><td class="command fixed-width">7.3</td>
			<td>Added mouse and title-indication when Joshua is processing commands (waiting cursor not behaving correctly in WebKit, bug report submitted). New Pirate-stylesheet and several minor tweaks to existing styles.</td>
		</tr>
		<tr><td class="command fixed-width">7.4</td>
			<td>LCARS assimilated (yes, I have been watching Star Trek).</td>
		</tr>
		<tr><td class="command fixed-width">7.5</td>
			<td>You can now draw. Granted it\'s only one brush and one color (for now), but it\'s still pretty cool right? A bunch of other tweaks.</td>
		</tr>
		<tr><td class="command fixed-width">7.6</td>
			<td>A fair amount of housecleaning before porting source to Github.</td>
		</tr>
		<tr><td class="command fixed-width">7.7</td>
			<td>Housecleaning continued, and bugfixing as a result of said cleaning. Added a <span class="dark">timer</span> command for Rachael. LCARS is now the default theme.</td>
		</tr>
		<tr><td class="command fixed-width">7.8</td>
			<td>I hated the Hacker theme, say hello to Helvetica instead. Diesel is once again the default theme. Made the timer less useless.</td>
		</tr>
		<tr><td class="command fixed-width">7.9</td>
			<td>Backend reworked (again?! yes!). Streamlined themes, fixed some initializing glitches. Simple <span class="dark">calc</span> and a method to check for <span class="dark">prime</span> numbers.</td>
		</tr>
		<tr class="major"><td class="command fixed-width">8.0 <span class="dark">Stable</span></td>
			<td>First stable release, well, on WebKit-based platforms. (I use Chrome and this site is maintained by the WFMWF-principle.)</td>
		</tr>
		<tr class="major"><td></td><td><a class="toggle">See full history.</a></td></tr>
	</table>',

'destru' => '<p><em>Destru Kaneda</em> is my gaming alias (+10 nerd points). Destru is a play on Destrega and the word \'destruction\'. Kaneda, as anyone fascinated by manga will tell you, is from the epic "Akira".
When it comes to games I prefer PvP (with lasers), but until a decent science fiction MMO releases, you can find me playing MW2 on <a href="http://live.xbox.com/member/Destru%20Kaneda">Xbox Live</a>.</p>',

'ascii' => '<pre class="ascii">
       dP                   dP                        
       88                   88                        
       88 .d8888b. .d8888b. 88d888b. dP    dP .d8888b.
       88 88\'  `88 Y8ooooo. 88\'  `88 88    88 88\'  `88
88.  .d8P 88.  .88       88 88    88 88.  .88 88.  .88
 `Y8888\'  `88888P\' `88888P\' dP    dP `88888P\' `88888P8
</pre>',

'pvp' => 'PVP > PVE',
'cheese' => 'I\'m in ur fridge, <a href="misc/cheese.jpg" class="view">stealin\' ur cheeze</a>.',
'geek' => '<pre>GMU d-(---)pu s+++:-- a-- C++++$ U>+++ P+ L+ E---- W+++$ w PS+++ PE-- Y++ PGP-- t+ tv-- b+ D++ G e- h r++ y+*</pre>',
'ip' => $_SERVER['REMOTE_ADDR'],
'lol' => 'stfu nub',
'omg' => 'lol',
'stfu' => 'wat lol',
'wat' => 'lol',
'flattr' => 'I can dig the concept. Here\'s hoping it catches on beyond Germany. You can <a href="http://flattr.com/thing/58145/">flattr Joshua</a>.',
'farts' => 'Would you look at that? A lil mouse ran in and tooted.',
'fart' => 'Rachael? Are you pluggin stuff into Joshua again?',
'elp' => 'Looks like you might need some... Type slower!',
'hello' => '<p class="joshua">'.$joshua.'I am currently unable to provide stimulating conversation. Please try again at a later point in time.',
'drunk' => '<p>Touch your nose. Walk a straight line. Do calculus.</p><p>Hell son, do a flip.</p>',
'calc' => '<p>45.2658812</p><p>That\'s the square root of 2049.</p>',
'deckard' => '<a href="gallery/deckard.jpg" class="view">Alexander Deckard</a>, in the flesh.',
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
'test' => 'I rarely test, but I frequently break.',
'olly' => 'Is also crescent fresh.',
'pooper' => 'Are you twelve?',
'smart' => 'I am Mount Cleverest!',
'tetris' => 'The greatest game ever written. Bar none. Fight me on <a href="http://tetrisfriends.com">Tetris Friends</a> (I\'m DestruKaneda).',
'poop' => 'And farts! Don\'t forget farts!',
'yarr' => 'Harr harr! You\'d be wise to try the pirate style, matey!',
'nintendo' => '<p>When I say nintendo you say rock.</p><p>Nintendo!</p>',
'hamburger' => 'The <a href="http://www.recipezaar.com/Royal-Red-Robin-Burger-143864" class="blank">Royal Red Robin Burger</a> is the king of all hamburgers. And I should know, <a href="misc/alexander_the_burger_king.jpg" class="view">I eat a lot of junk food</a>.',
'rock' => 'Nintendo!',
'dentist' => '<p>When I was a kid I soon realized that my teeth were made of glass. Because every time I sat in a dentist chair they wouldn\'t let me leave until at least three or four cavities were taken care of. So naturally I developed a fear of the dentist. Now that I\'m older that means I won\'t go before the pain is tantamount to getting kicked in the balls repeatedly by a field goal kicker.</p><p>But you know what would ease my pain? Not being charged an arm and a leg for this torture. What the fuck, Norway? You mean to tell me I can go to the hospital and a surgeon will fix me up practically for free, but a dentist will charge me half a months paycheck for less work? Guess I\'m chewing painkillers and booking a flight to Poland.</p>',
'sheldon' => 'Sheldon is a genius. Really.',
'wtf' => 'Can you pay them? The people who put everything together? That\'s the only way I can explain it.',
'armadillo' => 'Do not hurt the infamous armadillo, whose tales of awesometude have reached even the cold shores of Norway. We, the vikings of this barren land, will fight to preserve this majestic creature\'s right to proper lodgings. Something something, axe murder.',
'toes' => '<em>"I don\'t care much for toes. Sure, they help you keep your balance, but name me one more useful thing about them?"</em> Ouch! That\'s how I used to feel about toes, but <a href="misc/toes.jpg" class="view">that was before I met these guys</a>. Dey r super ql an fun to b aroun lol!',
'pnk' => 'Hei Eirik, slutt snokinga etter kommandoer. Kom heller og ta en øl.',
'inge' => 'Behold the majesty that is <a href="misc/ingusmccloud.jpg" class="view">Ingus McCloud</a>.',
'peter' => 'Real men are not afraid to show their emotions. <a href="misc/malebonding.gif" class="view">Like me and Peter</a>.',
'alexander' => 'I make <a href="http://binaerpilot.no">electronic music</a> and kiss penguins. Well, one penguin.',
'kgb' => '<p class="joshua">'.$joshua.'That information is classified.',
'paranoid' => 'Just because you\'re paranoid, don\'t mean they\'re not <a href="misc/kgb.jpg" class="view">after you</a>.',
'host' => 'Joshua is hosted by <a href="http://optical.no" class="blank">Optical</a>. I strongly recommend them.',
'sleep' => 'There is no sleep in here. <a href="http://sleep.doesntexist.com">Sleep doesn\'t exist</a>.',
'konami' => 'Do you speak Konami? Tell me the code.',
'prudiful' => 'A mix of "Pretty" and "Beautiful". A synomyn for Rachael.',
'hot' => 'One of the hottest things on the planet, the infamous <a href="misc/deadsexy.jpg" class="view">Peter Andersen</a>.',
'asimov' => 'Individual science fiction stories may seem as trivial as ever to the blinder critics and philosophers of today. But the core of science fiction, its essence, has become crucial to our salvation if we are to be saved at all.',
'joshua' => 'Joshua is my homepage. Willfully ignoring any and all conventions that "aren\'t cool" (but absolutely crucial), it has turned into a website that never ceases to entertain me (while developing) and confuse everybody else (while visiting). I work with making the web easy and accesible every day, so consider this my outlet. For more information see <span class="dark">about</span>.',
'hidden' => 'Look closer! Have you tried cheating? To <span class="dark">cheat</span> or not to <span class="dark">cheat</span>, that is the question.',
'sharks' => 'Don\'t poop. Ever. They\'re like the Thunderdome, only two men enter and no man leaves.',
'lamb' => 'A lamb stays a lamb. Forever. Sheep are not lambs, they are a different species entirely. Dr. Young, the world renowned lambologist, has done research into the matter.',
'mouse' => 'Pro mouse w/steelmat? <a class="view" href="misc/donald.jpg">I has one</a>.',
'butts' => 'Cuz I\'m buuuurnin\', buuuuuurnin\', burnin\' with deeeesiiire.',
'user' => $_SERVER['HTTP_USER_AGENT'],
'status' => '<p class="joshua">'.$joshua.'Currently running version '.$version.' on '.$_SERVER['SERVER_SOFTWARE'],
'henrik' => 'Lillebroren min som skal bli rockestjerne.',
'freiburg' => 'I don\'t know why I hate you so much.',
'survice' => 'How Alexander spells "survive" apparently. Brain tumor, anyone?',
'almond' => 'Giving an almond is when you go down on a woman. At least according to Dr. Alxaendr, the illiterate cage monkey.',
'purpose' => 'Matthew thinks that Joshua <a href="http://mwholt.com">has no real purpose</a>. Yet his site uses a console interface for the exact same reason mine does. Then again, this site is more about having fun rather than coming off as pretentious.',
'stupid' => 'I try to fill my life with the things I love, whether or not they hold meaning to anyone else is besides the point.',
'batdog' => 'Who is batdog? <a href="misc/batdog.jpg" class="view">He is the bat</a>, Rachael\'s guardian.',
'smeg' => 'We are <a href="http://steamcommunity.com/groups/clansmeg">Clan SMEG</a>. Also known as The Boys From The Dwarf. For more information see <span class="dark">game cs:s</span>.',
'coffee' => 'Cheers, I take mine black. Rachael? Non-fat half the syrup double tall white chocolate almond mocha...',
'blocktrix' => 'The greatest game in the world. It\'s like <a href="http://en.tetrinet.no/">Tetris on steroids</a>.',
'trick' => '"Luckily I don\'t have a trick knee like grandpa over here." Quoth Rachael, the harpy.',
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
'fizzbuzz' => '&lt;?while($i++<100){$p=($i%3<1?"Fizz":"").($i%5<1?"Buzz":"");echo($p?$p:$i)."\n";}?&gt;',
'justmtv' => 'An amazing idea I had that never will be realized. Streaming music videos without interruption online. Or in other words, what MTV should be.',
'everywhere' => '<p>Can you hear me calling<br/>Out your name<br/>You know that I\'m falling<br/>And I don\'t know what to say<br/>I\'ll speak a little louder<br/>I\'ll even shout<br/>You know that I\'m proud<br/>And I can\'t get the words out</p><p>Oh I...<br/>I want to be with you everywhere</span></p>',
'git' => 'Git is awesome. I don\'t know how I survived without it.<br/><a class="external" href="http://github.com/destru/">Destru@Github</a>',
'bomfunk' => 'We grew up on the south side, ghettoblaster was huge!',
'thanks' => '<p>I\'m a firm believer in giving credit where credit is due, this is a list over all the people who in some way have contributed to Joshua. Thank you for being awesome.<p>
	<p>
		<a href="http://ejohn.org/">John Resig</a> for jQuery.
		<a href="http://miklos.ca/">Miklos Bacso</a> for Osenoa (Joshua\'s Father).
		<a href="http://onaluf.org/">Selim Arsever</a> for gameQuery.
		<a href="http://schillmania.com/">Scott Schiller</a> for SoundManager2.
		<a href="http://paulbakaus.com/">Paul Bakaus</a> and the jQuery UI team.
		<a href="http://www.kelvinluck.com/">Kelvin Luck</a> for jScrollPane.
		<a href="http://brandonaaron.net/">Brandon Aaron</a> for jQuery Mousewheel.
		<a href="http://malsup.com/">Mike Alsup</a> for jQuery Cycle.
		<a href="http://fancybox.net/">Janis Skarnelis</a> for FancyBox.
		<a href="http://www.stilbuero.de/">Klaus Hartl</a> for jQuery Cookie.
		<a href="http://www.sedgeman.com/" class="blank">Luke Sedgeman</a> for his pixel people.
		<a href="http://keith-wood.name/">Keith Wood</a> for jQuery Countdown.
		<a href="http://vision-media.ca/">Vision Media</a> for the sparks effect.
		<a href="http://elliottkember.com/">Elliott Kember</a> for the spin effect.
		<a href="http://lucasbaltes.com/">Lucas Baltes</a> for his Figlet PHP class.
		<a href="http://bananarenders.com/">Bananarenders</a> for the original TR2N background.
	</p>
	<p>I\'d also like to thank Rachael for being patient with me while working on this, George McGinley Smith for his work on easing methods and an  unknown person for the remade <a href="images/background-konami.png" class="view">Contra logo</a>. Last but not least, I\'d like to thank John Malkovich for being John Malkovich.</p>',

'porn' =>'<pre>Perfect Breasts             <span class="pink">(o)(o)</span>
Fake Silicone Breasts       <span class="pink">( + )( + )</span>
High Nipple Breasts         <span class="pink">(*)(*)</span>
Big Nipple Breasts          <span class="pink">(@)(@)</span>
A Cups                      <span class="pink">oo</span>
D Cups                      <span class="pink">{ O }{ O }</span>
Wonder Bra Breasts          <span class="pink">(oYo)</span>
Cold Breasts                <span class="pink">( ^ )( ^ )</span>
Lopsided Breasts            <span class="pink">(o)(O)</span>
Pierced Breasts             <span class="pink">(Q)(Q)</span>
Hanging Tassels Breasts     <span class="pink">(p)(p)</span>
Against-The-Shower-Door     <span class="pink">(  -  )(  -  )</span>
Android Breasts             <span class="pink">|o||o|</span>
Porn Star Breasts           <span class="pink">($)($)</span></pre>');
?>