<?php

if (isset($_POST['twofactor-submit'])) {

  //grab selector
  $selector = $_POST["selector"];
  $pin = $_POST["code"];

  //set current date
  $currentDate = date("U");


  //already in the folder "include", so don't need to write "include/"
  require 'dbh.inc.php';

  //send the pin to the SQLiteDatabase
  $sql = "SELECT * FROM twofactor WHERE pinExpires >= ? AND pinSelector = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql))
  {
    echo "There was an error!";
    exit();
  }
  //grab the infomration from the sql database
  else
  {
    //execute the sql statement in the database
    //tell what to replace the question mark
    mysqli_stmt_bind_param($stmt, "ss", $currentDate, $selector);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    //check if the result takes nothing
    if (!$row = mysqli_fetch_assoc($result))
    {
      echo "You need to resubmit your two-factor authentification request.";
      exit();
    }
    else
    {
      //check the hashed pin
      // $convertedPin = hex2bin($pin);
      $pinCheck = password_verify($pin, $row['pinToken']);
      if ($pinCheck == false)
      {
        echo "The pin is wrong!";
        header("Location: ../twofactor.php?error=wrongpin");
        exit();
      }
      else if ($pinCheck == true)
      {
        //two factor authentification
        echo "Two factor successful!";
        header("Location: ../index.php?login=success");
        exit();
      }
      else
      {
        echo "There was an error!";

        exit();
      }
    }
  }
}
else {
  header("Location: ../index.php");
  exit();
}
