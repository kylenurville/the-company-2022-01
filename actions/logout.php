<?php
session_start();
session_unset();                // Remove the values
session_destroy();              // Delete all session variables

header("location: ../views");   // go to views/index.php
exit;