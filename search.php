<?php

session_start();

if ( ! isset($_SESSION['name']) ) {

  $_SESSION['failure'] = "You must log in.";
	header('Location: index.php');
  return;
}

error_reporting(E_ALL ^ E_NOTICE);

###
### The key words are saved in the $Search variable seperated by a comma
###

$Search = "";
if(isset($_POST['sendkeywords'])){
  if (is_array($_POST['sendkeywords'])) {
    foreach($_POST['sendkeywords'] as $value){
      $value = str_replace("_", " ", $value);
      if ($Search == "") {
      	$Search = $value;
      } else {
      	$Search = $Search . "," . $value;
      }
    }
  } else {
    $value = $_POST['sendkeywords'];
    $Search = $value;
  }
} elseif (isset($_POST['query'])){
  if (is_array($_POST['query'])) {
    foreach($_POST['query'] as $value){
      $value = str_replace("_", " ", $value);
      if ($Search == "") {
      	$Search = $value;
      } else {
      	$Search = $Search . "," . $value;
      }
    }
  } else {
    $value = $_POST['query'];
    $Search = $value;
  }
}



// $Search is the variable with the keywords that enters data processing in following code

if (isset($_POST['hits'])) {
  $hits = $_POST['hits'];
} else {
  $hits = 50; // number of hits desired by output
}

//checking for number of hits, if higher than 80, send to maxhits error page
if ($hits > 80) {

  $_SESSION['failure'] = "Max num of hits are 80, sorry. Please try another search.";
  header('Location: paperSearch.php');
  return;

}

if (isset($_POST['selectd'])) {
  $selectd = $_POST['selectd']; // type of sorting. by most relevant, or by most recent
} else {
  $selectd = 0;
}


//checking for spaces in user's input. If got spaces, replace them with hyphens for the API call to work
if (strpos($Search, ' ') !== false){
	$Search = preg_replace('#[ -]+#', '-', $Search);
}else{
        ;
}

//setting sorting option
if ($selectd == '0'){

	$sortd = '';

}elseif ($selectd == '1'){

	$sortd = '%20sort_date:y';
}

//setting $data with API's query information. Info in json format

$command = 'https://www.ebi.ac.uk/europepmc/webservices/rest/search?query='. $Search . $sortd . '&resultType=core&pageSize='.$hits.'&format=json';

// Transforming json request into array. Just the dictionary with key 'resultList' is retreived
$data = file_get_contents($command);
$arr = json_decode($data,true);
$json = @$arr['resultList'];


// Traversing the json request in order to retreive the names of articles and publication year
foreach($json as $key => $value){
     if(is_array($value)){
          foreach($value as $k){
                foreach ($k as $r => $v){

			if($r == 'title'){

				$title[] = $v;

			 }elseif($r == 'pubYear'){

				$year[] = $v;

			 }elseif($r == 'fullTextUrlList'){

				foreach ($v as $w){

					$links[] = $w;
																	}
				}
			}
		}
	}
}

// checking for empty array. If no hits were found, take user to nohits error page
if(empty($title)){
  if (isset($_POST['sendkeywords'])) {
      $_SESSION['failure'] = 'Your query retreived no hits, sorry. Please try another search';
      header('Location: groupProj.php');
      return;
  } else {
      $_SESSION['failure'] = 'Your query retreived no hits, sorry. Please try another search';
      header('Location: paperSearch.php');
      return;
  }
}





// preprocessing array with info of urls, making sure there are not duplicates
// the original json request returns several urls linked to one paper, here we keep just one per hit
foreach ($links as $key => $value){

	$urls[] = array_unique($value);

}

// Defining function that traverses array of information of papers with core parameter (core parameter is on 
// the http request for json file)
function search_recursive_by_key($array, $searchkey){

	global $url; 

	foreach ($array as $key => $val) {

	if (is_array($val)){
		  search_recursive_by_key($val, $searchkey);
	  } else {

		  if($searchkey == $key){
			  if($searchkey == 'url'){

					 $url[] = $val;
				 }
		  }
	  }
  }
}

// executing function for getting urls in array
search_recursive_by_key($urls,'url');

// merging arrays for building table as hyperlinks
$papers = array();

foreach($url as $key => $value){

	$papers[$value] = [$title[$key], $year[$key]];
}

?>

<!-- end of controller -->

<!-- printing table -->

<!DOCTYPE html>

<html>


<!-- here are the styles used. I think we should build a css, and put header and footer so the hits page looks as 
part of the app. The two classes with the information are "Titles" and "thehits"... Titles is the body of the table, 
and thehits is just the sentence that describes the number of hits retreived by the query -->


<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Paper Search Connections</title>

    <!-- Custom styles for this template -->
    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:100,300,400">

    <meta charset="UTF-8">	
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

	  <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

</head>

<body>

  <!-- Begin navbar -->
  <header class="primary-header container group">

  <h1 class="logo">
  <a href="groupProj.php">Science <br> Connections</a>
  </h1>
  <h3 class="tagline">
  <a class="btn btn-alt" href="logout.php">Log out</a>
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

  <table class="center" border="0" cellspacing="2" cellpadding="4" id="myTable">
      <thead>
          <tr><!-- headers of table -->
        <th>Title </th>
        <th>Links to papers</th>
        <th>Publication Year</th>
          </tr>
      </thead>
      <tbody>
  	<?php foreach($papers as $key => $value) { ?>
		<tr>
      <td ><?= $value[0] ?></td>
      <td style="text-align:center"><a href="<?= $key ?>"> Link </a></td>
      <td ><?= $value[1] ?></td>
    </tr>
		<?php }?>
        </tbody>
    </table>
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
    
    <!-- Script -->

    <script>
    $(document).ready( function () {
    $('#myTable').DataTable();
    } );
    </script>

  </body>

</html>