<?php

//check if user clicked the button "login" to access this page
if (isset($_POST['login-submit'])) {

  require 'dbh.inc.php';

  $mailuid = $_POST['mailuid'];
  $password = $_POST['pwd'];
  $phone = $_POST['phone'];

  //check any emptyfields
  if (empty($mailuid) || empty($password)) {
      //send username and email back to the main page
      header("Location: ../index.php?error=emptyfields");
      //stop the below codes running
      exit();
  }
  else {
    $sql = "SELECT * FROM users WHERE uidUsers=? OR emailUsers=?;";
    $stmt = mysqli_stmt_init($conn);
    //check for errors
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../index.php?error=sqlerror");
      exit();
    }
    //grab the information from the sql database
    else {
      mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      //check if the result takes nothing
      if ($row = mysqli_fetch_assoc($result)) {
        //check if password matches
        $pwdCheck = password_verify($password, $row['pwdUsers']);
        if ($pwdCheck == false) {
          header("Location: ../index.php?error=wrongpwd");
          exit();
        }
        else if ($pwdCheck == true) {

          /* start of two factor */

          //read in email from the database
          $email = $row['emailUsers'];

          //check the retrieved phone number with the user input's phone number
          $phoneCheck = password_verify($phone, $row['phoneUsers']);

          if ($phoneCheck == false) {
            header("Location: ../index.php?error=wrongphone");
            exit();
          }
          else if ($phoneCheck == true) {
            //start the session to log in the website
            session_start();
            $_SESSION['userId'] = $row['idUsers'];
            $_SESSION['userUid'] = $row['uidUsers'];

            //generate a designated ID named "selector"
            $selector = bin2hex(random_bytes(8));
            //set when the token expires
            $expires = date("U") + 1800; //code expires in 30 minutes
            //generate a random pin
            $randomPin = rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
            $hashedPin = password_hash($randomPin, PASSWORD_DEFAULT);

            //connect to the database
            require 'dbh.inc.php';
            //clear up the data in the database
            $sql = "DELETE FROM twofactor WHERE pinEmail=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was an error!";
              exit();
            } else {
              //execute the sql statement in the database
              //tell what to replace the question mark
              mysqli_stmt_bind_param($stmt, "s", $email);
              mysqli_stmt_execute($stmt);
            }
            //insert pin token in the database
            //why question mark? we shouldn't put any unproducted data right into the database
            $sql = "INSERT INTO twofactor (pinEmail, pinToken, pinExpires, pinSelector) VALUES (?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "There was an error!";
              exit();
            } else {
              //execute the sql statement in the database
              //tell what to replace the question mark
              mysqli_stmt_bind_param($stmt, "ssss", $email, $hashedPin, $expires, $selector);
              mysqli_stmt_execute($stmt);
            }
            //close statement
            mysqli_stmt_close($stmt);
            //close connection
            mysqli_close($conn);


            /* Start of Twilio */
            include '../Twilio/vendor/autoload.php';

            $sid = ''; //Please enter your Twilio SID
            $token = ''; //Please enter your Twilio Token
            $client = new Twilio\Rest\Client($sid, $token);

            $message = 'Please insert the following code in the blank space shown in the website Secret Note:';
            $message .= $randomPin;

            $client->messages->create(
              '9999999999', array ( //Please enter a sender's phone number to show for receivers
                  'from' => '+19999999999', //Please enter a sender's phone number that you obtained from Twilio
                  'body' => $message
              )
            );
            /* End of Twilio */
          }

          /* PHPMailer Set Up */
          // require_once('../PHPMailer/PHPMailerAutoload.php');
          // $mail = new PHPMailer(true);
          // try {
          //   $mail->isSMTP();
          //   $mail->SMTPAuth = true;
          //   $mail->SMTPSecure = 'ssl';
          //   $mail->Host = 'smtp.gmail.com';
          //   $mail->Port = '465';
          //   $mail->isHTML(true);
          //   $mail->Username = ''; //Please enter your gmail address
          //   $mail->Password = ''; //Please enter your gmail password
          //   $mail->setFrom(''); //Please enter your gmail address
          //   $mail->Subject = 'Insert the code for Secret Note';
          //   $mail->Body = '<p style="font-family=arial;"> Please insert the following code in the blank space shown in the website Secret Note:</p>';
          //   $mail->Body .= $randomPin;
          //   $mail->Body .= '<p style="font-family=arial;">If you did not make this reqeust, please ignore this email.</p>';
          //   $mail->addAddress($email);
          //   $mail->send();
          //   echo 'Message has been sent';
          // }
          // catch (Exception $e) {
          //   echo "Message could not be set. Mailer Error: {$mail->ErrorInfo}";
          // }
          /* End of PHPMailer */

          //move to the two factor authentification page
          header("Location: ../twofactor.php?setpin=success&selector=" . $selector);
          exit();
        }
        else {
          header("Location: ../index.php?error=wrongpwd");
          exit();
        }
      }
      //show error message if data is not found
      else {
        header("Location: ../index.php?error=nouser");
        exit();
      }
    }
  }
}
else {
  header("Location: ../index.php");
  exit();
}
