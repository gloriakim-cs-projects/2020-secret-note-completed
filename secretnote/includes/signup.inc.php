<?php
if (isset($_POST['signup-submit'])) {

  //already in the folder "include", so don't need to write "include/"
  require 'dbh.inc.php';

  //what goes incide of '' is the input's name from singup.php
  $username = $_POST['uid'];
  $email = $_POST['mail'];
  $phone = $_POST['phone'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwd-repeat'];
  //check user mistakes
  if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($passwordRepeat)) {
      //send username, email, and phone back to the signup page
      header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail=".$email."&phone=".$phone);
      //stop the below codes running
      exit();
  }
  //check for invalid username & email address & phone
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username) && !preg_match("/^[0-9]{10}+$/", $phone)) {
    //send nothing back to the signup page
    header("Location: ../signup.php?error=invalidmailuidphone");
    exit();
  }
  //check for invalid email address
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //send userid & phone back to the signup page
    header("Location: ../signup.php?error=invalidmail&uid=".$username."&phone=".$phone);
    exit();
  }
  //check for invalid username
  else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    //send email &phone back to the signup page
    header("Location: ../signup.php?error=invaliduid&mail=".$email."&phone=".$phone);
    exit();
  }
  //check for invalid password
  else if (!preg_match("/^[0-9]{10}+$/", $phone)) {
    //send email & userid back to the signup page
    header("Location: ../signup.php?error=invalidphone&mail=".$email."&uid=".$username);
    exit();
  }
  //check for Password
  else if ($password !== $passwordRepeat) {
    //send username and email back to the signup page
    header("Location: ../signup.php?error=passwordcheck&uid=".$username."&mail=".$email."&phone=".$phone);
    exit();
  }
  else {
    $sql = "SELECT uidUsers FROM users WHERE uidUsers=?;";
    //connect with the database from dbh.inc.php
    //stmt = statement
    $stmt = mysqli_stmt_init($conn);
    //check errors first in php codes
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../signup.php?error=sqlerror");
      exit();
    }
    //no error
    else {
      mysqli_stmt_bind_param($stmt, "s", $username); //s = string, i = integer, d = double (basic text = string)
      mysqli_stmt_execute($stmt);
      //store the result in the variable stmt
      mysqli_stmt_store_result($stmt);
      //check the number of rows (which should be 1 (no user match) or 0 (one user match))
      $resultCheck = mysqli_stmt_num_rows($stmt);
      if ($resultCheck > 0) {
        header("Location: ../signup.php?error=usertaken&mail=".$email);
        exit();
      }
      else {
        $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers, phoneUsers) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        //check for errors
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../signup.php?error=sqlerror");
          exit();
        }
        else {
          //hash pasword for security purposes
          //automatically updates this hashing method (DH% and SHE is outdated)
          $hashedPhone = password_hash($phone, PASSWORD_DEFAULT);
          $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
          //insert data in database
          mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashedPwd, $hashedPhone); //s = string, i = integer, d = double (basic text = string)
          mysqli_stmt_execute($stmt);
          header("Location: ../signup.php?signup=success");
          exit();
        }
      }
    }
  }
  //close the statement of the database
  mysqli_stmt_close($stmt);
  //close the connection of the database
  mysqli_close($conn);
}
// if user did not visit this page without clicking the sign-up button, do below
else {
  header("Location: ../signup.php");
  exit();
}
