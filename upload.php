<?php 
	require_once('parse.php');
	require_once('functions.php');

	if (isset($_FILES['routine'])) {
		if (is_suitable($_FILES['routine'])) {
			// $filename = './a.pdf';
			// var_dump(move_uploaded_file($_FILES['routine']['tmp_name'], $filename));
			$filename = $_FILES['routine']['tmp_name'];
			echo'<pre>';
			print_r($_FILES);
			echo'</pre>';

			$par = new Parse($filename);
			$classes = $par->main();
			echo "<pre>";
			print_r($classes);
			echo "</pre>";
			$timetable = new Timetable($classes);
			$timetable->save();
			echo "<pre>";
			print_r($classes);
			echo "</pre>";

		} else {
			echo "Invalid file type/size";
		}

	}
?>
<?php if (! isset($_FILES['routine'])): ?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Uplaod routine</title>
	</head>
	<body>

	<form method="post" enctype="multipart/form-data">
		<input id="file" type="file" name="routine" onchange="submit()">	
	</form>

	<script type="text/javascript">
		function submit() {
			input = document.getElementById('file');
			input.submit();
		}
	</script>
	</body>
	</html>
<?php endif ?>