<?php
session_start();
ob_start();
// Destroy session
session_unset();
session_destroy();
setcookie('userLoggedIn', '', time() - 3600, '/');
setcookie('token', '', time() - 3600, '/');
setcookie('message', '', time() - 3600, '/');
header("location: ../Views/Login.php");
