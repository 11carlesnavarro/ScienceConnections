
<?php
#print_r($_POST["doi"]."\n");


#$url = "https://doi.org/".$_POST["doi"];
#if (!empty($url)) {
#	$page = file_get_contents($url);	
#	if (!empty($page)) {
#		$outfile = "xtracomponents.html";
#		file_put_contents($outfile, $page);
#	}
#}




#$pattern = '/window.dataLayer = (.*);/';
// the following line prevents the browser from parsing this as HTML.
#header('Content-Type: text/plain');
#$contents = file_get_contents($outfile);
#$pattern = '/xtra(.*)ml/';
#if(preg_match_all($pattern, $contents, $matches)){
	#echo "found matches:\n";
#}
#else{
	#echo "no matches found";
#}

#print($matches[1][0]);
#print($contents);

session_start();

if ( ! isset($_SESSION['name']) ) {

  $_SESSION['failure'] = "You must log in.";
	header('Location: index.php');
  return;
}

if ($_POST["doi"]) {
  $url = "https://pubmed.ncbi.nlm.nih.gov/".$_POST["doi"]."/";

} else {
  
  $_SESSION['failure'] = "You must submit an article.";
	header('Location: groupProj.php');
  return;
}

if (!empty($url)) {
	$page = file_get_contents($url);	
	if (!empty($page)) {
		$outfile = "Outputs/xtracomponents.html";
		file_put_contents($outfile, $page);
	}
}

$contents = file_get_contents($outfile);

$pattern = '/name="keywords" content="(.*?)">/';
// the following line prevents the browser from parsing this as HTML.
#header('Content-Type: text/plain');
$contents = file_get_contents($outfile);
#$pattern = '/xtra(.*)ml/';
if(preg_match($pattern, $contents, $matches)){
	#echo "found matches:\n";
}
else{
	echo "no matches found";
}

$keywords = $matches[1];
$individualkeywords = explode(",", $keywords);
###
### in order to post the keywords containing spaces I have to replace those with underscores.
###
$SendingTheKeywords = str_replace(' ','_', explode(",", $keywords));
#print ($individualkeywords[0]."> > > ");

foreach ($individualkeywords as $word) {
	#print($word."< < > >");
	# code...
}

#print ($matches[1]);




#print($pattern."> > > > ");
#print($."> > > > ");
#print($matches[1]."< < < < ");
#$dataLayer = $matches[0];

#$DOM = new DOMDocument;
#$DOM -> loadHTML($outfile);
#$keywords = $DOM->getElementsByTagName('h1');

#for ($i = 0; $i < $keywords->length; $i++)
#	echo $keywords->item($i)->nodeValue . "<br/>";

#print_r($keywords);


#print_r($url);
?>

<!-- Main Form follows-->

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Key Words Science Connections</title>

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
        <form class= "parameter-search" name="MainForm" action="search.php" method="POST" enctype="multipart/form-data">
            <!--<div class="container text-left">
                <div class="col-md-4">
                    <div class="form-group">-->
                        <label>Your Keyword options:</label>
                        <br>
                        <br>
                        <div class="form-check">
                            <!-- input options from the keyword search -->
                            <?php 
                                $n = 0; 
                                foreach (array_keys($individualkeywords) as $individualkeyword ) {
                                  if ($n > 0) {?>
                                <input class="form-check-input" type="checkbox" name="sendkeywords[]" value =  <?= $SendingTheKeywords[$individualkeyword] ?> /> <?= $individualkeywords[$individualkeyword]."\n" ?> <br>                       
                            <?php } $n += 1; } ?>
                        </div>
                <br>
                <input class="btn btn-default" type="submit" name="submit" value="Submit">
            </div>
        </form>
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
