<?php

// first, require your class
require_once("author.php");
// use the constructor to create an object
$author = new Author(null,"yay!", "object oriented!", "purple", "flying");
// connect to mySQL and populate the database
//yes, this is bad - but we'll isolate these parameters later...
try {
	//tell mysqli to throw exceptions
	mysqli_report(MYSQLI_REPORT_STRICT);

	//now go ahead and connect
	$mysqli = new mysqli("localhost", "jking", "wahulantouchlucre", "jking");

	//now, insert into mySQL
	$author->insert($mysqli);

	// finally, disconnect from mySQL
	$mysqli->close();

	//var_dump the result to affirm we got a real primary key
	var_dump($author);
	}
	catch(Exception $exception){
	// echo the error message and location for now
	echo "Exception: ". $exception->getMessage() . "<br />";
	echo $exception->getFile() . ":". $exception->getLine();
}


?>