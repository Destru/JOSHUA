<div id="config" class="window">
	<h1>Configuration</h1>
	<h2>Presets</h2>
	<div id="presets" class="tiny">
		<div class="reset">Reset</div>
		<div class="gamer">Gamer</div>
		<div class="rachael">Rachael</div>
		<div class="pulsar">Pulsar</div>
		<div class="identity">Identity</div>
	</div>
	<h2>Themes</h2>
	<div id="themes" class="tiny">
<?php foreach(scandir("themes") as $file) {
		if(stristr($file, '.css')) {
			$title = str_replace('.css','',$file);
			print "\t\t".'<div class="'.$title.'">'.$title.'</div>'."\n";
		}
	} ?>
	</div>
	<div class="next-gen extra">
		<h2>Background</h2>
		<div id="backgrounds" class="tiny">
			<div class="none">None</div>
			<div class="atari">Atari</div>
			<div class="pirate">Pirate</div>
			<div class="rachael">Rachael</div>
			<div class="sleep">Sleep</div>
		</div>
	</div>
	<h2>Effects</h2>
	<div id="fx" class="tiny">
		<div class="none">None</div>
		<div class="sparks">Sparks</div>
		<div class="malkovich">Malkovich</div>
		<div class="draw">Draw</div>
		<div class="spin">Spin</div>
	</div>
	<div class="tron extra">
		<h2>Team</h2>
		<div class="tiny">
			<div class="blue"></div>
			<div class="purple"></div>
			<div class="pink"></div>
			<div class="red"></div>
			<div class="orange"></div>
			<div class="yellow"></div>
			<div class="green"></div>
		</div>
	</div>
	<div class="next-gen extra">
		<div id="sliders">
			<div class="frame"><div id="opacity" class="slider">Opacity</div></div>
		</div>
	</div>
</div>