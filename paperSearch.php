<?php

session_start();

if ( ! isset($_SESSION['name']) ) {

  $_SESSION['failure'] = "You must log in.";
	header('Location: index.php');
  return;
}


$status = false;

$failure = false;

if ( isset($_SESSION['failure']) ) {
  $failure = htmlentities($_SESSION['failure']);

  unset($_SESSION['failure']);
}

?>


<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Paper search Science Connections</title>

		<!-- Custom styles for this template -->
		<link href="css/custom.css" rel="stylesheet">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:100,300,400">
	</head>

	<body>

		<!-- Begin navbar -->

		<header class="primary-header container group">
			<h1 class="logo">
				<a href="groupProj.php">Science <br> Connections</a>
			</h1>

			<h3 class="tagline">
				<a class="btn btn-alt" href="helpers/logout.php">Log out</a>
			</h3>

			<nav class="nav primary-nav">
				<a href="groupProj.php">Home</a>
				<a href="runR.php">Text Analysis</a>
				<a href="paperSearch.php">Articles</a>
				<a href="History.php">History</a>
			</nav>
		</header>

		<!-- End navbar -->

		<section class="row">
			<div class="grid">
				<form class="register-form" action="search.php" method="post">

					<fieldset class="register-group">
					<?php
          				if ( $failure !== false ) 
          				{
              				// Look closely at the use of single and double quotes
              				echo(
                  			'<p style="color: red;" class="col-sm-10 col-sm-offset-2">'.
                      		htmlentities($failure).
                  			"</p>\n"
              				);
          				}
          			?>
						<label>
							My query   <!-- text input area  -->

							<input type="text" name="query" placeholder="Enter query" required>
						</label>
							<!-- End text input area text -->
	   
							<!--

	       <label for="api">Choose database:</label>
	
	       	<select name= "api" id="api">
	       		<option value="pubmed">Pubmed</option>
	       		<option value="option2">option2</option>
	       		<option value="option3">option3</option>
	       		<option value="option4">option4</option> 
	       </select>
	    -->

            
	    <label for="">Choose number of hits:
	    
	    <input type="number" id="hits" name="hits" value=50>
	    </label>

	    <label for="selectd">Sort by:
	    
	    <select name= "selectd" id="selectd">
		    <option value="0">Most relevant</option>
		    <option value="1">Most recent</option>
	    </select>
		</label>

		</fieldset>

	    <div class= sign-in>
		    <input class="btn btn-default" type="submit" name="submit" value="Search">
	    </div>
		
		</form>

		<p>
			<h4>How does this page work?</h4>
			<p>
			<strong>My query:</strong> This will be your search parameter. We will look for papers that contain this word in PubMed. <br>
			Query example: Bioinformatics. 
			</p>
			<p>
			<strong>Number of hits:</strong> Maximum number of papers you want to retrieve. The limit is 80.
			</p>
		</p>

			</div>
		</section>

		<!-- Footer -->
		<footer class="primary-footer container group">

			<small>&copy; Science Connections</small>

			<nav class="nav">
				<a href="groupProj.php">Home</a>
				<a href="runR.php">Text Analysis</a>
				<a href="paperSearch.php">Articles</a>
				<a href="History.php">History</a>
			</nav>
		</footer>
	</body>
</html>
