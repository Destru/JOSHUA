<?php // sudo
if ($command == "sudo") {
	if (empty($option)) error('password');
	else {
		if ($dump == "sudo make me a sandwich") output('<p class="joshua">'.$joshua.'Okay.');
		else {
			if (hash('sha512', $option) == $keys['sudo']) {
				$_SESSION['sudo'] = 1;
				output('<p class="joshua">'.$joshua.'Authentification successful.');
			}
			else {
				unset($_SESSION['sudo']);
				error('password');
			}
		}
	}
}

// pseudo
$pseudo = array('reply', 'invalid', 'update');
if (in_array($command, $pseudo)) {
	if (isset($_SESSION['sudo'])) {
		// reply
		if ($command == "reply") {
			$storage = "msg.data";
			if (!file_exists($storage)) file_put_contents($storage, null);
			$message = trim(str_replace($command, '', $dump));
			if (strlen($message) > 0) {
				$fp = fopen($storage, 'a');
				fwrite($fp, date("d/m/y").'^<span class="light">'.$message.'</span>^127.0.0.1'."\n");
				fclose($fp);
				output('<p class="joshua">'.$joshua.'Reply stored.');
			}
			else output('<p class="error">'.$joshua.'Reply can\'t be empty.');
		}

		// invalid
		else if ($command == "invalid") {
			$storage = "invalid.data";
			if (!file_exists($storage)) file_put_contents($storage, null);
			if (isset($option) && $option == "clear") {
				$fp = fopen($storage, 'w');
				fclose($fp);
				output('<p class="joshua">'.$joshua.'Invalid command log cleared.');
			}
			else {
				$db = dbFile($storage);
				array_unique($db);
				output(implodeHuman($db));
			}
		}

		// update
		else if ($command == "update") {
			update(true);
			output('<p class="joshua">'.$joshua.'Updated static data.');

		}
	}
	else error('auth');
}

// *nix
$nix = array('ls', 'ps', 'cd', 'top', 'rm', 'cp', 'who', 'kill', 'll', 'df', 'mkdir',
	'grep', 'man', 'wget', 'rsync', 'cat', 'tail', 'ifconfig', 'ipconfig', 'del',
	'make', 'wget', 'curl', 'pwd', 'dir', 'mysql', 'su', 'netstat', 'login', 'ssh',
	'irssi', 'shutdown', 'mail', 'email', 'vi', 'vim', 'emacs', 'subl');
if (in_array($command, $nix)) {
	if (isset($_SESSION['sudo'])) {
		if ($command == "ll") $output = run('ls -al');
		elseif ($command == "df") $output = run('df -h');
		elseif ($command == "pwd") $output = run('pwd');
		elseif ($command == "ifconfig") $output = run('ifconfig');
		elseif ($command == "who") $output = run('who');
		elseif ($command == "ps") $output = run('ps aux');
		else $output = 'Command not implemented.';
		output('<pre>'.$output.'</pre>');
	}
	else error('auth');
}

?>
