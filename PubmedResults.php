<?php // Do not put any HTML above this line

session_start();

if ( ! isset($_SESSION['name']) ) {

    $_SESSION['failure'] = "You must log in.";
      header('Location: index.php');
    return;
  }

if (isset ($_SESSION['pubmed_title'])) {
    $title = $_SESSION['pubmed_title'];
    unset($_SESSION['pubmed_title']);
}

?>

<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>PubMed Outcome Science Connections</title>

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

        <div class = "plot div">
        <label> 
        <h2 style="position:relative; left:15%"> <?php echo "$title" ?> </h2>
        <br>
        <img src='Outputs/temp.png'> 
        </label>
        </div>

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