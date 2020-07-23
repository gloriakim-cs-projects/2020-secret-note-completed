<?php
  require "header.php";
?>

  <main>
    <div class="wrapper-twofactor">
      <section class="section-default">
        <h1>Two Factor Authentication</h1><br>
        <p>Please type the code that is sent to your phone.</p>
        <!-- <p>If you have not received the code, please press
        <a href="resend-code.php" class="link">here.</a></p> -->

        <?php
          //if there is an error, run an error message for users
          if (isset($_GET["error"])) { //if there is the word "error" in URL
            if ($_GET["error"] == "emptyfields") {
              echo '<p class="twofactorerror">Fill in the field!</p>';
            }
            else if ($_GET["error"] == "invalidcode") {
              echo '<p class="twofactorerror">Invalid code!</p>';
            }
          }
          else if (isset($_GET["twofactor"])) {
            if ($_GET["twofactor"] == "success") {
            echo '<p class="twofactorsuccess">Login successful!</p>';
            }
          }

          //two-factor selector
          if (isset($_GET["selector"])) {
            $selector = $_GET["selector"];
            //check selector
            if (empty($selector))
            {
              echo "Could not validate your request!";
            }
            else
            {
              if (isset($_GET["error"])) { //if there is the word "error" in URL
                echo '<p class="twofactorerror">The code is incorrect!</p>';
              }
              if (ctype_xdigit($selector) !== false)
              {
               ?>
               <form class="form-signup" action="includes/twofactor.inc.php" method="post">
                 <input type="hidden" name="selector" value="<?php echo $selector ?>">
                 <input type="text" class="input-box" name="code" placeholder="Code">
                 <button type="submit" class="submit-box"name="twofactor-submit">Verify</button>
               </form>
               <?php
              }
              else {
                echo "The selection has an error!";
             }
            }
          }
          ?>


      </section>
    </div>
  </main>

<?php
  require "footer.php";
?>
