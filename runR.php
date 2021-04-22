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

$stmt = $pdo->prepare("
  SELECT user_id FROM users
  WHERE name = :name 
");

$stmt->execute(array(':name' => $_SESSION['name']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$id = $row['user_id']; 

?>


<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Text Analysis Science Connections</title>

		<!-- Custom styles for this template -->
		<link href="css/custom.css" rel="stylesheet">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:100,300,400">

        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
				<form class="register-form" action="#" method="post">

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
							Mesh query   <!-- text input area  -->

							<input type="text" name="query" placeholder="Ex: Breast Cancer, Drug Treatment" required>
						</label>
							<!-- End text input area text -->
                        <label>
                            Select Publication Date
                            <input id="reportrange" name="reportrange" required>
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </label>
                        
                        <label>
                            Select analysis
                            <div class= analysis>
                            <label class="block"><input class="radio-button"  type="radio" name="analysis" value="articles_year" ><h6>Articles per month</h6></label>
                            <br>
                            <label class="block"><input  type="radio" name="analysis" value="wordcloud"><h6>Wordcloud</h6></label>
                            <br>
                            <label class="block"><input  type="radio" name="analysis" value="common_words"><h6>Common words</h6></label>
                            <br>
                            <label class="block"><input  type="radio" name="analysis" value="common_bigrams"><h6>Common bigrams</h6></label>
                            <br>
                            </div>
                        </label>

		            </fieldset>
                    

	    <div class= sign-in>
		    <input class="btn btn-default" type="submit" name="submit" value="Search">
	    </div>

        <?php

            if(isset($_POST['query']) && isset($_POST['analysis']) && isset($_POST['reportrange'])) {

            # Processing mesh terms
            $mesh = $_POST['query'];

            $mesh_terms = str_replace(",", "[Mesh],AND", $mesh);
            $mesh_terms = str_replace(" ", ",", $mesh_terms);
            $mesh_terms .= "[Mesh]";

            # Processing dates 
            $date = $_POST['reportrange'];
            $init_date = substr($date, 0, -13);
            $init_date = date("Y/m/d", strtotime($init_date));  

            $end_date = substr($date, -10);
            $end_date = date("Y/m/d", strtotime($end_date));  

            $date_range = $init_date.":".$end_date; 
    
            # Pubmed query 

            $pubmed_query = $mesh_terms.",AN"."D,".$date_range.",[DP]";
            $pubmed_message = "Processing your query, this may take a while...";
            
        
            # Executing R

            $directory = getcwd();

            if ($_POST['analysis'] == "articles_year") {

                $analysis_type = "Articles per month";
                $_SESSION['pubmed_title'] = "Published articles per month";
                exec('/usr/bin/Rscript Rscripts/articles_year.R '.$pubmed_query." ".$directory );

            } elseif ($_POST['analysis'] == "wordcloud") {

                $analysis_type = "Wordcloud";
                $_SESSION['pubmed_title'] = "Wordcloud";
                exec('/usr/bin/Rscript Rscripts/wordcloud.R '.$pubmed_query." ".$directory);

            } elseif ($_POST['analysis'] == "common_words") {

                $analysis_type = "Common words";
                $_SESSION['pubmed_title'] = "Most common words";
                exec('/usr/bin/Rscript Rscripts/common_words.R '.$pubmed_query." ".$directory);

            } elseif ($_POST['analysis'] == "common_bigrams") {

                $analysis_type = "Common bigrams";
                $_SESSION['pubmed_title'] = "Most common bigrams";
                exec('/usr/bin/Rscript Rscripts/common_bigrams.R '.$pubmed_query." ".$directory);
            }

            # Inserting Search data in the database

            $meshquery = str_replace(",", " ", $mesh_terms);

            $stmt = $pdo->prepare("
            INSERT INTO Pubmed_Searches (user_id, meshquery, analysis, daterange)
            VALUES (:user_id, :meshquery, :analysis, :daterange)
            ");
        
            $stmt->execute([
                ':user_id' => $id,
                ':meshquery' => $meshquery, 
                ':analysis' => $analysis_type, 
                ':daterange' => $date_range,
            ]);
            
            header('Location: PubmedResults.php');
            return;

            }


        ?>

        </form>
        
        <p>
			<h4>How does this page work?</h4>
			<p>
			<strong>Mesh* query:</strong> This will be your search parameter. We will look for papers based on the set of Mesh terms you introduce. <br>
			Mesh query example: Breast Neoplasms, Therapy. 
			</p>
			<p>
			<strong>Publication Date:</strong> The range of publication date of the papers you want to analyse. 
            </p>
            <p>
            <strong>Select analysis:</strong> You can choose different types of analysis<br>
                - Articles per month: Papers published monthly in the topic.<br>
                - Wordcloud: A cloud of words, being bigger the words that appear more in the set of articles. <br>
                - Commond words: Plot to proportion of the 20 most appearing words in the set of articles. <br>
                - Commond bigrams: Plot to proportion of the 20 most appearing bigrams in the set of articles.
            </p>
            <p>
            
            * The Medical Subject Headings (MeSH) thesaurus is a controlled and hierarchically-organized vocabulary produced by the National Library of Medicine. It is used for indexing, cataloging, and searching of biomedical and health-related information. MeSH includes the subject headings appearing in MEDLINE/PubMed, the NLM Catalog, and other NLM databases.


            
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

        <script type="text/javascript">
        $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }   

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        });
        </script>

	</body>
</html>