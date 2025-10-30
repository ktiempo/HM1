<?php
session_start();
include(__DIR__ . '/../dB/config.php');

if(!isset($_SESSION['auth'])){
    $_SESSION['message'] = "Login to access dashboard!";
    $_SESSION['code'] = "warning";
    header("Location: ../../login.php");
    exit();
}else
{
if($_SESSION['userRole'] != 'admin')
{
    $_SESSION['message'] = "You are not authorized as ADMIN!";
    $_SESSION['code'] = "warning";
    header("Location: ../../view/users/index.php");
    exit();
}
}


?>