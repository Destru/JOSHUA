<?php if(!preg_match("EVE-minibrowser",getenv("HTTP_USER_AGENT"))) header("Location: http://binaerpilot.no/alexander/eve/cheatsheets.html");
if(!empty($_GET['p'])) { $p = $_GET['p']; } else {
	// track this shit old school style
	$fp = fopen("counter.data", "r");
	$count = fread($fp, 1024); fclose($fp); $count = $count + 1;
	$fp = fopen("counter.data", "w"); fwrite($fp, $count); fclose($fp);
	$p = "main";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Bin&auml;rpilot EVE Resource Node</title>
	<link rel="stylesheet" type="text/css" href="igb.css">
</head>
<body>
<div id="wrapper">

<table width="100%" border="0" cellspacing="2" cellpadding="6">
	<tr>
		<td class="darker menu">
			<span class="highlight">[IGB]</span> <b>Bin&auml;rpilot EVE Resource Node</b>
			<a href="/">NPC Damage</a> |
			<a href="missions.html">Missions</a> |
			<a href="learning.html">Learning</a> | 
			<a href="fittings.html">Fittings</a>
		</td>
	</tr>
</table>
<br>

<?php include $p.'.html'; ?>

<br>
<?php if($p == "main") print '<span class="counter">VISITOR#'.$count; ?></span>
<br>&nbsp;

</div>
</body>
</html>