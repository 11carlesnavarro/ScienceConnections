<?php

session_start();


if (isset($_REQUEST['term']))
{
	try 
	{
  	$pdo = new PDO("mysql:host=localhost;dbname=SciCon", "scicon", "cpr7654");
		// set the PDO error mode to exception
  	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{	
		echo "Connection failed: " . $e->getMessage();
		die();
	}
	
	$stmt = $pdo->prepare('
		SELECT name FROM linkedin_universities
		WHERE name LIKE :prefix'
	);

	$stmt->execute([
		':prefix' => $_REQUEST['term']."%"
	]);

	$retval = [];

	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) 
	{
		$retval[] = $row['name'];
	}

	echo(json_encode($retval, JSON_PRETTY_PRINT));

}
