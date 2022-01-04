<?php
//error_reporting(E_ERROR);
$title = "Scratch that";
$password = 'password';
$theme = "light";
$footer = "I really 🧡 <a href='https://www.paypal.com/paypalme/dmpop'>coffee</a>";
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">

<!-- Author: Dmitri Popov, dmpop@linux.com
	 License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/classless.css" />
	<link rel="stylesheet" href="css/themes.css" />
	<style>
		textarea {
			font-size: 15px;
			width: 100%;
			height: 15em;
			line-height: 1.9;
			margin-top: 2em;
		}
	</style>
	<!-- Suppress form re-submit prompt on refresh -->
	<script>
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>
</head>

<body>
	<div class="card text-center">
		<div style="margin-top: 1em; margin-bottom: 1em;">
			<img style="display: inline; height: 2.5em; vertical-align: middle;" src="favicon.svg" alt="logo" />
			<h1 style="display: inline; margin-top: 0em; vertical-align: middle; letter-spacing: 3px;"><?php echo $title; ?></h1>
		</div>
		<hr>
		<?php
		$dir = 'content';
		$mdfile = $dir . "/content.md";
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
			file_put_contents($mdfile, '');
		}

		function Read()
		{
			global $mdfile;
			echo file_get_contents($mdfile);
		}
		function Write()
		{
			global $dir;
			global $mdfile;
			copy($mdfile, $dir . DIRECTORY_SEPARATOR . 'version_' . date('Y-m-d-H-i-s') . '.md');
			$fp = fopen($mdfile, "w");
			$data = $_POST["text"];
			fwrite($fp, $data);
			fclose($fp);
		}
		?>
		<?php
		if (isset($_POST["save"])) {
			if ($_POST['password'] != $password) {
				echo '<script>';
				echo 'alert("Wrong password!")';
				echo '</script>';
				exit();
			}
			Write();
			echo '<script>';
			echo 'alert("Changes have been saved.")';
			echo '</script>';
		};
		if (isset($_POST["clean"])) {
			if ($_POST['password'] != $password) {
				echo '<script>';
				echo 'alert("Wrong password!")';
				echo '</script>';
				exit();
			}
			foreach (glob($dir . "/version_*") as $filename) {
				unlink($filename);
				echo '<script>';
				echo 'alert("All versions have been removed.")';
				echo '</script>';
			}
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<textarea name="text"><?php Read(); ?></textarea>
			<div>
				<label for='password'>Password:</label>
			</div>
			<div>
				<input type="password" name="password">
			</div>
			<button style="margin-bottom: 1.5em;" type="submit" name="save">Save</button>
			<button style="margin-top: 1.5em;" type="submit" name="clean">Clean</button>
		</form>
		<div style="margin-bottom: 1em;">
			<?php echo $footer; ?>
		</div>
	</div>
</body>

</html>