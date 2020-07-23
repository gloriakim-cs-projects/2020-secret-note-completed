<?php
  require "header.php";
?>

  <main>
    <div class="wrapper-main">
      <section class="section-default">
        <h1>Signup</h1><br>
        <?php
          //if there is an error, run an error message for users
          if (isset($_GET["error"])) { //if there is the word "error" in URL
            if ($_GET["error"] == "emptyfields") {
              echo '<p class="signuperror">Fill in all fields!</p>';
            }
            else if ($_GET["error"] == "invalidmailuidphone") {
              echo '<p class="signuperror">Invalid username, email, and phone!</p>';
            }
            else if ($_GET["error"] == "invalidmail") {
              echo '<p class="signuperror">Invalid username!</p>';
            }
            else if ($_GET["error"] == "invaliduid") {
              echo '<p class="signuperror">Invalid email!</p>';
            }
            else if ($_GET["error"] == "invalidphone") {
              echo '<p class="signuperror">Invalid phone!</p>';
            }
            else if ($_GET["error"] == "passwordcheck") {
              echo '<p class="signuperror">Your passwords do not match!</p>';
            }
            else if ($_GET["error"] == "usertaken") {
              echo '<p class="signuperror">Username is already taken!</p>';
            }
          }
          else if (isset($_GET["signup"])) {
            if ($_GET["signup"] == "success") {
            echo '<p class="signupsuccess">Signup succsesful!</p>';
            }
          }
        ?>
        <form class="form-signup" action="includes/signup.inc.php" method="post">
          <input type="text" class="input-box" name="uid" placeholder="Username">
          <input type="text" class="input-box"name="mail" placeholder="E-mail">
          <input type="text" class="input-box"name="phone" placeholder="Phone">
          <input type="password" class="input-box"name="pwd" placeholder="Password">
          <input type="password" class="input-box"name="pwd-repeat" placeholder="Repeat Password">
          <button type="submit" class="submit-box"name="signup-submit">Signup</button>
        </form>
      </section>
    </div>
  </main>

<?php
  require "footer.php";
?>
