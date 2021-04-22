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

    <title>Science Connections</title>



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
      <a class="btn btn-alt" href="helpers/logout.php">Log Out</a>
      </h3>

      <nav class="nav primary-nav">
        <a href="groupProj.php">Home</a>
        <a href="runR.php">Text Analysis</a>
        <a href="paperSearch.php">Articles</a>
        <a href="History.php">History</a>
      </nav>

    </header>
    <!-- End navbar -->

    <!-- Hero -->

    <section class="hero container">

      <h2>Dedicated to analyze your research papers</h2>

      <p>We will analyse the paper that you provide to us from PubMed, finding the articles that contain the same keywords than your paper. </p>

      <form class="paper-form" action="ParameterSearch.php" method="post">

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
            <input class="submit-form" type="text" name="doi" placeholder="Enter PubMed ID " required>
          </label>

        </fieldset>

        <input class="btn btn-paper" type="submit" name="submit" value="Submit">
      </form>

      

    </section>

    <section class="row">
      <div class="grid">
      <!-- Researchers -->

      <section class="teaser col-1-3">
        <a href="runR.php">
          <h5>Text Analysis</h5>
          <img src="img/researcher.jpg" alt="Text Analysis">
          <h3>Personalized PubMed Analysis</h3>
        </a>
        <p>Based on the MeSH terms you choose, we will make different analysis of the papers found in the PubMed database. </p>
      </section><!--

      Articles

      --><section class="teaser col-1-3">
        <a href="paperSearch.php">
          <h5>Articles</h5>
          <img src="img/article.jpeg" alt="Articles">
          <h3>The best papers</h3>
        </a>
        <p>Find and read the best papers of the field of your chice.</p>
      </section><!--

      Interactivity

      --><section class="teaser col-1-3">
        <a href="History.php">
          <h5>History</h5>
          <img src="img/map.jpeg" alt="Interactive map">
          <h3>Analysis History</h3>
        </a>
        <p>The history of your previous text analysis on PubMed articles, you will have the necessary information to reproduce them again.</p>
      </section>
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