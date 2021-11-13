<?php
session_start();
ob_start();
include_once('../config.php');

if (isset($_COOKIE['message'])) {
    alert($_COOKIE['message']);
}

function alert($msg)
{
    echo "<script type='text/javascript'>alert('$msg');</script>";
    setcookie('message', '', time() - 3600, '/');
}
// Validate if logged in
if (isset($_SESSION['username'])) {
    header("location: /index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../Assets/css/register.css">
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>

<body class="page-1">
    <div class="container center ">
        <div class="card grid-container">
            <div class="card-left grid-item">
                <div class="logo">
                    <img src="../Assets/images/dorayaki.png" alt="" class="image">
                </div>
            </div>
            <div class="card-right grid-item">
                <div class="form">
                    <div class="head-form">
                        <h1>Register</h1>
                    </div>
                    <div class="form-input">
                        <form method="POST" action="../Middlewares/register.php">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <div class="form-input">
                                    <input onkeyup="checkUsername()" pattern="^[a-zA-Z0-9_]*$" minlength="5" id="username" name="username" placeholder="username" required>
                                    <div id="username-not-available" class="username-not-available"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <div class="form-input">
                                    <input type="email" id="email" name="email" placeholder="example@global.com" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="form-input">
                                    <input type="password" id="password" name="password" placeholder="password" minlength="5" required>
                                </div>
                            </div>
                            <div class="button-submit">
                                <button type="submit" id="register" name="register">Register</button>
                            </div>
                        </form>
                        <p class="subtext-login">Already have account ?<a href="Login.php"><span>Login Here</span></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function checkUsername() {
            let username = document.getElementById('username');
            let userValue = username.value;
            let notAvailUsername = document.getElementById('username-not-available');
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const data = JSON.parse(xhttp.responseText);
                    if (data[0]) {
                        username.style.borderColor = "red";
                        notAvailUsername.innerHTML = "Username is not available!";
                    } else {
                        if (!username.checkValidity()) {
                            username.style.borderColor = "red";
                            notAvailUsername.innerHTML = "Please input alphanumeric, number, and underscore";
                        } else {
                            username.style.borderColor = "green";
                            notAvailUsername.innerHTML = "";
                        }
                    }
                }
            };
            xhttp.open("GET", "../Middlewares/checkusername.php?username=" + userValue, true);
            xhttp.send();
        }
    </script>
</body>

</html>