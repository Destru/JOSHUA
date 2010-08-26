<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Steal my code." />
	<meta name="keywords" content="php, scripts, code, free, steal, bite, source" />
	<meta name="robots" content="index,follow,noarchive" />
	<meta name="author" content="alexander@binaerpilot.no" />
	<meta name="language" content="en" />
	<title>Simple and clean code for you to steal</title>
	<link rel="shortcut icon" href="http://binaerpilot.no/favicon.ico" type="image/x-icon" />
</head>
<body>
<code><span style="color: #000000"><span style="color: #0000BB">&lt;?php
<br /></span><span style="color: #FF8000">//&nbsp;copyleft&nbsp;alexander&nbsp;støver&nbsp;&lt;alexander@binaerpilot.no&gt;
<br />//&nbsp;use&nbsp;these&nbsp;scripts&nbsp;for&nbsp;whatever&nbsp;you&nbsp;like,&nbsp;share&nbsp;and&nbsp;enjoy!

<br />
<br />//&nbsp;list&nbsp;the&nbsp;available&nbsp;scripts
<br /></span><span style="color: #0000BB">$dir&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">opendir</span><span style="color: #007700">(</span><span style="color: #DD0000">'.'</span><span style="color: #007700">);
<br />while&nbsp;(</span><span style="color: #0000BB">$file&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">readdir</span><span style="color: #007700">(</span><span style="color: #0000BB">$dir</span><span style="color: #007700">))&nbsp;{

<br />&nbsp;&nbsp;&nbsp;&nbsp;if(</span><span style="color: #0000BB">stristr</span><span style="color: #007700">(</span><span style="color: #0000BB">$file</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">'.phps'</span><span style="color: #007700">))&nbsp;print&nbsp;</span><span style="color: #DD0000">'&lt;a&nbsp;href='</span><span style="color: #007700">.</span><span style="color: #0000BB">$file</span><span style="color: #007700">.</span><span style="color: #DD0000">'&gt;'</span><span style="color: #007700">.</span><span style="color: #0000BB">$file</span><span style="color: #007700">.</span><span style="color: #DD0000">'&lt;/a&gt;&lt;br/&gt;'</span><span style="color: #007700">;

<br />}
<br /></span><span style="color: #0000BB">closedir</span><span style="color: #007700">(</span><span style="color: #0000BB">$dir</span><span style="color: #007700">);
<br /></span><span style="color: #0000BB">?&gt;</span>
</span>
<br/><br/>
<?php $dir = opendir('.');
while ($file = readdir($dir)) {
	if(stristr($file, '.phps')) print "\r".'<a href='.$file.'>'.$file.'</a><br/>';
}
closedir($dir); ?>
<br/>
<span style="color: #0000BB">&lt;?php
<br /></span><span style="color: #FF8000">//&nbsp;http://binaerpilot.no/alexander/</span>
<br/><span style="color: #0000BB">?&gt;</span>
</code>
<?php include '../analytics.html'; ?>
</body>
</html>
