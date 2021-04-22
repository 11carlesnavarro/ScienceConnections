<?php // Do not put any HTML above this line

session_start();

if ( ! isset($_SESSION['name']) ) {

  $_SESSION['failure'] = "You must log in.";
	header('Location: index.php');
  return;
}



// Check to see if we have some POST data, if we do process it
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


$all_searches = $pdo->query("SELECT * FROM Pubmed_Searches WHERE user_id=".$_SESSION['user_id']);

while ( $row = $all_searches->fetch(PDO::FETCH_OBJ) ) 
{
    $searches[] = $row;
}




?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Search History Science Connections</title>

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
      <table class="center" border="0" cellspacing="2" cellpadding="4" id="myTable">
      <thead>
          <tr><!-- headers of table -->
        <th>Analysis Type </th>
        <th>Mesh Query</th>
        <th>Publication Date Range </th>
          </tr>
      </thead>
      <tbody>
      <?php 
      if (isset($searches)) {
      foreach($searches as $search) { ?>
		<tr>
      <td ><?php echo $search->analysis; ?></td>
      <td style="text-align:center"><?php echo $search->meshquery; ?></td>
      <td style="text-align:center"><?php echo $search->daterange; ?></td>
    </tr>
		<?php }}?>
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
