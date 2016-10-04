<?php 
	$user = 'sirjan';
	$password = 'password';
	$host = 'localhost';
	$database = 'timetable';

	$conn = mysqli_connect($host, $user, $password, $database);
	// if($conn) {
	// 	echo 'nice'; 
	// } 
	// else {
	//  echo 'bad';
	// }
 	if(! $conn) {
 		die('Failed to connect to database.<br> 
 			Please execute db_setup.sql in your mysql. <br>
 			If it still does not work, manually execute following command in mysql <br>
 			<b> GRANT ALL PRIVILEGES ON timetable.* TO \'sirjan\'@\'localhost\' IDENTIFIED BY \'password\';</b>
 			');
 	}
 ?>