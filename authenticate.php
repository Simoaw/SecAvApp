<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'webapp';
$DATABASE_PASS = 'Tigrou008';
$DATABASE_NAME = 'webappdb';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}	
//First add
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['name'], $_POST['age']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the name and age fields!');
}

//Second add
// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT nom, age FROM user WHERE nom = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['name']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
	
	//Third add
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($nom, $age);
		$stmt->fetch();
		// Account exists, now we verify the password.
		// Note: remember to use password_hash in your registration file to store the hashed passwords.
		if ($_POST['age'] === $age) {
			// Verification success! User has logged-in!
			// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $_POST['name'];
			//$_SESSION['id'] = $id;
			echo 'Welcome ' . $_SESSION['name'] . '!';
		} else {
			// Incorrect password
			echo 'Incorrect name and/or age!';
		}
	} else {
		// Incorrect username
		echo 'Incorrect name and/or age!';
}


	$stmt->close();
}
?>