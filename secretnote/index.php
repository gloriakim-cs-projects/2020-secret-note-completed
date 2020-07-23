<?php
  require "header.php";
?>

  <main>
    <div class="wrapper-main">
      <section class="section-default">
        <?php
          //check if a session is available (if so, that means someone logged in)
          if (isset($_SESSION['userId'])) {
              echo '<p class="logout-status">You are logged in!</p>';
          }
          else {
            echo '<p class="logout-status">You are logged out!</p>';
          }
        ?>
      </section>
    </div>
  </main>

<?php
  require "footer.php";
?>
