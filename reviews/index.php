<?php if(!empty($_GET['no'])) $no = $_GET['no']; if(!empty($_GET['en'])) $en = $_GET['en']; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php if(isset($no)) echo $no.' - '; ?>Reviews of really bad movies</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="robots" content="index,follow,noarchive" />
	<link rel="stylesheet" type="text/css" href="screen.css" />
	<link rel="shortcut icon" href="http://binaerpilot.no/favicon.ico" />
	<script type="text/javascript" src="http://binaerpilot.no/alexander/scripts/jquery.js"></script>
</head>

<body>
<div id="wrapper">
	<div id="header"><a href="http://binaerpilot.no/alexander/reviews/"><img src="images/logo.png" alt="" /></a></div>
	<div id="content">
		<?php
		if(isset($no) && file_exists('no/'.$no.'.html')) {
			print '<h1><a href="http://www.imdb.com/find?q='.$no.'">'.$no.'</a></h1>';
			print '<div class="picture"><a href="http://www.imdb.com/find?q='.$no.'"><img src="no/'.$no.'.jpg" alt="" /></a></div>';
			print '<div id="review">';
			include 'no/'.$no.'.html';
			print '</div>';
		}
		else unset($no);

		if(isset($en) && file_exists('en/'.$en.'.html')) {
			print '<h1><a href="http://www.imdb.com/find?q='.$en.'">'.$en.'</a></h1>';
			print '<div class="picture"><a href="http://www.imdb.com/find?q='.$en.'"><img src="en/'.$en.'.jpg" alt="" /></a></div>';
			print '<div id="review">';
			include 'en/'.$en.'.html';
			print '</div>';
		}
		else unset($en);

		if(!isset($no) && !isset($en)) {
		?>

		<h1>Watching the worst of the worst</h1>

		<p>
		One day we had this great idea.
		<em>"Hey guys, let's watch all <a href="http://www.imdb.com/chart/bottom">the worst movies</a> in the world!"</em>
		Alright, so it might not have been the greatest of ideas.
		When I think of all the hours wasted, in agony and in pain, if I didn't know any better I'd say this little club of ours was some sort of S&amp;M cult.
		And if you're thinking <em>"Well, there's not that many movies in the list."</em>, please observe that these are only the ones we bothered reviewing.
		We've seen at least three times this much. Yes, they were all bad.
		</p>

		<h2>The rating system</h2>

		<p>
		I realize the stars might be misguiding, movies are judged by <em>entertainment value</em> and not quality.
		All the movies we see are shit, thus if we judged by quality every single one of these would get the lowest of scores.
		To avoid this we judge by how much they make us laugh (or not).
		When we give a high rating it means we had a good time. Well, at least that's the idea.
		Some of these reviews are several years old now and consistency has never been one of our strong points.
		</p>
		<?php } ?>
		<p id="footer"><a href="http://binaerpilot.no/alexander/">Alexander St&oslash;ver</a> made this.</p>
	</div>
	<div id="menu">
		<ul class="menu">
			<li class="language">English Reviews</li>
			<?php
				$enHandle = opendir("en/");
				while ($file = readdir($enHandle)) { if(strpos($file,".html")) { $enFilenames[] = $file; } }
				sort($enFilenames);
				foreach ($enFilenames as $var) {
				  $name = str_replace('.html','',$var);
				  echo '<li><a href="'.$var.'">'.$name.'</a></li>';
				}
			?>
			<li class="language">Norske Anmeldelser</li>
			<?php
				$noHandle = opendir("no/");
				while ($file = readdir($noHandle)) { if(strpos($file,".html")) { $noFilenames[] = $file; } }
				sort($noFilenames);
				foreach ($noFilenames as $var) {
				  $name = str_replace('.html','',$var);
				  echo '<li><a href="'.$var.'">'.$name.'</a></li>';
				}
			?>
		</ul>
	</div>
	<br class="clear"/>
</div>
<?php include '../analytics.html'; ?>
</body>
</html>