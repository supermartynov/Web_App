<?php
session_start();
if (isset($_POST["button"])) {
    $name = ($_POST["name"]);
    $login = ($_POST["login"]);
    $password = ($_POST["password"]);
    $phone = ($_POST["phone"]);
    $_SESSION["name"] = $name;
    $_SESSION["login"] = $login;
    $_SESSION["phone"] = $phone;
    $_SESSION["password"] = $password;
    $_SESSION["password_auto"] = ($_POST["password_auto"]);
}
if (isset($_POST["button_2"])) {
    $_SESSION["password_aut"] = ($_POST["password_aut"]);
    $_SESSION["login_aut"] = ($_POST["login_aut"]);
}
if (isset($_POST["manager_button"])) {
    $_SESSION["login_manager"] = ($_POST["login_manager"]);
    $_SESSION["password_manager"] = ($_POST["password_manager"]);
}

if (isset($_POST["button_5"])) {
    $_SESSION["amount"] = ($_POST["amount"]);
    $_SESSION["date"] = ($_POST["date"]);
}


?>