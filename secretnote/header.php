<?php
  session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>

  <header>
    <div class="header-login">
      <img src="img/logo.png" alt="secret">
      <?php
        #show logout button only when users log in
        if (isset($_SESSION['userId'])) {
            echo '<form action="includes/logout.inc.php" method="post">
              <button type="submit" class="submit-box" name="logout-submit">Logout</button>
            </form>';
        }
        #show Login button only when users log out
        else {
          echo '<form action="includes/login.inc.php" method="post">
            <h1>Username</h1>
            <input type="text" class="input-box" name="mailuid" placeholder="Username">
            <h1>Password</h1>
            <input type="password" class="input-box" name="pwd" placeholder="Password">
            <h1>Phone Number</h1>
            <input type="text" class="input-box" name="phone" placeholder="Phone number">
            <button type="submit" class="submit-box" name="login-submit">Login</button>
          </form>
          <p> Do not have an account?
          <a href="signup.php" class="signup-box">Signup</a></p>';
          // <a href="twofactor.php"> Forgot your password?</a>';
        }
      ?>
    </div>
  </header>
