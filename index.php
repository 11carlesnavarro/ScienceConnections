<?php // Do not put any HTML above this line

session_start();

if ( isset($_SESSION['name']) ) {
	header('Location: groupProj.php');
  return;
}

$failure = false;

if ( isset($_SESSION['failure']) ) {
  $failure = htmlentities($_SESSION['failure']);

  unset($_SESSION['failure']);
}

$success = false;

if ( isset($_SESSION['message']) ) {
  $success = htmlentities($_SESSION['message']);

  unset($_SESSION['message']);
}



// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['password']) ) 
{
  $pass = htmlentities($_POST['password']);
  $email = htmlentities($_POST['email']);

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

  $check = hash('md5', $_POST['password']);
  $stmt = $pdo->prepare("
    SELECT * FROM users
    WHERE email = :email AND password = :password
  ");

  $stmt->execute(array(':email' => $_POST['email'], ':password' => $check));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row !== false) 
  {
      error_log("Login success ".$email);
      $_SESSION['name'] = $row['name'];
      $_SESSION['user_id'] = $row['user_id'];

      header("Location: groupProj.php");
      return;
  }

  error_log("Login fail ".$pass." $check");
  $_SESSION['failure'] = "Incorrect mail or password";

  header("Location: index.php");
  return;
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Log in Science Connections</title>

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
      <a class="btn btn-alt" href="signin.php">Sign in</a>
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

        <form class="register-form" action="#" method="post" >

          <fieldset class="register-group">
          <?php
                    // Note triple not equals and think how badly double
                    // not equals would work here...
                    if ( $failure !== false ) 
                    {
                        // Look closely at the use of single and double quotes
                        echo(
                            '<p style="color: red;" class="col-sm-10 col-sm-offset-2">'.
                                htmlentities($failure).
                            "</p>\n"
                        );
                    }

                    if ( $success !== false ) 
                    {
                    // Look closely at the use of single and double quotes
                      echo(
                        '<p style="color: green;" class="col-sm-10 col-sm-offset-2">'.
                            htmlentities($success).
                        "</p>\n"
                      );
                    }
                  
          ?>
              <!--  fake fields are a workaround for chrome/opera autofill getting the wrong fields -->
              <label>
              <input id="mail" style="display:none" type="text" name="fakeumailmbered">
              </label>
              <label>
              <input id="password" style="display:none" type="password" name="fakepasswordremembered">
              </label>   
              <!--
              <input autocomplete="nope"> turns off autocomplete on many other browsers that don't respect
              the form's "off", but not for "password" inputs.
              -->
            <label>
              Email

              <input id="real-mail" type="email" name="email" autocomplete="nope" placeholder="Email address" required>
              <!--<input type="email" name="email" placeholder="Email address" required>-->

            </label>

            <label>
              Password
              <input type="password" name="password" placeholder="Password" autocomplete="new-password" />
            </label>

          </fieldset>
          <div class= sign-in>
            <input class="btn btn-default" type="submit" name="submit" value="Log in">
          <p> Don't you have an account? <a href=signin.php> Sign in.</a></p>
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
