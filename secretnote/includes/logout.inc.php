<?php

session_start();
//deletes all values inside of the session (such as ID and password)
session_unset();
session_destroy();
//go back to the main page
header("Location: ../index.php");
